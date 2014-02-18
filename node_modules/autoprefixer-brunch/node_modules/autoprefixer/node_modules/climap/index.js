/*
Copyright 2013 Simon Lydell

This file is part of Climap.

Climap is free software: you can redistribute it and/or modify it under the terms of the GNU Lesser
General Public License as published by the Free Software Foundation, either version 3 of the
License, or (at your option) any later version.

Climap is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the
implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser General
Public License for more details.

You should have received a copy of the GNU Lesser General Public License along with Climap. If not,
see <http://www.gnu.org/licenses/>.
*/

var fs        = require("fs")
var path      = require("path")
var sourceMap = require("source-map")

"use strict"

function Climap(sources, output) {
	if (!(this instanceof Climap)) return new Climap(sources, output)

	if (!sources) {
		throw new Error("Sources are required!")
	}
	if (!Array.isArray(sources)) {
		this.single = true
		sources = [sources]
	}

	if (!output) {
		throw new Error("Output is required!")
	}

	this.output = output
	this.sources = sources
		.map(this.readSource)
		.map(this.stripSourceMapComment)
	this.commonRoot = this.findCommonRoot(this.sources.map(function(obj) { return obj.source }))
	this.sourceRoot = path.relative(path.dirname(this.output), this.commonRoot)
}

Climap.prototype.readSource = function(obj) {
	if (typeof obj === "string") {
		var filePath = obj
		return {
			source: filePath,
			content: fs.readFileSync(filePath).toString()
		}
	}
	return obj
}

var sourceMapComment = /(?:\r\n|\r|\n)\S+# sourceMappingURL=(\S+) ?\S*\s*$/
Climap.prototype.stripSourceMapComment = function(obj) {
	var match = obj.content.match(sourceMapComment)
	if (match) {
		obj.inSourceMap = match[1]
		obj.content = obj.content.slice(0, match.index)
	}
	return obj
}

var sep = /[\/\\]/
Climap.prototype.findCommonRoot = function(sources) {
	var commonSplit, commonSplitLength
	var currentSplit, currentSplitLength
	var index

	return sources.reduce(function(commonRoot, source) {
		commonSplit = commonRoot.split(sep)
		currentSplit = source.split(sep)
		commonSplitLength = commonSplit.length
		currentSplitLength = currentSplit.length

		index = 0
		while (index < commonSplitLength && index < currentSplitLength) {
			if (commonSplit[index] === currentSplit[index]) {
				index++
			} else {
				break
			}
		}

		return commonSplit.slice(0, index).join("/")
	}, path.dirname(sources[0]))
}

Climap.prototype.parse = function(parse) {
	this.parsedSources = this.sources.map(function(source, index, sources) {
		var relativeSource = path.relative(this.commonRoot, source.source)
		return parse(source.content, relativeSource, index, sources)
	}.bind(this))
	return this
}

Climap.prototype.reduce = function(/* reduce, initialValue */) {
	if (!this.parsedSources) throw new Error("Parse first!")

	this.ast = Array.prototype.reduce.apply(this.parsedSources, arguments)
	return this
}

Climap.prototype.compile = function(compile) {
	if (!this.ast) {
		if (this.single) {
			this.reduce(function() {})
		} else {
			throw new Error("Reduce first!")
		}
	}

	var file = path.basename(this.output)
	var data = {
		file: file,
		sourceMappingURL: file + ".map",
		sourceRoot: this.sourceRoot
	}
	var compiled = compile(this.ast, data)

	if (!compiled.content) {
		throw new Error("Compile must return an object with a 'content' property.")
	}
	this.compiled = compiled.content

	if (compiled.map) {
		var map = new sourceMap.SourceMapConsumer(compiled.map)
		var sourceRoot = map.sourceRoot || ""
		map = sourceMap.SourceMapGenerator.fromSourceMap(map)

		var root = path.join(path.dirname(this.output), sourceRoot)

		// Apply in-source maps (if any). This requires a lot of path juggling.
		this.sources.forEach(function(obj) {
			if (obj.inSourceMap) {
				var inSourceMapPath = path.resolve(path.dirname(obj.source), obj.inSourceMap)

				var inSourceMap = fs.readFileSync(inSourceMapPath).toString()
				inSourceMap = new sourceMap.SourceMapConsumer(inSourceMap)
				inSourceMap.sourceRoot = path.relative(sourceRoot, inSourceMap.sourceRoot)

				var sourceDir = path.relative(root, path.dirname(inSourceMapPath))
				var source = path.join(sourceDir, inSourceMap.file)
				map.applySourceMap(inSourceMap, source)
			}
		}.bind(this))

		// Optimize the `sources` array to be as short as possible.
		map = map.toJSON()
		var commonRoot = this.findCommonRoot(map.sources)
		map.sources = map.sources.map(function(source) {
			return path.relative(commonRoot, source)
		})
		map.sourceRoot = path.join(sourceRoot, commonRoot)
		if (map.sourceRoot === ".") delete map.sourceRoot

		this.map = JSON.stringify(map)
	}

	return this
}

Climap.prototype.write = function(callback) {
	if (!this.compiled) throw new Error("Compile first!")

	if (callback) {
		var skipWriting = callback(this.compiled, this.map)
		if (skipWriting) return this
	}

	fs.writeFileSync(this.output, this.compiled)
	if (this.map) {
		fs.writeFileSync(this.output + ".map", this.map)
	}

	return this
}

module.exports = Climap
