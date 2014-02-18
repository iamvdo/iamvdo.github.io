# Copyright 2013 Andrey Sitnik <andrey@sitnik.ru>,
# sponsored by Evil Martians.
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU Lesser General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU Lesser General Public License for more details.
#
# You should have received a copy of the GNU Lesser General Public License
# along with this program.  If not, see <http:#www.gnu.org/licenses/>.

autoprefixer = require('../autoprefixer')
fs           = require('fs')
Climap       = require('climap')

class Binary
  constructor: (process) ->
    @arguments = process.argv.slice(2)
    @stdin     = process.stdin
    @stderr    = process.stderr
    @stdout    = process.stdout

    @status     = 0
    @command    = 'compile'
    @inputFiles = []

    @parseArguments()

  help: -> '''
    Usage: autoprefixer [OPTION...] FILES

    Parse CSS files and add prefixed properties and values.

    Options:
      -b, --browsers BROWSERS  add prefixes for selected browsers
      -o, --output FILE        set output CSS file
      -m, --map                create <output file>.map source map file
      -i, --inspect            show selected browsers and properties
      -h, --help               show help text
      -v, --version            print program version
    '''

  desc: -> '''
    Files:
      By default, prefixed CSS will rewrite original files.
      If you didn't set input files, autoprefixer will +
        read from stdin stream.
      Output CSS will be written to stdout stream on +
        `-o -' argument or stdin input.

    Browsers:
      Separate browsers by comma. For example, `-b "> 1%, opera 12"'.
      You can set browsers by global usage statictics: `-b \"> 1%\"'.
      or last version: `-b "last 2 versions"' (by default).
    '''
    .replace(/\+\s+/g, '')

  print: (str) ->
    str = str.replace(/\n$/, '')
    @stdout.write(str + "\n")

  error: (str) ->
    @status = 1
    @stderr.write(str + "\n")

  version: ->
    require('../../package.json').version

  parseArguments: ->
    args = @arguments.slice()
    while args.length > 0
      arg = args.shift()

      switch arg
        when '-h', '--help'
          @command = 'showHelp'

        when '-v', '--version'
          @command = 'showVersion'

        when '-i', '--inspect'
          @command = 'inspect'

        when '-u', '--update'
          @command = 'update'

        when '-b', '--browsers'
          @requirements = args.shift().split(',').map (i) -> i.trim()

        when '-o', '--output'
          @outputFile = args.shift()

        when '-m', '--map'
          @sourceMap = true

        else
          if arg.match(/^-\w$/) || arg.match(/^--\w[\w-]+$/)
            @command = undefined

            @error "autoprefixer: Unknown argument #{ arg }"
            @error ''
            @error @help()

          else
            @inputFiles.push(arg)

    if @sourceMap and (not @outputFile or @outputFile == '-')
      delete @sourceMap

  showHelp: (done) ->
    @print @help()
    @print ''
    @print @desc()
    done()

  showVersion: (done) ->
    @print "autoprefixer #{ @version() }"
    done()

  inspect: (done) ->
    @print @compiler().inspect()
    done()

  update: (done) ->
    try
      coffee = require('coffee-script')
    catch
      @error "Install coffee-script npm package"
      return done()

    updater = require('./updater')

    updater.request => @stdout.write('.')
    updater.done =>
      @print ''
      if updater.changed.length == 0
        @print 'Everything up-to-date'
      else
        @print 'Update ' + updater.changed.join(' and ') + ' data'
      done()

    updater.run()

  # Lazy loading for Autoprefixer instance
  compiler: ->
    @compilerCache ||= autoprefixer(@requirements)

  compile: (done) ->
    if @inputFiles.length == 0
      @outputFile ||= '-'
      css = ''
      @stdin.resume()
      @stdin.on 'data', (chunk) -> css += chunk
      @stdin.on 'end', =>
        @compileCSS({source: 'stdin', content: css}, @outputFile)

    else if @outputFile
      @compileCSS(@inputFiles, @outputFile)

    else
      for file in @inputFiles
        @compileCSS(file)

    done()

  compileCSS: (input, output = input) ->
    try
      compiler = @compiler()

      Climap(input, output)
        .parse (content, source) ->
          compiler.parse(content, {source})

        .reduce (concat, current) ->
          concat.stylesheet.rules.push(current.stylesheet.rules...)
          concat

        .compile (ast, data) =>
          compiled = compiler.compile(ast,
            {sourcemap: @sourceMap, filename: data.file})
          if @sourceMap
            compiled.map.sourceRoot = data.sourceRoot
            compiled.code +=
              "\n/*# sourceMappingURL=#{data.sourceMappingURL} */"
            return {content: compiled.code, map: compiled.map}
          else
            return {content: compiled}

        .write (compiled, map) =>
          if output == '-'
            @print compiled
            true

    catch error
      @error "autoprefixer: #{ error.message }"

  # Execute command selected by arguments
  run: (done) ->
    if @command
      @[@command](done)
    else
      done()

module.exports = Binary
