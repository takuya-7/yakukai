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
$companyName = (!empty($_GET['src_str'])) ? $_GET['src_str'] : '';
// 都道府県
$prefecture = (!empty($_GET['pref'])) ? $_GET['pref'] : '';
// 業種
$industry = (!empty($_GET['i'])) ? $_GET['i'] : '';
// ソート順
$sort = (!empty($_GET['sort'])) ? $_GET['sort'] : '';
// パラメータに不正な値が入っているかチェック
if(!is_int((int)$currentPageNum)){
  error_log('エラー発生:指定ページに不正な値が入りました');
  header('Location:search.php');
  exit();
}

// 表示件数
$listSpan = 20;

// sql文、OFFSET句で使用するための現在表示レコードの先頭の値を算出。この値の次のレコードから取得することになる（そのため最初は0。表示件数が20なので次は20となる。pの値から算出すれば良い
$currentMinNum = ($currentPageNum - 1) * $listSpan;

// DBから商品データ（total:商品総数、total_page:総ページ数、data:表示する商品データ）を取得
$dbCompanyData = getCompanyList($currentMinNum, $companyName, $prefecture, $industry, $sort, $listSpan);

// DBから都道府県データを取得
$dbPrefectureData = getPrefecture();

// DBから業種データを取得
$dbIndustryData = getIndustry();

debug('現在のページ：' . $currentPageNum);
// debug('フォーム用DBデータ：'.print_r($dbFormData,true));
// debug('カテゴリデータ：'.print_r($dbIndustryData,true));

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
    <div class="content-wrapper">
      <div class="container">
        <div class="page-heading">
          <h1 class="page-title">気になる会社を検索</h1>
          <div class="search-wrap">
            <form method="get" action="">
              <div class="search-item">
                <input class="" type="text" placeholder="企業名で検索" name="src_str" value="<?php if(!empty($_GET['src_str'])) echo $_GET['src_str']; ?>">
              </div>
              
              <div class="search-item">
                <div class="cp_ipselect cp_sl01 w-100">
                  <select name="pref">
                    <option value="0" <?php if(getFormData('pref', true) == 0) echo 'selected'; ?>>都道府県（本社所在地）</option>
                    <?php
                      foreach($dbPrefectureData as $key => $val){
                        ?>
                      <option value="<?php echo $val['id']; ?>" <?php if(getFormData('pref', true) == $val['id']) echo 'selected'; ?>>
                      <?php echo $val['name']; ?>
                    </option>
                    <?php
                      }
                      ?>
                  </select>
                </div>
              </div>
              
              <div class="search-item">
                <div class="cp_ipselect cp_sl01 w-100">
                  <select name="i">
                    <option value="0" <?php if(getFormData('i', true) == 0) echo 'selected'; ?>>業種</option>
                    
                    <?php
                      foreach($dbIndustryData as $key => $val){
                        ?>
                      <option value="<?php echo $val['id']; ?>" <?php if(getFormData('i', true) == $val['id']) echo 'selected'; ?>>
                      <?php echo $val['name']; ?>
                    </option>
                    <?php
                      }
                      ?>
                  </select>
                </div>
              </div>
              
              <div class="search-item">
                <div class="cp_ipselect cp_sl01 w-100">
                  <select name="sort">
                    <option value="0" <?php if(getFormData('sort', true) == 0) echo 'selected'; ?>>表示順</option>
                    <option value="1" <?php if(getFormData('sort', true) == 1) echo 'selected'; ?>>クチコミ数</option>
                    <option value="2" <?php if(getFormData('sort', true) == 2) echo 'selected'; ?>>幸福度</option>
                  </select>
                </div>
              </div>

              <button class="" type="submit">口コミを検索する</button>
            </form>
          </div>
        </div>
  
        <div class="result-form">
          <div class="result-heading">
            <div class="result-num">
              <p>
                <span><?php echo sanitize($dbCompanyData['total']); ?></span>
                 件中　
                <span><?php echo (!empty($dbCompanyData['data'])) ? $currentMinNum+1 : 0; ?></span>
                〜
                <span><?php echo $currentMinNum+count($dbCompanyData['data']); ?></span>
                件表示
              </p>
            </div>
          </div>

          <ul>
            <?php
              foreach($dbCompanyData['data'] as $key => $val):
            ?>
              <li class="company-card">
                <a href="company.php<?php echo(!empty(appendGetParam())) ? appendGetParam().'&c_id='.$val['id'] : '?c_id='.$val['id']; ?>">
                  <div class="company-header">
                    <h2><?php echo sanitize($val['name']); ?></h2>
                    <p>幸福度：<?php echo sanitize($val['rating']); ?></p>
                    <p>クチコミ数：<?php echo sanitize($val['posts_count']); ?></p>
                    <p>本社所在地：<?php echo sanitize($val['prefecture_name'].$val['city_name']); ?></p>
                  </div>
                  <div class="item-content">
                    <div class="post-content">
                      <p>口コミ情報をここに入れます。口コミ情報をここに入れます。口コミ情報をここに入れます。口コミ情報をここに入れます。</p>
                    </div>
                  </div>
                </a>
              </li>
            <?php
              endforeach;
            ?>
          </ul>


          <div class="pagination-heading">
            <p>
              <span><?php echo sanitize($dbCompanyData['total']); ?></span>
                件中　
              <span><?php echo (!empty($dbCompanyData['data'])) ? $currentMinNum+1 : 0; ?></span>
              〜
              <span><?php echo $currentMinNum+count($dbCompanyData['data']); ?></span>
              件表示
            </p>
          </div>
          <?php pagination($currentPageNum, $dbCompanyData['total_page']); ?>

        </div>
      </div>
    </div>
  </main>
    

  <?php
    require('footer.php');
  ?>
