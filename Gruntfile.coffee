module.exports = (grunt) ->
  grunt.initConfig
    less:
      development:
        files: [
            {
                expand: true,
                cwd: 'public/assets/less',
                src: ['**/*.less', '!_vars.less', '!old_main.less'],
                dest: 'public/assets/css/',
                ext: '.css',
                extDot: 'first'
            },
        ]
    watch:
      style:
        files: [
          'public/assets/less/*.less',
          'public/assets/less/as/*.less',
          'public/assets/less/lc/*.less',
        ],
        tasks: ['less']

  grunt.loadNpmTasks 'grunt-contrib-less'
  grunt.loadNpmTasks 'grunt-contrib-watch'

  grunt.registerTask 'default', [
    'watch'
  ]
