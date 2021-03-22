<?php
require('config.php');
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「 プロフィール入力');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');

//================================
// 画面処理
//================================

$dbFormData = getUser($_SESSION['user_id']);

debug('取得したユーザー情報：' . print_r($dbFormData, true));

if(!empty($_POST)){
  debug('POST送信があります。');
  debug('POST情報：' . print_r($_POST, true));

  $sex = $_POST['user_sex'];
  $birthYear = $_POST['user_birth_year'];
  $addr = $_POST['user_pref'];
  $phLicense = $_POST['user_phlicense'];
  $carrierType = $_POST['user_carrier_type'];
  $exPhType = (!empty($_POST['user_experience_phtype'])) ? $_POST['user_experience_phtype'] : 0;
  $exYear = (!empty($_POST['user_experience_years'])) ? $_POST['user_experience_years'] : 0;
  $empType = (!empty($_POST['user_employment_type'])) ? $_POST['user_employment_type'] : 0;

  // バリデーションの必要性？？

  if(empty($err_msg)){
    try{
      // データベースアクセス、情報更新
      $dbh = dbConnect();

      $sql = 'UPDATE users SET sex = :sex, birth_year = :birth_year, addr = :addr, ph_license = :ph_license, carrier_type = :carrier_type, ex_phtype = :ex_phtype, ex_year = :ex_year, emp_type = :emp_type WHERE id = :u_id';

      $data = array(':sex' => $sex, ':birth_year' => $birthYear, ':addr' => $addr, ':ph_license' => $phLicense, ':carrier_type' => $carrierType, ':ex_phtype' => $exPhType, ':ex_year' => $exYear, ':emp_type' => $empType, ':u_id' => $dbFormData['id']);

      $stmt = queryPost($dbh, $sql, $data);

      if($stmt){
        // debug('クエリ成功！');
        $_SESSION['msg_success'] = SUC02;
        debug('マイページに遷移します。');

        header('Location:mypage.php');
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
      <div class="container">
        <div class="form-container natural-shadow  col col-sm-9 col-md-7 col-lg-6">
          <form method="post" action="" class="px-4">
            <h2 class="page-title">プロフィール入力</h2>
            
            <div class="form-item mb-5">
              <label class="fw-bold mb-2">性別</label>
              <div class="radio-custom">
                <div class="form-check custom-control-inline">
                  <input class="form-check-input " type="radio" name="user_sex" value="1" id="flexRadioDefault1">
                  <label class="form-check-label" for="flexRadioDefault1">
                    男性
                  </label>
                </div>
                <div class="form-check custom-control-inline">
                  <input class="form-check-input" type="radio" name="user_sex" value="2" id="flexRadioDefault2">
                  <label class="form-check-label" for="flexRadioDefault2">
                    女性
                  </label>
                </div>
              </div>
            </div>

            <div class="form-item mb-5">
              <label class="fw-bold mb-2">生まれ年</label>
              <div class="cp_ipselect cp_sl01">
                <select name="user_birth_year">
                  <option value="">生まれ年</option>
                  <option value="1946">1946年以前</option>
                  <option value="1947">1947年</option>
                  <option value="1948">1948年</option>
                  <option value="1949">1949年</option>
                  <option value="1950">1950年</option>
                  <option value="1951">1951年</option>
                  <option value="1952">1952年</option>
                  <option value="1953">1953年</option>
                  <option value="1954">1954年</option>
                  <option value="1955">1955年</option>
                  <option value="1956">1956年</option>
                  <option value="1957">1957年</option>
                  <option value="1958">1958年</option>
                  <option value="1959">1959年</option>
                  <option value="1960">1960年</option>
                  <option value="1961">1961年</option>
                  <option value="1962">1962年</option>
                  <option value="1963">1963年</option>
                  <option value="1964">1964年</option>
                  <option value="1965">1965年</option>
                  <option value="1966">1966年</option>
                  <option value="1967">1967年</option>
                  <option value="1968">1968年</option>
                  <option value="1969">1969年</option>
                  <option value="1970">1970年</option>
                  <option value="1971">1971年</option>
                  <option value="1972">1972年</option>
                  <option value="1973">1973年</option>
                  <option value="1974">1974年</option>
                  <option value="1975">1975年</option>
                  <option value="1976">1976年</option>
                  <option value="1977">1977年</option>
                  <option value="1978">1978年</option>
                  <option value="1979">1979年</option>
                  <option value="1980">1980年</option>
                  <option value="1981">1981年</option>
                  <option value="1982">1982年</option>
                  <option value="1983">1983年</option>
                  <option value="1984">1984年</option>
                  <option value="1985">1985年</option>
                  <option value="1986">1986年</option>
                  <option value="1987">1987年</option>
                  <option value="1988">1988年</option>
                  <option value="1989">1989年</option>
                  <option value="1990">1990年</option>
                  <option value="1991">1991年</option>
                  <option value="1992">1992年</option>
                  <option value="1993">1993年</option>
                  <option value="1994">1994年</option>
                  <option value="1995">1995年</option>
                  <option value="1996">1996年</option>
                  <option value="1997">1997年</option>
                  <option value="1998">1998年</option>
                  <option value="1999">1999年</option>
                  <option value="2000">2000年</option>
                  <option value="2001">2001年</option>
                  <option value="2002">2002年</option>
                  <option value="2003">2003年</option>
                  <option value="2004">2004年</option>
                  <option value="2005">2005年以降</option>
                </select>
              </div>
            </div>

            <div class="form-item mb-5">
              <label class="fw-bold mb-2">現住所</label>
              <div class="cp_ipselect cp_sl01">
                <select name="user_pref">
                  <option value="" hidden>都道府県</option>
                  <option value="1">北海道</option>
                  <option value="2">青森県</option>
                  <option value="3">岩手県</option>
                  <option value="4">宮城県</option>
                  <option value="5">秋田県</option>
                  <option value="6">山形県</option>
                  <option value="7">福島県</option>
                  <option value="8">茨城県</option>
                  <option value="9">栃木県</option>
                  <option value="10">群馬県</option>
                  <option value="11">埼玉県</option>
                  <option value="12">千葉県</option>
                  <option value="13">東京都</option>
                  <option value="14">神奈川県</option>
                  <option value="15">新潟県</option>
                  <option value="16">富山県</option>
                  <option value="17">石川県</option>
                  <option value="18">福井県</option>
                  <option value="19">山梨県</option>
                  <option value="20">長野県</option>
                  <option value="21">岐阜県</option>
                  <option value="22">静岡県</option>
                  <option value="23">愛知県</option>
                  <option value="24">三重県</option>
                  <option value="25">滋賀県</option>
                  <option value="26">京都府</option>
                  <option value="27">大阪府</option>
                  <option value="28">兵庫県</option>
                  <option value="29">奈良県</option>
                  <option value="30">和歌山県</option>
                  <option value="31">鳥取県</option>
                  <option value="32">島根県</option>
                  <option value="33">岡山県</option>
                  <option value="34">広島県</option>
                  <option value="35">山口県</option>
                  <option value="36">徳島県</option>
                  <option value="37">香川県</option>
                  <option value="38">愛媛県</option>
                  <option value="39">高知県</option>
                  <option value="40">福岡県</option>
                  <option value="41">佐賀県</option>
                  <option value="42">長崎県</option>
                  <option value="43">熊本県</option>
                  <option value="44">大分県</option>
                  <option value="45">宮崎県</option>
                  <option value="46">鹿児島県</option>
                  <option value="47">沖縄県</option>
                </select>
              </div>

            </div>
            <div class="form-item mb-5">
              <label class="fw-bold mb-2">薬剤師免許</label>
              <div class="radio-custom">
                <div class="form-check custom-control-inline">
                  <input class="form-check-input " type="radio" name="user_phlicense" value="1" id="flexRadioDefault1">
                  <label class="form-check-label" for="flexRadioDefault1">
                    保有
                  </label>
                </div>
                <div class="form-check custom-control-inline">
                  <input class="form-check-input" type="radio" name="user_phlicense" value="0" id="flexRadioDefault2">
                  <label class="form-check-label" for="flexRadioDefault2">
                    無し
                  </label>
                </div>
              </div>
            </div>
            <div class="form-item mb-5">
              <label class="fw-bold mb-2">キャリア状況</label>
              <div class="radio-custom">
                <div class="form-check custom-control-inline">
                  <input class="form-check-input " type="radio" name="user_carrier_type" value="1" id="1">
                  <label class="form-check-label" for="flexRadioDefault1">
                    社会人
                  </label>
                </div>
                <div class="form-check custom-control-inline">
                  <input class="form-check-input" type="radio" name="user_carrier_type" value="0" id="0">
                  <label class="form-check-label" for="flexRadioDefault2">
                    学生
                  </label>
                </div>
              </div>
            </div>

            <div class="worker-form <?php if($edit_flg && $dbFormData['carrier_type']) echo 'form-show' ?>">
              <div class="form-item mb-5">
                <label class="fw-bold mb-2">経歴</label>
                <div class="select-box-item">
                  <div class="cp_ipselect cp_sl01">
                    <select name="user_experience_phtype">
                      <option value="" hidden>業種</option>
                      <option value="10">調剤薬局</option>
                      <option value="20">ドラッグストア</option>
                      <option value="30">病院</option>
                      <option value="40">製薬企業</option>
                      <option value="50">行政</option>
                      <option value="60">その他</option>
                    </select>
                  </div>
                </div>
                <div class="select-box-item">
                  <div class="cp_ipselect cp_sl01">
                    <select name="user_experience_years">
                      <option value="" hidden>経験年数</option>
                      <option value="1">1年</option>
                      <option value="2">2年</option>
                      <option value="3">3年</option>
                      <option value="4">4年</option>
                      <option value="5">5年</option>
                      <option value="6">6年</option>
                      <option value="7">7年</option>
                      <option value="8">8年</option>
                      <option value="9">9年</option>
                      <option value="10">10年</option>
                      <option value="11">11年</option>
                      <option value="12">12年</option>
                      <option value="13">13年</option>
                      <option value="14">14年</option>
                      <option value="15">15年</option>
                      <option value="16">16年</option>
                      <option value="17">17年</option>
                      <option value="18">18年</option>
                      <option value="19">19年</option>
                      <option value="20">20年</option>
                      <option value="21">21年</option>
                      <option value="22">22年</option>
                      <option value="23">23年</option>
                      <option value="24">24年</option>
                      <option value="25">25年</option>
                      <option value="26">26年</option>
                      <option value="27">27年</option>
                      <option value="28">28年</option>
                      <option value="29">29年</option>
                      <option value="30">30年</option>
                      <option value="31">31年</option>
                      <option value="32">32年</option>
                      <option value="33">33年</option>
                      <option value="34">34年</option>
                      <option value="35">35年</option>
                      <option value="36">36年</option>
                      <option value="37">37年</option>
                      <option value="38">38年</option>
                      <option value="39">39年</option>
                      <option value="40">40年以上</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="form-item mb-5">
                <label class="fw-bold mb-2">現在の状況</label>
                <div class="select-box-item">
                  <div class="cp_ipselect cp_sl01">
                    <select name="user_employment_type">
                      <option value="" hidden>現在の状況</option>
                      <option value="10">正社員</option>
                      <option value="20">パート</option>
                      <option value="30">派遣</option>
                      <option value="40">離職中</option>
                      <option value="50">その他</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <div class="btn-container">
              <input type="submit" class="btn" value="登録する">
            </div>
          </form>
        </div>
      </div>
    </main>
    
    <?php
      require('footer.php');
    ?>