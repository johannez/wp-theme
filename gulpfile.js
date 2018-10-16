var gulp = require('gulp');
var argv = require('yargs').argv;

var autoprefixer = require('gulp-autoprefixer');
var imagemin = require('gulp-imagemin');
var pixrem = require('gulp-pixrem');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var svgmin = require('gulp-svgmin');
var svgstore = require('gulp-svgstore');
var rename = require('gulp-rename');
var uglify = require('gulp-uglify');

var paths = {
  sassSrc: 'sass/**/*.{scss,sass}',
  sassDest: 'css',
  jsSrc: 'js/source/*.js',
  jsDest: 'js/build',
  imgSrc: 'images/source/**/*.{png,jpg,gif}',
  imgDest: 'images/optimized',
  svgSrc: 'images/source/**/*.svg',
  svgDest: 'images/optimized'
}

var browserList = ['last 5 versions', '> 5%', 'Firefox ESR'];

gulp.task('sass', function () {
  return gulp.src('sass/site.scss')
    .pipe(sourcemaps.init())
    //.pipe(sassGlob())
    .pipe(sass({outputStyle: 'expanded'}).on('error', sass.logError))
    // Need the next two lines as an intermediate write, otherwise autoprefizer doesnt cooperate with sourcemaps
    // https://github.com/ByScripts/gulp-sample/blob/master/gulpfile.js
    .pipe(sourcemaps.write({includeContent: false}))
      .pipe(sourcemaps.init({loadMaps: true}))
    .pipe(autoprefixer({browsers: browserList}))
    .pipe(pixrem())
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(paths.sassDest));
});

gulp.task('js', function() {
  return gulp.src(paths.jsSrc)
    .pipe(sourcemaps.init())
    .pipe(uglify())
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(paths.jsDest));
});

gulp.task('imagemin', function() {
  return gulp.src(paths.imgSrc)
    .pipe(imagemin())
    .pipe(gulp.dest(paths.imgDest));
});

gulp.task('svgmin', function() {
  return gulp.src(paths.svgSrc)
    .pipe(svgmin())
    .pipe(gulp.dest(paths.svgDest));
});

gulp.task('svgstore', function () {
    return gulp
        .src(paths.svgSrc)
        .pipe(svgmin())
        .pipe(svgstore())
        .pipe(rename('icons.svg'))
        .pipe(gulp.dest(paths.svgDest));
});

// Watch files for change and set Browser Sync
gulp.task('watch', function() {
  gulp.watch(paths.sassSrc, ['sass']);
  gulp.watch(paths.jsSrc, ['js']);
});

// Default task
gulp.task('default', ['svgstore', 'sass', 'js']);
