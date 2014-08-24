module.exports = (grunt) ->
  grunt.initConfig
    less:
      development:
        files:
          'public/assets/css/main.css': 'public/assets/less/main.less'
    watch:
      style:
        files: ['public/assets/less/*.less'],
        tasks: ['less']

  grunt.loadNpmTasks 'grunt-contrib-less'
  grunt.loadNpmTasks 'grunt-contrib-watch'

  grunt.registerTask 'default', [
    'watch']
