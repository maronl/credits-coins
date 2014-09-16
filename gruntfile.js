module.exports = function(grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        uglify: {
            options: {
                banner: '/*! <%= pkg.name %> (<%= pkg.version %>) - <%= grunt.template.today("yyyy-mm-dd") %> */\n',
                compress: { drop_console: true }
            },
            admin_options: {
                    src: 'admin/js/credits-coins-admin-options.js',
                    dest: 'admin/js/prod/credits-coins-admin-options.<%= pkg.version %>.min.js'
            },
            admin_profile: {
                src: 'admin/js/credits-coins-admin-user-profile.js',
                dest: 'admin/js/prod/credits-coins-admin-user-profile.<%= pkg.version %>.min.js'
            },
            public: {
                src: 'public/js/credits-coins-public.js',
                dest: 'public/js/prod/credits-coins-public.<%= pkg.version %>.min.js'
            }
        },
        watch: {
            scripts: {
                files: ['admin/js/*.js','public/js/*.js'],
                tasks: ['uglify'],
            },
            options: {
                livereload: true,
                spawn: false
            }
        },
    });

    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-uglify');

    grunt.registerTask('default', ['uglify', 'watch']);
};