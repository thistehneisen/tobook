module.exports = (grunt) ->
  grunt.initConfig
    less:
      development:
        files: [
            { src: 'public/assets/less/main.less', dest: 'public/assets/css/main.css' },
            { src: 'public/assets/less/dashboard.less', dest: 'public/assets/css/dashboard.css' },
            { src: 'public/assets/less/home.less', dest: 'public/assets/css/home.css' },
            { src: 'public/assets/less/search.less', dest: 'public/assets/css/search.css' },
            { src: 'public/assets/less/as/appointment.less', dest: 'public/assets/css/as/appointment.css' },
            { src: 'public/assets/less/as/layout-1.less', dest: 'public/assets/css/as/layout-1.css' }
            { src: 'public/assets/less/fd/main.less', dest: 'public/assets/css/fd/main.css' },
            { src: 'public/assets/less/lc/loyalty.less', dest: 'public/assets/css/lc/loyalty.css' }
            { src: 'public/assets/less/lc/lcapp.less', dest: 'public/assets/css/lc/lcapp.css' }
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
