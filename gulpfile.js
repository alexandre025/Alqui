var gulp       = require('gulp');
var browserify = require('gulp-browserify');
var sass       = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var autoprefixer = require('gulp-autoprefixer');

gulp.task('watch', function() {
  gulp.watch('src/js/**/*.js', ['js']);
  gulp.watch('src/sass/**/*.scss', ['sass']);
});

// SCSS Files
gulp.task('sass', function () {
  gulp.src('./src/sass/main.scss')
    .pipe(sourcemaps.init())
    .pipe(sass())
    .pipe(sourcemaps.write())
    .pipe(autoprefixer({
      browsers: ['last 2 versions'],
      cascade: false
    }))
    .pipe(gulp.dest('./public/css'))
});

// JS Files
gulp.task('js', function() {
  gulp.src('src/js/app.js')
    .pipe(browserify({
      insertGlobals : true,
      debug : true
    }))
    .pipe(gulp.dest('./public/js'))
});

gulp.task('default', ['js', 'sass', 'watch']);
