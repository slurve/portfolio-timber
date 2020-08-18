module.exports = function(grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    sass: {
      options: {
        loadPath: require('node-bourbon').includePaths
      },
      dist: {
        options: {
          style: 'compressed'
        },
        files: {
          'style.css': 'scss/style.scss'
        }
      },
      dev: {
        options: {
          style: 'nested',
          sourcemap: 'none'
        },
        files: {
          'style.css': 'scss/style.scss'
        }
      }
    },

    concat: {
      css: {
        src: [
          'style.css'
        ],
        dest: 'style.css'
      }
    },

    watch: {
      grunt: { files: ['Gruntfile.js'] },

      sass: {
        files: 'scss/**/*.scss',
        tasks: ['sass:dev', 'concat']
      },

      js: {
        files: [
					'js/main.js'
        ],
        tasks: ['concat'],
        options: {
          spawn: false,
        }
      },

    }

  });

  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-watch');

  grunt.registerTask('build', ['sass:dist','concat']);
  grunt.registerTask('dev', ['sass:dev','concat']);
  grunt.registerTask('default', ['build']);
  
}