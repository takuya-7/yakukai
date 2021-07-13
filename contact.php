<?php
require('config.php');
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「 お問い合わせ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

// POSTパラメータを取得
//----------------------------
if(!empty($_POST)){
  debug('POST送信があります。');
  debug('POST情報：' . print_r($_POST, true));

  $name = $_POST['name'];
  $email = $_POST['email'];
  $title = $_POST['title'];
  $message = $_POST['message'];

  // バリデーション
  validRequired($name, 'name');
  validRequired($email, 'email');
  validRequired($title, 'title');
  validRequired($message, 'message');

  if(empty($err_msg)){
    validMaxLen($name, 'name', 20);
    validEmail($email, 'email');
    validMaxLen($email, 'email');
    validMaxLen($title, 'title', 50);
    validMaxLen($message, 'message', 800);
  }

  if(empty($err_msg)){
    debug('バリデーションOK');
    // メール送信
    $from = 'support@yakukai.net';
    $to = $email;
    $subject = '【お問い合わせ】'.$title.'｜ヤクカイ';
    $comment = <<<EOM
【{$name}様より下記内容でお問い合わせがありました。】
{$message}

////////////////////////////////////////
ヤクカイサポート
URL  https://yakukai.net
E-mail support@yakukai.net
////////////////////////////////////////
EOM;
    
    sendMail($from, $to, $subject, $comment);
  }
}
?>

<?php
$siteTitle = 'お問い合わせ';
require('head.php');
?>

<body>
  <div class="l-all-wrapper">
  <?php
    require('header.php');
  ?>

  <main>
    <div class="l-content-wrapper">
      <div class="l-container">
        <div class="l-content">
          <div class="l-inner-container">
            <h1 class="u-text-center u-mb-4">お問い合わせ</h1>
            
            <form action="" method="post" class="c-form">

              <fieldset class="c-form__field">
                <label class="<?php if(!empty($err_msg['name'])) echo 'err'; ?>">
                  お名前 （必須）
                  <input type="text" name="name" value="<?php if(!empty($_POST['name'])) echo $_POST['name']; ?>">
                </label>
                <div class="c-form__message">
                  <?php 
                  if(!empty($err_msg['name'])) echo $err_msg['name'];
                  ?>
                </div>
              </fieldset>

              <fieldset class="c-form__field">
                <label class="<?php if(!empty($err_msg['email'])) echo 'err'; ?>">
                  メールアドレス （必須）
                  <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">
                </label>
                <div class="c-form__message">
                  <?php 
                  if(!empty($err_msg['email'])) echo $err_msg['email'];
                  ?>
                </div>
              </fieldset>
              
              <fieldset class="c-form__field">
                <label class="<?php if(!empty($err_msg['title'])) echo 'err'; ?>">
                  題名 （必須）
                  <input type="text" name="title" value="<?php if(!empty($_POST['title'])) echo $_POST['title']; ?>">
                </label>
                <div class="c-form__message">
                  <?php 
                  if(!empty($err_msg['title'])) echo $err_msg['title'];
                  ?>
                </div>
              </fieldset>

              <fieldset class="c-form__field">
                <label class="<?php if(!empty($err_msg['message'])) echo 'err'; ?>">
                  メッセージ本文 （必須）
                  <textarea name="message" class="c-form__field__textarea<?php if(!empty($err_msg['message'])) echo ' err'; ?>" style="min-height: 8rem;"><?php if(!empty($_POST['message'])) echo $_POST['message']; ?></textarea>
                </label>
                <div class="c-form__message">
                  <?php
                  if(!empty($err_msg['message'])) echo $err_msg['message'];
                  ?>
                </div>
              </fieldset>

              <div class="u-mb-4">
                <button type="submit" class="c-button c-button--blue c-button--width100">送信する</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>

  <?php
    require('footer.php');
  ?>