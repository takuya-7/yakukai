<?php

//共通変数・関数ファイルを読込み
require('config.php');
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
// DBから企業の平均評価値を取得
$dbCompanyRatings = getCompanyRatings($c_id);
// DBからクチコミのカテゴリ情報を取得
$dbCategoryData = getCategory();
// 企業IDからDBクチコミ情報を取得
$dbPostData = getPostList($c_id);
// 企業IDからピックアップクチコミを取得
// $dbPickUpPosts = getPickUpPosts($c_id);

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
        <button class="btn-blue mb-3">
          <a href="surveyInfo.php">クチコミを投稿する</a>
        </button>

        <?php

          foreach($dbCategoryData as $key => $val){
            echo $val['name'];
            echo '<br>';
          }

          echo '<br><br>';
          

          

          // foreach($dbCompanyRatings as $key => $val){
          //   echo $key.'=>'.$val['AVG(rating)'];
          //   echo '<br>';
          // }
        ?>

        <div class="company-wrapper">
          <div class="company-heading">
            <section>
              <h1 class="page-title"><?php echo $dbCompanyData['name']; ?><span class="company-title-append">のクチコミ・評判・年収</span></h1>
              <div class="head-rating mb-4">
                <span class="level-of-well-being">幸福度</span>
                <br>
                <span class="heart5_rating" data-rate="<?php echo round($dbCompanyRatings[0]['AVG(rating)'], 1); ?>"></span>
                <span class="rating-val"><?php echo round($dbCompanyRatings[0]['AVG(rating)'], 1); ?></span>
                <span class="answer-count">（回答：0人）</span>
              </div>

              <canvas id="header-chart"></canvas>

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
                    <?php echo $dbCompanyRatings[10]['name']; ?>
                    <div class="param">
                      <span class="value"><?php echo round($dbCompanyRatings[10]['AVG(rating)'], 1)*20; ?></span><span class="percent"> %</span>
                    </div>
                  </div>
                  <span class="bar_rating" data-rate="<?php echo round($dbCompanyRatings[10]['AVG(rating)'], 1)*20; ?>"></span>
                </div>
                <div>
                  <div class="item-name">
                    <?php echo $dbCompanyRatings[8]['name']; ?>
                    <div class="param">
                      <span class="value"><?php echo round($dbCompanyRatings[8]['AVG(rating)'], 1)*20; ?></span><span class="percent"> %</span>
                    </div>
                  </div>
                  <span class="bar_rating" data-rate="<?php echo round($dbCompanyRatings[8]['AVG(rating)'], 1)*20; ?>"></span>
                </div>
                <div>
                  <div class="item-name">
                    <?php echo $dbCompanyRatings[9]['name']; ?>
                    <div class="param">
                      <span class="value"><?php echo round($dbCompanyRatings[9]['AVG(rating)'], 1)*20; ?></span><span class="percent"> %</span>
                    </div>
                  </div>
                  <span class="bar_rating" data-rate="<?php echo round($dbCompanyRatings[9]['AVG(rating)'], 1)*20; ?>"></span>
                </div>
                <div>
                  <div class="item-name">
                    <?php echo $dbCompanyRatings[7]['name']; ?>
                    人間関係の満足度
                    <div class="param">
                      <span class="value"><?php echo round($dbCompanyRatings[7]['AVG(rating)'], 1)*20; ?></span><span class="percent"> %</span>
                    </div>
                  </div>
                  <span class="bar_rating" data-rate="<?php echo round($dbCompanyRatings[7]['AVG(rating)'], 1)*20; ?>"></span>
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
              <h2>処方せん処理枚数</h2>
              <p>回答者の平均枚数（1日8時間あたり）：枚</p>
              <p>回答者の枚数範囲：〜枚</p>
            </section>
            
            <h2>Pick up クチコミ</h2>
            
            <?php foreach($dbCategoryData as $key => $category){ ?>
              <section>

                <?php $dbPickUpPosts = getPickUpPosts($c_id, $category['id']); ?>

                <?php foreach($dbPickUpPosts as $key => $val){ ?>
                  <div class="kutikomi-header">
                    <div class="user-icon">
                      <i class="gg-profile"></i>
                    </div>
  
                    <h3>
                      <span><?php echo $dbCompanyData['name']; ?></span><br>
                      <?php echo $val['category']; ?>
                    </h3>
  
                    <div class="user-info">
                      回答者：
                      <a href="">
                        <?php echo SEX[$val['sex']]; ?>
                        <?php echo '、'.ENTRY_TYPE[$val['entry_type']]; ?>
                        <?php echo '、'.REGISTRATION[$val['registration']].'（回答時）'; ?>
                        <?php echo '、在籍'.($val['a_update_date']-$val['entry_date']).'年'; ?>
                      </a>
                    </div>
  
                    <span class="heart5_rating" data-rate="<?php echo round($val['rating'], 1); ?>"></span>
                    <span class="fs-3 ms-1">
                      <?php echo round($val['rating'], 1); ?>
                    </span>
                    <p></p>
                  </div>
  
                  <h4 class="fs-1rem fw-bold"><?php echo $val['answer_item']; ?>：</h4>
                  <p><?php echo $val['answer']; ?></p>
  
                  <span class="post-date">クチコミ投稿：<?php echo date('Y年m月', strtotime($val['a_update_date'])); ?></span>
  
                  <div class="border-bottom mb-3"></div>
  
                <?php } ?>
                  
                  <div class="category-btn">
                    <a href=""><span class="fw-bold">「<?php echo $category['name']; ?>」</span> <br><span class="fs-08 category-append">のクチコミをもっと見る（件）</span></a>
                  </div>
                
              </section>
            <?php } ?>
            

            

            

            <section>
              <h2>カテゴリからクチコミを探す</h2>
              <div class="kutikomi-category-list">
                <ul>
                  <?php foreach($dbCategoryData as $key => $val){ ?>
                    <li><a href=""><?php echo $val['name']; ?>（件）</a></li>
                  <?php } ?>
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
    
    <footer id="footer">
      <ul class="container">
        <li><a href="index.php">HOME</a></li>
        <li><a href="">ご利用案内</a></li>
        <li><a href="">プライバシーポリシー</a></li>
        <li><a href="">サイトマップ</a></li>
        <li><a href="">お問い合わせ</a></li>
      </ul>

      <span class="copyright">
        Copyright © ヤクカイ. All Rights Reserved.
      </span>
    </footer>
    
    <!-- jQuery読み込み -->
    <script src="js/vender/jquery-3.5.1.min.js"></script>
    <!-- Chart.js読み込み -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.1.0/chart.min.js" integrity="sha512-RGbSeD/jDcZBWNsI1VCvdjcDULuSfWTtIva2ek5FtteXeSjLfXac4kqkDRHVGf1TwsXCAqPTF7/EYITD0/CTqw==" crossorigin="anonymous"></script>
    <script>
      $(function(){
        // フッターを最下部に固定
        var $ftr = $('#footer');
        if( window.innerHeight > $ftr.offset().top + $ftr.outerHeight() ){
          //ウインドウ内コンテンツ部分の高さ　＞　普通に表示した時のコンテンツ部分左上からフッターまで高さ + フッターのボーダー外側の高さ
          $ftr.attr({
            'style': 
              'position:fixed; top:' + (window.innerHeight - $ftr.outerHeight()) +'px;'
          });
        }

        // レーダーチャート処理
        var canvas = document.getElementById('header-chart');

        if(canvas.getContext){
          var ctx = document.getElementById("header-chart");
          var myRadarChart = new Chart(ctx, {
            type: 'radar', 
            data: { 
              labels: [
                "<?php echo $dbCompanyRatings[1]['name']; ?>：<?php echo round($dbCompanyRatings[1]['AVG(rating)'], 1); ?>",
                "<?php echo $dbCompanyRatings[2]['name']; ?>：<?php echo round($dbCompanyRatings[2]['AVG(rating)'], 1); ?>",
                "<?php echo $dbCompanyRatings[3]['name']; ?>：<?php echo round($dbCompanyRatings[3]['AVG(rating)'], 1); ?>",
                "<?php echo $dbCompanyRatings[4]['name']; ?>：<?php echo round($dbCompanyRatings[4]['AVG(rating)'], 1); ?>",
                "<?php echo $dbCompanyRatings[5]['name']; ?>：<?php echo round($dbCompanyRatings[5]['AVG(rating)'], 1); ?>",
                "<?php echo $dbCompanyRatings[6]['name']; ?>：<?php echo round($dbCompanyRatings[6]['AVG(rating)'], 1); ?>",
              ],

              datasets: [{
                label: '<?php echo $dbCompanyData['name']; ?>の評価値',
                data: [
                  <?php echo round($dbCompanyRatings[1]['AVG(rating)'], 1); ?>,
                  <?php echo round($dbCompanyRatings[2]['AVG(rating)'], 1); ?>,
                  <?php echo round($dbCompanyRatings[3]['AVG(rating)'], 1); ?>,
                  <?php echo round($dbCompanyRatings[4]['AVG(rating)'], 1); ?>,
                  <?php echo round($dbCompanyRatings[5]['AVG(rating)'], 1); ?>,
                  <?php echo round($dbCompanyRatings[6]['AVG(rating)'], 1); ?>,
                ],
                backgroundColor: 'RGBA(225,95,150, 0.2)',
                // backgroundColor: '#f88dc8',
                borderColor: 'RGBA(225,95,150, 1)',
                borderWidth: 1,
                pointBackgroundColor: 'RGB(46,106,177,0)',
                // borderCapStyle: 'round',
                pointRadius: 0,
              }, 
              // {
              //   label: 'Bさん',
              //   data: [4, 3, 4, 2, 3],
              //   backgroundColor: 'RGBA(115,255,25, 0.5)',
              //   borderColor: 'RGBA(115,255,25, 1)',
              //   borderWidth: 1,
              //   pointBackgroundColor: 'RGB(46,106,177)'
              // }
              ]
            },
            options: {
              title: {
                display: true,
                text: '試験成績'
              },
              scale:{
                min: 0,
                max: 5,
                // display: false,
                ticks:{
                  // suggestedMin: 0,
                  // suggestedMax: 5,
                  stepSize: 1,
                  callback: function(value, index, values){
                    return  value +  '点'
                  }
                }
              }
            }
          });
        }
      });
    </script>
  </body>
</html>
