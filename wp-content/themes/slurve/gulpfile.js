const gulp = require("gulp");
const sass = require("gulp-sass");
const concat = require("gulp-concat");
const uglify = require("gulp-uglify");
const rename = require("gulp-rename");
const minifyCss = require("gulp-minify-css");
const sassGlob = require("gulp-sass-glob");
const sassFiles = "assets/scss/**/*.?(s)css";
const jsFiles = "assets/js/**/*.js";

gulp.task("sass", function() {
  return gulp
    .src(sassFiles)
    .pipe(sassGlob())
    .pipe(sass().on("error", sass.logError))
    .pipe(concat("app.css"))
    .pipe(gulp.dest("build/"))
    .pipe(rename("app.min.css"))
    .pipe(minifyCss())
    .pipe(gulp.dest("build/"));
});

gulp.task("js", function() {
  return gulp
    .src(["assets/js/scripts.js"])
    .pipe(concat("app.js"))
    .pipe(gulp.dest("build/"))
    .pipe(rename("app.min.js"))
    .pipe(uglify())
    .pipe(gulp.dest("build/"));
});

gulp.task("watch", function() {
  gulp.watch(sassFiles, gulp.series(["sass"]));
  gulp.watch(jsFiles, gulp.series(["js"]));
});

gulp.task("default", gulp.series(["sass", "js"]));
