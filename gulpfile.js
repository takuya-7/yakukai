//plug-in
var gulp = require('gulp');   // browserify使えるので
// var minifycss = require('gulp-minify-css');
var minifycss = require('gulp-clean-css');
var sass = require('gulp-sass');
var changed  = require('gulp-changed');
var imagemin = require('gulp-imagemin');
var browserify = require('browserify');
var source     = require('vinyl-source-stream');

// タスクの書き方はそれぞれのライブラリのサイトに載っている

// gulpの実行の仕方
// gulp タスク名で実行できる
// gulp minify-css

// gulpタスクの作成
// gulp.task()を使う
// 第一引数に任意のタスク名、第二引数に実行したい処理をfunction関数で渡してあげる
// 関数内ではpipe()で処理を繋ぐ（jQueryのメソッドチェーンと同じ）

// sassをコンパイル
gulp.task('sass', function(){
  gulp.src('./src/scss/style.scss')
  .pipe(sass())
  .pipe(gulp.dest('dist/scss/'));
});

//CSS圧縮
gulp.task('minify-css', function() {
  return gulp.src("dist/scss/style.css")  // 対象のファイルの指定（*で全てのCSSファイル指定）
  .pipe(minifycss())    // 圧縮実行
  .pipe(gulp.dest('dist/css/'));  // 圧縮したファイルをどこに置くか
});

// 画像圧縮
// 圧縮前と圧縮後のディレクトリを定義
var paths = {
  srcDir : 'src',
  dstDir : 'dist'
};
// jpg,png,gif画像の圧縮タスク
gulp.task('imagemin', function(){
  var srcGlob = paths.srcDir + '/**/*.+(jpg|jpeg|png|gif)';   // **で以下の階層全てという意味
  var dstGlob = paths.dstDir + '/img/';
  gulp.src( srcGlob )
  .pipe(changed( dstGlob ))   // 吐き出し前と吐き出し後の差分を見て圧縮
  .pipe(imagemin([      // 画像圧縮
    imagemin.gifsicle({interlaced: true}),
    imagemin.jpegtran({progressive: true}),
    imagemin.optipng({optimizationLevel: 5})
  ]
  ))
  .pipe(gulp.dest( dstGlob ));
});


// gulpからbrowserify実行
gulp.task('browserify', function () {
  return browserify(paths.srcDir + '/js/app.js')
  .bundle()
  .pipe(source('bundle.js'))
  .pipe(gulp.dest(paths.dstDir + '/js'));
});

// defaultで動かすタスクを指定
// defaultに設定しておくとgulpコマンドだけでタスクが実行される
// 書き方は第二引数に配列でタスクを指定する
// gulp.task(‘default’,[‘タスク名’,’タスク名’,’タスク名’,…]);
gulp.task('default',['sass', 'minify-css', 'browserify', 'imagemin']);
// 「gulp」と打つだけで上記全て実行できるように！

// Gulpを使ったファイルの監視
// watch()を使う
// 第一引数は監視したいディレクトリ配下
// 第二引数に変更があった場合に実行するタスクを配列形式で指定
gulp.task('watch', function(){
  // src配下で変更があれば、画像を圧縮
  gulp.watch(paths.srcDir + '/**/*', ['imagemin']);
  // src/scss配下のscssファイルに変更があれば、scssをcssにコンパイル
  gulp.watch(paths.srcDir + '/scss/*.scss', ['sass']);
  // dist/scss配下のcssファイルに変更があれば、cssを圧縮
  gulp.watch(paths.dstDir + '/scss/*.css', ['minify-css']);
});