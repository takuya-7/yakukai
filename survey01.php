<?php
require('config.php');
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「 クチコミ投稿（1/3）');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');

//================================
// 画面処理
//================================

// 画面表示用データ取得
//================================
// ユーザーのクチコミレコード取得
$dbPostData = getPost($_SESSION['user_id']);
$c_id = $dbPostData[0]['company_id'];
if(!empty($c_id)){
  debug('クチコミレコードに企業IDが登録されていました！');
  $dbCompanyData = getCompanyOne($c_id);
}else{
  debug('クチコミレコードに企業IDの登録がありません。surveyInfo.phpに遷移します。');
  header('Location:survey.php');
  exit();
}


//================================
// POST送信があった場合
if(!empty($_POST)){
  debug('POST送信があります。');
  debug('POST情報：' . print_r($_POST, true));

  // DB登録
  
  header('Location:survey02.php');
}

?>

<?php
$siteTitle = '就業状況の調査';
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
            <?php echo $dbCompanyData['name']; ?>の就業状況について教えてください。（1/3）
          </h1>

          <p class="mb-4 text-center">企業を選び直す場合は<a href="surveyInfo.php">こちら</a></p>

          <form action="" method="post" class="mx-4">
            <div class="mb-5">
              <label class="fw-bold mb-2">雇用形態<span class="fw-normal text-danger">（必須）</span></label>
              <div class="ml-5">
                <div class="cp_ipselect cp_sl01">
                  <select name="user_employment_type">
                    <option value="" hidden>選択してください</option>
                    <option value="10" <?php if($_POST['emp_type'] == 10) echo 'selected'; ?>>正社員</option>
                    <option value="20" <?php if($_POST['emp_type'] == 20) echo 'selected'; ?>>契約社員</option>
                    <option value="30" <?php if($_POST['emp_type'] == 30) echo 'selected'; ?>>派遣社員</option>
                    <option value="40" <?php if($_POST['emp_type'] == 40) echo 'selected'; ?>>アルバイト・パート</option>
                    <option value="50" <?php if($_POST['emp_type'] == 50) echo 'selected'; ?>>その他</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="mb-5">
              <label class="fw-bold mb-2">在籍状況<span class="fw-normal text-danger">（必須）</span></label>
              <div class="radio-custom">
                <div class="form-check custom-control-inline">
                  <input class="form-check-input " type="radio" name="registration" value="1" id="flexRadioDefault1" <?php if($_POST['registration']) echo 'checked'; ?>>
                  <label class="form-check-label" for="flexRadioDefault1">
                    現職
                  </label>
                </div>
                <div class="form-check custom-control-inline">
                  <input class="form-check-input" type="radio" name="registration" value="0" id="flexRadioDefault2" <?php if(!$_POST['registration']) echo 'checked'; ?>>
                  <label class="form-check-label" for="flexRadioDefault2">
                    退職済み
                  </label>
                </div>
              </div>
            </div>

            <div class="mb-5">
              <label class="fw-bold mb-2">入社形態<span class="fw-normal text-danger">（必須）</span></label>
              <div class="radio-custom">
                <div class="form-check custom-control-inline">
                  <input class="form-check-input " type="radio" name="entry_type" value="1" id="flexRadioDefault1" <?php if($_POST['entry_type']) echo 'checked'; ?>>
                  <label class="form-check-label" for="flexRadioDefault1">
                    新卒入社
                  </label>
                </div>
                <div class="form-check custom-control-inline">
                  <input class="form-check-input" type="radio" name="entry_type" value="0" id="flexRadioDefault2" <?php if(!$_POST['entry_type']) echo 'checked'; ?>>
                  <label class="form-check-label" for="flexRadioDefault2">
                    中途入社
                  </label>
                </div>
              </div>
            </div>

            <div class="mb-5">
              <label class="fw-bold mb-2">入社年月<span class="fw-normal text-danger">（必須）</span></label>

              <div class="d-flex flex-row">
                <div class="cp_ipselect cp_sl01">
                  <select name="user_enter_year">
                    <option value="" hidden>年</option>
                    <option value="1990" <?php if($_POST['birth_year'] == 1990) echo 'selected'; ?>>1990年以前</option>
                    <?php for($i=1991; $i<=date('Y'); $i++){ ?>
                      <option value="<?php echo $i; ?>" <?php if($_POST['birth_year'] == $i) echo 'selected'; ?>><?php echo $i; ?>年</option>
                    <?php } ?>
                  </select>
                </div>
  
                <div class="cp_ipselect cp_sl01 ml-3">
                  <select name="user_enter_month">
                    <option value="" hidden>月</option>
                    <?php for($i=1; $i<=12; $i++){ ?>
                      <option value="<?php echo $i; ?>" <?php if($_POST['birth_year'] == $i) echo 'selected'; ?>><?php echo $i; ?>月</option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>

            <div class="mb-5">
              <label class="fw-bold mb-2">事業部・部署<span class="fw-normal text-success">（任意）</span></label>
              <input type="text" name="" placeholder="事業部・部署">
            </div>

            <div class="mb-5">
              <label class="fw-bold mb-2">役職<span class="fw-normal text-success">（任意）</span></label>
              <input type="text" name="" placeholder="役職">
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