gulp = require 'gulp'
del = require 'del'
_ = require 'lodash'
gulpLoadPlugins = require 'gulp-load-plugins'
plugins = gulpLoadPlugins()

paths =
  dest: 'public/built/'
  base: __dirname + '/resources'
  img: ['resources/**/img/**/*.*']
  js: ['resources/**/scripts/**/*.js', '!resources/**/scripts/**/*.min.js']
  coffee: ['resources/**/scripts/**/*.coffee']
  less: ['resources/**/styles/**/*.less', '!resources/**/styles/**/*.import.less']

# Do nothing, just copy images to proper folders
gulp.task 'img', ->
  gulp.src paths.img
    .pipe gulp.dest paths.dest

# Rev JS files
gulp.task 'js', ->
  gulp.src paths.js, base: paths.base
    .pipe plugins.rev()
    .pipe gulp.dest paths.dest
    .pipe plugins.rev.manifest path: 'js-manifest.json'
    .pipe gulp.dest paths.dest

# Compile CoffeeScript
gulp.task 'coffee', ['clean', 'js'], ->
  gulp.src paths.coffee, base: paths.base
    .pipe plugins.coffee()
    .pipe plugins.uglify()
    .pipe plugins.rev()
    .pipe gulp.dest paths.dest
    .pipe plugins.rev.manifest path: 'script-manifest.json'
    .pipe gulp.dest paths.dest

# Compile LESS
gulp.task 'less', ['clean'], ->
  gulp.src paths.less, base: paths.base
    .pipe plugins.less()
    .pipe plugins.cssmin()
    .pipe plugins.rev()
    .pipe gulp.dest paths.dest
    .pipe plugins.rev.manifest path: 'style-manifest.json'
    .pipe gulp.dest paths.dest

# Clean the built directory
gulp.task 'clean', ->
    # Use del.sync to make sure that built directory is empty
    del.sync [paths.dest]

# Build assets to be ready for production
gulp.task 'build', ['coffee', 'less', 'img'], ->
  # Concat all manifest files
  manifests = {}
  for type in ['js', 'script', 'style']
    do ->
      _.merge manifests, require "./#{paths.dest}#{type}-manifest.json"

  # Write the file
  require 'fs'
    .writeFile "#{__dirname}/rev-manifest.json", JSON.stringify manifests, null, '  '

# For development
# Watch file changes run related tasks
gulp.task 'default', ->
  # Watch CoffeeScript
  gulp.watch paths.coffee, ['build']
  gulp.watch paths.less, ['build']
