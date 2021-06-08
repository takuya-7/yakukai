<?php
require('config.php');
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「 クチコミ投稿（2/3）');
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
// 企業IDから企業情報を取得
if(!empty($company_id)){
  debug('クチコミレコードに企業IDが登録されていました。企業情報を取得します。');
  $dbCompanyData = getCompanyOne($company_id);
}else{
  debug('クチコミレコードに企業IDの登録がありません。surveyInfo.phpに遷移します。');
  header('Location:surveyInfo.php');
  exit();
}
// 項目・質問文・評価値を取得
// DBに既に評価値があれば評価値もまとめて取得
if(!empty(getQuestionsAndRatings($dbPostData[0]['id'], $user_id, $company_id))){
  $dbRatingItems = getQuestionsAndRatings($dbPostData[0]['id'], $user_id, $company_id);
}else{    // まだDBに登録していなければ項目・質問文を取得
  $dbRatingItems = getRatingItemsAndQuestions();
}

// 残業時間格納
$form_over_time = array(0, 3, 5, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100, 120, 140, 160, 180, 200);

// POSTパラメータを取得
//----------------------------
if(!empty($_POST)){
  debug('POST送信があります。');
  debug('POST情報：' . print_r($_POST, true));

  $postRatings = array();

  // 回答ごとに、インデックスがあれば２次元配列にカテゴリID、質問項目のID、送信された回答を格納。なければ回答部分にNULLを格納。
  foreach($dbRatingItems as $key => $val){
    if(!empty($_POST[$val['english_name']])){
      $postRatings[$key] = array(
        'rating_item_id' => $val['rating_item_id'],
        'english_name' => $val['english_name'],
        'rating' => $_POST[$val['english_name']],
      );
    }else{
      $postRatings[$key] = array(
        'rating_item_id' => $val['rating_item_id'],
        'english_name' => $val['english_name'],
        'rating' => NULL,
      );
    }
  }
  debug('$postAnswers：'.print_r($postRatings, true));

  $over_time = $_POST['over_time'];
  $anual_total_salary = $_POST['anual_total_salary'];

  if(!empty($_POST['monthly_total_salary'])){
    $monthly_total_salary = $_POST['monthly_total_salary'];
  }else{
    $monthly_total_salary = NULL;
  }
  if(!empty($_POST['monthly_overtime_salary'])){
    $monthly_overtime_salary = $_POST['monthly_overtime_salary'];
  }else{
    $monthly_overtime_salary = NULL;
  }
  if(!empty($_POST['monthly_allowance'])){
    $monthly_allowance = $_POST['monthly_allowance'];
  }else{
    $monthly_allowance = NULL;
  }
  if(!empty($_POST['anual_bonus_salary'])){
    $anual_bonus_salary = $_POST['anual_bonus_salary'];
  }else{
    $anual_bonus_salary = NULL;
  }

  //未入力チェック
  foreach($postRatings as $key => $val){
    validRequired($val['rating'], $val['english_name']);
  }
  validRequired($over_time, 'over_time');
  validRequired($anual_total_salary, 'anual_total_salary');
  
  // 半角数字チェック
  if(!empty($anual_total_salary)){
    validNumber($anual_total_salary, 'anual_total_salary');
  }
  if(!empty($monthly_total_salary)){
    validNumber($monthly_total_salary, 'monthly_total_salary');
  }
  if(!empty($monthly_overtime_salary)){
    validNumber($monthly_overtime_salary, 'monthly_overtime_salary');
  }
  if(!empty($monthly_allowance)){
    validNumber($monthly_allowance, 'monthly_allowance');
  }
  if(!empty($anual_bonus_salary)){
    validNumber($anual_bonus_salary, 'anual_bonus_salary');
  }

  if(empty($err_msg)){
    debug('バリデーションOKです！');
    
    // まだ評価値が投稿されていなければ、INSERTを行う
    if(empty(getRatings($dbPostData[0]['id'], $user_id, $company_id,))){
      debug('評価値がまだ登録されていません。評価値の挿入を行います！');
      try{
        $dbh = dbConnect();
        // 評価値をDB、ratingsテーブルに登録
        foreach($postRatings as $key => $val){
          $sql = 'INSERT INTO ratings (rating_item_id, rating, post_id, user_id, company_id, create_date) VALUES (:rating_item_id, :rating, :post_id, :user_id, :company_id, :create_date)';
          $data = array(
            ':rating_item_id' => $val['rating_item_id'],
            ':rating' => $val['rating'],
            ':post_id' => $dbPostData[0]['id'],
            ':user_id' => $user_id,
            ':company_id' => $company_id,
            ':create_date' => date('Y-m-d H:i:s'),
          );
          debug('postRatingsのdata：'.print_r($data, true));
          $stmtRatings = queryPost($dbh, $sql, $data);
        }
        if($stmtRatings){
          debug('評価値をデータベースに新規登録しました！');
        }
      } catch (Exeption $e) {
        error_log('エラー発生：' . $e->getMessage());
      }
    }else{    // 既にratingsテーブルに、ユーザーの投稿があった場合、UPDATE
      debug('評価値が既に登録されています。評価値の更新を行います！');
      try{
        $dbh = dbConnect();
        // 評価値をDB、ratingsテーブルで更新
        foreach($postRatings as $key => $val){
          $sql = 'UPDATE ratings SET rating = :rating WHERE rating_item_id = :rating_item_id AND post_id = :post_id AND user_id = :user_id AND company_id = :company_id AND delete_flg = 0';
          $data = array(
            ':rating' => $val['rating'],
            ':rating_item_id' => $val['rating_item_id'],
            ':post_id' => $dbPostData[0]['id'],
            ':user_id' => $user_id,
            ':company_id' => $company_id,
          );
          debug('postRatingsのdata：'.print_r($data, true));
          $stmtRatings = queryPost($dbh, $sql, $data);
        }
        if($stmtRatings){
          debug('評価値の更新が完了しました！');
        }
      } catch (Exeption $e) {
        error_log('エラー発生：' . $e->getMessage());
      }
    }

    try{
      // 残業時間、年収・給与情報をDB、postsテーブルに登録
      $sql = 'UPDATE posts SET over_time = :over_time, anual_total_salary = :anual_total_salary, monthly_total_salary = :monthly_total_salary, monthly_overtime_salary = :monthly_overtime_salary, monthly_allowance = :monthly_allowance, anual_bonus_salary = :anual_bonus_salary WHERE user_id = :user_id AND company_id = :company_id AND delete_flg = 0';
      $data = array(
        ':over_time' => $over_time,
        ':anual_total_salary' => $anual_total_salary,
        ':monthly_total_salary' => $monthly_total_salary,
        ':monthly_overtime_salary' => $monthly_overtime_salary,
        ':monthly_allowance' => $monthly_allowance,
        ':anual_bonus_salary' => $anual_bonus_salary,
        ':user_id' => $user_id,
        ':company_id' => $company_id,
      );
      $stmtPosts = queryPost($dbh, $sql, $data);
      if($stmtRatings && $stmtPosts){
        debug('===================================================');
        debug('年収・給与情報をデータベースに登録しました！評価値、給与情報、両方登録できているため次のページへ遷移します！');
        debug('===================================================');
        header('Location:survey03.php');
        exit();
      }
    } catch (Exeption $e) {
      error_log('エラー発生：' . $e->getMessage());
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
      <div class="l-container">
        <div class="bg-white py-4">
          <h1 class="mb-4">
            <?php echo $dbCompanyData['info']['name']; ?>について教えてください。（2/3）
          </h1>

          <?php if(!empty($err_msg)){ ?>
            <div class="mb-4 text-center">
              <span class="fw-bold text-red">
                入力不備がございます。
              </span>
            </div>
          <?php } ?>

          <form action="" method="post" class="mx-3">
            <?php foreach($dbRatingItems as $key => $val){ ?>

              <div class="mb-2 fw-bold">
                <?php echo $val['name']; ?><span class="fw-normal text-red">（必須）</span>
                <div class="d-block">
                  <span class="text-red">
                    <?php if(!empty($err_msg[$val['english_name']])) echo $err_msg[$val['english_name']]; ?>
                  </span>
                </div>
              </div>

              <div class="mb-3">
                <?php echo $val['question']; ?>
              </div>

              <div class="survey-list mb-5">
                <ul class="">
                  <li>
                    <label class="<?php
                        if(!empty($_POST[$val['english_name']])){
                          if($_POST[$val['english_name']] == 5) echo ' checked';
                        }elseif(!empty($val['rating'])){
                          if($val['rating'] == 5) echo ' checked';
                        }
                      ?>">
                      <input type="radio" name="<?php echo $val['english_name']; ?>" value="5"
                      <?php
                        if(!empty($_POST[$val['english_name']])){
                          if($_POST[$val['english_name']] == 5) echo ' checked';
                        }elseif(!empty($val['rating'])){
                          if($val['rating'] == 5) echo ' checked';
                        }
                      ?>>
                      <span>そう思う</span>
                    </label>
                  </li>

                  <li>
                    <label class="<?php
                        if(!empty($_POST[$val['english_name']])){
                          if($_POST[$val['english_name']] == 4) echo ' checked';
                        }elseif(!empty($val['rating'])){
                          if($val['rating'] == 4) echo ' checked';
                        }
                      ?>">
                      <input type="radio" name="<?php echo $val['english_name']; ?>" value="4"
                      <?php
                        if(!empty($_POST[$val['english_name']])){
                          if($_POST[$val['english_name']] == 4) echo ' checked';
                        }elseif(!empty($val['rating'])){
                          if($val['rating'] == 4) echo ' checked';
                        }
                      ?>>
                      <span>まあそう思う</span>
                    </label>
                  </li>

                  <li>
                    <label class="<?php
                        if(!empty($_POST[$val['english_name']])){
                          if($_POST[$val['english_name']] == 3) echo ' checked';
                        }elseif(!empty($val['rating'])){
                          if($val['rating'] == 3) echo ' checked';
                        }
                      ?>">
                      <input type="radio" name="<?php echo $val['english_name']; ?>" value="3"
                      <?php
                        if(!empty($_POST[$val['english_name']])){
                          if($_POST[$val['english_name']] == 3) echo ' checked';
                        }elseif(!empty($val['rating'])){
                          if($val['rating'] == 3) echo ' checked';
                        }
                      ?>>
                      <span>どちらとも言えない</span>
                    </label>
                  </li>

                  <li>
                    <label class="<?php
                        if(!empty($_POST[$val['english_name']])){
                          if($_POST[$val['english_name']] == 2) echo ' checked';
                        }elseif(!empty($val['rating'])){
                          if($val['rating'] == 2) echo ' checked';
                        }
                      ?>">
                      <input type="radio" name="<?php echo $val['english_name']; ?>" value="2"
                      <?php
                        if(!empty($_POST[$val['english_name']])){
                          if($_POST[$val['english_name']] == 2) echo ' checked';
                        }elseif(!empty($val['rating'])){
                          if($val['rating'] == 2) echo ' checked';
                        }
                      ?>>
                      <span>あまりそう思わない</span>
                    </label>
                  </li>

                  <li>
                    <label class="<?php
                        if(!empty($_POST[$val['english_name']])){
                          if($_POST[$val['english_name']] == 1) echo ' checked';
                        }elseif(!empty($val['rating'])){
                          if($val['rating'] == 1) echo ' checked';
                        }
                      ?>">
                      <input type="radio" name="<?php echo $val['english_name']; ?>" value="1"
                      <?php
                        if(!empty($_POST[$val['english_name']])){
                          if($_POST[$val['english_name']] == 1) echo ' checked';
                        }elseif(!empty($val['rating'])){
                          if($val['rating'] == 1) echo ' checked';
                        }
                      ?>>
                      <span>そう思わない</span>
                    </label>
                  </li>
                </ul>
              </div>
            <?php } ?>

            <div class="mb-2 fw-bold">
              残業時間<span class="fw-normal text-red">（必須）</span>
            </div>

            <div class="mb-3">
              あなたのこの会社での残業時間（月間）はどの程度か教えてください。
            </div>

            <div class="d-block mb-2">
              <span class="text-red fw-bold">
                <?php if(!empty($err_msg['over_time'])) echo $err_msg['over_time']; ?>
              </span>
            </div>

            <div class="ms-3 mb-5">
              <div class="cp_ipselect cp_sl01">
                <select name="over_time" class="<?php if(!empty($err_msg['over_time'])) echo 'bg-red'; ?>">
                  <option value="">残業時間</option>
                  <?php foreach($form_over_time as $val){ ?>
                    <option value="<?php echo $val; ?>"
                      <?php
                        if(!empty($_POST['over_time'])){
                          if($_POST['over_time'] == $val) echo ' selected';
                        }elseif(!empty($dbPostData[0]['over_time'])){
                          if($dbPostData[0]['over_time'] == $val) echo ' selected';
                        }
                      ?>><?php echo $val; ?>時間</option>
                  <?php } ?>

                </select>
              </div>
            </div>



            <div class="mb-2 fw-bold">
              給与・年収<span class="fw-normal text-red">（必須）</span>
            </div>

            <div class="mb-3">
              あなたのこの会社での給与・年収について教えてください（手取りではなく額面）。
            </div>

            <div class="mb-2 row">
              <div class="col-5 pe-0">
                <span class="fw-bold lh-2-5">年収</span><span class="fw-normal text-red">（必須）</span>
                <div class="d-block">
                  <span class="text-red fw-bold">
                    <?php if(!empty($err_msg['anual_total_salary'])) echo $err_msg['anual_total_salary']; ?>
                  </span>
                </div>
              </div>
              <div class="col-7 d-inline">
                <input type="text" name="anual_total_salary" placeholder="例：400" value="<?php
                  if(!empty($_POST['anual_total_salary'])){
                    echo $_POST['anual_total_salary'];
                  }elseif(!empty($dbPostData[0]['anual_total_salary'])){
                    echo $dbPostData[0]['anual_total_salary'];
                  }
                ?>" class="w-75 d-inline h-2-5<?php if(!empty($err_msg['anual_total_salary'])) echo ' bg-red'; ?>">
                <span class="fs-08">万円</span>
              </div>
            </div>

            <div class="mb-2 row">
              <div class="col-5 pe-0">
                <span class="lh-2-5">月給（総額）</span>
                <div class="d-block">
                  <span class="text-red fw-bold">
                    <?php if(!empty($err_msg['monthly_total_salary'])) echo $err_msg['monthly_total_salary']; ?>
                  </span>
                </div>
              </div>
              <div class="col-7 d-inline">
                <input type="text" name="monthly_total_salary" value="<?php
                  if(!empty($_POST['monthly_total_salary'])){
                    echo $_POST['monthly_total_salary'];
                  }elseif(!empty($dbPostData[0]['monthly_total_salary'])){
                    echo $dbPostData[0]['monthly_total_salary'];
                  }
                ?>" class="w-75 d-inline h-2-5<?php if(!empty($err_msg['monthly_total_salary'])) echo ' bg-red'; ?>">
                <span class="fs-08">万円</span>
              </div>
            </div>

            <div class="mb-2 row">
              <div class="col-5 pe-0">
                <span class="lh-2-5"> - 残業代（月額）</span>
                <div class="d-block">
                  <span class="text-red fw-bold">
                    <?php if(!empty($err_msg['monthly_overtime_salary'])) echo $err_msg['monthly_overtime_salary']; ?>
                  </span>
                </div>
              </div>
              <div class="col-7 d-inline">
                <input type="text" name="monthly_overtime_salary" value="<?php
                  if(!empty($_POST['monthly_overtime_salary'])){
                    echo $_POST['monthly_overtime_salary'];
                  }elseif(!empty($dbPostData[0]['monthly_overtime_salary'])){
                    echo $dbPostData[0]['monthly_overtime_salary'];
                  }
                ?>" class="w-75 d-inline h-2-5<?php if(!empty($err_msg['monthly_overtime_salary'])) echo ' bg-red'; ?>">
                <span class="fs-08">万円</span>
              </div>
            </div>

            <div class="mb-2 row">
              <div class="col-5 pe-0">
                <span class="lh-2-5"> - 手当て（月額）</span>
                <div class="d-block">
                  <span class="text-red fw-bold">
                    <?php if(!empty($err_msg['monthly_allowance'])) echo $err_msg['monthly_allowance']; ?>
                  </span>
                </div>
              </div>
              <div class="col-7 d-inline">
                <input type="text" name="monthly_allowance" value="<?php
                  if(!empty($_POST['monthly_allowance'])){
                    echo $_POST['monthly_allowance'];
                  }elseif(!empty($dbPostData[0]['monthly_allowance'])){
                    echo $dbPostData[0]['monthly_allowance'];
                  }
                ?>" class="w-75 d-inline h-2-5<?php if(!empty($err_msg['monthly_allowance'])) echo ' bg-red'; ?>">
                <span class="fs-08">万円</span>
              </div>
            </div>

            <div class="mb-2 row">
              <div class="col-5 pe-0">
                <span class="lh-2-5">賞与（年額）</span>
                <div class="d-block">
                  <span class="text-red fw-bold">
                    <?php if(!empty($err_msg['anual_bonus_salary'])) echo $err_msg['anual_bonus_salary']; ?>
                  </span>
                </div>
              </div>
              <div class="col-7 d-inline">
                <input type="text" name="anual_bonus_salary" value="<?php
                  if(!empty($_POST['anual_bonus_salary'])){
                    echo $_POST['anual_bonus_salary'];
                  }elseif(!empty($dbPostData[0]['anual_bonus_salary'])){
                    echo $dbPostData[0]['anual_bonus_salary'];
                  }
                ?>" class="w-75 d-inline h-2-5<?php if(!empty($err_msg['anual_bonus_salary'])) echo ' bg-red'; ?>">
                <span class="fs-08">万円</span>
              </div>
            </div>

            <button type="submit" class="c-button c-button--blue c-button--width100">次へ</button>
          </form>
        </div>
      </div>
    </div>
  </main>

  <?php
    require('footer.php');
  ?>