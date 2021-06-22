<?php
//共通変数・関数ファイルを読込み
require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　Ajax　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//===============================================
// Ajax処理(answer_idとuser_idでgoodテーブルにレコードがあるか確認し、あればレコードを削除。なければレコードを追加。)
//===============================================

// postがあり、ユーザーIDがあり、ログインしている場合
if(isset($_POST['answer_id']) && isset($_SESSION['user_id']) && isLogin()){
  debug('POST送信があります。');
  $answer_id = $_POST['answer_id'];
  debug('回答ID：'.$answer_id);

  try{
    // まずはレコードがあるか検索→あればレコードを削除（グッド消去）、なければ追加
    $dbh = dbConnect();
    $sql = 'SELECT * FROM good WHERE answer_id = :answer_id AND user_id = :user_id';
    $data = array(':answer_id' => $answer_id, ':user_id' => $_SESSION['user_id']);
    $stmt = queryPost($dbh, $sql, $data);
    $resultCount = $stmt->rowCount();
    debug('グッド件数：'.$resultCount);

    if(!empty($resultCount)){
      debug('グッドボタンが押されていたので削除します。');
      $sql = 'DELETE FROM good WHERE answer_id = :answer_id AND user_id = :user_id';
      $data = array(':answer_id' => $answer_id, ':user_id' => $_SESSION['user_id']);
      $stmt = queryPost($dbh, $sql, $data);
      if($stmt){
          debug('グッドを削除しました。');
      }
    }else{
      debug('グッド登録します。');
      $sql = 'INSERT INTO good (answer_id, user_id, create_date) VALUES (:answer_id, :user_id, :create_date)';
      $data = array('answer_id' => $answer_id, ':user_id' => $_SESSION['user_id'], ':create_date' => date('Y-m-d H:i:s'));
      $stmt = queryPost($dbh, $sql, $data);
      if($stmt){
          debug('グッドを追加しました。');
      }
    }
  }catch (Exception $e){
      error_log('エラー発生：'.$e->getMessage());
  }
}
debug('Ajax処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>