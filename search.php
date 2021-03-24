<?php

//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　企業名検索画面　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//================================
// 画面処理
//================================

// 画面表示用データ取得
//================================
// GETパラメータを取得
//----------------------------
// （現在のページ番号）カレントページのGETパラメータを取得。pの値がなければ1を入れる
$currentPageNum = (!empty($_GET['p'])) ? $_GET['p'] : 1;

// 企業名
$companyName = (!empty($_GET['c_name'])) ? $_GET['c_name'] : '';

// 都道府県
$prefecture = (!empty($_GET['pref'])) ? $_GET['pref'] : '';

// 業種
$industry = (!empty($_GET['i'])) ? $_GET['i'] : '';

// ソート順
$sort = (!empty($_GET['sort'])) ? $_GET['sort'] : '';

// パラメータに不正な値が入っているかチェック
if(!is_int((int)$currentPageNum)){
  error_log('エラー発生:指定ページに不正な値が入りました');
  header('Location:index.php');
  exit();
}

// 表示件数
$listSpan = 20;

// sql文、OFFSET句で使用するための現在表示レコードの先頭の値を算出。この値の次のレコードから取得することになる（そのため最初は0。表示件数が20なので次は20となる。pの値から算出すれば良い
$currentMinNum = ($currentPageNum - 1) * $listSpan;

// DBから商品データ（total:商品総数、total_page:総ページ数、data:表示する商品データ）を取得
$dbCompanyData = getCompanyList($companyName, $prefecture, $industry, $sort, $listSpan);

// DBから業種データを取得
$dbIndustryData = getIndustry();

debug('現在のページ：' . $currentPageNum);

//debug('フォーム用DBデータ：'.print_r($dbFormData,true));
//debug('カテゴリデータ：'.print_r($dbCategoryData,true));

?>

<?php
$siteTitle = '企業検索';
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
    <div class="container">
      <h1 class="page-title">気になる会社を検索</h1>

      <div class="top-search-box">
        <form method="get" action="">
          <input class="" type="text" placeholder="企業名で検索" name="searchWords">
          <button class="" type="submit">検索</button>

          <div class="select-box-items">
            <div class="select-box-item">
              <div class="cp_ipselect cp_sl01">
                <select name="pref_id">
                  <option value="">都道府県</option>
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

            <div class="select-box-item">
              <div class="cp_ipselect cp_sl01">
                <select name="i_id">
                  <option value="0" <?php if(getFormData('i_id', true) == 0) echo 'selected'; ?>>業種</option>

                  <?php
                    foreach($dbIndustryData as $key => $val){
                  ?>
                    <option value="<?php echo $val['id']; ?>" <?php if(getFormData('i_id', true) == $val['id']) echo 'selected'; ?>>
                      <?php echo $val['name']; ?>
                    </option>
                  <?php
                    }
                  ?>
                </select>
              </div>
            </div>
          </div>

          <div class="cp_ipselect cp_sl01">
            <select name="sort">
              <option value="0" <?php if(getFormData('sort', true) == 0) echo 'selected'; ?>>表示順</option>
              <option value="1" <?php if(getFormData('sort', true) == 1) echo 'selected'; ?>>クチコミ数</option>
              <option value="2" <?php if(getFormData('sort', true) == 2) echo 'selected'; ?>>総合評価</option>
            </select>
          </div>
        </form>
      </div>

      <div class="result-form">
        <?php
          foreach($dbCompanyData['data'] as $key => $val):
        ?>

          <div class="card">
            <div class="company-header">
              <h2>企業名<?php echo sanitize($val['name']); ?></h2>
            </div>
            <div class="item-content">

              <a href="company.php">口コミを見る</a>
            </div>
          </div>
          
        <?php
          endforeach;
        ?>
      </div>
      
    </div>
  </main>
    

  <?php
    require('footer.php');
  ?>
