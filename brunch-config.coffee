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
    pleeease:
      rem: false
      pseudoElements: false
      opacity: false
    imageoptimizer:
      smushit: true
      path: '/'
  overrides:
    DEV:
      optimize: true
      plugins:
        pleeease:
          minifier: false
        off: ['imageoptmizer-brunch']
