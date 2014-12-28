gulp = require 'gulp'
del = require 'del'
gulpLoadPlugins = require 'gulp-load-plugins'
plugins = gulpLoadPlugins()

paths =
  dest: 'public/built/'
  base: __dirname + '/resources'
  js: ['resources/**/scripts/**/*.js', '!resources/**/scripts/**/*.min.js']
  coffee: ['resources/**/scripts/**/*.coffee']
  less: ['resources/**/styles/**/*.less', '!resources/**/styles/**/*.import.less']

gulp.task 'img', ->
  gulp.src paths.img
    .pipe gulp.dest paths.dest + 'img'

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
gulp.task 'build', ['coffee', 'less']

# For development
# Watch file changes run related tasks
gulp.task 'default', ->
  # Watch CoffeeScript
  gulp.watch paths.coffee, ['coffee']
  gulp.watch paths.less, ['less']
