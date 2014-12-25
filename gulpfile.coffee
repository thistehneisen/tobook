gulp = require 'gulp'
gulpLoadPlugins = require 'gulp-load-plugins'
plugins = gulpLoadPlugins()

paths =
  dest: 'public/built/'
  base: __dirname + '/public/assets'
  img: 'public/assets/img/**/*'
  js: ['public/assets/js/**/*.js', '!assets/js/**/*.min.js']
  coffee: ['public/assets/js/**/*.coffee']
  less: ['public/assets/less/**/*.less']

gulp.task 'img', ->
  gulp.src paths.img
    .pipe gulp.dest paths.dest + 'img'

# Compile CoffeeScript
gulp.task 'coffee', ->
  gulp.src paths.coffee, base: paths.base
    .pipe plugins.coffee()
    .pipe plugins.uglify()
    .pipe plugins.rev()
    .pipe gulp.dest paths.dest
    .pipe plugins.rev.manifest path: 'script-manifest.json'
    .pipe gulp.dest paths.dest

# Compile LESS
gulp.task 'less', ->
  gulp.src paths.less, base: paths.base
    .pipe plugins.less()
    .pipe plugins.cssmin()
    .pipe plugins.concat 'css/style.css'
    .pipe plugins.rev()
    .pipe gulp.dest paths.dest
    .pipe plugins.rev.manifest path: 'style-manifest.json'
    .pipe gulp.dest paths.dest

# Build assets to be ready for production
gulp.task 'build', ['coffee', 'less']

# For development
# Watch file changes run related tasks
gulp.task 'default', ->
  # Watch CoffeeScript
  gulp.watch paths.coffee, ['coffee']
  gulp.watch paths.less, ['less']
