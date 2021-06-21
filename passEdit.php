<?php
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「  パスワード変更ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

require('auth.php');

$userData = getUser($_SESSION['user_id']);
debug('取得したユーザー情報：' . print_r($userData, true));

if(!empty($_POST)){
    debug('POST送信があります。');
    debug('POST情報：' . print_r($_POST, true));

    // 変数にユーザ情報を代入
    $pass_old = $_POST['pass_old'];
    $pass_new = $_POST['pass_new'];
    $pass_new_re = $_POST['pass_new_re'];

    // バリデーション
    validRequired($pass_old, 'pass_old');
    validRequired($pass_new, 'pass_new');
    validRequired($pass_new_re, 'pass_new_re');

    // DBに保存されているハッシュ化されているパスワードを取得
    try{
        $dbh = dbConnect();
        $sql = 'SELECT password, id FROM users WHERE id = :id AND email = :email AND delete_flg = 0';
        $data = array(
            ':id' => $_SESSION['user_id'],
            ':email' => $userData['email']
        );
        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    }catch ( Exception $e){
        debug('エラー発生：' . $e->getMessage());
        $err_msg['common'] = MSG07;
    }

    if(empty($err_msg)){
        debug('未入力チェックOK。');

        // 各種バリデーション
        // 入力されたパスワードのバリデーション
        validPass($pass_old, 'pass_old');
        validPass($pass_new, 'pass_new');

        // 入力された現在のパスワードとDBのパスワードを照合。違っていればエラーメッセージを代入
        if(!password_verify($pass_old, $result['password'])){
            $err_msg['common'] = MSG14;
        }

        // 新しいパスワードと現在のパスワードが違うかチェック。同じならエラーメッセージを代入
        if($pass_old === $pass_new){
            $err_msg['pass_new'] = MSG15;
        }

        // 新しいパスワードの再入力があっているか
        validMatch($pass_new, $pass_new_re, 'pass_new_re');

        if(empty($err_msg)){
            debug('バリデーションOK！');

            try{
                $dbh = dbConnect();
                $sql = 'UPDATE users SET password = :password WHERE id = :id';
                $data = array(':id' => $_SESSION['user_id'], ':password' => password_hash($pass_new, PASSWORD_DEFAULT));

                $stmt = queryPost($dbh, $sql, $data);

                if($stmt){
                    debug('パスワードの変更に成功しました！');
                    $_SESSION['msg_success'] = SUC01;

//                     // メール送信
//                     $username = ($userData['username']) ? $userData['username'] : 'ご利用者';
//                     $from = 'info@gmail.com';
//                     $to = $userData['email'];
//                     $subject = 'メール変更完了　｜　ヤクカイ';
//                     $comment = <<<EOM
// { $username }様

// パスワードが変更されました。
                      
// ////////////////////////////////////////
// ヤクカイ
// URL  https://yakukai.com/
// E-mail info@yakukai.com
// ////////////////////////////////////////
// EOM;
//                     sendMail($from, $to, $subject, $comment);
                    header('Location:mypage.php');
                // }else{
                //     debug('クエリに失敗しました。');
                //     $err_msg['common'] = MSG07;
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
$siteTitle = 'パスワード変更';
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
                    <h1  class="c-page-title">パスワード変更</h1>

                    <?php if(getErrMsg('common')){ ?>
                        <div class="c-form__message">
                            <?php echo getErrMsg('common'); ?>
                        </div>
                    <?php } ?>

                    <fieldset class="c-form__field">
                        <label class="<?php if(!empty($err_msg['pass_old'])) echo 'err'; ?>">
                            現在のパスワード
                            <input type="password" name="pass_old" value="<?php echo getFormData('pass_old'); ?>">
                        </label>
                        <div class="c-form__message">
                            <?php echo getErrMsg('pass_old'); ?>
                        </div>
                    </fieldset>
                    
                    <fieldset class="c-form__field">
                        <label class="<?php if(!empty($err_msg['pass_new'])) echo 'err'; ?>">
                            新しいパスワード
                            <input type="password" name="pass_new" value="<?php echo getFormData('pass_new'); ?>">
                        </label>
                        <div class="c-form__message">
                            <?php echo getErrMsg('pass_new'); ?>
                        </div>
                    </fieldset>
                    
                    <fieldset class="c-form__field">
                        <label class="<?php if(!empty($err_msg['pass_new_re'])) echo 'err'; ?>">
                            新しいパスワード（再入力）
                            <input type="password" name="pass_new_re" value="<?php echo getFormData('pass_new_re'); ?>">
                        </label>
                        <div class="c-form__message">
                            <?php echo getErrMsg('pass_new_re'); ?>
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