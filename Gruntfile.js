// Information about URL for page
var name = 'roosmekaniska';

module.exports = function(grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),



    // CSS
    // Compile style.scss and compress it
    sass: {
      production: {
        options: {
          style: 'compressed'
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
    criticalcss: {
      home: {
        options: {
          url: "http://dev." + name + ".se/index.php",
          width: 1200,
          height: 900,
          outputfile: "critical/critical.css",
          filename: "style.css",
          buffer: 800*1024,
          ignoreConsole: false
        }
      }
      // Add more pages here "pagename: { ..content.. }"
    },



    // IMAGES
    webp: {
      files: {
        expand: true,
        cwd: 'build/images/',
        src: ['**/*.png', '**/*.jpg'],
        dest: 'build/webp/'
      },
      options: {
        quality: 90,
        alphaQuality: 90
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
        files: ['sass/style.scss'],
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
          src : ['*.html', '*.php', '*.css', 'js/*.js']
        },
        options: {
          watchTask: true,
          proxy: "dev." + name + ".se/"
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
  grunt.registerTask('production', ['jshint', 'uglify:production', 'sass:production', 'autoprefixer', 'watch']);
  grunt.registerTask('css', ['criticalcss']);

}
