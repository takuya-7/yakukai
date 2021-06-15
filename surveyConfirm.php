<?php
require('config.php');
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「 クチコミ投稿確認画面');
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
$dbQuestionsAndRatings = getQuestionsAndRatings($dbPostData[0]['id'], $user_id, $company_id);
// 質問文・回答を取得
$dbQuestionsAndAnswers = getQuestionsAndAnswers($dbPostData[0]['id'], $user_id, $company_id, true);

// POSTパラメータを取得
//----------------------------
if(!empty($_POST)){
  debug('POST送信があります。');
  debug('POST情報：' . print_r($_POST, true));

  // postsテーブルでpost_flg更新
  try{
    $dbh = dbConnect();
    $sql = 'UPDATE posts SET post_flg = 1 WHERE id = :id AND user_id = :user_id AND company_id = :company_id AND delete_flg = 0';
    $data = array(
      ':id' => $dbPostData[0]['id'],
      ':user_id' => $user_id,
      ':company_id' => $company_id,
    );
    $stmt_post = queryPost($dbh, $sql, $data);
    if($stmt_post){
      debug('postsテーブルでpost_flgを1に更新しました！');
    }
  } catch (Exeption $e) {
    error_log('エラー発生：' . $e->getMessage());
  }

  // ratingsテーブルでpost_flg更新
  try{
    $dbh = dbConnect();
    $sql = 'UPDATE ratings SET post_flg = 1 WHERE post_id = :post_id AND user_id = :user_id AND company_id = :company_id AND delete_flg = 0';
    $data = array(
      ':post_id' => $dbPostData[0]['id'],
      ':user_id' => $user_id,
      ':company_id' => $company_id,
    );
    $stmt_rating = queryPost($dbh, $sql, $data);
    if($stmt_rating){
      debug('ratingsテーブルでpost_flgを1に更新しました！');
    }
  } catch (Exeption $e) {
    error_log('エラー発生：' . $e->getMessage());
  }

  // answersテーブルでpost_flg更新
  try{
    $dbh = dbConnect();
    $sql = 'UPDATE answers SET post_flg = 1 WHERE post_id = :post_id AND user_id = :user_id AND company_id = :company_id AND delete_flg = 0';
    $data = array(
      ':post_id' => $dbPostData[0]['id'],
      ':user_id' => $user_id,
      ':company_id' => $company_id,
    );
    $stmt_answer = queryPost($dbh, $sql, $data);
    if($stmt_answer){
      debug('answersテーブルでpost_flgを1に更新しました！');
    }
  } catch (Exeption $e) {
    error_log('エラー発生：' . $e->getMessage());
  }

  if($stmt_post && $stmt_rating && $stmt_answer){
    debug('postsテーブル、ratingsテーブル、answersテーブル全てでpost_flg = 1を設定できました！');
    debug('マイページへ遷移します！');
    header('Location:mypage.php');
    exit();
  }
  
}

?>

<?php
$siteTitle = '入力内容確認';
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
        <div class="l-content u-mb-5">
          <div class="l-inner-container">
            <h1 class="c-page-title">
              入力内容の確認
            </h1>
            
            <div class="c-box">
              <p class="c-box__note">入力内容を確認して投稿を完了させてください。</p>
            </div>
            
  
            <!-- 会社確認 -->
            <div class="c-box01">
              <span class="u-fw-bold u-mb-3">
                <?php echo sanitize($dbCompanyData['info']['name']); ?>
              </span>
              <br>
              <span class="ms-3">本社所在地：<?php echo sanitize($dbCompanyData['info']['prefecture_name'].$dbCompanyData['info']['city_name']); ?></span>
            </div>
          </div>
        </div>
        
        <div class="l-content u-mb-5">
          <div class="l-inner-container">
            <!-- STEP2 -->
            <div class="">
              <?php foreach($dbQuestionsAndRatings as $key => $val){ ?>
                <div class="u-mb-4">
                  <div class="u-mb-3">
                    <?php echo $val['question']; ?>
                  </div>
    
                  <div class="u-mb-3 u-ms-3 u-fw-bold">
                    <?php echo RATING_ANSWER[$val['rating']]; ?>
                  </div>
      
                  <div class="border-bottom"></div>
                </div>
              <?php } ?>
            </div>
    
            <div class="u-mb-3">
    
              <div class="u-mb-3">
                あなたのこの会社での給与・年収について教えてください（手取りではなく額面）。
              </div>
    
              <table class="table">
                <tr>
                  <td>年収</td>
                  <td class="fw-bold"><span class="u-fs-12"><?php echo $dbPostData[0]['anual_total_salary']; ?></span><span class="u-fs-08"> 万円</span></td>
                </tr>
                <?php if(!empty($dbPostData[0]['monthly_total_salary'])){ ?>
                  <tr>
                    <td>月給（総額）</td>
                    <td class="fw-bold"><span class="u-fs-12"><?php echo $dbPostData[0]['monthly_total_salary']; ?></span><span class="u-fs-08"> 万円</span></td>
                  </tr>
                <?php } ?>
                <?php if(!empty($dbPostData[0]['monthly_overtime_salary'])){ ?>
                  <tr>
                    <td>残業代（月額）</td>
                    <td class="fw-bold"><span class="u-fs-12"><?php echo $dbPostData[0]['monthly_overtime_salary']; ?></span><span class="u-fs-08"> 万円</span></td>
                  </tr>
                <?php } ?>
                <?php if(!empty($dbPostData[0]['monthly_allowance'])){ ?>
                  <tr>
                    <td>手当て（月額）</td>
                    <td class="fw-bold"><span class="u-fs-12"><?php echo $dbPostData[0]['monthly_allowance']; ?></span><span class="u-fs-08"> 万円</span></td>
                  </tr>
                <?php } ?>
                <?php if(!empty($dbPostData[0]['anual_bonus_salary'])){ ?>
                  <tr>
                    <td>賞与（年額）</td>
                    <td class="fw-bold"><span class="u-fs-12"><?php echo $dbPostData[0]['anual_bonus_salary']; ?></span><span class="u-fs-08"> 万円</span></td>
                  </tr>
                <?php } ?>
              </table>
            </div>
            <a href="survey02.php">STEP2の回答を修正する</a>
          </div>
        </div>
        
    
        <div class="l-content  u-mb-5">
          <div class="l-inner-container">
            <!-- STEP3 -->
            <?php foreach($dbQuestionsAndAnswers as $key => $val){ ?>
              <div class="u-mb-4">
                <div class="u-mb-3">
                  <?php echo $val['question']; ?>
                </div>
    
                <div class="u-mb-4 u-p-2 border u-bg-gray">
                  <?php echo $val['answer']; ?>
                </div>
    
                <div class="border-bottom"></div>
              </div>
            <?php } ?>

            <a href="survey03.php">STEP3の回答を修正する</a>
          </div>
        </div>
          
        <form action="" method="post" class="mx-3">
          <input type="hidden" name="post_flg" value="1">
          <button type="submit" class="c-button c-button--blue c-button--width100">この内容で投稿する</button>
        </form>
          
      </div>
    </div>
  </main>

  <?php
    require('footer.php');
  ?>