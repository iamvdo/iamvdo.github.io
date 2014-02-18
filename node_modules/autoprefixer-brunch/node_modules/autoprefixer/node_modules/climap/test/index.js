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
var chai      = require("chai")
var sinonChai = require("sinon-chai")
var sinon     = require("sinon")
var cssParse  = require("css-parse")
var sheet     = require("sheet")
var rimraf    = require("rimraf")
var Climap    = require("../")

"use stict"

chai.use(sinonChai)
chai.should()

function read(file) {
	return fs.readFileSync(file).toString()
}

function sep(string, escape) {
	var separator = path.sep
	if (escape && separator === "\\") {
		separator = "\\\\"
	}
	return string.replace(/[\/\\]/g, separator)
}

function Instance() {
	return Climap({source: "a", content: "a"}, "o")
}


describe("Climap", function() {

	beforeEach(function() {
		if (!fs.existsSync("test/generated")) {
			fs.mkdirSync("test/generated")
		}
	})

	afterEach(function() {
		rimraf.sync("test/generated")
	})


	it("is a constructor", function() {
		Climap.should.be.a("function")
		new Climap({source: "a", content: "a"}, "o").should.be.an.instanceof(Climap)
	})


	it("also works without `new`", function() {
		Climap({source: "a", content: "a"}, "o").should.be.an.instanceof(Climap)
	})


	it("requires two paramaters", function() {
		;(function() { Climap() }).should.throw(/sources/i)
		;(function() { Climap("a") }).should.throw(/output/i)
		;(function() { Climap("a", "b") }).should.not.throw(/sources|output/i)
	})

	it("sets `.output`", function() {
		Climap({source: "a", content: "a"}, "output/file.js").output.should.eql("output/file.js")
	})


	it("sets `.sources`", function() {
		Climap({source: "a", content: "a"}, "o").sources.should.eql([
			{source: "a", content: "a"}
		])

		Climap("test/files/a.js", "o").sources.should.eql([
			{source: "test/files/a.js", content: read("test/files/a.js")}
		])

		Climap([
			{source: "a", content: "a\n//# sourceMappingURL=foo.js.map"},
			"test/files/a.js"
		], "o").sources.should.eql([
			{source: "a", content: "a", inSourceMap: "foo.js.map"},
			{source: "test/files/a.js", content: read("test/files/a.js")}
		])
	})


	it("sets `.sourceRoot`", function() {
		Climap({source: "/foo/bar/a.js", content: "a"}, "/foo/bar/output.js").sourceRoot
			.should.eql("")

		Climap({source: "/foo/bar/baz/a.js", content: "a"}, "/foo/bar/output.js").sourceRoot
			.should.eql("baz")

		Climap([
			{source: "/foo/bar/a.js", content: "a"},
			{source: "/foo/baz/b.js", content: "b"}
		], "/foo/bar/output.js").sourceRoot.should.eql("..")

		Climap([
			{source: "src/app/a.js", content: "a"},
			{source: "src/vendor/b.js", content: "b"}
		], "public/bundle.js").sourceRoot.should.eql(sep("../src"))
	})


	describe("readSource", function() {

		it("returns an object which represents the content of a file and its path", function() {
			Climap.prototype.readSource("test/files/a.js").should.eql({
				source: "test/files/a.js",
				content: read("test/files/a.js")
			})
			Climap.prototype.readSource({source: "a", content: "a"})
				.should.eql({source: "a", content: "a"})
		})

	})


	describe("stripSourceMapComment", function() {

		it("strips valid source map comments and saves them", function() {
			Climap.prototype.stripSourceMapComment({
				content: "a\n//# sourceMappingURL=foo.js.map"
			}).should.eql({
				content: "a",
				inSourceMap: "foo.js.map"
			})

			Climap.prototype.stripSourceMapComment({
				content: "a\r/*# sourceMappingURL=foo.js.map */"
			}).should.eql({
				content: "a",
				inSourceMap: "foo.js.map"
			})

			Climap.prototype.stripSourceMapComment({
				content: "//# sourceMappingURL=foo.js.map"
			}).should.eql({
				content: "//# sourceMappingURL=foo.js.map",
			})

			Climap.prototype.stripSourceMapComment({
				content: "a\r\nanything_but_whitespace;# sourceMappingURL=foo.js.map same_here!  \n"
			}).should.eql({
				content: "a",
				inSourceMap: "foo.js.map"
			})

			Climap.prototype.stripSourceMapComment({
				content: "a\n//#sourceMappingURL=missing_space"
			}).should.eql({
				content: "a\n//#sourceMappingURL=missing_space",
			})

			Climap.prototype.stripSourceMapComment({
				content: "a\n# sourceMappingURL=missing_comment_token"
			}).should.eql({
				content: "a\n# sourceMappingURL=missing_comment_token",
			})

		})

	})

	describe("findCommonRoot", function() {

		it("returns the common root among a set of paths", function() {

			Climap.prototype.findCommonRoot([
				"/foo/bar/a.js",
				"/foo/bar/b.js"
			]).should.eql("/foo/bar")

			Climap.prototype.findCommonRoot([
				"/foo/bar/a.js"
			]).should.eql("/foo/bar")

			Climap.prototype.findCommonRoot([
				"./foo/bar/a.js",
				"/foo/bar/b.js"
			]).should.eql("")

			Climap.prototype.findCommonRoot([
				"/foo/bar/a.js",
				"/foo/bar/b.js",
				"/foo/baz/c.js",
			]).should.eql("/foo")

		})


		it("works with both Windows an *nix separators", function() {
			Climap.prototype.findCommonRoot([
				"/foo\\bar/a.js"
			]).should.eql("/foo/bar")
		})

	})


	describe("parse", function() {

		it("allows chaining", function() {
			var instance = Instance()
			instance.parse(function() {}).should.equal(instance)
		})


		it("takes a parsing function, and calls it with appropriate paramaters", function() {
			var parse = sinon.spy()
			var instance = Climap([
				{source: "src/app/a.js", content: "a"},
				{source: "src/vendor/b.js", content: "b"}
			], "public/bundle.js")
			instance.parse(parse)
			parse.should.have.been.calledTwice
			parse.firstCall
				.should.have.been.calledWithExactly("a", sep("app/a.js"), 0, instance.sources)
			parse.secondCall
				.should.have.been.calledWithExactly("b", sep("vendor/b.js"), 1, instance.sources)
		})


		it("saves the results in `.parsedSources`", function() {
			Climap([
				{source: "src/app/a.js", content: "a"},
				{source: "src/vendor/b.js", content: "b"}
			], "public/bundle.js").parse(function(content, source, index) {
				return index
			}).parsedSources.should.eql([0, 1])
		})


		it("fails with invalid paramaters", function() {
			var instance = Instance()
			;(function() { instance.parse() }).should.throw()
			;(function() { instance.parse("string") }).should.throw()
		})

	})


	describe("reduce", function() {

		it("allows chaining", function() {
			var instance = Instance()
			instance.parsedSources = []
			instance.reduce(function() {}, "default").should.equal(instance)
		})


		it("requires parsing first", function() {
			var instance = Instance()

			;(function() {
				instance.reduce()
			}).should.throw(/parse/i)

			;(function() {
				instance.parsedSources = []
				instance.reduce()
			}).should.not.throw(/parse/i)
		})


		it("takes a reducing function and passes it to `Array#reduce`", function() {
			var reduce = sinon.spy()
			sinon.spy(Array.prototype, "reduce")

			var instance = Instance()
			instance.parsedSources = ["a", "b"]

			instance.reduce(reduce)
			Array.prototype.reduce.should.have.been.calledWith(reduce)
			reduce.firstCall.should.have.been.calledWith("a", "b")

			Array.prototype.reduce.restore()
		})


		it("optionally takes an initial value and passes it to `Array#reduce` too", function() {
			var reduce = sinon.spy()
			sinon.spy(Array.prototype, "reduce")

			var instance = Instance()
			instance.parsedSources = ["a", "b"]

			instance.reduce(reduce, "default")
			Array.prototype.reduce.should.have.been.calledWith(reduce, "default")
			reduce.firstCall.should.have.been.calledWith("default", "a")

			Array.prototype.reduce.reset()
			reduce.reset()
			instance.reduce(reduce, undefined)
			Array.prototype.reduce.should.have.been.calledWith(reduce, undefined)
			reduce.firstCall.should.have.been.calledWith(undefined, "a")

			Array.prototype.reduce.restore()
		})


		it("saves the results in `.ast`", function() {
			var instance = Instance()
			instance.parsedSources = [1, 2, 3]
			instance.reduce(function(sum, value) {
				return sum + value
			})
			instance.ast.should.eql(6)
		})


		it("fails with invalid paramaters", function() {
			var instance = Instance()
			instance.parsedSources = []
			;(function() { instance.reduce() }).should.throw()
			;(function() { instance.reduce("string") }).should.throw()
		})

	})


	describe("compile", function() {

		it("allows chaining", function() {
			var instance = Instance()
			instance.ast = {}
			instance.compile(function() {return {content: "a"}}).should.equal(instance)
		})


		it("requires reducing first, unless a non array of sources was provided", function() {
			;(function() {
				Climap([{source: "a", content: "a"}], "o").compile()
			}).should.throw(/reduce/i)

			var instance = Instance()

			;(function() {
				instance.compile()
			}).should.not.throw(/reduce/i)

			;(function() {
				instance.ast = {}
				instance.compile()
			}).should.not.throw(/reduce/i)
		})


		it("takes a compiling function, and calls it with appropriate paramaters", function() {
			var compile = sinon.stub().returns({content: "a"})
			var instance = Climap([
				{source: "src/app/a.js", content: "a"},
				{source: "src/vendor/b.js", content: "b"}
			], "public/bundle.js")
			var ast = {}
			instance.ast = ast
			instance.compile(compile)
			compile.should.have.been.calledOnce
			compile.should.have.been.calledWithExactly(ast, {
				file: "bundle.js",
				sourceMappingURL: "bundle.js.map",
				sourceRoot: sep("../src")
			})
		})


		it("requires the compiling function to return {content}", function() {
			;(function() {
				var instance = Instance()
				instance.ast = {}
				instance.compile(function(ast) {
					return {contentMissing: true}
				})
			}).should.throw(/content/i)
		})


		it("saves the returned `{content}` in `.compiled`", function() {
			var instance = Instance()
			var ast = {}
			instance.ast = ast
			instance.compile(function(ast) {
				return {content: ast}
			})
			instance.compiled.should.eql(ast)
		})


		it("saves the returned `{map}` in `.map`", function() {
			var instance = Instance()
			var map = {version: 3, file: "a", sources: ["b"], names: [], mappings: "AAAA"}
			instance.ast = {}
			instance.map = map
			instance.compile(function(ast) {
				return {content: ast, map: map}
			})
			JSON.parse(instance.map).should.eql(map)
		})


		it("fails with invalid paramaters", function() {
			var instance = Instance()
			instance.ast = {}
			;(function() { instance.compile() }).should.throw()
			;(function() { instance.compile("string") }).should.throw()
		})

	})


	describe("write", function() {

		it("allows chaining", function() {
			var instance = Climap({source: "a", content: "a"}, "test/generated/test.txt")
			instance.compiled = "a"
			instance.write().should.equal(instance)
			instance.write(function() { return false }).should.equal(instance)
			instance.write(function() { return true }).should.equal(instance)
		})


		it("requires compiling first", function() {
			var instance = Climap({source: "a", content: "a"}, "test/generated/test.txt")

			;(function() {
				instance.write()
			}).should.throw(/compile/i)

			;(function() {
				instance.compiled = "a"
				instance.write()
			}).should.not.throw(/compile/i)
		})


		it("writes `.compiled`", function() {
			var instance = Climap({source: "a", content: "a"}, "test/generated/output.txt")
			instance.compiled = "compiled"
			instance.write()
			read("test/generated/output.txt").should.eql("compiled")
		})


		it("writes `.map`", function() {
			var instance = Climap({source: "a", content: "a"}, "test/generated/output.txt")
			instance.compiled = "compiled"
			instance.map = "map"
			instance.write()
			read("test/generated/output.txt.map").should.eql("map")
		})


		it("applies in-source maps correctly", function() {
			var parseCSS = function(content, source) {
				return cssParse(content, {position: true, source: source})
			}
			var compileCSS = function(ast, data) {
				data.compress = true
				data.map = true
				var compiled = sheet(ast, data)
				return {content: compiled.css, map: compiled.map}
			}
			var concatCSS = function(concat, current) {
				Array.prototype.push.apply(concat.stylesheet.rules, current.stylesheet.rules)
				return concat
			}

			// Minify source{1,2}.css, with source maps generation.
			;["source1", "source2"].forEach(function(file) {
				Climap("test/files/" + file + ".css", "test/generated/" + file + ".min.css")
					.parse(parseCSS)
					.compile(compileCSS)
					.write()
			})

			// Concatenate source{1,2}.min.css and source3.css, with source maps generation.
			var files = [
				"test/generated/source1.min.css",
				"test/generated/source2.min.css",
				"test/files/source3.css"
			]
			Climap(files, "test/generated/source-bundle.css")
				.parse(parseCSS)
				.reduce(concatCSS)
				.compile(compileCSS)
				.write()

			// Note: The expected source map was verified using
			// <http://sokra.github.io/source-map-visualization/>
			read("test/generated/source-bundle.css.map")
				.should.eql(sep(read("test/files/source-bundle.css.map.expected"), true))
		})


		it("runs the provided callback with appropriate paramaters", function() {
			var callback = sinon.spy()
			var instance = Climap({source: "a", content: "a"}, "test/generated/test.txt")
			instance.compiled = "compiled"

			instance.write(callback)
			callback.should.have.been.calledOnce
			callback.should.have.been.calledWithExactly("compiled", undefined)

			callback.reset()
			instance.map = "map"
			instance.write(callback)
			callback.should.have.been.calledOnce
			callback.should.have.been.calledWithExactly("compiled", "map")
		})


		it("only writes if the provided callback returns something falsy", function() {
			var instance = Climap({source: "a", content: "a"}, "test/generated/output.txt")
			instance.compiled = "compiled"

			instance.write(function() { return true })
			fs.existsSync("test/generated/output.txt").should.be.false

			instance.write(function() { return false })
			read("test/generated/output.txt").should.eql("compiled")
		})


		it("fails with invalid paramaters", function() {
			var instance = Instance()
			instance.compiled = "a"
			;(function() { instance.write("string") }).should.throw()
		})

	})

})
