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
// ユーザーID取得
$u_id = $_SESSION['user_id'];
// 雇用形態取得
$dbEmploymentType = getEmploymentType();
// ユーザーのクチコミレコード取得
$dbPostData = getPost($u_id);
// ユーザーのクチコミレコードから企業ID取得
$c_id = $dbPostData[0]['company_id'];
if(!empty($c_id)){
  debug('クチコミレコードに企業IDが登録されていました。企業情報を取得します。');
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

  $user_employment_type = $_POST['user_employment_type'];
  $user_registration= $_POST['user_registration'];
  $user_entry_type = $_POST['user_entry_type'];
  $user_entry_year = $_POST['user_entry_year'];
  $user_entry_month =$_POST['user_entry_month'];
  $user_department = $_POST['user_department'];
  $user_position = $_POST['user_position'];

  //未入力チェック
  validRequired($user_employment_type, 'user_employment_type');
  validRequired($user_registration, 'user_registration');
  validRequired($user_entry_type, 'user_entry_type');
  validRequired($user_entry_year, 'user_entry_year');
  validRequired($user_entry_month, 'user_entry_month');

  // バリデーション
  validMaxLen($user_department, 'user_department');
  validMaxLen($user_position, 'user_position');

  if(empty($err_msg)){
    try{
      $dbh = dbConnect();
      $sql = 'UPDATE posts SET employment_type = :employment_type, registration = :registration, entry_type = :entry_type, entry_date = :entry_date, department = :department, position = :position WHERE user_id = :user_id AND company_id = :company_id AND delete_flg = 0';
      $data = array(
        ':employment_type' => $user_employment_type,
        ':registration' => $user_registration,
        ':entry_type' => $user_entry_type,
        ':entry_date' => $user_entry_year.'-'.$user_entry_month.'-01',
        ':department' => $user_department,
        ':position' => $user_position,
        ':user_id' => $u_id,
        ':company_id' => $c_id,
      );
      $stmt = queryPost($dbh, $sql, $data);

      if($stmt){
        debug('就業状況をデータベースに登録しました！次のページへ遷移します！');
        header('Location:survey02.php');
        exit();
      }
    } catch (Exeption $e) {
      error_log('エラー発生：' . $e->getMessage());
    }
  }
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
    <div class="l-content-wrapper">
      <div class="l-container">
        <div class="l-content">
          <div class="l-inner-container">
            <form action="" method="post" class="c-form">
              <h1 class="c-page-title">
                <?php echo $dbCompanyData['info']['name']; ?>の就業状況について教えてください。（1/3）
              </h1>
              <p class="mb-4 u-text-center">企業を選び直す場合は<a href="surveyInfo.php">こちら</a></p>
    
              <fieldset class="c-form__field">
                <label class="c-form__field__name">
                  雇用形態<span class="c-form__field--required">（必須）</span>
                  <span class="text-red ms-3">
                    <?php if(!empty($err_msg['user_employment_type'])) echo $err_msg['user_employment_type']; ?>
                  </span>
                </label>
                <div class="ms-3">
                  <div class="cp_ipselect cp_sl01">
                    <select name="user_employment_type" class="<?php if(!empty($err_msg['user_employment_type'])) echo 'bg-red'; ?>">
                      <option value="">雇用形態</option>
                      <?php foreach($dbEmploymentType as $key => $val){ ?>
                        <option value="<?php echo $val['id']; ?>"
                          <?php
                            if(!empty($_POST['user_employment_type'])){
                              if($_POST['user_employment_type'] == $val['id']) echo ' selected';
                            }elseif(!empty($dbPostData[0]['employment_type'])){
                              if($dbPostData[0]['employment_type'] == $val['id']) echo ' selected';
                            }
                          ?>><?php echo $val['name']; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              </fieldset>
    
              <fieldset class="c-form__field">
                <label class="c-form__field__name">
                  在籍状況<span class="c-form__field--required">（必須）</span>
                  <span class="text-red ms-3">
                    <?php if(!empty($err_msg['user_registration'])) echo $err_msg['user_registration']; ?>
                  </span>
                </label>
                <div class="c-radio">
                  <input type="hidden" name="user_registration" value="">
                  <div class="c-radio__item">
                    <label>
                      <input type="radio" name="user_registration" value="1"<?php
                        if(isset($_POST['user_registration'])){
                          if($_POST['user_registration'] == 1) echo ' checked';
                        }elseif(isset($dbPostData[0]['registration'])){
                          if($dbPostData[0]['registration'] == 1) echo ' checked';
                        }
                      ?>>
                      <span>現職</span>
                    </label>
                  </div>
                  <div class="c-radio__item">
                    <label>
                      <input type="radio" name="user_registration" value="0"<?php
                        if(isset($_POST['user_registration'])){
                          if($_POST['user_registration'] == 0) echo ' checked';
                        }elseif(isset($dbPostData[0]['registration'])){
                          if($dbPostData[0]['registration'] == 0) echo ' checked';
                        }
                      ?>>
                      <span>退職済み</span>
                    </label>
                  </div>
                </div>
              </fieldset>
    
              <fieldset class="c-form__field">
                <label class="c-form__field__name">
                  入社形態<span class="c-form__field--required">（必須）</span>
                  <span class="text-red ms-3">
                    <?php if(!empty($err_msg['user_entry_type'])) echo $err_msg['user_entry_type']; ?>
                  </span>
                </label>
                <div class="c-radio">
                  <input type="hidden" name="user_entry_type" value="">
                  <div class="c-radio__item">
                    <label>
                      <input type="radio" name="user_entry_type" value="0"<?php
                        if(isset($_POST['user_entry_type'])){
                          if($_POST['user_entry_type'] == 0) echo ' checked';
                        }elseif(isset($dbPostData[0]['entry_type'])){
                          if($dbPostData[0]['entry_type'] == 0) echo ' checked';
                        }
                      ?>>
                      <span>新卒入社</span>
                    </label>
                  </div>
                  <div class="c-radio__item">
                    <label>
                      <input type="radio" name="user_entry_type" value="1"<?php
                        if(isset($_POST['user_entry_type'])){
                          if($_POST['user_entry_type'] == 1) echo ' checked';
                        }elseif(isset($dbPostData[0]['entry_type'])){
                          if($dbPostData[0]['entry_type'] == 1) echo ' checked';
                        }
                      ?>>
                      <span>中途入社</span>
                    </label>
                  </div>
                </div>
              </fieldset>
    
              <fieldset class="c-form__field">
                <label class="c-form__field__name">
                  入社年月<span class="c-form__field--required">（必須）</span>
                  <span class="text-red ms-3">
                    <?php
                      if(!empty($err_msg['user_entry_year'])){
                        echo $err_msg['user_entry_type']; 
                      }elseif(!empty($err_msg['user_entry_month'])){
                        echo $err_msg['user_entry_month'];
                      }
                    ?>
                  </span>
                </label>
    
                <div class="u-d-flex flex-row">
                  <div class="cp_ipselect cp_sl01 u-me-3<?php if(!empty($err_msg['user_entry_year'])) echo ' bg-red'; ?>">
                    <select name="user_entry_year" class="">
                      <option value="" hidden>年</option>
                      <option value="1990" <?php ?>>1990年以前</option>
                      <?php for($i=1991; $i<=date('Y'); $i++){ ?>
                        <option value="<?php echo $i; ?>" 
                          <?php
                            if(isset($_POST['user_entry_year'])){
                              if($_POST['user_entry_year'] == $i) echo ' selected';
                            }elseif(isset($dbPostData[0]['entry_date'])){
                              if(date('Y', strtotime($dbPostData[0]['entry_date'])) == $i) echo ' selected';
                            }
                          ?>><?php echo $i; ?>年</option>
                      <?php } ?>
                    </select>
                  </div>
    
                  <div class="cp_ipselect cp_sl01<?php if(!empty($err_msg['user_entry_month'])) echo ' bg-red'; ?>">
                    <select name="user_entry_month" class="">
                      <option value="" hidden>月</option>
                      <?php for($i=1; $i<=12; $i++){ ?>
                        <option value="<?php echo $i; ?>" 
                          <?php
                            if(isset($_POST['user_entry_month'])){
                              if($_POST['user_entry_month'] == $i) echo ' selected';
                            }elseif(isset($dbPostData[0]['entry_date'])){
                              if(date('m', strtotime($dbPostData[0]['entry_date'])) == $i) echo ' selected';
                            }
                          ?>><?php echo $i; ?>月</option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              </fieldset>
    
              <fieldset class="c-form__field">
                <label class="c-form__field__name">事業部・部署<span class="c-form__field--free">（任意）</span></label>
                <input type="text" name="user_department" placeholder="例：薬局運営、人事" value="<?php
                  if(isset($_POST['user_department'])){
                    echo $_POST['user_department'];
                  }elseif(isset($dbPostData[0]['department'])){
                    echo $dbPostData[0]['department'];
                  }
                ?>">
                <div class="c-form__message">
                  <?php if(!empty($err_msg['user_department'])) echo $err_msg['user_department']; ?>
                </div>
              </fieldset>
    
              <fieldset class="c-form__field">
                <label class="c-form__field__name">役職<span class="c-form__field--free">（任意）</span></label>
                <input type="text" name="user_position" placeholder="例：薬局長、管理薬剤師、一般薬剤師" value="<?php
                  if(isset($_POST['user_position'])){
                    echo $_POST['user_position'];
                  }elseif(isset($dbPostData[0]['position'])){
                    echo $dbPostData[0]['position'];
                  }
                ?>">
                <div class="c-form__message">
                  <?php if(!empty($err_msg['user_position'])) echo $err_msg['user_position']; ?>
                </div>
              </fieldset>
    
              <div class="u-mb-3">
                <button type="submit" class="c-button c-button--blue c-button--width100">次へ</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>

  <?php
    require('footer.php');
  ?>