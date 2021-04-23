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
if(!empty($company_id)){
  debug('クチコミレコードに企業IDが登録されていました。企業情報を取得します。');
  $dbCompanyData = getCompanyOne($company_id);
}else{
  debug('クチコミレコードに企業IDの登録がありません。surveyInfo.phpに遷移します。');
  header('Location:surveyInfo.php');
  exit();
}
// 企業の評価項目を取得
$dbRatingItemsAndQuestions = getRatingItemsAndQuestions();
// 評価項目のIDを取得
$rating_item_ids = array();
foreach($dbRatingItemsAndQuestions as $key => $val){
  $rating_item_ids[] = $val['id'];
}
// POSTパラメータを取得
//----------------------------
if(!empty($_POST)){
  debug('POST送信があります。');
  debug('POST情報：' . print_r($_POST, true));

  // インデックスがあれば変数に値を格納。なければ''を格納。
  if(isset($_POST['total_well_being_rating'])){
    $total_well_being_rating = $_POST['total_well_being_rating'];
  }else{
    $total_well_being_rating = '';
  }
  if(isset($_POST['company_grouth_potential_rating'])){
    $company_grouth_potential_rating = $_POST['company_grouth_potential_rating'];
  }else{
    $company_grouth_potential_rating = '';
  }
  if(isset($_POST['unique_selling_point_rating'])){
    $unique_selling_point_rating = $_POST['unique_selling_point_rating'];
  }else{
    $unique_selling_point_rating = '';
  }
  if(isset($_POST['result_oriented_rating'])){
    $result_oriented_rating = $_POST['result_oriented_rating'];
  }else{
    $result_oriented_rating = '';
  }
  if(isset($_POST['u30_growth_potential_rating'])){
    $u30_growth_potential_rating = $_POST['u30_growth_potential_rating'];
  }else{
    $u30_growth_potential_rating = '';
  }
  if(isset($_POST['inovation_effort_rating'])){
    $inovation_effort_rating = $_POST['inovation_effort_rating'];
  }else{
    $inovation_effort_rating = '';
  }
  if(isset($_POST['leadership_evaluation_rating'])){
    $leadership_evaluation_rating = $_POST['leadership_evaluation_rating'];
  }else{
    $leadership_evaluation_rating = '';
  }
  if(isset($_POST['human_relationship_rating'])){
    $human_relationship_rating = $_POST['human_relationship_rating'];
  }else{
    $human_relationship_rating = '';
  }
  if(isset($_POST['working_hour_satisfaction_rating'])){
    $working_hour_satisfaction_rating = $_POST['working_hour_satisfaction_rating'];
  }else{
    $working_hour_satisfaction_rating = '';
  }
  if(isset($_POST['holiday_obtaining_satisfaction_rating'])){
    $holiday_obtaining_satisfaction_rating = $_POST['holiday_obtaining_satisfaction_rating'];
  }else{
    $holiday_obtaining_satisfaction_rating = '';
  }
  if(isset($_POST['salary_satisfaction_rating'])){
    $salary_satisfaction_rating = $_POST['salary_satisfaction_rating'];
  }else{
    $salary_satisfaction_rating = '';
  }
  if(isset($_POST['good_for_career_rating'])){
    $good_for_career_rating = $_POST['good_for_career_rating'];
  }else{
    $good_for_career_rating = '';
  }
  $anual_total_salary = $_POST['anual_total_salary'];
  $monthly_total_salary = $_POST['monthly_total_salary'];
  $monthly_overtime_salary = $_POST['monthly_overtime_salary'];
  $monthly_allowance = $_POST['monthly_allowance'];
  $anual_bonus_salary = $_POST['anual_bonus_salary'];

  //未入力チェック
  validRequired($total_well_being_rating, 'total_well_being_rating');
  validRequired($company_grouth_potential_rating, 'company_grouth_potential_rating');
  validRequired($unique_selling_point_rating, 'unique_selling_point_rating');
  validRequired($result_oriented_rating, 'result_oriented_rating');
  validRequired($u30_growth_potential_rating, 'u30_growth_potential_rating');
  validRequired($inovation_effort_rating, 'inovation_effort_rating');
  validRequired($leadership_evaluation_rating, 'leadership_evaluation_rating');
  validRequired($human_relationship_rating, 'human_relationship_rating');
  validRequired($working_hour_satisfaction_rating, 'working_hour_satisfaction_rating');
  validRequired($holiday_obtaining_satisfaction_rating, 'holiday_obtaining_satisfaction_rating');
  validRequired($salary_satisfaction_rating, 'salary_satisfaction_rating');
  validRequired($good_for_career_rating, 'good_for_career_rating');
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
    // 評価値を配列に格納
    $postRatings = array(
      array('rating_item_id' => $rating_item_ids[0], 'rating' => $total_well_being_rating),
      array('rating_item_id' => $rating_item_ids[1], 'rating' => $company_grouth_potential_rating),
      array('rating_item_id' => $rating_item_ids[2], 'rating' => $unique_selling_point_rating),
      array('rating_item_id' => $rating_item_ids[3], 'rating' => $result_oriented_rating),
      array('rating_item_id' => $rating_item_ids[4], 'rating' => $u30_growth_potential_rating),
      array('rating_item_id' => $rating_item_ids[5], 'rating' => $inovation_effort_rating),
      array('rating_item_id' => $rating_item_ids[6], 'rating' => $leadership_evaluation_rating),
      array('rating_item_id' => $rating_item_ids[7], 'rating' => $human_relationship_rating),
      array('rating_item_id' => $rating_item_ids[8], 'rating' => $working_hour_satisfaction_rating),
      array('rating_item_id' => $rating_item_ids[9], 'rating' => $holiday_obtaining_satisfaction_rating),
      array('rating_item_id' => $rating_item_ids[10], 'rating' => $salary_satisfaction_rating),
      array('rating_item_id' => $rating_item_ids[11], 'rating' => $good_for_career_rating),
    );
    try{
      $dbh = dbConnect();

      // 評価値をDB、ratingsテーブルに登録
      foreach($postRatings as $key => $val){
        $sql = 'INSERT INTO ratings (rating_item_id, rating, post_id, user_id, company_id, create_date) VALUES (:rating_item_id, :rating, :post_id, :user_id, :company_id, :create_date)';
        $date = array(
          ':rating_item_id' => $val['rating_item_id'],
          ':rating' => $val['rating'],
          ':post_id' => $dbPostData[0]['id'],
          ':user_id' => $user_id,
          ':company_id' => $company_id,
          ':create_date' => date('Y-m-d H:i:s'),
        );
        $stmtRatings = queryPost($dbh, $sql, $data);
      }
      if($stmtRatings){
        debug('評価値をデータベースに登録しました！');
      }
    } catch (Exeption $e) {
      error_log('エラー発生：' . $e->getMessage());
    }

    try{
      // 年収・給与情報をDB、postsテーブルに登録
      $sql = 'UPDATE posts SET anual_total_salary = :anual_total_salary, monthly_total_salary = :monthly_total_salary, monthly_overtime_salary = :monthly_overtime_salary, monthly_allowance = :monthly_allowance, anual_bonus_salary = :anual_bonus_salary WHERE user_id = :user_id AND company_id = :company_id AND delete_flg = 0';
      $data = array(
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
        debug('年収・給与情報をデータベースに登録しました！評価値、給与情報、両方登録できているため次のページへ遷移します！');
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
    <div class="content-wrapper">
      <div class="container">
        <div class="bg-white py-4">
          <h1 class="page-title mb-4">
            <?php echo $dbCompanyData['name']; ?>について教えてください。（2/3）
          </h1>

          <form action="" method="post" class="mx-3">
            <?php foreach($dbRatingItemsAndQuestions as $key => $val){ ?>

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
                    <label class="<?php if($_POST[$val['english_name']] == 5) echo 'checked'; ?>">
                      <input type="radio" name="<?php echo $val['english_name']; ?>" value="5"
                      <?php
                        if(!empty($_POST[$val['english_name']])){
                          if($_POST[$val['english_name']] == 5) echo ' checked';
                        }
                      ?>>
                      <span>そう思う</span>
                    </label>
                  </li>

                  <li>
                    <label class="<?php if($_POST[$val['english_name']] == 4) echo 'checked'; ?>">
                      <input type="radio" name="<?php echo $val['english_name']; ?>" value="4"
                      <?php
                        if(!empty($_POST[$val['english_name']])){
                          if($_POST[$val['english_name']] == 4) echo ' checked'; 
                        }
                      ?>>
                      <span>まあそう思う</span>
                    </label>
                  </li>

                  <li>
                    <label class="<?php if($_POST[$val['english_name']] == 3) echo 'checked'; ?>">
                      <input type="radio" name="<?php echo $val['english_name']; ?>" value="3"
                      <?php
                        if(!empty($_POST[$val['english_name']])){
                          if($_POST[$val['english_name']] == 3) echo ' checked';
                        }
                      ?>>
                      <span>どちらとも言えない</span>
                    </label>
                  </li>

                  <li>
                    <label class="<?php if($_POST[$val['english_name']] == 2) echo 'checked'; ?>">
                      <input type="radio" name="<?php echo $val['english_name']; ?>" value="2"
                      <?php
                        if(!empty($_POST[$val['english_name']])){
                          if($_POST[$val['english_name']] == 2) echo ' checked';
                        }
                      ?>>
                      <span>あまりそう思わない</span>
                    </label>
                  </li>

                  <li>
                    <label class="<?php if($_POST[$val['english_name']] == 1) echo 'checked'; ?>">
                      <input type="radio" name="<?php echo $val['english_name']; ?>" value="1"
                      <?php
                        if(!empty($_POST[$val['english_name']])){
                          if($_POST[$val['english_name']] == 1) echo ' checked';
                        }
                      ?>>
                      <span>そう思わない</span>
                    </label>
                  </li>
                </ul>
              </div>
            <?php } ?>

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
                <input type="text" name="anual_total_salary" placeholder="例：400" value="<?php echo getFormData('anual_total_salary', false); ?>" class="w-75 d-inline h-2-5<?php if(!empty($err_msg['anual_total_salary'])) echo ' bg-red'; ?>">
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
                <input type="text" name="monthly_total_salary" value="<?php echo getFormData('monthly_total_salary', false); ?>" class="w-75 d-inline h-2-5<?php if(!empty($err_msg['monthly_total_salary'])) echo ' bg-red'; ?>">
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
                <input type="text" name="monthly_overtime_salary" value="<?php echo getFormData('monthly_overtime_salary', false); ?>" class="w-75 d-inline h-2-5<?php if(!empty($err_msg['monthly_overtime_salary'])) echo ' bg-red'; ?>">
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
                <input type="text" name="monthly_allowance" value="<?php echo getFormData('monthly_allowance', false); ?>" class="w-75 d-inline h-2-5<?php if(!empty($err_msg['monthly_allowance'])) echo ' bg-red'; ?>">
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
                <input type="text" name="anual_bonus_salary" value="<?php echo getFormData('anual_bonus_salary', false); ?>" class="w-75 d-inline h-2-5<?php if(!empty($err_msg['anual_bonus_salary'])) echo ' bg-red'; ?>">
                <span class="fs-08">万円</span>
              </div>
            </div>

            <button type="submit" class="btn btn-blue">次へ</button>
          </form>
        </div>
      </div>
    </div>
  </main>

  <?php
    require('footer.php');
  ?>