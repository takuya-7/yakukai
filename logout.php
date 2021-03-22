<?php

require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　ログアウトページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

debug('ログアウトします。');

session_destroy();

debug('ログインページへ遷移します。');
header("Location:login.php");


// セッション削除の方法３つ
// $_SESSION = array();　セッション変数を空にする
// session_unset();     IDは削除せず、セッション変数の中身を消す
// session_destroy();   IDも全て削除する（セッション変数、セッションクッキーを破棄しない）