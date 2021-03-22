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
$siteTitle = 'プロフィール編集';
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
            <h2 class="page-title">プロフィール編集</h2>
            
            <div class="form-item mb-5">
              <label class="fw-bold mb-2">性別</label>
              <div class="radio-custom">
                <div class="form-check custom-control-inline">
                  <input class="form-check-input " type="radio" name="user_sex" value="1" id="flexRadioDefault1" <?php if($dbFormData['sex']) echo 'checked'; ?>>
                  <label class="form-check-label" for="flexRadioDefault1">
                    男性
                  </label>
                </div>
                <div class="form-check custom-control-inline">
                  <input class="form-check-input" type="radio" name="user_sex" value="2" id="flexRadioDefault2" <?php if(!$dbFormData['sex']) echo 'checked'; ?>>
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
                  <option value="1946" <?php if($dbFormData['birth_year'] == 1946) echo 'selected'; ?>>1946年以前</option>
                  <option value="1947" <?php if($dbFormData['birth_year'] == 1947) echo 'selected'; ?>>1947年</option>
                  <option value="1948" <?php if($dbFormData['birth_year'] == 1948) echo 'selected'; ?>>1948年</option>
                  <option value="1949" <?php if($dbFormData['birth_year'] == 1949) echo 'selected'; ?>>1949年</option>
                  <option value="1950" <?php if($dbFormData['birth_year'] == 1950) echo 'selected'; ?>>1950年</option>
                  <option value="1951" <?php if($dbFormData['birth_year'] == 1951) echo 'selected'; ?>>1951年</option>
                  <option value="1952" <?php if($dbFormData['birth_year'] == 1952) echo 'selected'; ?>>1952年</option>
                  <option value="1953" <?php if($dbFormData['birth_year'] == 1953) echo 'selected'; ?>>1953年</option>
                  <option value="1954" <?php if($dbFormData['birth_year'] == 1954) echo 'selected'; ?>>1954年</option>
                  <option value="1955" <?php if($dbFormData['birth_year'] == 1955) echo 'selected'; ?>>1955年</option>
                  <option value="1956" <?php if($dbFormData['birth_year'] == 1956) echo 'selected'; ?>>1956年</option>
                  <option value="1957" <?php if($dbFormData['birth_year'] == 1957) echo 'selected'; ?>>1957年</option>
                  <option value="1958" <?php if($dbFormData['birth_year'] == 1958) echo 'selected'; ?>>1958年</option>
                  <option value="1959" <?php if($dbFormData['birth_year'] == 1959) echo 'selected'; ?>>1959年</option>
                  <option value="1960" <?php if($dbFormData['birth_year'] == 1960) echo 'selected'; ?>>1960年</option>
                  <option value="1961" <?php if($dbFormData['birth_year'] == 1961) echo 'selected'; ?>>1961年</option>
                  <option value="1962" <?php if($dbFormData['birth_year'] == 1962) echo 'selected'; ?>>1962年</option>
                  <option value="1963" <?php if($dbFormData['birth_year'] == 1963) echo 'selected'; ?>>1963年</option>
                  <option value="1964" <?php if($dbFormData['birth_year'] == 1964) echo 'selected'; ?>>1964年</option>
                  <option value="1965" <?php if($dbFormData['birth_year'] == 1965) echo 'selected'; ?>>1965年</option>
                  <option value="1966" <?php if($dbFormData['birth_year'] == 1966) echo 'selected'; ?>>1966年</option>
                  <option value="1967" <?php if($dbFormData['birth_year'] == 1967) echo 'selected'; ?>>1967年</option>
                  <option value="1968" <?php if($dbFormData['birth_year'] == 1968) echo 'selected'; ?>>1968年</option>
                  <option value="1969" <?php if($dbFormData['birth_year'] == 1969) echo 'selected'; ?>>1969年</option>
                  <option value="1970" <?php if($dbFormData['birth_year'] == 1970) echo 'selected'; ?>>1970年</option>
                  <option value="1971" <?php if($dbFormData['birth_year'] == 1971) echo 'selected'; ?>>1971年</option>
                  <option value="1972" <?php if($dbFormData['birth_year'] == 1972) echo 'selected'; ?>>1972年</option>
                  <option value="1973" <?php if($dbFormData['birth_year'] == 1973) echo 'selected'; ?>>1973年</option>
                  <option value="1974" <?php if($dbFormData['birth_year'] == 1974) echo 'selected'; ?>>1974年</option>
                  <option value="1975" <?php if($dbFormData['birth_year'] == 1975) echo 'selected'; ?>>1975年</option>
                  <option value="1976" <?php if($dbFormData['birth_year'] == 1976) echo 'selected'; ?>>1976年</option>
                  <option value="1977" <?php if($dbFormData['birth_year'] == 1977) echo 'selected'; ?>>1977年</option>
                  <option value="1978" <?php if($dbFormData['birth_year'] == 1978) echo 'selected'; ?>>1978年</option>
                  <option value="1979" <?php if($dbFormData['birth_year'] == 1979) echo 'selected'; ?>>1979年</option>
                  <option value="1980" <?php if($dbFormData['birth_year'] == 1980) echo 'selected'; ?>>1980年</option>
                  <option value="1981" <?php if($dbFormData['birth_year'] == 1981) echo 'selected'; ?>>1981年</option>
                  <option value="1982" <?php if($dbFormData['birth_year'] == 1982) echo 'selected'; ?>>1982年</option>
                  <option value="1983" <?php if($dbFormData['birth_year'] == 1983) echo 'selected'; ?>>1983年</option>
                  <option value="1984" <?php if($dbFormData['birth_year'] == 1984) echo 'selected'; ?>>1984年</option>
                  <option value="1985" <?php if($dbFormData['birth_year'] == 1985) echo 'selected'; ?>>1985年</option>
                  <option value="1986" <?php if($dbFormData['birth_year'] == 1986) echo 'selected'; ?>>1986年</option>
                  <option value="1987" <?php if($dbFormData['birth_year'] == 1987) echo 'selected'; ?>>1987年</option>
                  <option value="1988" <?php if($dbFormData['birth_year'] == 1988) echo 'selected'; ?>>1988年</option>
                  <option value="1989" <?php if($dbFormData['birth_year'] == 1989) echo 'selected'; ?>>1989年</option>
                  <option value="1990" <?php if($dbFormData['birth_year'] == 1990) echo 'selected'; ?>>1990年</option>
                  <option value="1991" <?php if($dbFormData['birth_year'] == 1991) echo 'selected'; ?>>1991年</option>
                  <option value="1992" <?php if($dbFormData['birth_year'] == 1992) echo 'selected'; ?>>1992年</option>
                  <option value="1993" <?php if($dbFormData['birth_year'] == 1993) echo 'selected'; ?>>1993年</option>
                  <option value="1994" <?php if($dbFormData['birth_year'] == 1994) echo 'selected'; ?>>1994年</option>
                  <option value="1995" <?php if($dbFormData['birth_year'] == 1995) echo 'selected'; ?>>1995年</option>
                  <option value="1996" <?php if($dbFormData['birth_year'] == 1996) echo 'selected'; ?>>1996年</option>
                  <option value="1997" <?php if($dbFormData['birth_year'] == 1997) echo 'selected'; ?>>1997年</option>
                  <option value="1998" <?php if($dbFormData['birth_year'] == 1998) echo 'selected'; ?>>1998年</option>
                  <option value="1999" <?php if($dbFormData['birth_year'] == 1999) echo 'selected'; ?>>1999年</option>
                  <option value="2000" <?php if($dbFormData['birth_year'] == 2000) echo 'selected'; ?>>2000年</option>
                  <option value="2001" <?php if($dbFormData['birth_year'] == 2001) echo 'selected'; ?>>2001年</option>
                  <option value="2002" <?php if($dbFormData['birth_year'] == 2002) echo 'selected'; ?>>2002年</option>
                  <option value="2003" <?php if($dbFormData['birth_year'] == 2003) echo 'selected'; ?>>2003年</option>
                  <option value="2004" <?php if($dbFormData['birth_year'] == 2004) echo 'selected'; ?>>2004年</option>
                  <option value="2005" <?php if($dbFormData['birth_year'] == 2005) echo 'selected'; ?>>2005年以降</option>
                </select>
              </div>
            </div>

            <div class="form-item mb-5">
              <label class="fw-bold mb-2">現住所</label>
              <div class="cp_ipselect cp_sl01">
                <select name="user_pref">
                  <option value="" hidden>都道府県</option>
                  <option value="1" <?php if($dbFormData['addr'] == 1) echo 'selected'; ?>>北海道</option>
                  <option value="2" <?php if($dbFormData['addr'] == 2) echo 'selected'; ?>>青森県</option>
                  <option value="3" <?php if($dbFormData['addr'] == 3) echo 'selected'; ?>>岩手県</option>
                  <option value="4" <?php if($dbFormData['addr'] == 4) echo 'selected'; ?>>宮城県</option>
                  <option value="5" <?php if($dbFormData['addr'] == 5) echo 'selected'; ?>>秋田県</option>
                  <option value="6" <?php if($dbFormData['addr'] == 6) echo 'selected'; ?>>山形県</option>
                  <option value="7" <?php if($dbFormData['addr'] == 7) echo 'selected'; ?>>福島県</option>
                  <option value="8" <?php if($dbFormData['addr'] == 8) echo 'selected'; ?>>茨城県</option>
                  <option value="9" <?php if($dbFormData['addr'] == 9) echo 'selected'; ?>>栃木県</option>
                  <option value="10" <?php if($dbFormData['addr'] == 10) echo 'selected'; ?>>群馬県</option>
                  <option value="11" <?php if($dbFormData['addr'] == 11) echo 'selected'; ?>>埼玉県</option>
                  <option value="12" <?php if($dbFormData['addr'] == 12) echo 'selected'; ?>>千葉県</option>
                  <option value="13" <?php if($dbFormData['addr'] == 13) echo 'selected'; ?>>東京都</option>
                  <option value="14" <?php if($dbFormData['addr'] == 14) echo 'selected'; ?>>神奈川県</option>
                  <option value="15" <?php if($dbFormData['addr'] == 15) echo 'selected'; ?>>新潟県</option>
                  <option value="16" <?php if($dbFormData['addr'] == 16) echo 'selected'; ?>>富山県</option>
                  <option value="17" <?php if($dbFormData['addr'] == 17) echo 'selected'; ?>>石川県</option>
                  <option value="18" <?php if($dbFormData['addr'] == 18) echo 'selected'; ?>>福井県</option>
                  <option value="19" <?php if($dbFormData['addr'] == 19) echo 'selected'; ?>>山梨県</option>
                  <option value="20" <?php if($dbFormData['addr'] == 20) echo 'selected'; ?>>長野県</option>
                  <option value="21" <?php if($dbFormData['addr'] == 21) echo 'selected'; ?>>岐阜県</option>
                  <option value="22" <?php if($dbFormData['addr'] == 22) echo 'selected'; ?>>静岡県</option>
                  <option value="23" <?php if($dbFormData['addr'] == 23) echo 'selected'; ?>>愛知県</option>
                  <option value="24" <?php if($dbFormData['addr'] == 24) echo 'selected'; ?>>三重県</option>
                  <option value="25" <?php if($dbFormData['addr'] == 25) echo 'selected'; ?>>滋賀県</option>
                  <option value="26" <?php if($dbFormData['addr'] == 26) echo 'selected'; ?>>京都府</option>
                  <option value="27" <?php if($dbFormData['addr'] == 27) echo 'selected'; ?>>大阪府</option>
                  <option value="28" <?php if($dbFormData['addr'] == 28) echo 'selected'; ?>>兵庫県</option>
                  <option value="29" <?php if($dbFormData['addr'] == 29) echo 'selected'; ?>>奈良県</option>
                  <option value="30" <?php if($dbFormData['addr'] == 30) echo 'selected'; ?>>和歌山県</option>
                  <option value="31" <?php if($dbFormData['addr'] == 31) echo 'selected'; ?>>鳥取県</option>
                  <option value="32" <?php if($dbFormData['addr'] == 32) echo 'selected'; ?>>島根県</option>
                  <option value="33" <?php if($dbFormData['addr'] == 33) echo 'selected'; ?>>岡山県</option>
                  <option value="34" <?php if($dbFormData['addr'] == 34) echo 'selected'; ?>>広島県</option>
                  <option value="35" <?php if($dbFormData['addr'] == 35) echo 'selected'; ?>>山口県</option>
                  <option value="36" <?php if($dbFormData['addr'] == 36) echo 'selected'; ?>>徳島県</option>
                  <option value="37" <?php if($dbFormData['addr'] == 37) echo 'selected'; ?>>香川県</option>
                  <option value="38" <?php if($dbFormData['addr'] == 38) echo 'selected'; ?>>愛媛県</option>
                  <option value="39" <?php if($dbFormData['addr'] == 39) echo 'selected'; ?>>高知県</option>
                  <option value="40" <?php if($dbFormData['addr'] == 40) echo 'selected'; ?>>福岡県</option>
                  <option value="41" <?php if($dbFormData['addr'] == 41) echo 'selected'; ?>>佐賀県</option>
                  <option value="42" <?php if($dbFormData['addr'] == 42) echo 'selected'; ?>>長崎県</option>
                  <option value="43" <?php if($dbFormData['addr'] == 43) echo 'selected'; ?>>熊本県</option>
                  <option value="44" <?php if($dbFormData['addr'] == 44) echo 'selected'; ?>>大分県</option>
                  <option value="45" <?php if($dbFormData['addr'] == 45) echo 'selected'; ?>>宮崎県</option>
                  <option value="46" <?php if($dbFormData['addr'] == 46) echo 'selected'; ?>>鹿児島県</option>
                  <option value="47" <?php if($dbFormData['addr'] == 47) echo 'selected'; ?>>沖縄県</option>
                </select>
              </div>

            </div>
            <div class="form-item mb-5">
              <label class="fw-bold mb-2">薬剤師免許</label>
              <div class="radio-custom">
                <div class="form-check custom-control-inline">
                  <input class="form-check-input " type="radio" name="user_phlicense" value="1" id="flexRadioDefault1" <?php if($dbFormData['ph_license']) echo 'checked'; ?>>
                  <label class="form-check-label" for="flexRadioDefault1">
                    保有
                  </label>
                </div>
                <div class="form-check custom-control-inline">
                  <input class="form-check-input" type="radio" name="user_phlicense" value="0" id="flexRadioDefault2" <?php if(!$dbFormData['ph_license']) echo 'checked'; ?>>
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
                  <input class="form-check-input " type="radio" name="user_carrier_type" value="1" id="1" <?php if($dbFormData['carrier_type']) echo 'checked'; ?>>
                  <label class="form-check-label" for="flexRadioDefault1">
                    社会人
                  </label>
                </div>
                <div class="form-check custom-control-inline">
                  <input class="form-check-input" type="radio" name="user_carrier_type" value="0" id="0" <?php if(!$dbFormData['carrier_type']) echo 'checked'; ?>>
                  <label class="form-check-label" for="flexRadioDefault2">
                    学生
                  </label>
                </div>
              </div>
            </div>

            <div class="worker-form <?php if($dbFormData['carrier_type']) echo 'form-show' ?>">
              <div class="form-item mb-5">
                <label class="fw-bold mb-2">経歴</label>
                <div class="select-box-item">
                  <div class="cp_ipselect cp_sl01">
                    <select name="user_experience_phtype">
                      <option value="" hidden>業種</option>
                      <option value="10" <?php if($dbFormData['ex_phtype'] == 10) echo 'selected'; ?>>調剤薬局</option>
                      <option value="20" <?php if($dbFormData['ex_phtype'] == 20) echo 'selected'; ?>>ドラッグストア</option>
                      <option value="30" <?php if($dbFormData['ex_phtype'] == 30) echo 'selected'; ?>>病院</option>
                      <option value="40" <?php if($dbFormData['ex_phtype'] == 40) echo 'selected'; ?>>製薬企業</option>
                      <option value="50" <?php if($dbFormData['ex_phtype'] == 50) echo 'selected'; ?>>行政</option>
                      <option value="60" <?php if($dbFormData['ex_phtype'] == 60) echo 'selected'; ?>>その他</option>
                    </select>
                  </div>
                </div>
                <div class="select-box-item">
                  <div class="cp_ipselect cp_sl01">
                    <select name="user_experience_years">
                      <option value="" hidden>経験年数</option>
                      <option value="1" <?php if($dbFormData['ex_year'] == 1) echo 'selected'; ?>>1年</option>
                      <option value="2" <?php if($dbFormData['ex_year'] == 2) echo 'selected'; ?>>2年</option>
                      <option value="3" <?php if($dbFormData['ex_year'] == 3) echo 'selected'; ?>>3年</option>
                      <option value="4" <?php if($dbFormData['ex_year'] == 4) echo 'selected'; ?>>4年</option>
                      <option value="5" <?php if($dbFormData['ex_year'] == 5) echo 'selected'; ?>>5年</option>
                      <option value="6" <?php if($dbFormData['ex_year'] == 6) echo 'selected'; ?>>6年</option>
                      <option value="7" <?php if($dbFormData['ex_year'] == 7) echo 'selected'; ?>>7年</option>
                      <option value="8" <?php if($dbFormData['ex_year'] == 8) echo 'selected'; ?>>8年</option>
                      <option value="9" <?php if($dbFormData['ex_year'] == 9) echo 'selected'; ?>>9年</option>
                      <option value="10" <?php if($dbFormData['ex_year'] == 10) echo 'selected'; ?>>10年</option>
                      <option value="11" <?php if($dbFormData['ex_year'] == 11) echo 'selected'; ?>>11年</option>
                      <option value="12" <?php if($dbFormData['ex_year'] == 12) echo 'selected'; ?>>12年</option>
                      <option value="13" <?php if($dbFormData['ex_year'] == 13) echo 'selected'; ?>>13年</option>
                      <option value="14" <?php if($dbFormData['ex_year'] == 14) echo 'selected'; ?>>14年</option>
                      <option value="15" <?php if($dbFormData['ex_year'] == 15) echo 'selected'; ?>>15年</option>
                      <option value="16" <?php if($dbFormData['ex_year'] == 16) echo 'selected'; ?>>16年</option>
                      <option value="17" <?php if($dbFormData['ex_year'] == 17) echo 'selected'; ?>>17年</option>
                      <option value="18" <?php if($dbFormData['ex_year'] == 18) echo 'selected'; ?>>18年</option>
                      <option value="19" <?php if($dbFormData['ex_year'] == 19) echo 'selected'; ?>>19年</option>
                      <option value="20" <?php if($dbFormData['ex_year'] == 20) echo 'selected'; ?>>20年</option>
                      <option value="21" <?php if($dbFormData['ex_year'] == 21) echo 'selected'; ?>>21年</option>
                      <option value="22" <?php if($dbFormData['ex_year'] == 22) echo 'selected'; ?>>22年</option>
                      <option value="23" <?php if($dbFormData['ex_year'] == 23) echo 'selected'; ?>>23年</option>
                      <option value="24" <?php if($dbFormData['ex_year'] == 24) echo 'selected'; ?>>24年</option>
                      <option value="25" <?php if($dbFormData['ex_year'] == 25) echo 'selected'; ?>>25年</option>
                      <option value="26" <?php if($dbFormData['ex_year'] == 26) echo 'selected'; ?>>26年</option>
                      <option value="27" <?php if($dbFormData['ex_year'] == 27) echo 'selected'; ?>>27年</option>
                      <option value="28" <?php if($dbFormData['ex_year'] == 28) echo 'selected'; ?>>28年</option>
                      <option value="29" <?php if($dbFormData['ex_year'] == 29) echo 'selected'; ?>>29年</option>
                      <option value="30" <?php if($dbFormData['ex_year'] == 30) echo 'selected'; ?>>30年</option>
                      <option value="31" <?php if($dbFormData['ex_year'] == 31) echo 'selected'; ?>>31年</option>
                      <option value="32" <?php if($dbFormData['ex_year'] == 32) echo 'selected'; ?>>32年</option>
                      <option value="33" <?php if($dbFormData['ex_year'] == 33) echo 'selected'; ?>>33年</option>
                      <option value="34" <?php if($dbFormData['ex_year'] == 34) echo 'selected'; ?>>34年</option>
                      <option value="35" <?php if($dbFormData['ex_year'] == 35) echo 'selected'; ?>>35年</option>
                      <option value="36" <?php if($dbFormData['ex_year'] == 36) echo 'selected'; ?>>36年</option>
                      <option value="37" <?php if($dbFormData['ex_year'] == 37) echo 'selected'; ?>>37年</option>
                      <option value="38" <?php if($dbFormData['ex_year'] == 38) echo 'selected'; ?>>38年</option>
                      <option value="39" <?php if($dbFormData['ex_year'] == 39) echo 'selected'; ?>>39年</option>
                      <option value="40" <?php if($dbFormData['ex_year'] == 40) echo 'selected'; ?>>40年以上</option>
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
                      <option value="10" <?php if($dbFormData['emp_type'] == 10) echo 'selected'; ?>>正社員</option>
                      <option value="20" <?php if($dbFormData['emp_type'] == 20) echo 'selected'; ?>>パート</option>
                      <option value="30" <?php if($dbFormData['emp_type'] == 30) echo 'selected'; ?>>派遣</option>
                      <option value="40" <?php if($dbFormData['emp_type'] == 40) echo 'selected'; ?>>離職中</option>
                      <option value="50" <?php if($dbFormData['emp_type'] == 50) echo 'selected'; ?>>その他</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <div class="btn-container">
              <input type="submit" class="btn" value="変更する">
            </div>
          </form>
        </div>
      </div>
    </main>
    
    <?php
      require('footer.php');
    ?>