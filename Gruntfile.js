module.exports = function(grunt) {
  grunt.initConfig({
      pkg: grunt.file.readJSON('package.json'),
      config: grunt.file.readJSON('config.json'),

    // Download packages from other places
    // 'curl-dir': {
    //   'downloads/': [
    //     '<%= config.grunt.wp.link %><%= config.grunt.wp.version %>.zip',
    //   ],
    // },

    // Unzip downloaded folders
    // unzip: {
    //   'extracted/': 'downloads/<%= config.grunt.wp.version %>.zip',
    // },

    // PHP
    copy: {
      deploy: {
        files: [{
          expand: true,
          cwd: 'theme/',
          src: ['**/*.php', 'framework/**/*'],
          dest: '<%= config.grunt.deploy %>',
        }]
      }
    },

    // CSS
    sass: {
      production: {
        options: {
          style: 'compressed'
        },
        files: {
          '<%= config.grunt.deploy %>/style.css': 'theme/sass/style.scss'
        }
      },
      dev: {
        option: {
          style: 'expanded'
        },
        files: {
          '<%= config.grunt.deploy %>/style.css': 'theme/sass/style.scss'
        }
      }
    },
    autoprefixer:{
      dist:{
        files:{
          '<%= config.grunt.deploy %>/style.css':'<%= config.grunt.deploy %>/style.css'
        }
      }
    },

    
    // JS
    // Check js files for errors
    jshint: {
      all: ['/js/development/*.js', '!js/development/libs/**/*.js']
    },

    // Minify all js files
    uglify: {
      production: {
        files: {
          '<%= config.grunt.deploy %>/js/scripts.min.js': ['theme/js/development/**/*.js']
        }
      },
      dev: {
        options: {
          mangle: false,
          beautify: true
        },
        files: {
          '<%= config.grunt.deploy %>/js/scripts.min.js': ['theme/js/development/**/*.js']
        }
      }
    },



    // WATCH
    watch: {
      options: {
        livereload: true,
      },
      php: {
        files: ['theme/**/*.php'],
        tasks: ['copy:deploy']
      },
      css: {
        files: ['theme/sass/**/*.scss'],
        tasks: ['sass:dev', 'autoprefixer']
      },
      js: {
        files: ['theme/js/development/**/*.js'],
        tasks: ['jshint', 'uglify:dev']
      }
    }
  });

  // GRUNT LOADS
  // grunt.loadNpmTasks('grunt-curl');
  // grunt.loadNpmTasks('grunt-zip');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-autoprefixer');
  grunt.loadNpmTasks('grunt-contrib-watch');
  // grunt.loadNpmTasks('grunt-browser-sync');

  // GRUNT TRIGGERS
  grunt.registerTask('default', ['jshint', 'copy:deploy', 'uglify:dev', 'sass:dev', 'autoprefixer']);
  grunt.registerTask('dev', ['jshint', 'copy:deploy', 'uglify:dev', 'sass:dev', 'autoprefixer']);
  grunt.registerTask('production', ['jshint', 'copy:deploy', 'uglify:production', 'sass:production', 'autoprefixer']);
}
