<?php
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「  ユーザー登録');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

if(!empty($_POST)){

    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $pass_re = $_POST['pass_re'];

    //未入力チェック
    validRequired($email, 'email');
    validRequired($pass, 'pass');
    validRequired($pass_re, 'pass_re');


    if(empty($err_msg)){
        //Emailチェック（mailの形式か、最大文字数以下か、既に登録されていないか）
        validEmail($email, 'email');
        validMaxLen($email, 'email');
        
        if(empty($err_msg['email'])){
            validEmailDup($email);
        }
        

        //パスワードチェック（半角か、６文字以上か、２５５文字以下か）
        validHalf($pass, 'pass');
        validMinLen($pass, 'pass');
        validMaxLen($pass, 'pass');

        //パスワード（再入力）チェック
        validMinLen($pass_re, 'pass_re');
        validMaxLen($pass_re, 'pass_re');


        if(empty($err_msg)){
            //パスワード＝パスワード（再入力）？
            validMatch($pass, $pass_re, 'pass_re');

            if(empty($err_msg)){
                try{
                    //DBへ挿入
                    $dbh = dbConnect();
                    $sql = 'INSERT INTO users (email, password, login_time, create_date) VALUES (:email, :password, :login_time, :create_date)';
                    $data = array(
                        ':email' => $email,
                        ':password' => password_hash($pass, PASSWORD_DEFAULT),
                        ':login_time' => date('Y-m-d H:i:s'),
                        ':create_date' => date('Y-m-d H:i:s'),
                    );

                    $stmt = queryPost($dbh, $sql, $data);

                    if($stmt){
                        $sesLimit = 60 * 60;

                        $_SESSION['login_date'] = time();
                        $_SESSION['login_limit'] = $sesLimit;
                        $_SESSION['user_id'] = $dbh->lastInsertId();

                        debug('セッション変数の中身：' . print_r($_SESSION, true));

                        // プロフィール登録へ遷移
                        header('Location:profRegist.php');
                        exit();

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
}

?>

<?php
$siteTitle = 'ユーザー登録';
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
                <form action="" method="post" class="c-form c-form--small">
                    <h2 class="c-form__title">ユーザー登録</h2>
                    
                    <div class="c-form__message">
                        <?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?>
                    </div>

                    <fieldset class="c-form__field">
                        <label class="<?php if(!empty($err_msg['email'])) echo 'err'; ?>">
                            メールアドレス
                            <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>" placeholder="メールアドレスを入力してください">
                        </label>
                        <div class="c-form__message">
                            <?php if(!empty($err_msg['email'])) echo $err_msg['email']; ?>
                        </div>
                    </fieldset>

                    <fieldset class="c-form__field">
                        <label class="<?php if(!empty($err_msg['pass'])) echo 'err'; ?>">
                            パスワード
                            <input type="password" name="pass" value="<?php if(!empty($_POST['pass'])) echo $_POST['pass']; ?>" placeholder="パスワードを入力してください">
                        </label>
                        <div class="c-form__message">
                            <?php if(!empty($err_msg['pass'])) echo $err_msg['pass']; ?>
                        </div>
                    </fieldset>

                    <fieldset class="c-form__field u-mb-4">
                        <label class="<?php if(!empty($err_msg['pass_re'])) echo 'err' ?>">
                            パスワード（再入力）
                            <input type="password" name="pass_re" value="<?php if(!empty($_POST['pass_re'])) echo $_POST['pass_re']; ?>" placeholder="もう一度入力してください">
                        </label>
                        <div class="c-form__message">
                            <?php if(!empty($err_msg['pass_re'])) echo $err_msg['pass_re']; ?>
                        </div>
                    </fieldset>
                    
                    <div class="">
                        <input type="submit" class="c-button c-button--green c-button--width100" value="登録する">
                    </div>
    
                </form>
            </div>
        </div>
    </main>
    

    <?php
        require('footer.php');
    ?>