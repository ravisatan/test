var gulp = require('gulp');
var sass = require('gulp-sass');
var uglifycss = require('gulp-uglifycss');
var uglify = require('gulp-uglify');
var browserSync = require('browser-sync').create();

//compile sass
gulp.task('sass', function () {
    return gulp.src('./src/scss/*.scss')
        .pipe(sass.sync().on('error', sass.logError))
        .pipe(gulp.dest('./dist/css'))
        .pipe(browserSync.stream());
});

//minify css
gulp.task('minCss', function () {
    return gulp.src('./dist/css/*.css')
        .pipe(uglifycss({
            "uglyComments": true
        }))
        .pipe(gulp.dest('./dist/css'));
});

//minify js
gulp.task('jsMin', function () {
    return gulp.src('./src/js/*.js')
        .pipe(uglify())
        .pipe(gulp.dest('./dist/js'));
});

//watch changes, reload browser
gulp.task('serve', ['sass', 'minCss', 'jsMin'], function () {

    browserSync.init({
        server: "./dist"
    });

    // browserSync.init({
    //     proxy   : "http://localhost/esok/dist/"
    // });

    gulp.watch("./src/scss/*.scss", ['sass']).on('change', browserSync.reload);
    gulp.watch('./src/js/*.js', ['jsMin']).on('change', browserSync.reload);
    gulp.watch('./dist/css/*.css', ['minCss']);
    gulp.watch("./dist/*.html").on('change', browserSync.reload);
    //gulp.watch("./dist/**").on('change', browserSync.reload); //for editing any file in the dir
});

//run all tasks
//gulp.task('run', ['sass', 'css', 'jsMinify']);

//run default task which then runs all tasks
gulp.task('default', ['serve']);
