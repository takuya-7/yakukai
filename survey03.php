<?php
require('config.php');
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「 クチコミ投稿（3/3）');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');

//================================
// 画面処理
//================================

// 画面表示用データ取得
//================================
// ユーザーID取得
$user_id = $_SESSION['user_id'];
// ユーザーのクチコミレコード取得
$dbPostData = getPost($user_id);
// ユーザーのクチコミレコードから企業ID取得
$company_id = $dbPostData[0]['company_id'];
if(!empty($company_id)){
  debug('クチコミレコードに企業IDが登録されていました。企業情報を取得します。');
  $dbCompanyData = getCompanyOne($company_id);
}else{
  debug('クチコミレコードに企業IDの登録がありません。surveyInfo.phpに遷移します。');
  header('Location:surveyInfo.php');
  exit();
}
// 項目・質問文・回答内容を取得
// DBに既に回答があれば回答もまとめて取得
if(!empty(getQuestionsAndAnswers($dbPostData[0]['id'], $user_id, $company_id))){
  $dbAnswerItems = getQuestionsAndAnswers($dbPostData[0]['id'], $user_id, $company_id);
}else{    // まだDBに登録していなければ項目・質問文を取得
  $dbAnswerItems = getAnswerItemsAndQuestions();
}
// 入力合計文字数の変数設定。
$totalCount = 0;

// 既に回答がDBに登録されている場合、入力合計文字数を算出
if(!empty(getAnswers($dbPostData[0]['id'], $user_id, $company_id,))){
  foreach($dbAnswerItems as $key => $val){
    $totalCount += mb_strlen($val['answer']);
  }
}

// POSTパラメータを取得
//----------------------------
if(!empty($_POST)){
  debug('POST送信があります。');
  debug('POST情報：' . print_r($_POST, true));

  $postAnswers = array();

  // 回答ごとに、インデックスがあれば２次元配列にカテゴリID、質問項目のID、送信された回答を格納。なければ回答部分にNULLを格納。
  foreach($dbAnswerItems as $key => $val){
    if(!empty($_POST[$val['english_name']])){
      $postAnswers[$key] = array(
        'category_id' => $val['category_id'],
        'answer_item_id' => $val['answer_item_id'],
        'english_name' => $val['english_name'],
        'answer' => $_POST[$val['english_name']],
      );
    }else{
      $postAnswers[$key] = array(
        'category_id' => $val['category_id'],
        'answer_item_id' => $val['answer_item_id'],
        'english_name' => $val['english_name'],
        'answer' => NULL,
      );
    }
  }
  debug('$postAnswers：'.print_r($postAnswers, true));

  // 合計500文字以上かチェック
  $totalCount = 0;
  foreach($postAnswers as $key => $val){
    $totalCount += mb_strlen($val['answer']);
  }
  if($totalCount < 500){
    $err_msg['total_count'] = '入力が合計500文字以上できていません。';
  }

  // 最大800文字数チェック
  foreach($postAnswers as $key => $val){
    validMaxLen($val['answer'], $val['english_name'], 800);
  }

  // バリデーション追加：空白削除trim()、html等無効化。sanitize。htmlspecialchars
  
  if(empty($err_msg)){
    debug('バリデーションOKです！');
    // まだ回答が投稿されていなければ、INSERTを行う
    if(empty(getAnswers($dbPostData[0]['id'], $user_id, $company_id,))){
      debug('回答がまだ登録されていません。回答の挿入を行います！');
      try{
        $dbh = dbConnect();
        foreach($postAnswers as $key => $val){
          $sql = 'INSERT INTO answers (answer_item_id, answer, post_id, user_id, company_id, category_id, create_date) VALUES (:answer_item_id, :answer, :post_id, :user_id, :company_id, :category_id, :create_date)';
          $data = array(
            ':answer_item_id' => $val['answer_item_id'],
            ':answer' => $val['answer'],
            ':post_id' => $dbPostData[0]['id'],
            ':user_id' => $user_id,
            ':company_id' => $company_id,
            ':category_id' => $val['category_id'],
            ':create_date' => date('Y-m-d H:i:s'),
          );
          debug('DBに新規登録するpostAnswersのdata：'.print_r($data, true));
          $stmt = queryPost($dbh, $sql, $data);
        }
        if($stmt){
          debug('回答をデータベースに新規登録しました！');
          debug('次のページへ遷移します。');
          header('Location:surveyConfirm.php');
          exit();
        }
      } catch (Exeption $e) {
        error_log('エラー発生：' . $e->getMessage());
      }
    }else{    // 既にanswersテーブルに、ユーザーの投稿があった場合、UPDATE
      debug('回答が既に登録されています。回答の更新を行います！');
      try{
        $dbh = dbConnect();
        // 評価値をDB、answersテーブルで更新
        foreach($postAnswers as $key => $val){
          $sql = 'UPDATE answers SET answer = :answer WHERE answer_item_id = :answer_item_id AND post_id = :post_id AND user_id = :user_id AND company_id = :company_id AND category_id = :category_id AND delete_flg = 0';
          $data = array(
            ':answer' => $val['answer'],
            ':answer_item_id' => $val['answer_item_id'],
            ':post_id' => $dbPostData[0]['id'],
            ':user_id' => $user_id,
            ':company_id' => $company_id,
            ':category_id' => $val['category_id'],
          );
          debug('DBで更新するpostAnswersのdata：'.print_r($data, true));
          $stmt = queryPost($dbh, $sql, $data);
        }
        if($stmt){
          debug('回答の更新が完了しました！');
          debug('次のページへ遷移します。');
          header('Location:surveyConfirm.php');
          exit();
        }
      } catch (Exeption $e) {
        error_log('エラー発生：' . $e->getMessage());
      }
    }
  }
}

?>

<?php
$siteTitle = '企業調査';
require('head.php');
?>

<body>
  <?php
    require('header.php');
  ?>

  <!-- セッション変数内のメッセージを表示 -->
  <p id="js-show-msg" style="display:none;" class="msg-slide">
    <?php echo getSessionFlash('msg_success'); ?>
  </p>

  <main>
    <div class="l-content-wrapper">
      <div class="container">
        <div class="bg-white py-4">
          <h1 class="page-title mb-4">
            <?php echo $dbCompanyData['info']['name']; ?>について教えてください。（3/3）
          </h1>

          <div class="border mb-5 m-3 p-3 text-center text-red">
            <p>合計500文字以上になるようにご回答下さい。</p>
          </div>

          <?php if(!empty($err_msg['total_count'])){ ?>
            <div class="mb-4 text-center">
              <span class="fw-bold text-red">
                <?php echo $err_msg['total_count']; ?>
              </span>
            </div>
          <?php } ?>

          <form action="" method="post" class="mx-3">
            <?php foreach($dbAnswerItems as $key => $val){ ?>

              <div class="mb-5">
                <div class="mb-2 fw-bold">
                  <?php echo $val['name']; ?>
                </div>

                <div class="mb-3">
                  <?php echo $val['question']; ?>
                </div>

                <div class="d-block">
                  <span class="text-red">
                    <?php if(!empty($err_msg[$val['english_name']])) echo $err_msg[$val['english_name']]; ?>
                  </span>
                </div>

                <textarea name="<?php echo $val['english_name']; ?>" id="js-count<?php echo $key; ?>" class="js-character-count" style="min-height: 8rem;"><?php
                  if(!empty($_POST[$val['english_name']])){
                    echo $_POST[$val['english_name']];
                  }elseif(!empty($val['answer'])){
                    echo $val['answer'];
                  }
                ?></textarea>

                <div class="fs-08 float-end">
                  <span class="text-gray">
                    合計文字数：<span class="js-count-view fw-bold text-black<?php if($totalCount >= 500) echo ' text-green'; ?>"><?php echo (!empty($totalCount)) ? $totalCount : 0;  ?></span> / 500字
                  </span>
                </div>
              </div>
              
            <?php } ?>

            <button type="submit" class="btn btn-blue js-disabled-submit">次へ</button>
          </form>
        </div>
      </div>
    </div>
  </main>

  <?php
    require('footer.php');
  ?>