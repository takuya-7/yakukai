<?php

//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　企業情報画面　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//================================
// 画面処理
//================================

// 画面表示用データ取得
//================================
// GETパラメータを取得
//----------------------------
// 企業IDのGETパラメータを取得
$c_id = (!empty($_GET['c_id'])) ? $_GET['c_id'] : '';

// DBから企業データを取得
$dbCompanyData = getCompanyOne($c_id);

// 企業IDからDB口コミ情報を取得
// $dbPostData = getPostList($c_id);

// $viewDataが空かどうか（空ならユーザーが不正なGETパラメータを入れて商品データを取得できていない状態）をチェック
if(empty($dbCompanyData)){
  error_log('エラー発生:指定ページに不正な値が入りました');
  header('Location:search.php');
}

debug('企業データ：'.print_r($dbCompanyData,true));

?>

<?php
$siteTitle = $dbCompanyData['name'];
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
        <div class="company-wrapper">
          <div class="company-heading">
            <section>
              <h1 class="page-title"><?php echo $dbCompanyData['name']; ?><span class="company-title-append">のクチコミ・評判・年収</span></h1>
              <div class="head-rating mb-4">
                <span class="level-of-well-being">幸福度</span>
                <br>
                <span class="heart5_rating" data-rate="<?php echo sanitize($dbCompanyData['rating']); ?>"></span>
                <span class="rating-val"><?php echo sanitize($dbCompanyData['rating']); ?></span>
                <span class="answer-count">（回答：0人）</span>
              </div>
              <div class="main-params-summary row text-center mb-4">
                <div class="col-6 border-end">
                  <div class="title">
                    平均年収<br>（正社員薬剤師）
                  </div>
                  <div class="mb-2">
                    <span class="param">600</span>
                    <span> 万円</span>
                  </div>
                  <div>
                    <span>（回答：0件）</span>
                  </div>
                </div>
                <div class="col-6">
                  <div class="title">
                    残業時間<br>（月間）
                  </div>
                  <div class="mb-2">
                    <span class="param">15</span>
                    <span> 時間</span>
                  </div>
                  <div>
                    <span>（回答：0件）</span>
                  </div>
                </div>
              </div>
              <div class="params">
                <div class="item">
                  <div class="item-name">
                    給与・年収の納得度
                    <div class="param">
                      <span class="value">75</span><span class="percent"> %</span>
                    </div>
                  </div>
                  <span class="bar_rating" data-rate="75"></span>
                </div>
                <div>
                  <div class="item-name">
                    勤務時間の納得度
                    <div class="param">
                      <span class="value">75</span><span class="percent"> %</span>
                    </div>
                  </div>
                  <span class="bar_rating" data-rate="75"></span>
                </div>
                <div>
                  <div class="item-name">
                    休日・休暇の納得度
                    <div class="param">
                      <span class="value">75</span><span class="percent"> %</span>
                    </div>
                  </div>
                  <span class="bar_rating" data-rate="75"></span>
                </div>
                <div>
                  <div class="item-name">
                    人間関係の満足度
                    <div class="param">
                      <span class="value">75</span><span class="percent"> %</span>
                    </div>
                  </div>
                  <span class="bar_rating" data-rate="75"></span>
                </div>
              </div>
            </section>
          </div>

          <div class="page-content">
            <section>
              <h2>年収データ</h2>
              <p>回答者の平均年収：〜万円</p>
              <p>回答者の年収範囲：〜万円</p>
              <p>回答者数：人</p>
              <p>回答者の平均年齢：人</p>
            </section>

            <section>
              <h2>処方箋処理枚数</h2>
              <p>回答者の平均枚数（1日8時間あたり）：枚</p>
              <p>回答者の枚数範囲：〜枚</p>
            </section>
            
            <h2>Pick up クチコミ</h2>
            <section>
              <div class="kutikomi-header">
                <div class="user-icon">
                  <i class="gg-profile"></i>
                </div>
                <h3>
                  <span><?php echo $dbCompanyData['name']; ?></span><br>
                  仕事のやりがい・成長
                </h3>
                <div class="user-info">
                  回答者：
                  <a href="">男性、新卒入社、現職（回答時）、在籍2年</a>
                </div>
                <span class="heart5_rating" data-rate="<?php echo sanitize($dbCompanyData['rating']); ?>"></span>
                <span class="fs-3 ms-1">
                  <?php echo sanitize($dbCompanyData['rating']); ?>
                </span>
                <p></p>
              </div>
              <p>ここにクチコミが入ります。ここにクチコミが入ります。ここにクチコミが入ります。ここにクチコミが入ります。</p>
            </section>

            <section>
              <h2>カテゴリからクチコミを探す</h2>
              <div class="kutikomi-category-list">
                <ul>
                  <li><a href="">仕事のやりがい・成長（件）</a></li>
                  <li><a href="">年収・給与（件）</a></li>
                  <li><a href="">福利厚生・職場環境（件）</a></li>
                  <li><a href="">仕事内容・仕事量（件）</a></li>
                  <li><a href="">働き方（勤務時間・休日日数・制度）（件）</a></li>
                  <li><a href="">企業文化・組織体制（件）</a></li>
                  <li><a href="">女性の働きやすさ（件）</a></li>
                  <li><a href="">入社理由・入社後のギャップ（件）</a></li>
                  <li><a href="">退職検討理由（件）</a></li>
                  <li><a href="">強み・弱み・将来性（件）</a></li>
                </ul>
              </div>
              <p>
                <a href="">全てのクチコミを見る</a>
              </p>
            </section>

            <section>
              <h2>企業概要</h2>
              <div class="company-info">
                <dl>
                  <dt>企業名</dt>
                  <dd><?php echo $dbCompanyData['name']; ?></dd>
                  <dt>フリガナ</dt>
                  <dd><?php echo $dbCompanyData['furigana']; ?></dd>
                  <dt>本社所在地</dt>
                  <dd><?php echo $dbCompanyData['prefecture_name'].$dbCompanyData['city_name'].$dbCompanyData['street_number']; ?></dd>
                </dl>
              </div>
            </section>
          </div>
        </div>
  
        
      </div>
    </div>
  </main>
    

  <?php
    require('footer.php');
  ?>
