<?php

//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　トップページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//================================
// 画面処理
//================================

// 画面表示用データ取得
//================================
// DBから都道府県データを取得
$dbPrefectureData = getPrefecture();
// DBから業種データを取得
$dbIndustryData = getIndustry();

// GETパラメータを取得
//----------------------------
// （現在のページ番号）カレントページのGETパラメータを取得。pの値がなければ1を入れる
// $currentPageNum = (!empty($_GET['p'])) ? $_GET['p'] : 1;
// DBから商品データ（total:商品総数、total_page:総ページ数、data:表示する商品データ）を取得
// $dbProductData = getProductList($currentMinNum, $category, $sort, $listSpan);

// DBからカテゴリデータを取得
// $dbCategoryData = getCategory();

// debug('現在のページ：' . $currentPageNum);

//debug('フォーム用DBデータ：'.print_r($dbFormData,true));
//debug('カテゴリデータ：'.print_r($dbCategoryData,true));

?>

<?php
$siteTitle = 'HOME';
require('head.php'); 
?>

  <body>
    
    <main>
      <div class="top-wrap">
        <header>
          <div class="container">
            <div class="header-left">
              <a href="index.php">ヤクカイ</a>
            </div>

            <div class="header-right">
              <nav>
                <ul>
                  <?php if(empty($_SESSION['user_id'])){ ?>
                    <li><a href="signup.php">ユーザー登録</a></li>
                    <li><a href="login.php">ログイン</a></li>
                  <?php }else{ ?>
                    <li><a href="mypage.php">マイページ</a></li>
                    <li><a href="logout.php">ログアウト</a></li>
                  <?php } ?>
                </ul>
              </nav>
            </div>
          </div>
        </header>
        <div class="container">
          <div class="top-head">
            <h1>
              会社クチコミ・転職例で<br>
              薬剤師により良い選択肢を。
            </h1>
          </div>
  
          <div class="top-search-box">
            <form method="get" action="search.php">
              <input class="" type="text" placeholder="企業名で検索" name="src_str" value="<?php if(!empty($_GET['src_str'])) echo $_GET['src_str']; ?>">
              <button class="" type="submit">検索</button>
            </form>
          </div>
        </div>
      </div>
      
      <div class="content-wrapper">
        <div class="container">
          <section class="top-widget natural-shadow">
            <div class="top-widget-title">
              <h2>人気の企業</h2>
            </div>
            <div></div>
          </section>
  
          <section class="top-widget natural-shadow">
            <div class="top-widget-title">
              <h2>エリア・業種から口コミを探す</h2>
            </div>
            <div class="select-box">
              <form method="get" action="search.php">
                <div class="select-box-items">
                  <div class="select-box-item">
                    <div class="cp_ipselect cp_sl01">
                      <select name="pref">
                      <option value="0" <?php if(getFormData('pref', true) == 0) echo 'selected'; ?>>都道府県</option>
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
    
                  <div class="select-box-item-x">
                    ×
                  </div>
    
                  <div class="select-box-item">
                    <div class="cp_ipselect cp_sl01">
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
                </div>
  
                <button type="submit">
                  この条件で口コミを探す
                </button>
              </form>
            </div>
          </section>
    
          <section class="top-widget natural-shadow">
            <h2>ヤクカイ - 薬剤師のためのキャリア会議</h2>
            <div class="content">
              <p>ヤクカイは、薬剤師同士が会社クチコミ・仕事経験を共有することで就職・転職時のより良い選択を応援します。</p>
            </div>
          </section>
  
          <section class="signup-wrap">
            <div class="buttons">
              <button class="signup" onclick="location.href='signup.php'">
                ユーザー登録する（無料）
              </button>
              <button class="login" onclick="location.href='login.php'">
                ログインする
              </button>
            </div>
          </section>
        </div>
      </div>
    </main>

    <!-- footer -->
    <?php
      require('footer.php'); 
    ?>
