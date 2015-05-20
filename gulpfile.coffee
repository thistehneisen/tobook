gulp     = require 'gulp'
coffee   = require 'gulp-coffee'
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
root = "#{__dirname}/resources/#{env}"

paths =
  rev: "#{__dirname}/rev.json"
  dest: 'public/a'
  coffee: ["#{root}/**/scripts/**/*.coffee"]
  less: ["#{root}/**/styles/**/*.less", "!#{root}/**/styles/**/*.import.less"]
  static: ['/**/img/**/*.*', '/**/fonts/**/*.*']

gulp.task 'static', ->
  paths.static.forEach (path) ->
    gulp.src root+path
      .pipe gulp.dest paths.dest

gulp.task 'coffee', ->
  gulp.src paths.coffee
    .pipe cached 'scripts'
    .pipe coffee()
    .pipe remember 'scripts'
    .pipe gulpif !!env, uglify()
    .pipe rev()
    .pipe gulp.dest paths.dest
    .pipe rev.manifest path: paths.rev, merge: true
    .pipe gulp.dest __dirname

gulp.task 'less', ->
  gulp.src paths.less
    .pipe cached 'styles'
    .pipe less()
    .pipe remember 'styles'
    .pipe gulpif !!env, cssmin()
    .pipe gulp.dest paths.dest
    .pipe rev()
    .pipe gulp.dest paths.dest
    .pipe rev.manifest path: paths.rev, merge: true
    .pipe gulp.dest __dirname

gulp.task 'default', ['coffee', 'less', 'static']

gulp.task 'watch', ['default'], ->
  watcher = gulp.watch p, ['default']
  watcher.on 'change', (e) ->
    if e.type is 'deleted'
      delete cached.caches.scripts[e.path]
      remember.forget 'scripts', e.path
