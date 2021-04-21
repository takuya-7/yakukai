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
$u_id = $_SESSION['user_id'];
// ユーザーのクチコミレコード取得
$dbPostData = getPost($u_id);
// ユーザーのクチコミレコードから企業ID取得
$c_id = $dbPostData[0]['company_id'];
if(!empty($c_id)){
  debug('クチコミレコードに企業IDが登録されていました。企業情報を取得します。');
  $dbCompanyData = getCompanyOne($c_id);
}else{
  debug('クチコミレコードに企業IDの登録がありません。surveyInfo.phpに遷移します。');
  header('Location:surveyInfo.php');
  exit();
}
// 企業の評価項目を取得
$dbRatingItemsAndQuestions = getRatingItemsAndQuestions();

// POSTパラメータを取得
//----------------------------
if(!empty($_POST)){
  debug('POST送信があります。');
  debug('POST情報：' . print_r($_POST, true));

  $total_well_being_rating = $_POST['total_well_being_rating'];
  $company_grouth_potential_rating = $_POST['company_grouth_potential_rating'];
  $unique_selling_point_rating = $_POST['unique_selling_point_rating'];
  $result_oriented_rating = $_POST['result_oriented_rating'];
  $u30_growth_potential_rating = $_POST['u30_growth_potential_rating'];
  $inovation_effort_rating = $_POST['inovation_effort_rating'];
  $leadership_evaluation_rating = $_POST['leadership_evaluation_rating'];
  $human_relationship_rating = $_POST['human_relationship_rating'];
  $working_hour_satisfaction_rating = $_POST['working_hour_satisfaction_rating'];
  $holiday_obtaining_satisfaction_rating = $_POST['holiday_obtaining_satisfaction_rating'];
  $salary_satisfaction_rating = $_POST['salary_satisfaction_rating'];
  $good_for_career_rating = $_POST['good_for_career_rating'];
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
  validRequired($monthly_total_salary, 'monthly_total_salary');
  
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
                <div class="d-inline-block">
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
                    <label>
                      <input type="radio" name="<?php echo $val['english_name']; ?>" value="5">
                      <span>そう思う</span>
                    </label>
                  </li>

                  <li>
                    <label>
                      <input type="radio" name="<?php echo $val['english_name']; ?>" value="4">
                      <span>まあそう思う</span>
                    </label>
                  </li>

                  <li>
                    <label>
                      <input type="radio" name="<?php echo $val['english_name']; ?>" value="3">
                      <span>どちらとも言えない</span>
                    </label>
                  </li>

                  <li>
                    <label>
                      <input type="radio" name="<?php echo $val['english_name']; ?>" value="2">
                      <span>あまりそう思わない</span>
                    </label>
                  </li>

                  <li>
                    <label>
                      <input type="radio" name="<?php echo $val['english_name']; ?>" value="1">
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
              </div>
              <div class="col-7 d-inline">
                <input type="text" name="anual_total_salary" placeholder="例：400" class="w-75 d-inline h-2-5">
                <span class="fs-08">万円</span>
              </div>
            </div>

            <div class="mb-2 row">
              <div class="col-5 pe-0">
                <span class="lh-2-5">月給（月額）</span>
              </div>
              <div class="col-7 d-inline">
                <input type="text" name="monthly_total_salary" class="w-75 d-inline h-2-5">
                <span class="fs-08">万円</span>
              </div>
            </div>

            <div class="mb-2 row">
              <div class="col-5 pe-0">
                <span class="lh-2-5"> - 残業代（月額）</span>
              </div>
              <div class="col-7 d-inline">
                <input type="text" name="monthly_overtime_salary" class="w-75 d-inline h-2-5">
                <span class="fs-08">万円</span>
              </div>
            </div>

            <div class="mb-2 row">
              <div class="col-5 pe-0">
                <span class="lh-2-5"> - 手当て（月額）</span>
              </div>
              <div class="col-7 d-inline">
                <input type="text" name="monthly_allowance" class="w-75 d-inline h-2-5">
                <span class="fs-08">万円</span>
              </div>
            </div>

            <div class="mb-2 row">
              <div class="col-5 pe-0">
                <span class="lh-2-5">賞与（年額）</span>
              </div>
              <div class="col-7 d-inline">
                <input type="text" name="anual_bonus_salary" class="w-75 d-inline h-2-5">
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