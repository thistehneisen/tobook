gulp = require 'gulp'
del = require 'del'
_ = require 'lodash'
fs = require 'fs'
chmod = require 'gulp-chmod'
streamqueue = require 'streamqueue'
gulpLoadPlugins = require 'gulp-load-plugins'
plugins = gulpLoadPlugins()

paths =
  dest: 'public/built/'
  base: __dirname + '/resources'
  tmp: __dirname + '/resources/tmp'
  img: ['resources/tmp/**/img/**/*.*']
  fonts: ['resources/tmp/**/fonts/**/*.*']
  js: ['resources/tmp/**/scripts/**/*.js', '!resources/tmp/**/scripts/**/*.min.js']
  coffee: ['resources/tmp/**/scripts/**/*.coffee']
  less: ['resources/tmp/**/styles/**/*.less', '!resources/tmp/**/styles/**/*.import.less']

# Rev JS files
gulp.task 'js', ->
  gulp.src paths.js, base: paths.tmp
    .pipe plugins.rev()
    .pipe chmod 755
    .pipe gulp.dest paths.dest
    .pipe plugins.rev.manifest path: 'js-manifest.json'
    .pipe gulp.dest paths.dest

# Compile CoffeeScript
gulp.task 'coffee', ['js'], ->
  gulp.src paths.coffee, base: paths.tmp
    .pipe plugins.coffee()
    .pipe plugins.uglify()
    .pipe plugins.rev()
    .pipe chmod 755
    .pipe gulp.dest paths.dest
    .pipe plugins.rev.manifest path: 'script-manifest.json'
    .pipe gulp.dest paths.dest

# Compile LESS
gulp.task 'less', ->
  gulp.src paths.less, base: paths.tmp
    .pipe plugins.less()
    .pipe plugins.cssmin()
    .pipe plugins.rev()
    .pipe chmod 755
    .pipe gulp.dest paths.dest
    .pipe plugins.rev.manifest path: 'style-manifest.json'
    .pipe gulp.dest paths.dest

# Clone the resources to every instance
gulp.task 'clone', ->
  files = fs.readdirSync paths.base
  for folder in files
    do ->
      # Skip tmp folder
      return if folder is 'tmp'

      target = "#{paths.base}/tmp/#{folder}"
      base   = gulp.src "#{paths.base}/varaa/**/*.*"
      ext    = gulp.src "#{paths.base}/#{folder}/**/*.*"
      streamqueue objectMode: true, base, ext
        .pipe chmod 755
        .pipe gulp.dest target

# Handful function to copy resources
copy = (from, to) ->
  gulp.src from, base: paths.tmp
    .pipe chmod 755
    .pipe gulp.dest to

gulp.task 'clean', ['clone'], ->
  # Use del.sync to make sure that built directory is empty
  del.sync [paths.dest]

  # Since there is no process for fonts/images, just copy them to target folder
  copy paths.fonts, paths.dest
  copy paths.img, paths.dest

# Build assets to be ready for production
gulp.task 'build', ['less', 'coffee'], ->
  # Folder `resources` has this structure
  # ```
  #   varaa            <---------------- Based assets
  #   clearbooking     <------|
  #   foo              <------|--------- Other instances
  #   bar              <------|
  # ```
  # Folder `varaa` acts as the root folder and other folders just need to make
  # changes that are not presenting in `varaa`. While building, assets in
  # `varaa` will be cloned to all instances
  #
  # The build process is, clean the target folder first, clone resources from
  # `varaa` to other instances, then build from clone folders.

  # After building, we'll merge all manifest files into one
  manifests = {}
  for type in ['js', 'script', 'style']
    do ->
      _.merge manifests, require "./#{paths.dest}#{type}-manifest.json"

  # Write the file
  fs.writeFile "#{__dirname}/rev-manifest.json", JSON.stringify manifests, null, '  '
