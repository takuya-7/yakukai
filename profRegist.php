<?php
require('config.php');
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「 プロフィール入力・編集');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');

//================================
// 画面処理
//================================
// ユーザー情報取得
$dbFormData = getUser($_SESSION['user_id']);
// DBから都道府県データを取得
$dbPrefectureData = getPrefecture();
// DBから業種データを取得
$dbIndustryData = getIndustry();
// DBから雇用形態取得
$dbEmploymentType = getEmploymentType();

debug('取得したユーザー情報：' . print_r($dbFormData, true));

if(!empty($_POST)){
  debug('POST送信があります。');
  debug('POST情報：' . print_r($_POST, true));

  $sex = $_POST['user_sex'];
  $birthYear = $_POST['user_birth_year'];
  $addr = $_POST['user_pref'];
  $phLicense = $_POST['user_phlicense'];
  $carrierType = $_POST['user_carrier_type'];
  $exPhType = $_POST['user_experience_phtype'];
  $exYear = $_POST['user_experience_years'];
  $empType = $_POST['user_employment_type'];

  // バリデーションの必要性？？
  validRequired($sex, 'user_sex');
  validRequired($birthYear, 'user_birth_year');
  validRequired($addr, 'user_pref');
  validRequired($phLicense, 'user_phlicense');
  validRequired($carrierType, 'user_carrier_type');
  if($carrierType == 1){
    validRequired($exPhType, 'user_experience_phtype');
    validRequired($exYear, 'user_experience_years');
    validRequired($empType, 'user_employment_type');
  }


  if(empty($err_msg)){
    try{
      // データベースアクセス、情報更新
      $dbh = dbConnect();

      $sql = 'UPDATE users SET sex = :sex, birth_year = :birth_year, addr = :addr, ph_license = :ph_license, carrier_type = :carrier_type, ex_phtype = :ex_phtype, ex_year = :ex_year, emp_type = :emp_type WHERE id = :u_id';

      $data = array(':sex' => $sex, ':birth_year' => $birthYear, ':addr' => $addr, ':ph_license' => $phLicense, ':carrier_type' => $carrierType, ':ex_phtype' => $exPhType, ':ex_year' => $exYear, ':emp_type' => $empType, ':u_id' => $dbFormData['id']);

      $stmt = queryPost($dbh, $sql, $data);

      if($stmt){
        // debug('クエリ成功！');
        // $_SESSION['msg_success'] = SUC02;
        debug('クチコミ投稿ページに遷移します。');
        header('Location:surveyInfo.php');
        exit();
      // }else{
      //     debug('クエリに失敗しました。');
      //     $err_msg['common'] = MSG13;
      }
    } catch (Exeption $e) {
      error_log('エラー発生：' . $e->getMessage());
      $err_msg['common'] = MSG07;
    }
  }
}
debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>

<?php
$siteTitle = 'プロフィール入力';
require('head.php');
?>

<body class="">
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
              <form method="post" action="" class="c-form">
                <h2>プロフィール入力</h2>
    
                <fieldset class="c-form__field">
                  <label class="c-form__field__name">性別<span class="c-form__field--required">（必須）</span></label>
                  <?php if(!empty($err_msg['user_sex'])){ ?>
                    <div class="u-mb-2">
                      <span class="u-text-red ms-3 fw-bold">
                        <?php echo $err_msg['user_sex']; ?>
                      </span>
                    </div>
                  <?php } ?>
                  <div class="c-radio">
                    <div class="c-radio__item">
                      <input type="radio" name="user_sex" value="1" id="user_sex_1"<?php
                        if(isset($_POST['user_sex'])){
                          if($_POST['user_sex'] == 1) echo ' checked';
                        }elseif($dbFormData['sex'] == 1){
                          echo ' checked';
                        }
                      ?>>
                      <label class="form-check-label" for="user_sex_1">
                        男性
                      </label>
                    </div>
                    <div class="c-radio__item">
                      <input type="radio" name="user_sex" value="2" id="user_sex_2"<?php
                        if(isset($_POST['user_sex'])){
                          if($_POST['user_sex'] == 2) echo ' checked';
                        }elseif($dbFormData['sex'] == 2){
                          echo ' checked';
                        }
                      ?>>
                      <label for="user_sex_2">
                        女性
                      </label>
                    </div>
                  </div>
                </fieldset>
    
                <fieldset class="c-form__field">
                  <label class="c-form__field__name">生まれ年<span class="c-form__field--required">（必須）</span></label>
                  <?php if(!empty($err_msg['user_birth_year'])){ ?>
                    <div class="u-mb-2">
                      <span class="u-text-red ms-3 fw-bold">
                        <?php echo $err_msg['user_birth_year']; ?>
                      </span>
                    </div>
                  <?php } ?>
                  <div class="cp_ipselect cp_sl01">
                    <select name="user_birth_year">
                      <option value="" hidden>生まれ年</option>
                      <option value="1946" <?php
                          if(isset($_POST['user_birth_year'])){
                            if($_POST['user_birth_year'] == 1946) echo ' selected';
                          }elseif($dbFormData['birth_year'] == 1946){
                            echo ' selected';
                          }
                        ?>>1946年以前</option>
                      <?php for($i=1947; $i<date('Y')-18; $i++){ ?>
                        <option value="<?php echo $i; ?>" 
                          <?php
                            if(isset($_POST['user_birth_year'])){
                              if($_POST['user_birth_year'] == $i) echo ' selected';
                            }elseif($dbFormData['birth_year'] == $i){
                              echo ' selected';
                            }
                          ?>><?php echo $i; ?>年</option>
                      <?php } ?>
                      <option value="<?php echo date('Y')-18; ?>" <?php
                          if(isset($_POST['user_birth_year'])){
                            if($_POST['user_birth_year'] == date('Y')-18) echo ' selected';
                          }elseif($dbFormData['birth_year'] >=  date('Y')-18){
                            echo ' selected';
                          }
                        ?>><?php echo date('Y')-18; ?>年以降</option>
                    </select>
                  </div>
                </fieldset>
    
                <fieldset class="c-form__field">
                  <label class="c-form__field__name">現住所<span class="c-form__field--required">（必須）</span></label>
                  <?php if(!empty($err_msg['user_pref'])){ ?>
                    <div class="u-mb-2">
                      <span class="u-text-red ms-3 fw-bold">
                        <?php echo $err_msg['user_pref']; ?>
                      </span>
                    </div>
                  <?php } ?>
                  <div class="cp_ipselect cp_sl01">
                    <select name="user_pref">
                      <option value="">都道府県</option>
                      <?php foreach($dbPrefectureData as $key => $val){ ?>
                        <option value="<?php echo $val['id']; ?>"<?php
                          if(isset($_POST['user_pref'])){
                            if($_POST['user_pref'] == $val['id']) echo ' selected';
                          }elseif($dbFormData['addr'] == $val['id']){
                            echo ' selected';
                          }
                        ?>>
                        <?php echo $val['name']; ?>
                        </option>
                      <?php } ?>
    
                    </select>
                  </div>
                </fieldset>
    
                <fieldset class="c-form__field">
                  <label class="c-form__field__name">薬剤師免許<span class="c-form__field--required">（必須）</span></label>
                  <?php if(!empty($err_msg['user_phlicense'])){ ?>
                    <div class="u-mb-2">
                      <span class="u-text-red ms-3 fw-bold">
                        <?php echo $err_msg['user_phlicense']; ?>
                      </span>
                    </div>
                  <?php } ?>
                  <div class="c-radio">
                    <div class="c-radio__item">
                      <input type="radio" name="user_phlicense" value="1" id="user_phlicense_1"<?php
                        if(isset($_POST['user_phlicense'])){
                          if($_POST['user_phlicense'] == 1) echo ' checked';
                        }elseif($dbFormData['ph_license'] == 1){
                          echo ' checked';
                        }
                      ?>>
                      <label for="user_phlicense_1">
                        保有
                      </label>
                    </div>
                    <div class="c-radio__item">
                      <input type="radio" name="user_phlicense" value="0" id="user_phlicense_0"<?php
                        if(isset($_POST['user_phlicense'])){
                          if($_POST['user_phlicense'] == 0) echo ' checked';
                        }elseif($dbFormData['ph_license'] == 0){
                          echo ' checked';
                        }
                      ?>>
                      <label for="user_phlicense_0">
                        無し
                      </label>
                    </div>
                  </div>
                </fieldset>
    
                <fieldset class="c-form__field">
                  <label class="c-form__field__name">キャリア状況<span class="c-form__field--required">（必須）</span></label>
                  <?php if(!empty($err_msg['user_carrier_type'])){ ?>
                    <div class="u-mb-2">
                      <span class="u-text-red ms-3 fw-bold">
                        <?php echo $err_msg['user_carrier_type']; ?>
                      </span>
                    </div>
                  <?php } ?>
                  <div class="c-radio">
                    <div class="c-radio__item">
                      <input type="radio" name="user_carrier_type" value="1" id="user_carrier_type_1"<?php
                        if(isset($_POST['user_carrier_type'])){
                          if($_POST['user_carrier_type'] == 1) echo ' checked';
                        }elseif($dbFormData['carrier_type'] == 1){
                          echo ' checked';
                        }
                      ?>>
                      <label class="form-check-label" for="user_carrier_type_1">
                        社会人
                      </label>
                    </div>
                    <div class="c-radio__item">
                      <input type="radio" name="user_carrier_type" value="0" id="user_carrier_type_0"<?php
                        if(isset($_POST['user_carrier_type'])){
                          if($_POST['user_carrier_type'] == 0) echo ' checked';
                        }elseif($dbFormData['carrier_type'] == 0){
                          echo ' checked';
                        }
                      ?>>
                      <label class="form-check-label" for="user_carrier_type_0">
                        学生
                      </label>
                    </div>
                  </div>
                </fieldset>
    
                <div class="js-worker-form<?php
                    if(isset($_POST['user_carrier_type'])){
                      if($_POST['user_carrier_type'] == 1){
                        echo ' is-active'; 
                      }
                    }elseif($dbFormData['carrier_type'] == 1){
                      echo ' is-active'; 
                    }
                  ?>">
    
                  <fieldset class="c-form__field">
                    <label class="c-form__field__name">経歴<span class="c-form__field--required">（必須）</span></label>
                    <?php if(!empty($err_msg['user_experience_phtype'])){ ?>
                      <div class="u-mb-2">
                        <span class="u-text-red ms-3 fw-bold">
                          <?php echo $err_msg['user_experience_phtype']; ?>
                        </span>
                      </div>
                    <?php }elseif(!empty($err_msg['user_experience_years'])){ ?>
                      <div class="u-mb-2">
                        <span class="u-text-red ms-3 fw-bold">
                          <?php echo $err_msg['user_experience_years']; ?>
                        </span>
                      </div>
                    <?php } ?>
                    <div class="select-box-item">
                      <div class="cp_ipselect cp_sl01">
                        <select name="user_experience_phtype">
                          <option value="">業種</option>
                          <?php foreach($dbIndustryData as $key => $val){ ?>
                            <option value="<?php echo $val['id']; ?>" <?php
                              if(!empty($_POST['user_experience_phtype'])){
                                if($_POST['user_experience_phtype'] == $val['id']) echo ' selected';
                              }elseif($dbFormData['ex_phtype'] == $val['id']){
                                echo ' selected'; 
                              }
                              ?>><?php echo $val['name']; ?>
                            </option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                    <div class="select-box-item">
                      <div class="cp_ipselect cp_sl01">
                        <select name="user_experience_years">
                          <option value="" hidden>経験年数</option>
                          <?php for($i = 1; $i<40; $i++){ ?>
                            <option value="<?php echo $i; ?>"<?php
                                if(!empty($_POST['user_experience_years'])){
                                  if($_POST['user_experience_years'] == $i) echo ' selected';
                                }elseif($dbFormData['ex_year'] == $i){
                                  echo ' selected'; 
                                }
                              ?>><?php echo $i; ?>年</option>
                          <?php } ?>
                          <option value="40"<?php
                            if(!empty($_POST['user_experience_years'])){
                              if($_POST['user_experience_years'] == 40) echo ' selected';
                            }elseif($dbFormData['ex_year'] == 40){
                              echo ' selected'; 
                            }
                          ?>>40年以上</option>
                        </select>
                      </div>
                    </div>
                  </fieldset>
    
                  <fieldset class="c-form__field">
                    <label class="c-form__field__name">現在の状況<span class="c-form__field--required">（必須）</span></label>
                    <?php if(!empty($err_msg['user_employment_type'])){ ?>
                      <div class="u-mb-2">
                        <span class="u-text-red ms-3 fw-bold">
                          <?php echo $err_msg['user_employment_type']; ?>
                        </span>
                      </div>
                    <?php } ?>
                    <div class="select-box-item">
                      <div class="cp_ipselect cp_sl01">
                        <select name="user_employment_type">
                          <option value="">現在の状況</option>
                          <?php foreach($dbEmploymentType as $key => $val){ ?>
                            <option value="<?php echo $val['id']; ?>"
                              <?php
                                if(!empty($_POST['user_employment_type'])){
                                  if($_POST['user_employment_type'] == $val['id']) echo ' selected';
                                }elseif($dbFormData['emp_type'] == $val['id']){
                                  echo ' selected'; 
                                }
                              ?>><?php echo $val['name']; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </fieldset>
                </div>
    
                <div class="u-mb-3">
                  <button type="submit" class="c-button c-button--blue c-button--width100">登録する</button>
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