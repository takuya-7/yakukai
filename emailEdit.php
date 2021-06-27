<?php
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「  メールアドレス変更ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

require('auth.php');

$userData = getUser($_SESSION['user_id']);
debug('取得したユーザー情報：' . print_r($userData, true));

if(!empty($_POST)){
  debug('POST送信があります。');
  debug('POST情報：' . print_r($_POST, true));

  // 変数にユーザ情報を代入
  $email = $_POST['email'];

  // バリデーション
  validRequired($email, 'email');

  if(empty($err_msg)){
    validEmail($email, 'email');
    validMaxLen($email, 'email');
  }
  if(empty($err_msg)){
    debug('バリデーションOK');
    // メールアドレス変更
    try{
      $dbh = dbConnect();
      $sql = 'UPDATE users SET email = :email WHERE id = :id';
      $data = array(
        ':email' => $email,
        ':id' => $_SESSION['user_id'],
      );
      $stmt = queryPost($dbh, $sql, $data);
      if($stmt){
        debug('メールアドレスの変更に成功しました！');
        $_SESSION['msg_success'] = SUC06;
        // 変更確認メール送信
  //       $username = ($userData['username']) ? $userData['username'] : 'ご利用者';
  //       $from = 'info@yakukai.net';
  //       $to = $email;
  //       $subject = 'メールアドレス変更完了　｜　ヤクカイ';
  //       $comment = <<<EOM
  // { $username }様
  
  // メールアドレスが変更されました。
                        
  // ////////////////////////////////////////
  // ヤクカイ
  // URL  https://yakukai.net/
  // E-mail info@yakukai.net
  // ////////////////////////////////////////
  // EOM;
  //       sendMail($from, $to, $subject, $comment);
        header('Location:mypage.php');
      }
    }catch(Exception $e){
      error_log('エラー発生：' . $e->getMessage());
      $err_msg['common'] = MSG07;
    }
  }
}
debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>

<?php
$siteTitle = 'メールアドレス変更';
require('head.php');
?>

<body>

    <?php
      require('header.php');
    ?>

    <main>
      <div class="l-content-wrapper">
        <div class="l-container">
          <form action="" method="post" class="c-form c-form--small">
            <h1  class="c-page-title">メールアドレス変更</h1>

            <div class="c-box u-mb-4">
              <p>現在のメールアドレス</p>
              <div class="u-ms-3">
                <?php echo $userData['email']; ?>
              </div>
            </div>

            <?php if(getErrMsg('common')){ ?>
              <div class="c-form__message">
                  <?php echo getErrMsg('common'); ?>
              </div>
            <?php } ?>

            <fieldset class="c-form__field">
              <label class="<?php if(!empty($err_msg['email'])) echo 'err'; ?>">
                新規メールアドレス
                <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">
              </label>
              <div class="c-form__message">
                  <?php echo getErrMsg('pass_old'); ?>
              </div>
            </fieldset>

            <div class="u-mb-3">
              <button type="submit" class="c-button c-button--blue c-button--width100">変更する</button>
            </div>
          </form>
        </div>
      </div>
    </main>

    <?php
      require('footer.php');
    ?>