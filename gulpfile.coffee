gulp = require 'gulp'
del = require 'del'
_ = require 'lodash'
fs = require 'fs'
streamqueue = require 'streamqueue'
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

# Clone the resources to every instance
gulp.task 'clone', ->
  files = fs.readdirSync paths.base
  for folder in files
    do ->
      target = "#{paths.dest}tmp/#{folder}"
      base   = gulp.src "#{paths.base}/varaa/**/*.*"
      ext    = gulp.src "#{paths.base}/#{folder}/**/*.*"
      streamqueue objectMode: true, base, ext
        .pipe gulp.dest target

# Build assets to be ready for production
gulp.task 'build', ['coffee', 'less', 'img'], ->
  # Folder `resources` has this structure
  # ```
  #   varaa            <---------------- Based assets
  #   clearbooking     <------
  #   foo              <------ Other instances
  #   bar              <------
  # ```
  # Folder `varaa` acts as the root folder and other folders just need to make
  # changes that are not presenting in `varaa`. While building, assets in
  # `varaa` will be cloned to all instances

  # Concat all manifest files
  manifests = {}
  for type in ['js', 'script', 'style']
    do ->
      _.merge manifests, require "./#{paths.dest}#{type}-manifest.json"

  # Write the file
  fs.writeFile "#{__dirname}/rev-manifest.json", JSON.stringify manifests, null, '  '

# For development
# Watch file changes run related tasks
gulp.task 'default', ->
  # Watch CoffeeScript
  gulp.watch paths.coffee, ['build']
  gulp.watch paths.less, ['build']
