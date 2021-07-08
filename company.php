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
$company_id = filter_input(INPUT_GET, 'c_id');
// $company_id = (!empty($_GET['c_id'])) ? $_GET['c_id'] : '';
// DBから企業データを取得
$dbCompanyData = getCompanyOne($company_id);
// DBから企業の平均評価値を取得
$dbCompanyRatings = getCompanyRatings($company_id);
// DBからクチコミのカテゴリ情報を取得
$dbCategoryData = getCategory($company_id);

// $viewDataが空かどうか（空ならユーザーが不正なGETパラメータを入れて商品データを取得できていない状態）をチェック
if(empty($dbCompanyData)){
  error_log('エラー発生:指定ページに不正な値が入りました');
  header('Location:search.php');
}
debug('企業データ：'.print_r($dbCompanyData,true));
?>

<?php
$siteTitle = $dbCompanyData['info']['name'];
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
        <div class="u-mb-3">
          <a href="surveyInfo.php" class="c-button c-button--blue c-button--width100">クチコミを投稿する</a>
        </div>

        <div class="l-content p-company-head">
          <section>
            <h1 class="c-page-title"><?php echo $dbCompanyData['info']['name']; ?><span class="company-title-append">のクチコミ・評判・年収</span></h1>
            <div class="head-rating u-mb-4">
              <span class="level-of-well-being">幸福度</span>
              <br>
              <span class="heart5_rating" data-rate="<?php echo number_format($dbCompanyRatings[0]['AVG(rating)'], 1); ?>"></span>
              <span class="rating-val"><?php echo number_format($dbCompanyRatings[0]['AVG(rating)'], 1); ?></span>
              <span class="answer-count">（回答：<?php echo $dbCompanyRatings['rating_count']['COUNT(rating)']; ?>件）</span>
            </div>

            <div class="l-company-head">
              <div class="l-company-head__chart">
                <canvas id="header-chart"></canvas>
              </div>
  
              <div class="l-company-head__info">
                <div class="main-params-summary row u-text-center u-mb-4">
                  <div class="col-6 border-end">
                    <div class="title">
                      平均年収<br>（正社員薬剤師）
                    </div>
                    <div class="u-mb-2">
                      <span class="param"><?php echo round($dbCompanyData['user_data']['AVG(anual_total_salary)'], 0); ?></span>
                      <span> 万円</span>
                    </div>
                    <div>
                      <span>（回答：<?php echo $dbCompanyData['user_data']['COUNT(anual_total_salary)']; ?>件）</span>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="title">
                      平均残業時間<br>（月間）
                    </div>
                    <div class="u-mb-2">
                      <span class="param"><?php echo round($dbCompanyData['user_data']['AVG(over_time)'], 0); ?></span>
                      <span> 時間</span>
                    </div>
                    <div>
                      <span>（回答：<?php echo $dbCompanyData['user_data']['COUNT(over_time)']; ?>件）</span>
                    </div>
                  </div>
                </div>
                <div class="p-satisfaction">
                  <div>
                    <div>
                      <?php echo $dbCompanyRatings[10]['name']; ?>
                      <div class="p-satisfaction__item">
                        <span class="p-satisfaction__item__value"><?php echo round($dbCompanyRatings[10]['AVG(rating)'], 1)*20; ?></span><span class="percent"> %</span>
                      </div>
                    </div>
                    <span class="bar_rating" data-rate="<?php echo round($dbCompanyRatings[10]['AVG(rating)'], 1)*20; ?>"></span>
                  </div>
                  <div>
                    <div>
                      <?php echo $dbCompanyRatings[8]['name']; ?>
                      <div class="p-satisfaction__item">
                        <span class="p-satisfaction__item__value"><?php echo round($dbCompanyRatings[8]['AVG(rating)'], 1)*20; ?></span><span class="percent"> %</span>
                      </div>
                    </div>
                    <span class="bar_rating" data-rate="<?php echo round($dbCompanyRatings[8]['AVG(rating)'], 1)*20; ?>"></span>
                  </div>
                  <div>
                    <div>
                      <?php echo $dbCompanyRatings[9]['name']; ?>
                      <div class="p-satisfaction__item">
                        <span class="p-satisfaction__item__value"><?php echo round($dbCompanyRatings[9]['AVG(rating)'], 1)*20; ?></span><span class="percent"> %</span>
                      </div>
                    </div>
                    <span class="bar_rating" data-rate="<?php echo round($dbCompanyRatings[9]['AVG(rating)'], 1)*20; ?>"></span>
                  </div>
                  <div>
                    <div>
                      <?php echo $dbCompanyRatings[7]['name']; ?>
                      <div class="p-satisfaction__item">
                        <span class="p-satisfaction__item__value"><?php echo round($dbCompanyRatings[7]['AVG(rating)'], 1)*20; ?></span><span class="percent"> %</span>
                      </div>
                    </div>
                    <span class="bar_rating" data-rate="<?php echo round($dbCompanyRatings[7]['AVG(rating)'], 1)*20; ?>"></span>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>

        <div class="l-company-main">
          <section class="p-company-section">
            <h2>年収データ</h2>
            <p>回答者の平均年収：<?php echo round($dbCompanyData['user_data']['AVG(anual_total_salary)'], 0); ?>万円</p>
            <p>回答者の年収範囲：<?php echo round($dbCompanyData['user_data']['MIN(anual_total_salary)'], 0); ?>〜<?php echo round($dbCompanyData['user_data']['MAX(anual_total_salary)'], 0); ?>万円</p>
            <p>回答者数：<?php echo $dbCompanyData['user_data']['COUNT(anual_total_salary)']; ?>人</p>
            <p>回答者の平均年齢：<?php echo date('Y')-round($dbCompanyData['user_data']['AVG(users.birth_year)']); ?>歳</p>
          </section>

          <!-- <section>
            <h2>処方せん処理枚数</h2>
            <p>回答者の平均枚数（1日8時間あたり）：枚</p>
            <p>回答者の枚数範囲：〜枚</p>
          </section> -->
          
          <h2>Pick up クチコミ</h2>
          
          <?php foreach($dbCategoryData as $key => $category){ ?>
            <?php $dbPickUpPosts = getPickUpPosts($company_id, $category['id']); ?>
            <?php if(!empty($dbPickUpPosts)){ ?>
              <section class="p-post">
                <?php foreach($dbPickUpPosts as $key => $val){ ?>
                  <?php if(!empty($val['answer'])){ ?>
                    <div class="p-post__header">
                      <div class="user-icon">
                        <i class="gg-profile"></i>
                      </div>
    
                      <h3 class="p-post__header__title">
                        <span class="p-post__header__company"><?php echo $dbCompanyData['info']['name']; ?></span><br>
                        <?php echo $val['category']; ?>
                      </h3>
                      
                      <div class="p-post__header__user-info">
                        回答者：
                        <a href="post.php<?php echo '?c_id='.$company_id.'&p_id='.$val['post_id']; ?>">
                          <?php echo SEX[$val['sex']]; ?>
                          <?php echo '、'.ENTRY_TYPE[$val['entry_type']]; ?>
                          <?php echo '、'.REGISTRATION[$val['registration']].'（回答時）'; ?>
                          <?php echo '、在籍'.getYearDiff($val['a_update_date'], $val['entry_date']).'年'; ?>
                        </a>
                      </div>

                      <div class="px-3">
                        <span class="heart5_rating" data-rate="<?php echo $val['rating']; ?>"></span>
                        <span class="fs-3 ms-1">
                          <?php echo number_format($val['rating'], 1); ?>
                        </span>
                      </div>
                      
                    </div>

                    <h4 class="p-post__item-name"><?php echo $val['answer_item']; ?>：</h4>
                    <p><?php echo $val['answer']; ?></p>
    
                    <div class="p-post__bottom">
                      <span class="c-good">
                        <span class="c-good__comment">参考になった！</span>
                        <i class="fa fa-thumbs-up c-good__icon
                        <?php
                          if(isset($_SESSION['user_id'])){
                            echo ' js-click-good';
                            if(isGood($_SESSION['user_id'], $val['answer_id'])) echo ' is-good-active';
                          }
                        ?>" aria-hidden="true" data-answer_id="<?php echo sanitize($val['answer_id']);?>" ></i>
                        <span class="c-good__count js-good-count"><?php echo getGoodCount($val['answer_id']); ?></span>
                      </span>
                      <span class="p-post__date">クチコミ投稿：<?php echo date('Y年m月', strtotime($val['a_update_date'])); ?></span>
                    </div>
    
                    <div class="border-bottom u-mb-3"></div>
                  <?php } ?>
                <?php } ?>

                <div class="category-btn">
                  <a href="category.php?co=<?php echo $company_id.'&ca='.$category['id']; ?>"><span class="fw-bold">「<?php echo $category['name']; ?>」</span> <br><span class="u-fs-08 category-append">のクチコミをもっと見る（<?php echo $category['count']['COUNT(answers.id)']; ?>件）</span></a>
                </div>
                  
              </section>
            <?php } ?>
          <?php } ?>            

          <section class="p-company-section">
            <h2>カテゴリからクチコミを探す</h2>
            <div class="kutikomi-category-list">
              <ul class="p-category-list">
                <?php foreach($dbCategoryData as $key => $val){ ?>
                  <li><a class="p-category-list__item" href="category.php?co=<?php echo $company_id.'&ca='.$val['id']; ?>"><?php echo $val['name']; ?>（<?php echo $val['count']['COUNT(answers.id)']; ?>件）</a></li>
                <?php } ?>
              </ul>
            </div>
          </section>

          <section class="p-company-section">
            <h2>企業概要</h2>
            <div class="company-info">
              <dl>
                <dt>企業名</dt>
                <dd><?php echo $dbCompanyData['info']['name']; ?></dd>
                <dt>フリガナ</dt>
                <dd><?php echo $dbCompanyData['info']['furigana']; ?></dd>
                <dt>本社所在地</dt>
                <dd><?php echo $dbCompanyData['info']['prefecture_name'].$dbCompanyData['info']['city_name'].$dbCompanyData['info']['street_number']; ?></dd>
              </dl>
            </div>
          </section>
        </div>
        
      </div>
    </div>
  </main>
    
  <!-- footer -->
  <?php require('footer.php'); ?>
