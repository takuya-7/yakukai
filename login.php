<?php

require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　ログインページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

require('auth.php');

if(!empty($_POST)){
    debug('POST送信があります。');
    //変数にユーザー情報代入
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $pass_save = (!empty($_POST['pass_save'])) ? true : false;

    //バリデーション
    //email
    validEmail($email, 'email');
    validMaxLen($email, 'email');

    //pass
    validHalf($pass, 'pass');
    validMinLen($pass, 'pass');
    validMaxLen($pass, 'pass');

    //未入力チェック
    validRequired($email, 'email');
    validRequired($pass, 'pass');
    

    if(empty($err_msg)){
        debug('バリデーションOKです。');
        try{
            //データベースでemailがマッチしたidとパスワードを取得して$resultに入れる
            $dbh = dbConnect();
            $sql = 'SELECT password,id FROM users WHERE email = :email AND delete_flg = 0';
            $data = array(':email' => $email);

            $stmt = queryPost($dbh, $sql, $data);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            //debugで$resultの中身をログに残す
            debug('クエリ結果の中身:' . print_r($result,true));
            //debug($result);   はどうなるのか？？？？？？　配列をそのまま出力できない？


            //if文で取得したパスワードを照合（$resultが空ではない && $passをハッシュ化したものと$result内のハッシュ化されたパスワードの値が合致）
            if(!empty($result) && password_verify($pass, array_shift($result))){
                //マッチした場合
                //debugでマッチしたことをログに残す
                debug('パスワードがマッチしました');
                //時間に関する処理：ログイン時間・ログイン期限を設定
                //（最終ログイン日時を更新、ログイン保持にチェックがついていれば有効期限$_SESSION['login_limit']を30日にする）
                $sesLimit = 60*60;
                $_SESSION['login_date'] = time();

                if($pass_save){
                    debug('ログイン保持にチェックがあります。');
                    $_SESSION['login_limit'] = $sesLimit * 24 * 30;
                }else{
                    debug('ログイン保持にチェックはありません。');
                    $_SESSION['login_limit'] = $sesLimit;
                }
                
                //DBから取得したユーザーIDを$_SESSIONに格納
                $_SESSION['user_id'] = $result['id'];

                //$_SESSIONの中身をログに残す
                debug('セッション変数の中身：' . print_r($_SESSION, true));

                //マイページへ遷移
                debug('マイページへ遷移します。');
                header('Location:mypage.php');
                exit();
            }else{
                //マッチしなかった場合
                //debugでアンマッチだったことを残す
                debug('パスワードがアンマッチです。');
                //MSG09を格納
                $err_msg['common'] = MSG09;
            }
            
        }catch ( Exception $e){
            //debugでエラー内容を出力
            debug('エラー発生：' . $e->getMessage());
            //MSG07を格納
            $err_msg['common'] = MSG07;
        }
    }
}
debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>

<?php
$siteTitle = 'ログイン';
require('head.php');
?>

  <body class="page-login">

    <!-- メニュー -->
    <?php
      require('header.php');
    ?>

    <!-- サクセスメッセージ表示 -->
    <!-- 表示されないんだが？？？？？passRemindReceive.phpからlogin.phpに来るとmsg_successの中身が消えてるんだが？？？？？？？？？？？？なんで？？？？？？ -->
    <!-- ↑の答え：passRemindReceive.phpで、header後にexit()をしておらず、その後の処理が走り、getSessionFlash内で中身が空にされていたため -->
    <!-- 結論：headerの後はexitをつけろ！ -->
    <p id="js-show-msg" style="display:none;" class="msg-slide">
      <?php echo getSessionFlash('msg_success'); ?>
    </p>

    
    <main>
      <div class="container">
        <div class="form-container">
          <form action="" method="post" class="form natural-shadow col col-sm-9 col-md-7 col-lg-6">
            <h2 class="title">ログイン</h2>
            
            <div class="area-msg">
              <?php 
                if(!empty($err_msg['common'])) echo $err_msg['common'];
              ?>
            </div>

            <label class="<?php if(!empty($err_msg['email'])) echo 'err'; ?>">
              メールアドレス
              <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">
            </label>
            <div class="area-msg">
              <?php 
              if(!empty($err_msg['email'])) echo $err_msg['email'];
              ?>
            </div>

            <label class="<?php if(!empty($err_msg['pass'])) echo 'err'; ?>">
              パスワード
              <input type="password" name="pass" value="<?php if(!empty($_POST['pass'])) echo $_POST['pass']; ?>">
            </label>
            <div class="area-msg">
              <?php 
              if(!empty($err_msg['pass'])) echo $err_msg['pass'];
              ?>
            </div>

            <label>
                <input type="checkbox" name="pass_save"> 次回ログインを省略する
            </label>

              <div class="btn-container">
                <input type="submit" class="btn btn-mid" value="ログイン">
              </div>
              パスワードを忘れた方は<a href="passRemindSend.php">コチラ</a>
          </form>
        </div>

      </div>
    </main>

    <!-- footer -->
    <?php
      require('footer.php');
    ?>
