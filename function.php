<?php
//================================
// ログ
//================================
//ログを取るか
ini_set('log_errors', 'On');
//ログの出力ファイルを指定
ini_set('error_log', 'php.log');

//================================
// デバッグ
//================================
//デバッグフラグ
$debug_flg = true;
//デバッグログ関数
function debug($str){
    global $debug_flg;
    if($debug_flg){
      error_log('デバッグ：' . $str);
    }
}

//================================
// セッション準備・セッション有効期限を延ばす
//================================
//セッションファイルの置き場を変更する（/var/tmp/以下に置くと30日は削除されない）
session_save_path("/var/tmp/");
//ガーベージコレクションが削除するセッションの有効期限を設定（30日以上経っているものに対してだけ１００分の１の確率で削除）
ini_set('session.gc_maxlifetime', 60*60*24*30);
//ブラウザを閉じても削除されないようにクッキー自体の有効期限を延ばす
ini_set('session.cookie_lifetime', 60*60*24*30);

//セッションを使う
session_start();
//現在のセッションIDを新しく生成したものと置き換える（なりすましのセキュリティ対策）
session_regenerate_id();

//================================
// 画面表示処理開始ログ吐き出し関数
//================================
//ログ吐き出し内容：画面表示処理開始、セッションID、セッション変数中身、現在日時タイムスタンプ、ログイン期限日時タイムスタンプ
function debugLogStart(){
  debug('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> 画面表示処理開始');
  debug('セッションID：' . session_id());
  debug('セッション変数の中身' . print_r($_SESSION, true));
  debug('現在日時タイムスタンプ：' . time());
  if(!empty($_SESSION['login_date']) && !empty($_SESSION['login_limit'])){
      debug('ログイン期限日時タイムスタンプ：' . ( $_SESSION['login_date'] + $_SESSION['login_limit'] ) );
  }
}

//================================
// 定数
//================================
//エラーメッセージを定数に設定
define('MSG01','入力必須です');
define('MSG02', 'Emailの形式で入力してください');
define('MSG03','パスワード（再入力）が合っていません');
define('MSG04','半角英数字のみご利用いただけます');
define('MSG05','6文字以上で入力してください');
define('MSG06','256文字以内で入力してください');
define('MSG07','エラーが発生しました。しばらく経ってからやり直してください。');
define('MSG08', 'そのEmailは既に登録されています');
define('MSG09', 'メールアドレスまたはパスワードが違います');
define('MSG10', '電話番号の形式が違います');
define('MSG11', '郵便番号の形式が違います');
define('MSG12', '半角数字で入力してください');
define('MSG13', '情報の更新ができませんでした');
define('MSG14', '現在のパスワードが違います');
define('MSG15', '現在のパスワードと同じです');
define('MSG16', '文字で入力してください');
define('MSG17', '正しくありません');
define('MSG18', '有効期限が切れています');
define('MSG19', '800文字以内で入力してください');

define('SUC01', 'パスワードの変更が完了しました');
define('SUC02', 'プロフィールを変更しました');
define('SUC03', 'メールを送信しました');
define('SUC04', '登録しました');
define('SUC05', '購入しました！相手と連絡を取りましょう！');
define('SUC06', 'メールアドレスの変更が完了しました');
define('SUC07', '退会が完了しました');

//================================
// バリデーション関数
//================================
// エラーメッセージ格納用の配列
$err_msg = array();
// 未入力チェック
function validRequired($str, $key){
  if($str === '' || $str === NULL){   // 数値の0も入力OKにするためにemptyは使わない
    global $err_msg;
    $err_msg[$key] = MSG01;
  }
}
// Email形式チェック
function validEmail($str, $key){
  if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $str)){
    global $err_msg;
    $err_msg[$key] = MSG02;
  }
}
// Email重複チェック
function validEmailDup($email){
  global $err_msg;
  try {
    $dbh = dbConnect();
    $sql = 'SELECT count(*) FROM users WHERE email = :email AND delete_flg = 0';
    // emailをユニークに設定すると、退会後に再設定できない
    // delete_flg=0を条件に追加すれば、退会したemailは検索に引っかからないので再度登録できる
    $data = array(':email' => $email);
    $stmt = queryPost($dbh, $sql, $data);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!empty(array_shift($result))){
      $err_msg['email'] = MSG08;
    }
  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
    $err_msg['common'] = MSG07;
  }
}
// 同値チェック
function validMatch($str1, $str2, $key){
  if($str1 !== $str2){
    global $err_msg;
    $err_msg[$key] = MSG03;
  }
}
// 最小文字数チェック
function validMinLen($str, $key, $min = 6){
  if(mb_strlen($str) < $min){
    global $err_msg;
    $err_msg[$key] = MSG05;
  }
}
// 最大文字数チェック
function validMaxLen($str, $key, $max = 255){
  if(mb_strlen($str) > $max){
    global $err_msg;
    if($max == 255){
      $err_msg[$key] = MSG06;
    }elseif($max == 800){
      $err_msg[$key] = MSG19;
    }
  }
}
// 半角英数字チェック
function validHalf($str, $key){
  if(!preg_match("/^[a-zA-Z0-9]+$/", $str)){
    global $err_msg;
    $err_msg[$key] = MSG04;
  }
}
// 電話番号チェック
function validTel($str, $key){
  if(!preg_match("/0\d{1,4}\d{1,4}\d{4}/", $str)){
    global $err_msg;
    $err_msg[$key] = MSG10;
  }
}
// 郵便番号チェック
function validZip($str, $key){
  if(!preg_match("/^\d{7}$/", $str)){
    global $err_msg;
    $err_msg[$key] = MSG11;
  }
}
// 半角数字チェック
function validNumber($str, $key){
  if(!preg_match("/^[0-9]+$/", $str)){
    global $err_msg;
    $err_msg[$key] = MSG12;
  }
}
// パスワードチェック
function validPass($str, $key){
  validHalf($str, $key);
  validMinLen($str, $key);
  validMaxLen($str, $key);
}
// ８文字かどうかのチェック
function validLen($str, $key, $len = 8){
  if(mb_strlen($str) !== $len){
    global $err_msg;
    $err_msg[$key] = $len . MSG16;
  }
}
// セレクトボックスチェック
function validSelect($str, $key){
  if(!preg_match('/^[0-9]+$/', $str)){
    global $err_msg;
    $err_msg[$key] = MSG17;
  }
}
// エラーメッセージ取得
function getErrMsg($key){
  global $err_msg;
  if(!empty($err_msg[$key])){
    return $err_msg[$key];
  }
}

//================================
// ログイン認証
//================================
function isLogin(){
  // ログインしている場合
  if(!empty($_SESSION['login_date'])){
    debug('ログイン済みユーザーです。');
    if( ($_SESSION['login_date'] + $_SESSION['login_limit']) < time()){
      debug('ログイン有効期限オーバーです。');
      // セッションを削除（ログアウトする）
      session_destroy();
      return false;
    }else{
      debug('ログイン有効期限内です。');
      return true;
    }
  }else{
    debug('未ログインユーザーです。');
    return false;
  }
}

//================================
// データベース
//================================
//DB接続関数
function dbConnect(){
  // DBへの接続準備
  // 環境設定
  // $environment = 'docker';

  // switch($environment){
  //   // ローカル用設定
  //   case 'manp':
  //     $dsn = 'mysql:dbname=yakukai;host=localhost:8889;charset=utf8';
  //     $user = 'root';
  //     $password = 'root';
  //     break;
  //   // dockerコンテナ用設定
  //   case 'docker':
  //     $dsn = 'mysql:host=mysql;dbname=yakukai;charset=utf8';
  //     $user = 'mysql_user';
  //     $password = 'mysql_pass';
  //     break;
  //   // AWS用設定
  //   case 'aws':
  //     $dsn = 'mysql:dbname=yakukai;host=rds-yakukai.cu8fk2ptro5d.ap-northeast-1.rds.amazonaws.com;port=3306;charset=utf8';
  //     $user = 'yakukai_admin';
  //     $password = 'yakuyakudbb';
  //     break;
  // }

  // 環境変数取得、設定
  if(empty(getenv('PDO_DSN'))){
    // MAMP設定
    $dsn = 'mysql:dbname=yakukai;host=localhost:8889;charset=utf8';
  }else{
    $dsn = getenv('PDO_DSN');
  }
  if(empty(getenv('PDO_USER'))){
    // MAMP設定
    $user = 'root';
  }else{
    $user = getenv('PDO_USER');
  }
  if(empty(getenv('PDO_USER'))){
    // MAMP設定
    $password = 'root';
  }else{
    $password = getenv('PDO_PASSWORD');
  }

  debug('dsn: '.$dsn);
  debug('user: '.$user);
  debug('password: '.$password);
  
  $options = array(
    // SQL実行失敗時にはエラーコードのみ設定
    PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
    // デフォルトフェッチモードを連想配列形式に設定
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    // バッファードクエリを使う(一度に結果セットをすべて取得し、サーバー負荷を軽減)
    // SELECTで得た結果に対してもrowCountメソッドを使えるようにする
    // PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
  );
  // PDOオブジェクト生成（DBへ接続）
  $dbh = new PDO($dsn, $user, $password, $options);
  if($dbh){
    debug('PDOオブジェクト生成成功！');
  }else{
    debug('PDOオブジェクト生成失敗...');
  }
  return $dbh;
}
// SQL実行関数
function queryPost($dbh, $sql, $data){
  // クエリー作成
  $stmt = $dbh->prepare($sql);
  // プレースホルダに値をセットし、SQL文を実行
  if(!$stmt->execute($data)){     // true falseで返ってくる
    debug('クエリに失敗しました');
    debug('失敗したSQL：' . print_r($sql, true));
    $err_msg['common'] = MSG07;
    return 0;
  }
  debug('クエリ成功！');
  return $stmt;
}
// 業種情報取得
function getIndustry(){
  try{
    $dbh = dbConnect();
    $sql = 'SELECT * FROM industry';
    $data = array();
    $stmt = queryPost($dbh, $sql, $data);
    if($stmt){
      return $stmt->fetchAll();
    }else{
      return false;
    }
  } catch (Exception $e){
    error_log('エラー発生：' . $e->getMessage());
  }
}
function getUser($u_id){
  debug('ユーザー情報を取得します。');
  try{
    $dbh = dbConnect();
    $sql = 'SELECT email, sex, birth_year, addr, ph_license, carrier_type, ex_phtype, ex_year, emp_type, industry.name AS i_name, employment_type.name AS e_name FROM users
      LEFT JOIN industry ON users.ex_phtype = industry.id
      LEFT JOIN employment_type ON users.emp_type = employment_type.id
      WHERE users.id = :u_id AND users.delete_flg = 0';
    $data = array(':u_id' => $u_id);
    $stmt = queryPost($dbh, $sql, $data);
  } catch (Exeption $e){
    error_log('エラー発生：' . $e->getMessage());
  }
  return $stmt->fetch(PDO::FETCH_ASSOC);
}
function getPost($u_id){
  debug('ユーザーのクチコミ情報を取得します。');
  try{
    $dbh = dbConnect();
    $sql = 'SELECT * FROM posts WHERE user_id = :u_id AND delete_flg = 0';
    $data = array(':u_id' => $u_id);
    $stmt = queryPost($dbh, $sql, $data);
  } catch (Exeption $e){
    error_log('エラー発生：' . $e->getMessage());
  }
  return $stmt->fetchAll();
}
function getPostAll($post_id){
  debug('ユーザーのクチコミ情報（post, rating, answer）を取得します。');
  debug('ユーザーのクチコミ情報（post）を取得します。');
  try{
    $dbh = dbConnect();
    $sql = 'SELECT * FROM posts WHERE id = :post_id AND post_flg = 1 AND delete_flg = 0';
    $data = array(':post_id' => $post_id);
    $stmt = queryPost($dbh, $sql, $data);
    if($stmt){
      $result['post'] = $stmt->fetch(PDO::FETCH_ASSOC);
    }else{
      return false;
    }
  } catch (Exeption $e){
    error_log('エラー発生：' . $e->getMessage());
  }
  debug('ユーザーのクチコミ情報（rating）を取得します。');
  try{
    $dbh = dbConnect();
    $sql = 'SELECT ratings.rating_item_id, ratings.rating, rating_items.name FROM ratings LEFT JOIN rating_items ON ratings.rating_item_id = rating_items.id WHERE ratings.post_id = :post_id AND ratings.post_flg = 1 AND ratings.delete_flg = 0';
    $data = array(':post_id' => $post_id);
    $stmt = queryPost($dbh, $sql, $data);
    if($stmt){
      $result['rating'] = $stmt->fetchAll();
    }else{
      return false;
    }
  } catch (Exeption $e){
    error_log('エラー発生：' . $e->getMessage());
  }
  debug('ユーザーのクチコミ情報（answer）を取得します。');
  try{
    $dbh = dbConnect();
    $sql = 'SELECT answers.answer, category.id AS category_id, category.name AS category, answer_items.name AS answer_item FROM answers
      LEFT JOIN category ON answers.category_id = category.id
      LEFT JOIN answer_items ON answers.answer_item_id = answer_items.id
      WHERE answers.post_id = :post_id AND answers.post_flg = 1 AND answers.delete_flg = 0';
    $data = array(':post_id' => $post_id);
    $stmt = queryPost($dbh, $sql, $data);
    if($stmt){
      $result['answer'] = $stmt->fetchAll();
    }else{
      return false;
    }
  } catch (Exeption $e){
    error_log('エラー発生：' . $e->getMessage());
  }
  return $result;
}
function getPostList($company_id){
  debug('企業の回答者別クチコミリストを取得します');
  // 必要なテーブル：posts, users, ratings
  try{
    $dbh = dbConnect();
    $sql = 'SELECT users.sex, posts.id, posts.employment_type, posts.registration, posts.entry_type, posts.entry_date, posts.department, posts.position, posts.create_date, rating_item_id, ratings.rating
            FROM posts
            LEFT JOIN users ON posts.user_id = users.id
            LEFT JOIN employment_type ON posts.employment_type = employment_type.id
            LEFT JOIN ratings ON posts.user_id = ratings.user_id
            WHERE posts.company_id = :company_id AND ratings.rating_item_id = 1 AND posts.post_flg = 1 AND posts.delete_flg = 0 AND users.delete_flg = 0';
    $data = array(':company_id' => $company_id);
    $stmt = queryPost($dbh, $sql, $data);
    if($stmt){
      return $stmt->fetchAll();
    }else{
      return false;
    }
  } catch (Exeption $e){
    error_log('エラー発生：' . $e->getMessage());
  }
}
function getRatingByUserId($user_id){
  debug('ユーザーの評価値を取得します。');
  try{
    $dbh = dbConnect();
    $sql = 'SELECT * FROM ratings WHERE user_id = :user_id AND delete_flg = 0';
    $data = array(
      ':user_id' => $user_id,
    );
    $stmt = queryPost($dbh, $sql, $data);
  } catch (Exeption $e){
    error_log('エラー発生：' . $e->getMessage());
  }
  return $stmt->fetchAll();
}
function getRatings($post_id, $user_id, $company_id){
  debug('ユーザーの評価値を取得します。');
  try{
    $dbh = dbConnect();
    $sql = 'SELECT * FROM ratings WHERE post_id = :post_id AND user_id = :user_id AND company_id = :company_id AND delete_flg = 0';
    $data = array(
      ':post_id' => $post_id,
      ':user_id' => $user_id,
      ':company_id' => $company_id,
    );
    $stmt = queryPost($dbh, $sql, $data);
  } catch (Exeption $e){
    error_log('エラー発生：' . $e->getMessage());
  }
  return $stmt->fetchAll();
}
function getAnswer($user_id){
  debug('ユーザーの回答を取得します。');
  try{
    $dbh = dbConnect();
    $sql = 'SELECT * FROM answers WHERE user_id = :user_id AND delete_flg = 0 AND post_flg = 1';
    $data = array(':user_id' => $user_id);
    $stmt = queryPost($dbh, $sql, $data);
  } catch (Exeption $e){
    error_log('エラー発生：' . $e->getMessage());
  }
  return $stmt->fetchAll();
}
function getAnswers($post_id, $user_id, $company_id){
  debug('ユーザーの投稿を全て取得します。');
  try{
    $dbh = dbConnect();
    $sql = 'SELECT * FROM answers WHERE post_id = :post_id AND user_id = :user_id AND company_id = :company_id AND delete_flg = 0';
    $data = array(
      ':post_id' => $post_id,
      ':user_id' => $user_id,
      ':company_id' => $company_id,
    );
    $stmt = queryPost($dbh, $sql, $data);
  } catch (Exeption $e){
    error_log('エラー発生：' . $e->getMessage());
  }
  return $stmt->fetchAll();
}
function getEmploymentType(){
  debug('雇用形態テーブルの情報を取得します。');
  try{
    $dbh = dbConnect();
    $sql = 'SELECT * FROM employment_type WHERE delete_flg = 0';
    $data = array();
    $stmt = queryPost($dbh, $sql, $data);
  } catch (Exeption $e){
    error_log('エラー発生：' . $e->getMessage());
  }
  return $stmt->fetchAll();
}
function getRatingItemsAndQuestions(){
  debug('評価に関する項目、質問を取得します。');
  try{
    $dbh = dbConnect();
    $sql = 'SELECT * FROM rating_items LEFT JOIN questions ON rating_items.id = questions.rating_item_id WHERE rating_items.delete_flg = 0 AND questions.delete_flg = 0';
    $data = array();
    $stmt = queryPost($dbh, $sql, $data);
  } catch (Exeption $e){
    error_log('エラー発生：' . $e->getMessage());
  }
  return $stmt->fetchAll();
}
function getQuestionsAndRatings($post_id, $user_id, $company_id){
  debug('評価に関する項目、質問、評価値を取得します。');
  try{
    $dbh = dbConnect();
    $sql = 'SELECT * FROM rating_items LEFT JOIN questions ON rating_items.id = questions.rating_item_id LEFT JOIN ratings ON questions.rating_item_id = ratings.rating_item_id WHERE ratings.post_id = :post_id AND ratings.user_id = :user_id AND ratings.company_id = :company_id AND rating_items.delete_flg = 0 AND questions.delete_flg = 0 AND ratings.delete_flg = 0';
    $data = array(
      ':post_id' => $post_id,
      ':user_id' => $user_id,
      ':company_id' => $company_id,
    );
    $stmt = queryPost($dbh, $sql, $data);
  } catch (Exeption $e){
    error_log('エラー発生：' . $e->getMessage());
  }
  return $stmt->fetchAll();
}
function getAnswerItemsAndQuestions(){
  debug('項目と質問を取得します。');
  try{
    $dbh = dbConnect();
    $sql = 'SELECT * FROM answer_items LEFT JOIN questions ON answer_items.id = questions.answer_item_id WHERE answer_items.delete_flg = 0 AND questions.delete_flg = 0';
    $data = array();
    $stmt = queryPost($dbh, $sql, $data);
  }catch(Exeption $e){
    error_log('エラー発生：' . $e->getMessage());
  }
  return $stmt->fetchAll();
}
function getQuestionsAndAnswers($post_id, $user_id, $company_id, $exist_flg = false){
  debug('質問と回答を取得します。');
  try{
    $dbh = dbConnect();
    $sql = 'SELECT * FROM answer_items LEFT JOIN questions ON answer_items.id = questions.answer_item_id LEFT JOIN answers ON questions.answer_item_id = answers.answer_item_id WHERE answers.post_id = :post_id AND answers.user_id = :user_id AND answers.company_id = :company_id AND questions.delete_flg = 0 AND answers.delete_flg = 0';
    if($exist_flg){
      $sql .= ' AND answers.answer IS NOT NULL';
    }
    $data = array(
      ':post_id' => $post_id,
      ':user_id' => $user_id,
      ':company_id' => $company_id,
    );
    $stmt = queryPost($dbh, $sql, $data);
  } catch (Exeption $e){
    error_log('エラー発生：' . $e->getMessage());
  }
  return $stmt->fetchAll();
}
// カテゴリ情報($company_idを指定していた場合、カテゴリごとの投稿件数も)取得
function getCategory($company_id = NULL){
  debug('カテゴリ情報を取得します。');
  $result = array();
  try{
    $dbh = dbConnect();
    $sql = 'SELECT id, name FROM category';
    $data = array();
    $stmt = queryPost($dbh, $sql, $data);
    if($stmt){
      $result = $stmt->fetchAll();
    }else{
      return false;
    }
  } catch (Exception $e){
    error_log('エラー発生：' . $e->getMessage());
  }
  // $company_idを指定していた場合、カテゴリごとの投稿件数を取得
  if($company_id){
    debug('$company_idが指定されているためカテゴリごとの投稿件数を取得します。');
    foreach($result as $key => $val){
      try{
        $dbh = dbConnect();
        $sql = 'SELECT COUNT(answers.id)
                FROM category 
                LEFT JOIN answers ON category.id = answers.category_id
                WHERE answers.company_id = :company_id AND answers.category_id = :category_id AND answers.answer IS NOT NULL AND answers.post_flg = 1 AND answers.delete_flg = 0';
        $data = array(
          ':company_id' => $company_id,
          ':category_id' => $val['id'],
        );
        $stmt = queryPost($dbh, $sql, $data);
        if($stmt){
          $result[$key]['count'] = $stmt->fetch(PDO::FETCH_ASSOC);
        }else{
          return false;
        }
      } catch (Exception $e){
        error_log('エラー発生：' . $e->getMessage());
      }
    }
  }
  return $result;
}
// ---------------------------
// 企業情報
// ---------------------------
// トップページ。幸福度の高い企業、投稿数の多い企業を取得（6つ）
function getCompanyRanking(){
  debug('幸福度の高い企業、投稿数の多い企業の情報を取得します。');
  $result = array();
  debug('幸福度の高い企業を取得します。');
  try{
    $dbh = dbConnect();
    $sql = 'SELECT company.id, company.name, AVG(ratings.rating), COUNT(answers.answer)
              FROM ratings
              LEFT JOIN company ON ratings.company_id = company.id
              LEFT JOIN answers ON ratings.company_id = answers.company_id
              WHERE ratings.rating_item_id = 1 AND ratings.delete_flg = 0 AND ratings.post_flg = 1 AND answers.delete_flg = 0 AND answers.post_flg = 1
              GROUP BY ratings.company_id
              ORDER BY AVG(ratings.rating) DESC
              LIMIT 6';
    $data = array();
    $stmt = queryPost($dbh, $sql, $data);
    if($stmt){
      $result['rating'] =  $stmt->fetchAll();
    }else{
      return false;
    }
  } catch (Exception $e){
    error_log('エラー発生：'.$e->getMessage());
  }
  debug('クチコミ数の多い企業を取得します。');
  try{
    $dbh = dbConnect();
    $sql = 'SELECT company.id, company.name, AVG(ratings.rating), COUNT(answers.answer)
              FROM ratings
              LEFT JOIN company ON ratings.company_id = company.id
              LEFT JOIN answers ON ratings.company_id = answers.company_id
              WHERE ratings.rating_item_id = 1 AND ratings.delete_flg = 0 AND ratings.post_flg = 1 AND answers.delete_flg = 0 AND answers.post_flg = 1
              GROUP BY ratings.company_id
              ORDER BY COUNT(answers.answer) DESC
              LIMIT 6';
    $data = array();
    $stmt = queryPost($dbh, $sql, $data);
    if($stmt){
      $result['answer'] =  $stmt->fetchAll();
    }else{
      return false;
    }
  } catch (Exception $e){
    error_log('エラー発生：'.$e->getMessage());
  }
  return $result;
}
// company_idから企業カラム取得
function getCompanyOne($c_id){
  debug('c_idに合致する企業情報（companyテーブルの情報、industryテーブルの業種名）を取得します。');
  debug('取得する企業ID：'.$c_id);
  $result = array();
  try{
    $dbh = dbConnect();
    $sql = 'SELECT c.id, c.sequence_number, c.corporate_number, c.process, c.name, c.prefecture_name, c.city_name, c.street_number,	c.prefecture_code, c.city_code, c.post_code, c.successor_corporate_number, c.furigana, c.hihyoji, c.summary, c.icon, c.rating, c.posts_count, c.create_date, c.update_date, i.name AS industry
            FROM company AS c
            LEFT OUTER JOIN industry AS i ON c.industry_id = i.id
            WHERE c.id = :c_id AND c.delete_flg = 0 AND i.delete_flg = 0';
    // $sql = 'SELECT * FROM company LEFT OUTER JOIN industry ON company.industry_id = industry.id WHERE company.id = :c_id AND company.delete_flg = 0 AND industry.delete_flg = 0';
    $data = array(':c_id' => $c_id);
    $stmt = queryPost($dbh, $sql, $data);
    if($stmt){
      $result['info'] =  $stmt->fetch(PDO::FETCH_ASSOC);
    }else{
      return false;
    }
  } catch (Exception $e) {
    error_log('エラー発生：'.$e->getMessage());
  }

  debug('ユーザーによるデータ（平均年収等）を取得します。');
  try{
    $dbh = dbConnect();
    $sql = 'SELECT AVG(users.birth_year), COUNT(over_time), AVG(over_time), COUNT(anual_total_salary), AVG(anual_total_salary), MAX(anual_total_salary), MIN(anual_total_salary) FROM posts LEFT JOIN users ON posts.user_id = users.id WHERE posts.company_id = :c_id AND posts. post_flg = 1 AND users.delete_flg = 0 AND posts.delete_flg = 0';
    $data = array(':c_id' => $c_id);
    $stmt = queryPost($dbh, $sql, $data);
    if($stmt){
      $result['user_data'] = $stmt->fetch(PDO::FETCH_ASSOC);
    }else{
      return false;
    }
  } catch (Exception $e) {
    error_log('エラー発生：'.$e->getMessage());
  }

  return $result;
}
function getCompanyList($currentMinNum = 0, $companyName, $prefecture, $industry, $sort = 1, $span = 20){
  // 検索・表示処理：法人番号が登録されている企業のうち、口コミ数の多い順に検索ワードに合致した企業を取得
  debug('getCompanyListを実行します。');
  try{
    debug('DBから条件に合致する企業数を取得し、表示に必要な総ページ数を算出します');
    $dbh = dbConnect();
    // companyテーブルから、corporate_numberを持つカラム数を算出
    $sql = 'SELECT COUNT(corporate_number) AS num FROM company';
    // 企業名・都道府県・業種で抽出する条件文処理
    if(!empty($companyName)){
      $sql .= ' WHERE name LIKE "%'.$companyName.'%"';
      if(!empty($prefecture)){
        $sql .= ' AND prefecture_code = '.$prefecture;   // 都道府県で抽出する条件文
      }
      if(!empty($industry)){
        $sql .= ' AND industry_id = '.$industry;    // 業種で抽出する条件文
      }
    }else{
      if(!empty($prefecture)){
        $sql .= ' WHERE prefecture_code = '.$prefecture;
        if(!empty($industry)){
          $sql .= ' AND industry_id = '.$industry;
        }
      }else{
        if(!empty($industry)){
          $sql .= ' WHERE industry_id = '.$industry;
        }
      }
    }
    $data = array();
    $stmt = queryPost($dbh, $sql, $data);
    if($stmt){
      $res = $stmt->fetch(PDO::FETCH_ASSOC);
      $rst['total'] = $res['num'];
      debug('法人番号をもつ企業総数：'.print_r($rst['total'],true));
      // 総ページ数を算出
      $rst['total_page'] = ceil($rst['total']/$span);
    }else{
      return false;
    }

    debug('検索条件をもとに対象の企業データ（企業名、本社所在地）を取得します。');
    // SQL構築処理
    $sql = 'SELECT id, name, prefecture_name, city_name, rating, posts_count
            FROM company
            WHERE corporate_number IS NOT NULL';
    // 企業名・都道府県・業種で抽出する条件文処理
    if(!empty($companyName)){
      $sql .= ' AND name LIKE "%'.$companyName.'%"';
      if(!empty($prefecture)){
        // 都道府県で抽出する条件文追加
        $sql .= ' AND prefecture_code = '.$prefecture;
      }
      if(!empty($industry)){
        // 業種で抽出する条件文追加
        $sql .= ' AND industry_id = '.$industry;
      }
    }else{
      if(!empty($prefecture)){
        $sql .= ' AND prefecture_code = '.$prefecture;
        if(!empty($industry)){
          $sql .= ' AND industry_id = '.$industry;
        }
      }else{
        if(!empty($industry)){
          $sql .= ' AND industry_id = '.$industry;
        }
      }
    }
    // 検索条件（口コミ数の多い順・評価順）をSQL文に追加
    if(!empty($sort)){
      switch($sort){
        case 1:
          $sql .= ' ORDER BY posts_count DESC';
        break;
        case 2:
          $sql .= ' ORDER BY rating DESC';
        break;
      }
    }
    $sql .= ' LIMIT '.$span.' OFFSET '.$currentMinNum;
    $data = array();
    debug('SQL：'.$sql);
    $stmt = queryPost($dbh, $sql, $data);
    if($stmt){
      $rst['data'] = $stmt->fetchAll();
      debug('表示する商品データをDBから取得し、$rst[data]に格納しました。');
      return $rst;
    }else{
      return false;
    }
  } catch (Exception $e) {
    error_log('エラー発生：' . $e->getMessage());
  }
}
function getCompanyRatings($company_id){
  debug('企業の平均評価値を取得します。');
  debug('評価項目を取得します。');
  try{
    $dbh = dbConnect();
    $sql = 'SELECT * FROM rating_items WHERE delete_flg = 0';
    $data = array();
    $stmt = queryPost($dbh, $sql, $data);
    if($stmt){
      $rating_items = $stmt->fetchAll();
    }else{
      return false;
    }
  } catch (Exception $e){
    error_log('エラー発生：' . $e->getMessage());
  }

  debug('評価項目ごとに平均値を取得します。');
  try{
    $dbh = dbConnect();
    foreach($rating_items as $key => $val){
      $sql = 'SELECT ratings.rating_item_id, AVG(rating), rating_items.name FROM ratings LEFT JOIN rating_items ON ratings.rating_item_id = rating_items.id WHERE ratings.company_id = :company_id AND ratings.rating_item_id = :rating_item_id AND ratings.post_flg = 1 AND ratings.delete_flg = 0';
      $data = array(
        ':company_id' => $company_id,
        ':rating_item_id' => $val['id']
      );
      $stmt = queryPost($dbh, $sql, $data);
      if($stmt){
        $result[$key] = $stmt->fetch(PDO::FETCH_ASSOC);
      }else{
        return false;
      }
    }
  } catch (Exception $e){
    error_log('エラー発生：' . $e->getMessage());
  }

  debug('評価件数を取得します。');
  try{
    $dbh = dbConnect();
    $sql = 'SELECT COUNT(rating) FROM ratings WHERE company_id = :company_id AND rating_item_id = 1 AND post_flg = 1 AND delete_flg = 0';
    $data = array(
      ':company_id' => $company_id,
    );
    $stmt = queryPost($dbh, $sql, $data);
    if($stmt){
      $result['rating_count'] = $stmt->fetch(PDO::FETCH_ASSOC);
    }else{
      return false;
    }
  } catch (Exception $e){
    error_log('エラー発生：' . $e->getMessage());
  }
  return $result;
}
function getTopAnswer($company_id){
  debug('グッドが多い回答を１件取得します。');
  try{
    $dbh = dbConnect();
    $sql = 'SELECT answers.answer, answer_items.name AS answer_item, category.name AS category
            FROM answers
            LEFT JOIN good ON answers.id = good.answer_id
            LEFT JOIN answer_items ON answers.answer_item_id = answer_items.id
            LEFT JOIN category ON answers.category_id = category.id
            WHERE answers.company_id = :company_id AND answers.post_flg = 1 AND answers.delete_flg = 0
            GROUP BY answers.id
            ORDER BY COUNT(good.update_date) DESC
            LIMIT 1';
    $data = array(
      ':company_id' => $company_id,
    );
    $stmt = queryPost($dbh, $sql, $data);
    if($stmt){
      return $stmt->fetch(PDO::FETCH_ASSOC);
    }else{
      return false;
    }
  } catch (Exception $e){
    error_log('エラー発生：' . $e->getMessage());
  }
}
function getPickUpPosts($company_id, $category_id){
  debug('各企業ページで表示するPick Upクチコミ（post, answer）を取得します。');
  $result = array();

  debug('投稿を2件取得する');
  // 必要なデータ：category.name, users.sex, posts.employment_type, employment_type.name, posts.resistration, posts.entry_type, posts.department, posts.position, answer_items.name, answers.answer, ratings.ragting(rating_item_id = 1)
  // 対象テーブル：posts, category, employment_type, users, answer_items, answers, ratings
  try{
    $dbh = dbConnect();
    $sql = 'SELECT category.name AS category, users.sex, employment_type.name AS employment_type_name, posts.id AS post_id, posts.registration, posts.entry_type, posts.entry_date, posts.department, posts.position, answer_items.name AS answer_item, answers.id AS answer_id, answers.answer, answers.update_date AS a_update_date, ratings.rating
      FROM posts
      LEFT JOIN users ON posts.user_id = users.id
      LEFT JOIN employment_type ON posts.employment_type = employment_type.id
      LEFT JOIN answers ON posts.id = answers.post_id
      LEFT JOIN category ON answers.category_id = category.id
      LEFT JOIN answer_items ON answers.answer_item_id = answer_items.id
      LEFT JOIN ratings ON posts.id = ratings.post_id
      WHERE
        posts.company_id = :company_id AND
        answers.company_id = :company_id AND
        answers.category_id = :category_id AND
        answers.answer IS NOT NULL AND
        ratings.rating_item_id = 1 AND
        posts.post_flg = 1 AND
        answers.post_flg = 1 AND
        posts.delete_flg = 0 AND
        answers.delete_flg = 0 AND
        category.delete_flg = 0
      LIMIT 2';
    $data = array(
      ':company_id' => $company_id,
      ':category_id' => $category_id,
    );
    $stmt = queryPost($dbh, $sql, $data);
    if($stmt){
      return $result = $stmt->fetchAll();
    }else{
      return false;
    }
  } catch (Exception $e){
    error_log('エラー発生：' . $e->getMessage());
  }
}
function getPostByCategory($company_id, $category_id){
  debug('各企業における、カテゴリ内の投稿（post, answer）を取得します。');
  $result = array();

  // 必要なデータ：category.name, users.sex, posts.employment_type, employment_type.name, posts.resistration, posts.entry_type, posts.department, posts.position, answer_items.name, answers.answer, ratings.ragting(rating_item_id = 1)
  // 対象テーブル：posts, category, employment_type, users, answer_items, answers, ratings
  try{
    $dbh = dbConnect();
    $sql = 'SELECT category.name AS category, users.sex, employment_type.name AS employment_type_name, posts.registration, posts.entry_type, posts.entry_date, posts.department, posts.position, answer_items.name AS answer_item, answers.id AS answer_id, answers.answer, answers.update_date AS a_update_date, ratings.rating
      FROM posts
      LEFT JOIN users ON posts.user_id = users.id
      LEFT JOIN employment_type ON posts.employment_type = employment_type.id
      LEFT JOIN answers ON posts.id = answers.post_id
      LEFT JOIN category ON answers.category_id = category.id
      LEFT JOIN answer_items ON answers.answer_item_id = answer_items.id
      LEFT JOIN ratings ON posts.id = ratings.post_id
      WHERE
        posts.company_id = :company_id AND
        answers.company_id = :company_id AND
        answers.category_id = :category_id AND
        ratings.rating_item_id = 1 AND
        posts.post_flg = 1 AND
        answers.post_flg = 1 AND
        posts.delete_flg = 0 AND
        answers.delete_flg = 0 AND
        category.delete_flg = 0
      ';
    $data = array(
      ':company_id' => $company_id,
      ':category_id' => $category_id,
    );
    $stmt = queryPost($dbh, $sql, $data);
    if($stmt){
      return $stmt->fetchAll();
    }else{
      return false;
    }
  } catch (Exception $e){
    error_log('エラー発生：' . $e->getMessage());
  }
}
function getPrefecture(){
  try{
    $dbh = dbConnect();
    $sql = 'SELECT * FROM prefectures';
    $data = array();
    $stmt = queryPost($dbh, $sql, $data);
    if($stmt){
      return $stmt->fetchAll();
    }else{
      return false;
    }
  } catch (Exception $e){
    error_log('エラー発生：' . $e->getMessage());
  }
}
// グッドボタンを押しているかどうかを返す関数
function isGood($user_id, $answer_id){
  debug('グッド情報があるか確認します。');
  debug('ユーザーID：'.$user_id);
  debug('回答ID：'.$answer_id);

  try{
    $dbh = dbConnect();
    $sql = 'SELECT * FROM good WHERE answer_id = :answer_id AND user_id = :user_id';
    $data = array(':answer_id' => $answer_id, ':user_id' => $user_id);
    $stmt = queryPost($dbh, $sql, $data);

    if($stmt->rowCount()){
      debug('グッドボタンを押しています');
      return true;
    }else{
      debug('グッドボタンを押していません');
      return false;
    }
  }catch (Exception $e){
    error_log('エラー発生：'.$e->getMessage());
  }
}
function getGoodCount($answer_id){
  debug('グッドの数を取得します。');
  try{
    $dbh = dbConnect();
    $sql = 'SELECT update_date FROM good WHERE answer_id = :answer_id AND delete_flg = 0';
    $data = array(':answer_id' => $answer_id);
    $stmt = queryPost($dbh, $sql, $data);

    if($stmt){
      return $stmt->rowCount();
    }else{
      return false;
    }
  }catch (Exception $e){
    error_log('エラー発生：'.$e->getMessage());
  }
}

//================================
// セッション
//================================
// $_SESSIONの中身を一度だけ取得する
function getSessionFlash($key){
  if(!empty($_SESSION[$key])){
    $data = $_SESSION[$key];
    $_SESSION[$key] = '';     //セッションの中身を消さないと中身があれば毎回取得してしまう
    return $data;
  }
}

//================================
// メール送信
//================================
function sendMail($from, $to, $subject, $comment){
  if(!empty($to) && !empty($subject) && !empty($comment)){
    mb_language('Japanese');
    mb_internal_encoding('UTF-8');

    $result = mb_send_mail($to, $subject, $comment, 'From:' . $from);

    if($result){
      debug('メールを送信しました。');
    }else{
      debug('【エラー発生】メールの送信に失敗しました。');
    }
  }
}

//================================
// 時間・日付処理
//================================
function getYearDiff($date1, $date2){
  $o_date1 = new DateTime($date1);
  $o_date2 = new DateTime($date2);
  $diff = date_diff($o_date1, $o_date2);
  return $diff->y;
}

//================================
//  その他
//================================
// サニタイズ
function sanitize($str){
  return htmlspecialchars($str, ENT_QUOTES);
}
// フォーム入力保持
function getFormData($str, $flg = false){
  if($flg){
    $method = $_GET;
  }else{
    $method = $_POST;
  }
  global $dbFormData;
  // データベースに登録されているユーザーデータがある場合
  if(!empty($dbFormData)){
    // $strでのフォーム入力でエラーがある場合
    if(!empty($err_msg[$str])){
      // POSTされている場合
      if(isset($method[$str])){
        // 入力されていたものを返す
        return sanitize($method[$str]);
      }else{
        // $_POST[$str]に何も入ってない場合（実際そんなことはない）データベースに登録されていた情報を返す
        return sanitize($dbFormData[$str]);
      }
    }else{
      // $strでのフォーム入力でエラーがない場合（他の入力でエラーがある場合も含まれる）
      if(isset($method[$str]) && $method[$str] !== $dbFormData[$str]){
        //POSTにデータがあり、DBの情報と違う場合
        return sanitize($method[$str]);
      }else{
        // POST送信がないとき
        return sanitize($dbFormData[$str]);
      }
    }
  }else{
    // $dbFormDataが空のとき
    if(isset($method[$str])){
      // 入力情報がある場合
      return sanitize($method[$str]);
    }
  }
}

// 認証キー生成
function makeRandKey($length = 8){
  static $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJLKMNOPQRSTUVWXYZ0123456789';
  $str = '';
  for ($i=0; $i < $length; ++$i) {
    $str .= $chars[mt_rand(0,61)];
  }
  return $str;
}

// ページング
// $currentPageNum : 現在のページ数
// $totalPageNum : 総ページ数
// $link : 検索用GETパラメータリンク
// $pageColNum : ページネーション表示数
function pagination($currentPageNum, $totalPageNum, $link = '', $pageColNum = 5){
  // 総ページ数<表示項目数：ループの最小を１、ループの最大を総ページ数にする
  if($totalPageNum < $pageColNum){
    $minPageNum = 1;
    $maxPageNum = $totalPageNum;
    
    // 現在のページ数＝1（表示項目数<総ページ数）
  }elseif($currentPageNum == 1 && $pageColNum < $totalPageNum){
    $minPageNum = $currentPageNum;
    $maxPageNum = $currentPageNum+4;

    // 現在のページ数＝2（表示項目数<総ページ数）
  }elseif($currentPageNum == 2 && $pageColNum < $totalPageNum){
    $minPageNum = $currentPageNum-1;
    $maxPageNum = $currentPageNum+3;

    // 現在のページ数＝総ページ数-1（表示項目数<総ページ数）
  }elseif($currentPageNum == $totalPageNum-1 && $pageColNum < $totalPageNum){
    $minPageNum = $currentPageNum-3;
    $maxPageNum = $currentPageNum+1;

    // 現在のページ数＝総ページ数（表示項目数<総ページ数）
  }elseif($currentPageNum == $totalPageNum && $pageColNum < $totalPageNum){
    $minPageNum = $currentPageNum-4;
    $maxPageNum = $currentPageNum;

    // それ以外
  }else{
    $minPageNum = $currentPageNum-2;
    $maxPageNum = $currentPageNum+2;
  }

  echo '<div class="pagination">';
    echo '<ul class="pagination-list">';

    // １ページ目ではなければ、１ページ目へ移動の「<」を表示させる
    if($currentPageNum != 1){
      echo '<li class="list-item"><a href="?p=1">&lt;</a></li>';
    }

    // forでページリスト生成
    for($i = $minPageNum; $i <=$maxPageNum; $i++){
      echo '<li class="list-item';
      if($currentPageNum == $i) echo ' active';
      echo '">';

      echo '<a href="?p=' . $i . '">' . $i . '</a></li>';
    }

    // 最終ページではなければ、最終ページへ移動の「>」を表示させる
    if($currentPageNum != $maxPageNum){
      echo '<li class="list-item"><a href="?p=' . $maxPageNum . '">&gt;</a></li>';
    }  
    echo '</ul>';
  echo '</div>';
}

// 引数で受け取った配列のキーを持つGETパラメーターを除外して、GETパラメーターを返す
// $_GETをforeachで展開して、引数で受け取ったキー以外のときに展開時の$key, $valを$strに付け足していき、GETパラメーターを完成させる
// （引数で受け取ったものを除外したものができる）
// $arr_del_key：除外したいGETパラメーターの配列キー
function appendGetParam($arr_del_key = array()){
  if(!empty($_GET)){
    $str = '?';
    foreach($_GET as $key => $val){
      if(!in_array($key, $arr_del_key, true)){
        $str .= $key . '=' . $val . '&';
      }
    }
    $str = mb_substr($str, 0, -1, 'UTF-8');
    // 第３引数の$lengthに-1とすることで語尾の&は取得しない。（語尾-1まで取得？）
    return $str;
  }
}