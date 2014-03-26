exports.config =
  paths:
    public: 'app/public'
    watched: ['app/css','app/js','vendor','bower_components']
  files:
    javascripts:
      joinTo:
        'js/app.js': /^app/
        'js/vendor.js': /^(bower_components|vendor)/
      order:
        after: ['app/js/main.js']
    stylesheets:
      joinTo:
        'css/app.css': /^app/
        'css/vendor.css': /^(bower_components|vendor)/
      order:
        before: ['app/css/main.css','app/css/svg.css']
    templates:
      joinTo: 'app.js'
  modules:
    wrapper: false
    definition: false
  plugins:
    postcss:
      config: (postcss) ->
        postcss().
        use(require('css-mqpacker').processor).
        use(require('autoprefixer')().postcss)
    imageoptimizer:
      smushit: true
      path: '/'