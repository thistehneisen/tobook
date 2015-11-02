gulp     = require 'gulp'
coffee   = require 'gulp-coffee'
babel    = require 'gulp-babel'
concat   = require 'gulp-concat'
less     = require 'gulp-less'
cssmin   = require 'gulp-cssmin'
rev      = require 'gulp-rev'
uglify   = require 'gulp-uglify'
remember = require 'gulp-remember'
cached   = require 'gulp-cached'
gulpif   = require 'gulp-if'
args     = require 'yargs'

env = if args.argv.env then args.argv.env else 'varaa'
production = !!args.argv.production
root = "#{__dirname}/resources/#{env}"

paths =
  rev: "#{__dirname}/rev.json"
  dest: 'public/assets'
  coffee: ["#{root}/**/scripts/**/*.coffee"]
  es6: ["#{root}/**/scripts/**/*.es6"]
  less: ["#{root}/**/styles/**/*.less", "!#{root}/**/styles/**/*.import.less"]
  js: ["#{root}/**/scripts/**/*.js", "!#{root}/**/scripts/**/*.min.js"]
  static: ["#{root}/**/img/**/*.*", "#{root}/**/fonts/**/*.*"]

gulp.task 'static', ->
  paths.static.forEach (path) ->
    gulp.src path
      .pipe gulp.dest paths.dest

gulp.task 'js', ->
  gulp.src paths.js
    .pipe cached 'js'
    .pipe remember 'js'
    .pipe gulpif production, uglify()
    .pipe gulpif production, rev()
    .pipe gulp.dest paths.dest
    .pipe gulpif production, rev.manifest(path: paths.rev, merge: true)
    .pipe gulpif production, gulp.dest(__dirname)

gulp.task 'coffee', ->
  gulp.src paths.coffee
    .pipe cached 'coffee'
    .pipe coffee()
    .pipe remember 'coffee'
    .pipe gulpif production, uglify()
    .pipe gulpif production, rev()
    .pipe gulp.dest paths.dest
    .pipe gulpif production, rev.manifest(path: paths.rev, merge: true)
    .pipe gulpif production, gulp.dest(__dirname)

gulp.task 'es6', ->
  gulp.src paths.es6
    .pipe cached 'es6'
    .pipe babel()
    .pipe remember 'es6'
    .pipe gulpif production, uglify()
    .pipe gulpif production, rev()
    .pipe gulp.dest paths.dest
    .pipe gulpif production, rev.manifest(path: paths.rev, merge: true)
    .pipe gulpif production, gulp.dest(__dirname)

gulp.task 'less', ->
  gulp.src paths.less
    .pipe cached 'less'
    .pipe less()
    .pipe remember 'less'
    .pipe gulpif production, cssmin()
    .pipe gulpif production, rev()
    .pipe gulp.dest paths.dest
    .pipe gulpif production, rev.manifest(path: paths.rev, merge: true)
    .pipe gulpif production, gulp.dest(__dirname)

gulp.task 'default', ['coffee', 'js', 'es6', 'less', 'static']

gulp.task 'watch', ['default'], ->
  # Special rule for LESS files
  gulp.watch ["#{root}/**/styles/**/*.less"], ['less']

  ['coffee', 'js', 'es6'].forEach (task) ->
    watcher = gulp.watch paths[task], [task]
    watcher.on 'change', (e) ->
      if e.type is 'deleted'
        delete cached.caches[task][e.path] if cached.caches[task][e.path]
        remember.forget task, e.path
