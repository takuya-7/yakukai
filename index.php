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
// GETパラメータを取得
//----------------------------
// （現在のページ番号）カレントページのGETパラメータを取得。pの値がなければ1を入れる
$currentPageNum = (!empty($_GET['p'])) ? $_GET['p'] : 1;

// カテゴリー
// $category = (!empty($_GET['c_id'])) ? $_GET['c_id'] : '';

// ソート順
// $sort = (!empty($_GET['sort'])) ? $_GET['sort'] : '';

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
// $dbProductData = getProductList($currentMinNum, $category, $sort, $listSpan);

// DBからカテゴリデータを取得
// $dbCategoryData = getCategory();

debug('現在のページ：' . $currentPageNum);

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
            <form class="" action="">
              <input class="" type="text" placeholder="企業名で検索" name="SearchWords">
              <button class="" type="submit">検索</button>
            </form>
          </div>
        </div>
      </div>
      
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
            <form action="">
              <div class="select-box-items">
                <div class="select-box-item">
                  <div class="cp_ipselect cp_sl01">
                    <select name="pref_id">
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
  
                <div class="select-box-item-x">
                  ×
                </div>
  
                <div class="select-box-item">
                  <div class="cp_ipselect cp_sl01">
                    <select name="phtype_id">
                      <option value="" hidden>業種</option>
                      <option value="1">調剤薬局</option>
                      <option value="2">ドラッグストア</option>
                      <option value="3">病院</option>
                      <option value="4">その他</option>
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

        <section class="top-widget">
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


        
      

    </main>

    <!-- footer -->
    <?php
      require('footer.php'); 
    ?>
