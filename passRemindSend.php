<?php

require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　パスワード再発行メール送信ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

// ログイン認証はしない

//================================
// 画面処理
//================================

if(!empty($_POST)){
  debug('POST送信があります');
  debug('POST情報：' . print_r($_POST, true));

  $email = $_POST['email'];

  // email未入力チェック
  validRequired($email, 'email');

  if(empty($err_msg['email'])){
    debug('email未入力チェックOK');

    // emailバリデーション
    validEmail($email, 'email');
    validMaxLen($email, 'email');

    if(empty($err_msg['email'])){
      debug('emailバリデーションチェックOK');

      try{
        // 入力されたemailがDBに登録されているか（合致する数を取得？）
        $dbh = dbConnect();
        $sql = 'SELECT count(*) FROM users WHERE email = :email AND delete_flg = 0';
        $data = array(':email' => $email);

        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);   // 結果は0,1、true, falseで返ってくる

        // クエリ結果の値を取得
        $result = $stmt->fetch(PDO::FETCH_ASSOC);     //クエリ結果の中身：件数を取得している

        // 検索に引っかかってない場合： $result = array(1) { ["count(*)"]=> string(1) "0" }
        // 検索に引っかかってない場合でも!empty($result)はtrueになる

        // 登録が確認できれば認証キーを発行
        if($stmt && array_shift($result)){
          debug('クエリ成功。emailの登録あり！');
          $_SESSION['msg_success'] = SUC03;   // SUC03：メールを送信しました

          // 認証キー作成
          $auth_key = makeRandKey();

          // 認証キーをメールに記載して送信
          $from = 'info@market.com';
          $to = $email;
          $subject = '【パスワード再発行認証】｜ヤクカイ';
          $comment = <<<EOM
本メールアドレス宛にパスワード再発行のご依頼がありました。
下記のURLにて認証キーをご入力頂くとパスワードが再発行されます。

パスワード再発行認証キー入力ページ：http://localhost:8888/yakukai.com/passRemindRecieve.php
認証キー：{$auth_key}
※認証キーの有効期限は30分となります

認証キーを再発行されたい場合は下記ページより再度再発行をお願い致します。
http://localhost:8888/yakukai.com/passRemindSend.php

////////////////////////////////////////
ヤクカイ運営
URL  https://yakukai.com
E-mail info@yakukai.com
////////////////////////////////////////
EOM;
          
          sendMail($from, $to, $subject, $comment);

          // セッション変数に認証キー、email、認証期限を入れておく
          $_SESSION['auth_key'] = $auth_key;
          $_SESSION['auth_email'] = $email;
          $_SESSION['auth_key_limit'] = time() + (60*30);    // 認証は30分制限

          // セッション変数の中身をデバッグ
          debug('セッション変数の中身：' . print_r($_SESSION, true));
  
          // 認証キー入力ページへ遷移
          header('Location:passRemindReceive.php');
          exit();

        }else{
          debug('クエリに失敗したかDBに登録のないEmailが入力されました。');
          $err_msg['common'] = MSG07;
        }
        
      } catch (Exception $e){
        error_log('エラー発生：' . $e->getMessage());
        $err_msg['common'] = MSG07;
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

  <body class="page-login page-1colum">

    <!-- メニュー -->
    <?php
      require('header.php');
    ?>

    <main>
      <div class="l-content-wrapper">
        <div class="l-container">
          <h1>パスワード再発行</h1>
          <form action="" method="post" class="c-form">
            <p>ご指定のメールアドレス宛にパスワード再発行用のURLと認証キーをお送りします。</p>
      
            <fieldset class="c-form__field u-mb-5">
              <div class="c-form__message">
                  <?php echo getErrMsg('common'); ?>
              </div>
      
              <label class="<?php if(!empty($err_msg['email'])) echo 'err'; ?>">
                  Email
                  <input type="text" name="email" value="<?php echo getFormData('email'); ?>">
              </label>
      
              <div class="c-form__message">
                  <?php echo getErrMsg('email'); ?>
              </div>
            </fieldset>
      
            <div class="u-mb-4">
              <button type="submit" class="c-button c-button--blue c-button--width100">送信する</button>
            </div>
          </form>
          <a href="mypage.php">&lt; マイページに戻る</a>
        </div>
      </div>
    </main>

    


    <!-- footer -->
    <?php
      require('footer.php');
    ?>
