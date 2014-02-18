autoprefixer = require "autoprefixer"

module.exports = class AutoprefixerCompiler
	brunchPlugin: yes
	type: "stylesheet"
	extension: "css"
	pattern: /\.(?:css|scss|sass|less|styl)$/

	constructor: (@config)->
		{@browsers} = @config.plugins.autoprefixer ? {}

	compile: ({data, path, map}, callback)->
		try compiled = autoprefixer(@browsers...).compile(data, {sourcemap: true, source: path})
		catch error
		# Try to be compatible with the original version and the fork simultaneously.
		callback(error, {data: compiled?.code ? compiled, map: compiled?.map})
