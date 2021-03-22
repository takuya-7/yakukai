<?php

//================================
// ログイン認証・自動ログアウト
//================================

//セッションでログイン時間を持っているか？
if(!empty($_SESSION['login_date'])){

    debug('ログイン済みユーザーです');

    //ログイン期限外なら（ログイン時間＋ログイン期限＜現在日時）ログアウトしてログインページに遷移させる
    //ログイン期限内ならマイページへ遷移させる
    if( ($_SESSION['login_date'] + $_SESSION['login_limit']) < time()){
        debug('ログイン有効期限オーバーです');

        session_destroy();
        header("Location:login.php");
        exit();
    }else{
        debug('ログイン有効期限内です');

        $_SESSION['login_date'] = time();

        if(basename($_SERVER['PHP_SELF']) === 'login.php'){
            debug('マイページへ遷移します');
            header("Location:mypage.php");
            exit();
        }
    }
}else{
    debug('未ログインユーザーです');

    if(basename($_SERVER['PHP_SELF']) !== 'login.php'){
        header("Location:login.php");
        exit();
    }
}