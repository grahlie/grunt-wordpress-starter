// Information about URL for page
var name = 'wordpress';

module.exports = function(grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    // CSS
    // Compile style.scss and compress it
    sass: {
      production: {
        options: {
          style: 'expanded'
        },
        files: {
          'style.css': 'sass/style.scss'
        }
      },
      dev: {
        option: {
          style: 'expanded'
        },
        files: {
          'style.css': 'sass/style.scss'
        }
      }
    },
    autoprefixer:{
      dist:{
        files:{
          'style.css':'style.css'
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
          'js/scripts.min.js': ['js/development/**/*.js']
        }
      },
      dev: {
        options: {
          mangle: false,
          beautify: true
        },
        files: {
          'js/scripts.min.js': ['js/development/**/*.js']
        }
      }
    },



    // WATCH
    // Watch for changes in js and scss files
    watch: {
      css: {
        files: ['sass/*.scss', 'sass/**/*.scss'],
        tasks: ['sass:dev', 'autoprefixer']
      },
      js: {
        files: ['js/development/**/*.js'],
        tasks: ['jshint', 'uglify:dev']
      }
    },
    // Updates all browsers
    browserSync: {
      default_options: {
        bsFiles: {
          src : ['*.html', '*.php', '**/*.php', '*.css', 'js/*.js']
        },
        options: {
          watchTask: true,
          proxy: "dev." + name + ".se"
        }
      }
    }
  });

  // GRUNT LOADS
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-autoprefixer');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-browser-sync');

  // GRUNT TRIGGERS
  grunt.registerTask('dev', ['jshint', 'uglify:dev', 'sass:dev', 'autoprefixer', 'browserSync', 'watch']);
  grunt.registerTask('production', ['jshint', 'uglify:production', 'sass:production', 'autoprefixer']);

}
