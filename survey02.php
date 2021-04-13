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
// POSTパラメータを取得
//----------------------------
if(!empty($_POST)){
  
  // 企業情報取得
  $dbCompanyData = getCompanyOne($c_id);
  // パラメータに不正な値が入っているかチェック
  if(!is_int((int)$c_id)){
    error_log('エラー発生:指定ページに不正な値が入りました');
    header('Location:surveyInfo.php');
    exit();
  }
}else{
  header('Location:surveyInfo.php');
  exit();
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

          <form action="survey03.php" method="post" class="mx-4">
            <div class="mb-5">
              <label class="fw-bold mb-2">雇用形態<span class="fw-normal text-danger">（必須）</span></label>
            </div>


            <button class="btn btn-blue">次へ</button>
          </form>
          
        </div>
        
      </div>
    </div>
  </main>

  <?php
    require('footer.php');
  ?>