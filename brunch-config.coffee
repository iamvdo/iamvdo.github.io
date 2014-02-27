exports.config =
  paths:
    public: 'app/public'
    watched: ['app/css','app/js']
  files:
    javascripts:
      joinTo:
        'app.js': /^app/
        'vendor.js': /^vendor/
      order:
        after: ['app/js/main.js']
    stylesheets:
      joinTo:
        'app.css': /^app/
        'vendor.css': /^vendor/
      order:
        before: ['app/css/main.css','app/css/svg.css']
    templates:
      joinTo: 'app.js'
  modules:
    wrapper: false
    definition: false
  plugins:
    imageoptimizer:
      smushit: false
      path: '/'
