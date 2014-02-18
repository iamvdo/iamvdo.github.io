autoprefixer-brunch
===================

Adds [autoprefixer] support to [brunch].

[autoprefixer]: https://github.com/ai/autoprefixer
[brunch]: http://brunch.io/


Usage
=====

Install it: [how to][usage], [what plugin version to use][version]. `npm install --save
autoprefixer-brunch` should do the trick. Make sure that it is listed _after_ any CSS preprocessors
in the dependencies.

You can specify the browsers you want to target in your project:

```coffeescript
exports.config =
  …
  plugins:
    autoprefixer:
      browsers: ["last 1 version", "> 1%", "ie 8", "ie 7"]
```

[usage]: https://github.com/brunch/brunch/blob/master/docs/plugins.md#usage
[version]: https://github.com/brunch/brunch/blob/master/docs/faq.md#what-version-of-plugin-do-i-need-to-use


Note
====

Currently, [this fork with source map support][fork] of autoprefixer is used. When source map
officially lands in the original version, I'll switch to that. If you must use the original version,
this plugin should work by just switching dependencies.

[fork]: https://github.com/lydell/autoprefixer/tree/source-maps


License
=======

[MIT Licensed](LICENSE).
