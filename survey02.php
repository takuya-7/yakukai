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

          <form action="" method="post" class="mx-4">
            <?php foreach($dbRatingItemsAndQuestions as $key => $val){ ?>

              <div class="mb-2 fw-bold">
                <?php echo $val['name']; ?><span class="fw-normal text-red">（必須）</span>
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

            <button type="submit" class="btn btn-blue">次へ</button>
          </form>
        </div>
      </div>
    </div>
  </main>

  <?php
    require('footer.php');
  ?>