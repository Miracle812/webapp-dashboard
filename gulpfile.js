/*
 *  Altair Admin
 *  Automated tasks ( http://gulpjs.com/ )
 *
 *  1. minify/concatenate js files
 *  2. less to css
 *  3. minify json
 *  4. browser sync http://www.browsersync.io/docs/
 *  5. process all js
 *  6. process all less
 *  7. default task
 *  8. build release folder
 *  9. helpers
 *
 */

var gulp = require('gulp'),
    plugins = require("gulp-load-plugins")({
        pattern: ['gulp-*', 'gulp.*', '*'],
        replaceString: /\bgulp[\-.]/
    }),

    replace = require('gulp-replace'),
    concat = require('gulp-concat'),

    browserSync = require('browser-sync').create('bs_html'),

    chalk = require('chalk'),
    chalk_error = chalk.bold.red,

    pjson = require('./package.json'),
    version = pjson.version;


gulp.task('0_full_required_tasks', ['1_repair_less_path','2_newassets_clean','3_generate_navitas_theme','4_default','5_resources_copy']);
gulp.task('1_repair_less_path', function(){
    gulp.src(['resources/altair/assets/less/uikit_custom.less'])
        .pipe(replace('../../bower_components/', 'resources/vendor/'))
        .pipe(
        gulp.dest(function(file) {
            return file.base;
        })
    );
    gulp.src(['resources/altair/assets/less/plugins/_selectize.less'])
        .pipe(replace('../../../bower_components/', 'resources/vendor/'))
        .pipe(
        gulp.dest(function(file) {
            return file.base;
        })
    );
    gulp.src(['resources/altair/assets/less/plugins/_prism.less'])
        .pipe(replace('../../../bower_components/', 'resources/vendor/'))
        .pipe(
        gulp.dest(function(file) {
            return file.base;
        })
    );
    gulp.src(['resources/altair/assets/less/_uikit_custom.less'])
        .pipe(replace('../../bower_components/', 'resources/vendor/'))
        .pipe(
        gulp.dest(function(file) {
            return file.base;
        })
    );
});

gulp.task('2_newassets_clean', function() {
    return plugins.del.sync(
        ['public/newassets/**/*','public/data/**/*'],
        { force: true },
        function (err, paths) {console.log(err)}
    );
});


// generate my theme
gulp.task('3_generate_navitas_theme', function() {
    return gulp.src('resources/navitas/less/theme.less')
        .pipe(plugins.less())
        .on('error', function(err) {
            console.log(chalk_error(err.message));
            this.emit('end');
        })
        .pipe(plugins.autoprefixer({
            browsers: ['> 5%','last 2 versions'],
            cascade: false
        }))
        .pipe(gulp.dest('public/newassets/css/'))
        .pipe(plugins.minifyCss({
            keepSpecialComments: 0,
            advanced: false
        }))
        .pipe(plugins.rename('navitas_theme.min.css'))
        .pipe(gulp.dest('resources/navitas/css'))
        .pipe(gulp.dest('public/newassets/css/'));
});

gulp.task('4_default', ['default'/*,'codemirror_themes'*/]);

// copy files from /src
gulp.task('5_resources_copy',function() {
    // copy favicon
    var root_files = gulp.src(['favicon.ico'])
        .pipe(gulp.dest(release_dist_dir));

    // copy resources/vendor
    var bower_files = gulp.src([
            'resources/vendor/**',
            'resources/navitas/packages/**',
            '!resources/vendor/{autosize,autosize/**}',
            '!resources/vendor/{dense,dense/**}',
            '!resources/vendor/{fastclick,fastclick/**}',
            '!resources/vendor/{hammerjs,hammerjs/**}',
            '!resources/vendor/{jquery,jquery/**}',
            '!resources/vendor/{jquery.actual,jquery.actual/**}',
            '!resources/vendor/{jquery.dotdotdot,jquery.dotdotdot/**}',
            '!resources/vendor/{jquery.scrollbar,jquery.scrollbar/**}',
            '!resources/vendor/{jquery-bez,jquery-bez/**}',
            '!resources/vendor/{jquery-icheck,jquery-icheck/**}',
            '!resources/vendor/{kendo-ui,kendo-ui/**}',
            '!resources/vendor/{marked,marked/**}',
            '!resources/vendor/{modernizr,modernizr/**}',
            '!resources/vendor/{prism,prism/**}',
            '!resources/vendor/{selectize,selectize/**}',
            '!resources/vendor/{switchery,switchery/**}',
            '!resources/vendor/{velocity,velocity/**}',
            '!resources/vendor/{waypoints,waypoints/**}'
        ])
        .pipe(gulp.dest('public/newassets/packages/'));

    // copy icons
    var icons_files = gulp.src(['resources/altair/assets/icons/**'])
        .pipe(gulp.dest('public/newassets/icons/'));

    // copy images
    var img_files = gulp.src(['resources/altair/assets/img/**','resources/navitas/img/**'])
        .pipe(gulp.dest('public/newassets/img/'));

    //var js_files = gulp.src(['resources/altair/assets/js/**/*.js','resources/navitas/js/**/*.js'])
    var js_files = gulp.src(['resources/altair/assets/js/**/*.js','resources/navitas/js/**/**'])
        .pipe(gulp.dest('public/newassets/js/'));

    // copy data
    var json_files = gulp.src(['resources/altair/data/**/*.json','data/**/*.php','resources/navitas/data/**/*.json'])
        .pipe(gulp.dest('public/data/'));

    // copy codemirror files
    var codemirror_files = gulp.src('resources/altair/data/codemirror/*')
        .pipe(gulp.dest('public/data/codemirror/'));

    
    // copy easytree files
    var easytree_files = gulp.src('resources/altair/assets/js/custom/easytree/skin-material/*')
        .pipe(gulp.dest('public/newassets/js/custom/easytree/skin-material'));
    

    return plugins.mergeStream(root_files,bower_files,icons_files,img_files,js_files,json_files,codemirror_files,easytree_files);

});









// 1. -------------------- MINIFY/CONCATENATE JS FILES --------------------

// commmon
gulp.task('common_js', function () {
    return gulp.src([
        "resources/vendor/jquery/dist/jquery.js",
        "resources/vendor/modernizr/modernizr.js",
        "resources/vendor/moment/moment.js",
        "resources/vendor/dense/src/dense.js",
        "resources/vendor/fastclick/lib/fastclick.js",
        "resources/vendor/jquery.scrollbar/jquery.scrollbar.js",
        "resources/vendor/jquery-bez/jquery.bez.min.js",
        "resources/vendor/jquery.actual/jquery.actual.js",
        "resources/vendor/waypoints/lib/jquery.waypoints.js",
        "resources/vendor/velocity/velocity.js",
        "resources/vendor/velocity/velocity.ui.js",
        "resources/vendor/jQuery.dotdotdot/src/js/jquery.dotdotdot.js",
        "resources/vendor/jquery-icheck/icheck.js",
        "resources/vendor/selectize/dist/js/standalone/selectize.js",
        "resources/vendor/switchery/dist/switchery.js",
        "resources/vendor/prism/prism.js",
        "resources/vendor/prism/components/prism-php.js",
        "resources/vendor/prism/plugins/line-numbers/prism-line-numbers.js",
        "resources/vendor/autosize/dist/autosize.js",
        "resources/vendor/hammerjs/hammer.js",
        "resources/vendor/jquery.debouncedresize/js/jquery.debouncedresize.js",
        "resources/vendor/screenfull/dist/screenfull.js",
        "resources/vendor/Waves/dist/waves.js"
    ])
        .pipe(plugins.concat('common.js'))
        .on('error', function(err) {
            console.log(chalk_error(err.message));
            this.emit('end');
        })
        .pipe(gulp.dest('public/newassets/js/'))
        .pipe(plugins.uglify({
            mangle: true
        }))
        .pipe(plugins.rename('common.min.js'))
        .pipe(plugins.size({
            showFiles: true
        }))
        .pipe(gulp.dest('public/newassets/js/'));
});

// cutom uikit
gulp.task('uikit_js', function () {
    return gulp.src([
        // uikit core
        "resources/vendor/uikit/js/uikit.js",
        // uikit components
        "resources/vendor/uikit/js/components/accordion.js",
        "resources/vendor/uikit/js/components/autocomplete.js",
        "resources/altair/assets/js/custom/uikit_datepicker.js",
        "resources/vendor/uikit/js/components/form-password.js",
        "resources/vendor/uikit/js/components/form-select.js",
        "resources/vendor/uikit/js/components/grid.js",
        "resources/vendor/uikit/js/components/lightbox.js",
        "resources/vendor/uikit/js/components/nestable.js",
        "resources/vendor/uikit/js/components/notify.js",
        "resources/vendor/uikit/js/components/sortable.js",
        "resources/vendor/uikit/js/components/sticky.js",
        "resources/vendor/uikit/js/components/tooltip.js",
        "resources/altair/assets/js/custom/uikit_timepicker.js",
        "resources/vendor/uikit/js/components/upload.js",
        "resources/altair/assets/js/custom/uikit_beforeready.js"
    ])
        .pipe(plugins.concat('uikit_custom.js'))
        .pipe(gulp.dest('public/newassets/js/'))
        .pipe(plugins.uglify({
            mangle: true
        }))
        .pipe(plugins.rename('uikit_custom.min.js'))
        .pipe(plugins.size({
            showFiles: true
        }))
        .pipe(gulp.dest('public/newassets/js/'));
});

// uikit htmleditor
gulp.task('uikit_htmleditor_js', function () {
    return gulp.src([
        // htmleditor
        "resources/vendor/codemirror/lib/codemirror.js",
        "resources/vendor/codemirror/mode/markdown/markdown.js",
        "resources/vendor/codemirror/addon/mode/overlay.js",
        "resources/vendor/codemirror/mode/javascript/javascript.js",
        "resources/vendor/codemirror/mode/php/php.js",
        "resources/vendor/codemirror/mode/gfm/gfm.js",
        "resources/vendor/codemirror/mode/xml/xml.js",
        "resources/vendor/marked/lib/marked.js",
        "resources/vendor/uikit/js/components/htmleditor.js"
    ])
        .pipe(plugins.concat('uikit_htmleditor_custom.js'))
        .pipe(gulp.dest('public/newassets/js/'))
        .pipe(plugins.uglify({
            mangle: true
        }).on('error', function(e) {
            console.log('\x07',e.message); return this.end();
        }))
        .pipe(plugins.rename('uikit_htmleditor_custom.min.js'))
        .pipe(plugins.size({
            showFiles: true
        }))
        .pipe(gulp.dest('public/newassets/js/'));
});

// custom kendoui
gulp.task('kendoui_js', function () {
    // js
    return  gulp.src([
        "resources/vendor/kendo-ui/src/js/kendo.core.js",
        "resources/vendor/kendo-ui/src/js/kendo.color.js",
        "resources/vendor/kendo-ui/src/js/kendo.data.js",
        "resources/vendor/kendo-ui/src/js/kendo.calendar.js",
        "resources/vendor/kendo-ui/src/js/kendo.popup.js",
        "resources/vendor/kendo-ui/src/js/kendo.datepicker.js",
        "resources/vendor/kendo-ui/src/js/kendo.timepicker.js",
        "resources/vendor/kendo-ui/src/js/kendo.datetimepicker.js",
        "resources/vendor/kendo-ui/src/js/kendo.list.js",
        "resources/vendor/kendo-ui/src/js/kendo.fx.js",
        "resources/vendor/kendo-ui/src/js/kendo.userevents.js",
        "resources/vendor/kendo-ui/src/js/kendo.menu.js",
        "resources/vendor/kendo-ui/src/js/kendo.draganddrop.js",
        "resources/vendor/kendo-ui/src/js/kendo.slider.js",
        "resources/vendor/kendo-ui/src/js/kendo.mobile.scroller.js",
        "resources/vendor/kendo-ui/src/js/kendo.autocomplete.js",
        "resources/vendor/kendo-ui/src/js/kendo.combobox.js",
        "resources/vendor/kendo-ui/src/js/kendo.dropdownlist.js",
        "resources/vendor/kendo-ui/src/js/kendo.colorpicker.js",
        "resources/vendor/kendo-ui/src/js/kendo.combobox.js",
        "resources/vendor/kendo-ui/src/js/kendo.maskedtextbox.js",
        "resources/vendor/kendo-ui/src/js/kendo.multiselect.js",
        "resources/vendor/kendo-ui/src/js/kendo.numerictextbox.js",
        "resources/vendor/kendo-ui/src/js/kendo.toolbar.js",
        "resources/vendor/kendo-ui/src/js/kendo.panelbar.js",
        "resources/vendor/kendo-ui/src/js/kendo.window.js"
    ])
        .pipe(plugins.concat('kendoui_custom.js'))
        .pipe(gulp.dest('public/newassets/js/'))
        .pipe(plugins.uglify({
            mangle: true
        }))
        .pipe(plugins.rename('kendoui_custom.min.js'))
        .pipe(plugins.size({
            showFiles: true
        }))
        .pipe(gulp.dest('public/newassets/packages/kendo-ui/'));

});

// common/page specific functions




gulp.task('page_specific_js', function () {
    gulp.src([
        'resources/navitas/js/common.js',
        'resources/altair/assets/js/altair_admin_common.js'
    ])
        .pipe(concat('concat_common.js'))
        .pipe(gulp.dest('resources/altair/assets/js/'));
    
    return gulp.src([
        'resources/altair/assets/js/concat_common.js',
        'resources/navitas/assets/js/common.js',
        'resources/altair/assets/js/pages/*.js',
        'resources/altair/assets/js/custom/*.js',
        'resources/navitas/js/custom/*.js',
        '!resources/navitas/js/**/*.min.js',
        '!resources/altair/assets/js/**/*.min.js'
    ])
        .pipe(plugins.uglify({
            mangle: true
        }))
        .pipe(plugins.rename({
            extname: ".min.js"
        }))
        .pipe(gulp.dest(function(file) {
            return file.base;
        }));
});
// 2. -------------------- LESS TO CSS --------------------

// main styles
gulp.task('less_main', function() {
    return gulp.src(['resources/altair/assets/less/main.less'])
        .pipe(plugins.less())
        .on('error', function(err) {
            console.log(chalk_error(err.message));
            this.emit('end');
        })
        .pipe(plugins.autoprefixer({
            browsers: ['> 5%','last 2 versions'],
            cascade: false
        }))
        .pipe(gulp.dest('public/newassets/css'))
        .pipe(browserSync.stream())
        .pipe(plugins.minifyCss({
            keepSpecialComments: 0,
            advanced: false
        }))
        .pipe(plugins.rename('main.min.css'))
        .pipe(gulp.dest('public/newassets/css'));
});

// error page
gulp.task('less_error_page', function() {
    return gulp.src('resources/altair/assets/less/pages/error_page.less')
        .pipe(plugins.less())
        .on('error', function(err) {
            console.log(chalk_error(err.message));
            this.emit('end');
        })
        .pipe(plugins.autoprefixer({
            browsers: ['> 5%','last 2 versions'],
            cascade: false
        }))
        .pipe(gulp.dest('public/newassets/css'))
        .pipe(browserSync.stream())
        .pipe(plugins.minifyCss({
            keepSpecialComments: 0,
            advanced: false
        }))
        .pipe(plugins.rename('error_page.min.css'))
        .pipe(gulp.dest('public/newassets/css'));
});

// login page
gulp.task('less_login_page', function() {
    return gulp.src('resources/altair/assets/less/pages/login_page.less')
        .pipe(plugins.less())
        .on('error', function(err) {
            console.log(chalk_error(err.message));
            this.emit('end');
        })
        .pipe(plugins.autoprefixer({
            browsers: ['> 5%','last 2 versions'],
            cascade: false
        }))
        .pipe(gulp.dest('public/newassets/css'))
        .pipe(browserSync.stream())
        .pipe(plugins.minifyCss({
            keepSpecialComments: 0,
            advanced: false
        }))
        .pipe(plugins.rename('login_page.min.css'))
        .pipe(gulp.dest('public/newassets/css'));
});

// 3. -------------------- MINIFY JSON --------------------

gulp.task('json_minify', function() {
    return gulp.src([
        'resources/altair/data/*.json',
        'resources/navitas/data/*.json',
        '!resources/data/*.min.json'
    ])
        .pipe(plugins.jsonminify())
        .on('error', function(err) {
            console.log(chalk_error(err.message));
            this.emit('end');
        })
        .pipe(plugins.rename({
            extname: ".min.json"
        }))
        .pipe(gulp.dest('public/data'));
});

// 4. -------------------- BROWSER SYNC http://www.browsersync.io/docs/ --------------------

// 4. -------------------- BROWSER SYNC http://www.browsersync.io/docs/ --------------------

gulp.task('browser-sync', function() {
    bs_html.init({
        // http://www.browsersync.io/docs/options/#option-host
        host: "10.0.0.188",
        // http://www.browsersync.io/docs/options/#option-proxy
        proxy: "altair_html.local",
        // http://www.browsersync.io/docs/options/#option-port
        port: '3011',
        // http://www.browsersync.io/docs/options/#option-notify
        notify: false,
        ui: {
            port: 3010
        }
    });

    gulp.watch([
        'assets/less/**/*.less',
        '!assets/less/pages/error_page.less',
        '!assets/less/pages/login_page.less'
    ],['less_main']);
    gulp.watch('assets/less/pages/error_page.less',['less_error_page']);
    gulp.watch('assets/less/pages/login_page.less',['less_login_page']);

    gulp.watch([
        '*.php',
        '*.html',
        'php/**/*.php',
        'assets/js/**/*.js',
        '!assets/js/**/*.min.js'
    ]).on('change', bs_html.reload);
});


// 5. -------------------- PROCESS ALL JS --------------------

gulp.task('all_js', ['common_js','uikit_js','uikit_htmleditor_js','kendoui_js','page_specific_js']);

// 6. -------------------- PROCESS ALL LESS ------------------

gulp.task('all_less', ['less_main','less_error_page','less_login_page', 'kendoui_css']);


// 7. -------------------- DEFAULT TASK ----------------------

gulp.task('default', function(callback) {
    return plugins.runSequence(
        ['all_less','all_js','json_minify'],
        callback
    );
});

// 8. -------------------- RELEASE --------------------------

var release_dir = '_release/'+version+'/',
    release_doc_dir  = release_dir + '/altair_v'+version+'/documentation/',
    release_dist_dir = release_dir + '/altair_v'+version+'/admin/dist/',
    release_src_dir  = release_dir + '/altair_v'+version+'/admin/src/';
release_navitas_dir  = release_dir + '_navitas_site/';

// clean release folder


// generate demo site
gulp.task('release_demo_copy',function() {
    return gulp.src(release_dist_dir+'/**')
        .pipe(gulp.dest(release_dir+'_demo_site/'));
});

// generate all pages from php to html (/dist)
gulp.task('release_dist_generate_pages', function(){
    var os  = require('os-utils');
    var browser = os.platform() === 'linux' ? 'google-chrome' : (
        os.platform() === 'darwin' ? 'google chrome' : (
            os.platform() === 'win32' ? 'chrome' : 'firefox'));
    var options = {
        uri: 'http://127.0.0.1/altair/helpers/generate_pages/',
        app: browser
    };
    return gulp.src(__filename)
        .pipe(
        plugins.open(options)
    );
});

// copy documentation
gulp.task('release_doc_copy', function() {
    var release_doc = gulp.src([
        '../../documentation/**/*',
        '!../../documentation/node_modules/',
        '!../../documentation/node_modules/**',
        '!../../documentation/resources/vendor/**',
        '!../../documentation/package.json',
        '!../../documentation/bower.json',
        '!../../documentation/gulpfile.js'
    ])
        .pipe(gulp.dest(release_doc_dir));

    var release_doc_uikit = gulp.src('../../documentation/resources/vendor/uikit/**')
        .pipe(gulp.dest(release_doc_dir+'resources/vendor/uikit/'));

    return plugins.mergeStream(release_doc,release_doc_uikit);
});

// copy admin /src
gulp.task('release_src_copy', function() {
    // admin /src
    gulp.src([
        '../src/**',
        '!../src/resources/vendor/',
        '!../src/resources/vendor/**',
        '!../src/node_modules/',
        '!../src/node_modules/**'
    ],{ dot: true })
        .pipe(gulp.dest(release_src_dir));
});

// copy admin /src
gulp.task('release_cleanup', function() {
    // remove redundant plugins/files from /dist
    return plugins.del(
        [
            release_dist_dir+'bower.json',
            release_navitas_dir+'bower.json'
        ],
        { force: true },
        function (err, paths) {}
    );
});

// replace images (distribution)
gulp.task('release_replace_images', function() {
    var replace_dist_img =  gulp.src('../../__release_images/**')
        .pipe(gulp.dest(release_dist_dir+'assets/img/'));

    var replace_src_img =  gulp.src('../../__release_images/**')
        .pipe(gulp.dest(release_src_dir+'assets/img/'));

    return plugins.mergeStream(replace_dist_img,replace_src_img);

});

// add info banner to files
var project_name = pjson.name;
gulp.task('release_header_js', function(callback) {
    return gulp.src([
        release_src_dir+'assets/js/pages/*.js',
        '!'+release_src_dir+'assets/js/pages/*.min.js'
    ])
        .pipe(plugins.wrapper({
            header: function(file) {
                var file_name_js = file.path.replace(file.base, '');

                if(file_name_js == 'dashbord.js') {
                    var file_name_html = 'index.html';
                } else if(file_name_js == 'kendoui.js')  {
                    var file_name_html = 'kendoui_*.html';
                } else {
                    var file_name_html = file_name_js.replace('.js','.html');
                }

                return '/*\n' +
                    '*  ' + project_name.replace("_", " ") + '\n' +
                    '*  @version v' + pjson.version + '\n' +
                    '*  @author ' + pjson.author + '\n' +
                    '*  @license ' + pjson.license + '\n' +
                    '*  ' + file_name_js +
                    ' - ' +  file_name_html + '\n' +
                    '*/\n' +
                    '\n'
            }
        }))
        .pipe(gulp.dest(release_src_dir+'public/assets/js/pages/'));
});

gulp.task('release',function(callback){
    plugins.runSequence(
        ['default','release_clean'],
        ['release_dist_copy','release_src_copy','release_doc_copy'],
        'release_demo_copy',
        ['release_replace_images','release_cleanup','release_header_js'],
        'release_dist_generate_pages',
        callback
    );
});

// 9. -------------------- HELPERS --------------------------

// concatenate codemirror themes
gulp.task('codemirror_themes', function() {
    return gulp.src([
        'resources/vendor/codemirror/lib/codemirror.css',
        'resources/vendor/codemirror/theme/*.css'
        ])
        .pipe(plugins.concat('codemirror_themes.css'))
        .pipe(plugins.minifyCss({
            keepSpecialComments: 0,
            advanced: false
        }))
        .pipe(plugins.rename('codemirror_themes.min.css'))
        .pipe(gulp.dest('public/newassets/css'));
});

gulp.task('kendoui_css', function() {
    gulp.src([
        'resources/vendor/kendo-ui/styles/kendo.common-material.min.css',
        'resources/vendor/kendo-ui/styles/kendo.material.min.css'
        ])
        .pipe(plugins.concat('kendo-ui.material.css'))
        .pipe(plugins.minifyCss({
            keepSpecialComments: 0,
            advanced: false
        }))
        .pipe(plugins.concat('kendo-ui.material.min.css'))
        .pipe(gulp.dest('public/newassets/packages/kendo-ui'));

    var bower_kendoui_img = gulp.src([
            'resources/vendor/kendo-ui/styles/Material/**'
        ])
        .pipe(gulp.dest('public/newassets/packages/kendo-ui/Material/'));
});