[![Build Status](https://travis-ci.org/lydell/climap.png?branch=master)](https://travis-ci.org/lydell/climap)

Overview
========

Have you added source map support to your compiler, but aren't sure about how to expose it to your
CLI tool? Keep it simple! Add a `--map` switch and let Climap do the rest!

In order to make it so simple, Climap enforces some conventions. A source map for a file "foo.js"
will:

- be called "foo.js.map",
- be placed next to "foo.js",
- have all its sources listed relative to itself,
- have source maps of its source files applied to itself. (Takes care of "in source maps".)


Installation
============

`npm install climap`

```js
var Climap = require("climap")
```


Usage
=====

Given an array `files` of paths to input files, an output path `output`, some parsing, compiling,
merging and joining functions `parse`, `compile`, `merge` and `join`, respectively, and a flag
`useStdout`:

If each file should be processed on its own:

```js
files.forEach(function(file) {
	Climap(file, join(output, file))
		.parse(function(content, source, index, sources) {
			return parse(content, source)
		})
		.compile(function(ast, data) {
			var compiled = compile(ast, {
				file: data.file,
				sourceMappingURL: data.sourceMappingURL,
				sourceRoot: data.sourceRoot
			})
			return {content: compiled.content, map: compiled.map}
		})
		.write(function(compiled, map) {
			if (useStdout) {
				process.stdout.write(compiled)
				return true // Indicate that no file should be written.
			}
		})
})
```

If all the files should be processed together:

```js
Climap(files, output)
	.parse(parse)
	.reduce(function(merged, current, index, array) {
		return merge(merged, current)
	})
	.compile(compile)
	.write()
```

Climap was all about source map generation, right? Why do I have to write the whole
read–parse–compile–write chain with Climap in the back all the time? Good question. The answer is
that Climap needs to gather and exchange information during the whole process. It's also about
convenience.

Note
----

### `Climap` ###

The first argument of `Climap` can be either a string, or an array of strings, as seen. If a non
array string is passed, reducing is not mandatory (and probably useless). The strings are paths to
files, which will be read. You may also pass `{source: "path/to/file", content: "content of file"}`
objects instead of strings, or mix both variants.

`Climap` is actually a constructor. You may use the `new` keyword before it if you wish. You can of
course also assign the instance to a variable if you so desire, but all the relevant methods are
chainable, so there's really no need to.

### The parsing function ###

`source` is a shortened form of each file path, optimized to be used in a source map. The parser
should put that in the AST it creates.

### The compiling function ###

`data.file` is the basename of the to-be-written output file (second parameter passed to `Climap`).

`data.sourceMappingURL` is actually just `data.file + ".map"`. The compiler should put it in the `#
sourceMappingURL=...` comment at the end of the file. `Climap` cannot do this automatically, since
it cannot now what language you are targeting and thus doesn't know the correct comment syntax.

Note that the above means that the source map must always be kept in the same directory as its file.
That assumption is made for simplicity.

Finally, `data.sourceRoot` is automatically populated to reduces the size of `sources` array in the
source map as much as possible. It is relative to the source map itself.

### The writing function ###

As seen, it is optional. If omitted, or if it returns something falsy, the output file (second
parameter passed to `Climap`) will be written, along with its source map (if any). Usually that's
what you want, but you are given the opportunity to opt out here.

### The reducing function ###

`Climap#reduce` is exactly like `Array#reduce` (you may also pass an initial value, if you wish).

### Comprehensive example ###

See [example/css-minify-concat.js](example/css-minify-concat.js). It is a little CLI tool that
minifies and concatenates CSS files. Try it out: `node example/css-minify-concat.js --map
test/files/source?.css bundle.css`.


Licenses
========

LGPLv3 in general. The example program is GPLv3. All files which do not mention anything about
copyright and licenses are public domain.
