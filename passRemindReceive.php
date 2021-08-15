<?php

require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　パスワード再発行認証キー入力ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

// 認証キーが送られている人か確認（$_SESSION['auth_key']を持っているか？）。なければ認証キー送信ページへ
if(empty($_SESSION['auth_key'])){
  header('Location:passRemindSend.php');
  exit();
}

//================================
// 画面処理
//================================

if(!empty($_POST)){
  debug('POST送信があります。');
  debug('POST送信内容：' . print_r($_SESSION, true));

  $auth_key = $_POST['token'];

  // token未入力チェック
  validRequired($auth_key, 'token');

  if(empty($err_msg['token'])){
    debug('token未入力チェックOK');

    // tokenバリデーション（固定長、半角か）
    validLen($auth_key, 'token');
    validHalf($auth_key, 'token');

    if(empty($err_msg)){
      debug('tokenバリデーションOK！');

      // 認証キーが合っているか
      if($auth_key !== $_SESSION['auth_key']){
          $err_msg['common'] = MSG17;
      }

      // 認証期限内か
      if(time() > $_SESSION['auth_key_limit']){
          $err_msg['common'] = MSG18;
      }

      if(empty($err_msg)){
        debug('認証キー照合OK。認証期限内です。');

        // パスワード生成
        $pass = makeRandKey();

        debug('生成パスワード：' . $pass);      // メールは送れている体でやるため、デバッグで生成パスワードを確認。

        try{
          // DBに生成したパスワードを登録
          $dbh = dbConnect();
          $sql = 'UPDATE users SET password = :pass WHERE email = :email AND delete_flg = 0';
          $data = array(':email' => $_SESSION['auth_email'], ':pass' => password_hash($pass, PASSWORD_DEFAULT));
          $stmt = queryPost($dbh, $sql, $data);

          if($stmt){
            debug('クエリ成功。生成したパスワードの登録が完了しました。');

            // 生成したパスワードをメール送信
            $from = 'support@yakukai.net';
            $to = $_SESSION['auth_email'];
            $subject = '【パスワード再発行完了】｜ヤクカイ';
            $comment = <<<EOM
本メールアドレス宛にパスワードの再発行を致しました。
下記のURLにて再発行パスワードをご入力頂き、ログインください。

ログインページ：https://yakukai.net/login.php
再発行パスワード：{$pass}
※ログイン後、パスワードのご変更をお願い致します

////////////////////////////////////////
ヤクカイサポート
URL  https://yakukai.net/
E-mail support@yakukai.net
////////////////////////////////////////
EOM;
            // セッションunset
            session_unset();
            // unsetを使わないと、下の$_SESSIONを使えない。セッションIDが変わってしまう。destroyすると$_SESSION自体なくなるから？

            // SUC03：メールを送信しました
            $_SESSION['msg_success'] = SUC03;

            debug('セッション変数の中身：' . print_r($_SESSION, true));

            // ログインページへ遷移
            header('Location:login.php');
            exit();
          }else{
              debu('クエリに失敗しました。');
              $err_msg['common'] = MSG07;
          }
        } catch (Exception $e) {
            error_log('エラー発生：' . $e->getMessage());
            $err_msg['common'] = MSG07;
        }
      }
    }
  }
}

debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>

<?php
$siteTitle = 'パスワード再発行メール送信';
require('head.php');
?>

  <body>
    <div class="l-all-wrapper">
    
    <?php
      require('header.php');
    ?>

    <p id="js-show-msg" style="display:none;" class="msg-slide">
        <?php echo getSessionFlash('msg_success'); ?>
    </p>

    <main>
      <div class="l-content-wrapper">
        <div class="l-container">
          <form action="" method="post" class="c-form c-form--small u-mb-4">
            <p>ご指定のメールアドレスお送りした【パスワード再発行認証】メール内にある「認証キー」をご入力ください。</p>

            <fieldset class="c-form__field u-mb-5">
              <div class="c-form__message">
                  <?php echo getErrMsg('common'); ?>
              </div>
      
              <label class="<?php if(!empty($err_msg['token'])) echo 'err'; ?>">
                認証キー
                <input type="text" name="token" value="<?php echo getFormData('token'); ?>">
              </label>
      
              <div class="c-form__message">
                  <?php echo getErrMsg('token'); ?>
              </div>
            </fieldset>
    
            <div class="u-mb-4">
                <input type="submit" class="c-button c-button--blue c-button--width100" value="再発行する">
            </div>
          </form>
          <a href="passRemindSend.php">&lt; パスワード再発行メールを再度送信する</a>
        </div>
      </div>
    </main>

    <?php
      require('footer.php');
    ?>