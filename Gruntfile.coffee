module.exports = (grunt) ->
  grunt.initConfig
    less:
      development:
        files: [
            { src: 'public/assets/less/main.less', dest: 'public/assets/css/main.css' },
            { src: 'public/assets/less/as/appointment.less', dest: 'public/assets/css/as/appointment.css' },
            { src: 'public/assets/less/as/layout-1.less', dest: 'public/assets/css/as/layout-1.css' }
        ]
    watch:
      style:
        files: ['public/assets/less/*.less', 'public/assets/less/as/*.less'],
        tasks: ['less']

  grunt.loadNpmTasks 'grunt-contrib-less'
  grunt.loadNpmTasks 'grunt-contrib-watch'

  grunt.registerTask 'default', [
    'watch'
  ]
