// gulpfile.js

var gulp = require('gulp');
var cleanCSS = require('gulp-clean-css');
var sass = require('gulp-sass');
var uglify = require('gulp-uglify');
var browserSync = require('browser-sync').create();

var paths = {
    src: 'src/**/*',
    srcHTML: 'src/**/*.html',
    srcCSS: 'src/**/*.css',
    srcJS: 'src/**/*.js',

    dist: 'dist',
    distIndex: 'dist/index.html',
    distCSS: 'dist/**/*.css',
    distJS: 'dist/**/*.js'
};

gulp.task('sss', function () {
    return gulp.src('src/scss/style.scss')
        .pipe(sass())
        .pipe(cleanCSS({ compatibility: 'ie8' }))
        .pipe(gulp.dest('public/css'));
});

gulp.task('jsMinify', function () {
    return gulp.src('src/js/test.js')
        .pipe(uglify())
        .pipe(gulp.dest('public/js'));
});



gulp.task('watch', function () {
    gulp.watch('src/scss/*.scss', gulp.series('sss'));
});

gulp.task('do', (done) => {

    // Do some task
    console.log("task done");
    done();
});