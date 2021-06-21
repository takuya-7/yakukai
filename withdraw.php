<?php
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「  退会ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

require('auth.php');

if(!empty($_POST)){
  debug('POST送信があります。');

  try{
    $dbh = dbConnect();

    $sql1 = 'UPDATE  users SET delete_flg = 1 WHERE id = :id';
    // $sql2 = 'UPDATE  favorite SET delete_flg = 1 WHERE user_id = :id';

    $data = array(':id' => $_SESSION['user_id']);
    
    $stmt1 = queryPost($dbh, $sql1, $data);

    if($stmt1){
      debug('usersテーブルで「delete_flg = 1」の設定が完了しました。');

      $_SESSION = array();    //セッション変数を空に。
      session_destroy();

      //クッキーとして記録されているセッションIDを破棄する
      if(isset($_COOKIE['session_name'])){
          setcookie(session_name(), '', time()-42000, '/');
      }

      debug('セッション変数の中身：' . print_r($_SESSION, true)); //session_destroy()でセッションIDは破棄されたものの、セッション変数は破棄されておらず残っているため出力される

      debug('トップページへ遷移します。');
      header('Location:index.php');
    }
      
  } catch (Exeption $e){
      error_log('エラー発生：' . $e->getMessage());
      $err_msg['common'] = MSG07;
  }
}

debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>

<?php
$siteTitle = '退会';
require('head.php');
?>

<body>

    <?php
      require('header.php');
    ?>

    <main>
        <div class="l-content-wrapper">
            <div class="l-container">
                <div class="u-mb-3">
                  <form action="" method="post" class="c-form c-form--small">
                      <h1  class="c-page-title">退会</h1>
                      <div class="c-box">
                        <p>退会しても投稿済みのクチコミは削除されませんのでご注意ください。</p>
                      </div>
                      <div class="u-mb-3">
                          <input type="submit" class="c-button c-button--blue c-button--width100" value="退会する" name="submit">
                      </div>
                  </form>
                </div>
                <a href="mypage.php">&lt; マイページに戻る</a>
            </div>
        </div>
    </main>

    <?php
      require('footer.php');
    ?>