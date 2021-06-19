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

                // 画面遷移処理
                $dbUserData = getUser($result['id']);
                $dbPostData = getPost($result['id']);
                $dbRatingData = getRatingByUserId($result['id']);
                $dbAnswerData = getAnswer($result['id']);

                if(empty($dbUserData['birth_year'])){
                  debug('プロフィール登録がありません。プロフィール入力へ遷移します。');
                  header('Location:profRegist.php');
                  exit();
                }elseif(empty($dbPostData[0]['company_id'])){
                  debug('会社登録がありません。クチコミ投稿ページへ遷移します。');
                  header('Location:surveyInfo.php');
                  exit();
                }elseif(empty($dbPostData[0]['employment_type'])){
                  debug('クチコミ登録がありません。クチコミ投稿ページ（1/3）へ遷移します。');
                  header('Location:survey01.php');
                  exit();
                }elseif(empty($dbRatingData[0]['rating'])){
                  debug('評価値が投稿されていません。クチコミ投稿ページ（2/3）へ遷移します。');
                  header('Location:survey02.php');
                  exit();
                }elseif(empty($dbAnswerData[0]['answer'])){
                  debug('フリー回答がありません。クチコミ投稿ページ（3/3）へ遷移します。');
                  header('Location:survey03.php');
                  exit();
                }else{
                  debug('マイページへ遷移します。');
                  header('Location:mypage.php');
                  exit();
                }
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

    <p id="js-show-msg" style="display:none;" class="msg-slide">
      <?php echo getSessionFlash('msg_success'); ?>
    </p>

    
    <main>
      <div class="l-content-wrapper">
        <div class="l-container">
          
          <form action="" method="post" class="c-form c-form--small">
            <h2 class="c-form__title">ログイン</h2>
            
            <div class="c-form__message">
              <?php 
                if(!empty($err_msg['common'])) echo $err_msg['common'];
              ?>
            </div>

            <fieldset class="c-form__field">
              <label class="<?php if(!empty($err_msg['email'])) echo 'err'; ?>">
                メールアドレス
                <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">
              </label>
              <div class="c-form__message">
                <?php 
                if(!empty($err_msg['email'])) echo $err_msg['email'];
                ?>
              </div>
            </fieldset>

            <fieldset class="c-form__field">
              <label class="<?php if(!empty($err_msg['pass'])) echo 'err'; ?>">
                パスワード
                <input type="password" name="pass" value="<?php if(!empty($_POST['pass'])) echo $_POST['pass']; ?>">
              </label>
              <div class="c-form__message">
                <?php 
                if(!empty($err_msg['pass'])) echo $err_msg['pass'];
                ?>
              </div>
            </fieldset>

            <label class="u-mb-3">
                <input type="checkbox" name="pass_save"> 次回ログインを省略する
            </label>

            <div class="u-mb-4">
              <button type="submit" class="c-button c-button--blue c-button--width100">ログイン</button>
            </div>

            <div class="u-mb-3">
              パスワードをお忘れの方は<a href="passRemindSend.php">コチラ</a>
            </div>

            <div>
              会員登録がまだの方は<a href="signup.php">コチラ</a>からご登録ください。
            </div>
          </form>
        </div>
      </div>
    </main>

    <!-- footer -->
    <?php
      require('footer.php');
    ?>
