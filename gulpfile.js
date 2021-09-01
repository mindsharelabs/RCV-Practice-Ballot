const gulp = require('gulp');
const sass = require('gulp-sass');
const del = require('del');


gulp.task('block-styles', () => {
    return gulp.src('scss/style.scss')
        .pipe(sass({
          outputStyle: 'compressed'
        }).on('error', sass.logError))
        .pipe(gulp.dest('inc/css/'))
});


gulp.task('clean', () => {
    return del([
        'css/block-styles.css',
    ]);
});

gulp.task('watch', () => {
    gulp.watch('scss/*.scss', (done) => {
        gulp.series(['clean', 'block-styles', ])(done);
    });
});

gulp.task('default', gulp.series(['clean', 'block-styles', 'watch']));
