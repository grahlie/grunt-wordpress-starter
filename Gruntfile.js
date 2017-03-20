module.exports = function(grunt) {
  grunt.initConfig({
    config: {
      pkg: grunt.file.readJSON('package.json'),
      // name: path.basename(process.cwd()),
      name: 'grunt-wordpress-starter',
      deploy: '/Volumes/Storage/www/',
      wp: {
        version: 'wordpress-4.7.3-sv_SE',
        link: 'https://sv.wordpress.org/'
      },
    },

    // Download packages from other places
    'curl-dir': {
      'downloads/': [
        '<%= config.wp.link %><%= config.wp.version %>.zip',
      ],
    },

    // Unzip downloaded folders
    unzip: {
      'extracted/': 'downloads/<%= config.wp.version %>.zip',
    },

    // Remove stuff from WordPress intallation
    clean: {
      wp: [
        'extracted/wordpress/wp-content/themes/twenty*',
        'extracted/wordpress/wp-content/themes/index.php',
        'extracted/wordpress/wp-content/plugins/akismet',
        'extracted/wordpress/wp-content/plugins/hello.php',
      ],
      sass: [
        'extracted/wordpress/wp-content/themes/grahlie/style.css.map',
        'extracted/wordpress/wp-content/themes/grahlie/sass',
      ],
      js: [
        'extracted/wordpress/wp-content/themes/grahlie/js/development',
      ]
    },

    copy: {
      build: {
        files: [{
          expand: true,
          cwd: 'theme/',
          src: ['**'],
          dest: 'extracted/wordpress/wp-content/themes/grahlie/'
        }]
      },
      deploy: {
        files: [{
          expand: true,
          cwd: 'extracted/wordpress/',
          src: '**',
          dest: '<%= config.deploy %><%= config.name %>',
        }]
      }
    },

    // CSS
    // Compile style.scss and compress it
    sass: {
      production: {
        options: {
          style: 'compressed'
        },
        files: {
          'extracted/wordpress/wp-content/themes/grahlie/style.css': 'extracted/wordpress/wp-content/themes/grahlie/sass/style.scss'
        }
      },
      dev: {
        option: {
          style: 'expanded'
        },
        files: {
          'extracted/wordpress/wp-content/themes/grahlie/style.css': 'extracted/wordpress/wp-content/themes/grahlie/sass/style.scss'
        }
      }
    },
    autoprefixer:{
      dist:{
        files:{
          'extracted/wordpress/wp-content/themes/grahlie/style.css':'extracted/wordpress/wp-content/themes/grahlie/style.css'
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
          'extracted/wordpress/wp-content/themes/grahlie/js/scripts.min.js': ['extracted/wordpress/wp-content/themes/grahlie/js/development/**/*.js']
        }
      },
      dev: {
        options: {
          mangle: false,
          beautify: true
        },
        files: {
          'extracted/wordpress/wp-content/themes/grahlie/js/scripts.min.js': ['extracted/wordpress/wp-content/themes/grahlie/js/development/**/*.js']
        }
      }
    },



    // WATCH
    watch: {
      options: {
        livereload: true,
      },
      php: {
        files: ['theme/**.php'],
        tasks: ['copy']
      },
      css: {
        files: ['theme/sass/**/*.scss'],
        tasks: ['copy:build', 'sass:dev', 'autoprefixer', 'clean:sass', 'copy:deploy']
      },
      js: {
        files: ['theme/js/development/**/*.js'],
        tasks: ['copy:build', 'jshint', 'uglify:dev', 'clean:js', 'copy:deploy']
      }
    }
  });

  // GRUNT LOADS
  grunt.loadNpmTasks('grunt-curl');
  grunt.loadNpmTasks('grunt-zip');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-autoprefixer');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-browser-sync');

  // GRUNT TRIGGERS
  grunt.registerTask('default', ['curl-dir', 'unzip', 'jshint', 'copy:build', 'uglify:dev', 'sass:dev', 'autoprefixer', 'clean', 'copy:deploy']);
  grunt.registerTask('production', ['curl-dir', 'unzip', 'jshint', 'copy:build', 'uglify:production', 'sass:production', 'autoprefixer', 'clean', 'copy:deploy']);

}
