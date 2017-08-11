module.exports = function (grunt) {

    require('load-grunt-tasks')(grunt);
    require('time-grunt')(grunt);

    // For convenience put this file into your document root folder.

    // This is where we configure each task that we'd like to run.
    grunt.initConfig({
        themePath: '.', // Replace this with the actual path, e.g. wp-content/themes/THEME_NAME
        pkg: grunt.file.readJSON('package.json'),

        watch: {
            // This is where we set up all the tasks we'd like grunt to watch for changes.
            scripts: {
                files: ['<%= themePath %>/js/source/**/*.js'],
                tasks: ['clean:js', 'concat', 'uglify', 'notify:watch'],
                options: {
                    spawn: false
                }
            },
            images: {
                files: ['<%= themePath %>/img/source/**/*.{png,jpg,gif}'],
                tasks: ['imagemin', 'notify:watch'],
                options: {
                    spawn: false
                }
            },
            vector: {
                files: ['<%= themePath %>/img/source/**/*.svg'],
                tasks: ['svgmin', 'notify:watch'],
                options: {
                    spawn: false
                }
            },
            css: {
                files: ['<%= themePath %>/sass/**/*.{scss,sass}'],
                tasks: ['clean:css', 'sass', 'postcss', 'cssmin', 'notify:watch']
            }
        },

        notify: {
            watch: {
                options: {
                    title: 'Compilation complete',
                    message: 'SASS, Uglify, and imagemin finished'
                }
            }
        },

        clean: {
            options: {
                dot: true
            },
            css: ['<%= themePath %>/css/*.css*'],
            js: ['<%= themePath %>/js/build/*.js*']
        },

        // Concatenate JavaScript files before minimizing.
        concat: {
            options: {
                separator: ';',
                sourceMap: true
            },
            dist: {
                src: ['<%= themePath %>/js/source/**/*.js'],
                dest: '<%= themePath %>/js/build/site.js'
            }
        },

        // This is for minifying all of our scripts.
        uglify: {
            options: {
                sourceMap: false
            },
            my_target: {
                files: {
                    '<%= themePath %>/js/build/site.min.js': ['<%= themePath %>/js/build/site.js']
                }
            }
        },

        // This will optimize all of our images for the web.
        imagemin: {
            dynamic: {
                files: [{
                    expand: true,
                    cwd: '<%= themePath %>/img/source/',
                    src: ['<%= themePath %>/**/*.{png,jpg,gif}'],
                    dest: '<%= themePath %>/img/optimized/'
                }]
            }
        },

        svgmin: {
            options: {
                plugins: [{
                    removeViewBox: false
                },
                    {
                        removeUselessStrokeAndFill: false
                    }]
            },
            dist: {
                files: [{
                    expand: true,
                    cwd: '<%= themePath %>/img/source/',
                    src: ['<%= themePath %>/{,*/}*.svg'],
                    dest: '<%= themePath %>/img/optimized/'
                }]
            }
        },

        // This will compile all of our sass files
        sass: {
            // Additional configuration options can be found at https://github.com/sindresorhus/grunt-sass
            options: {
                sourceMap: true,
                // This controls the compiled css and can be changed to nested, compact or compressed.
                outputStyle: 'expanded',
                precision: 5
            },
            dist: {
                files: {
                    '<%= themePath %>/css/site.css': '<%= themePath %>/sass/site.scss',
                    '<%= themePath %>/css/print.css': '<%= themePath %>/sass/theme/print.scss',
                    '<%= themePath %>/css/wysiwyg.css': '<%= themePath %>/sass/theme/wysiwyg.scss'
                }
            }
        },

        postcss: {
            options: {
                map: true,
                processors: [
                    require('autoprefixer')({browsers: 'last 5 versions'}),
                    require('postcss-flexibility')
                ]
            },
            dist: {
                src: '<%= themePath %>/css/*.css'
            }
        },

        cssmin: {
            options: {
                roundingPrecision: -1
            },
            target: {
                files: {
                    '<%= themePath %>/css/site.min.css': ['<%= themePath %>/css/site.css']
                }
            }
        },

        modernizr: {
            dist: {
                "crawl": false,
                "customTests": [],
                "dest": "js/vendor/modernizr.js",
                "tests": [
                    "csscolumns",
                    "flexbox",
                    "history",
                    "inlinesvg",
                    "picture"
                ],
                "options": [
                    "html5shiv",
                    "html5printshiv",
                    "setClasses"
                ],
                "uglify": true
            }
        }

    });

    grunt.registerTask('default', [
        'clean',
        'sass',
        'postcss',
        'cssmin',
        'concat',
        'uglify',
        'imagemin',
        'svgmin'
    ]);

    grunt.registerTask('watch', [
        'clean',
        'sass',
        'postcss',
        'cssmin',
        'concat',
        'uglify',
        'imagemin',
        'svgmin',
        'notify:watch',
        'watch'
    ]);
};
