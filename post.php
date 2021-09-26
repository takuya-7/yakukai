<?php

//共通変数・関数ファイルを読込み
require('config.php');
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　個別クチコミページ　');
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
$company_id = (!empty($_GET['c_id'])) ? $_GET['c_id'] : '';
// post_idを取得
$post_id = (!empty($_GET['p_id'])) ? $_GET['p_id'] : '';
// DBから企業データを取得
$dbCompanyData = getCompanyOne($company_id);
// DBから企業の平均評価値を取得
$dbCompanyRatings = getCompanyRatings($company_id);
// DBからクチコミのカテゴリ情報を取得
$dbCategoryData = getCategory($company_id);
// DBからpost_idに紐づくクチコミ情報を取得
$dbPostData = getPostAll($post_id);
// DBから投稿者の情報取得
$dbUserData = getUser($dbPostData['post']['user_id']);
// DBからクチコミリスト取得
$dbPostList = getPostList($company_id);

// ユーザーID取得
$user_id = $_SESSION['user_id'];
// ユーザーのクチコミ投稿情報取得
$dbAnswerData = getAnswer($user_id);

// $viewDataが空かどうか（空ならユーザーが不正なGETパラメータを入れて商品データを取得できていない状態）をチェック
if(empty($dbCompanyData) || empty($dbPostData)){
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
  <div class="l-all-wrapper">
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
          <?php
            // クチコミ投稿が完了していない場合
            if(empty($dbAnswerData[0]['answer'])){
          ?>
          <a href="surveyInfo.php" class="c-button c-button--blue c-button--width100">クチコミを投稿する</a>
          <?php
            // クチコミ投稿が完了している場合
            }else{
          ?>
          <button class="c-button c-button--gray c-button--width100" style= "cursor:default">クチコミ投稿済み</button>
          <?php
            }
          ?>
        </div>

        <div class="l-content p-post-head">
          <section>
            <h1 class="c-page-title"><?php echo $dbCompanyData['info']['name']; ?><span class="company-title-append">の回答者別クチコミ</span></h1>
            <span class="p-user-info">回答者情報</span>
            <table class="table border u-text-center">
              <tbody>
                <tr>
                  <td>入社経路</td>
                  <td>
                    <?php
                      if(isset($dbPostData['post']['entry_type'])){
                        echo ENTRY_TYPE[$dbPostData['post']['entry_type']];
                      }else{
                        echo '-';
                      }
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>性別</td>
                  <td>
                    <?php
                      echo SEX[$dbUserData['sex']];
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>在籍期間</td>
                  <td>
                    <?php
                      if($dbPostData['post']['registration'] == 1){
                        echo getYearDiff($dbPostData['post']['entry_date'], $dbPostData['post']['update_date']).'年';
                      }else{
                        echo '-';
                      }
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>役職・部署</td>
                  <td>
                    <?php
                      if(isset($dbPostData['post']['department'])){
                        echo $dbPostData['post']['department'];
                      }else{
                        echo '-';
                      }
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>雇用形態</td>
                  <td>
                    <?php
                      if(isset($dbPostData['post']['employment_type'])){
                        echo EMP_TYPE[$dbPostData['post']['employment_type']];
                      }else{
                        echo '-';
                      }
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>在籍状況（投稿時）</td>
                  <td>
                    <?php
                      if(isset($dbPostData['post']['registration'])){
                        echo REGISTRATION[$dbPostData['post']['registration']];
                      }else{
                        echo '-';
                      }
                    ?>
                  </td>
                </tr>
              </tbody>
            </table>

            <div class="head-rating u-mb-4">
              <span class="level-of-well-being p-post-total-rating">総合的な幸福度</span>
              <br>
              <span class="heart5_rating" data-rate="<?php echo $dbPostData['rating'][0]['rating']; ?>"></span>
              <span class="rating-val"><?php echo $dbPostData['rating'][0]['rating']; ?></span>
              
            </div>

            <div class="u-mb-5">
              <div class="u-mb-3">
                <ul class="list-unstyled">
                  <?php foreach($dbPostData['rating'] as $key => $val){ ?>
                    <li class="p-0 u-mb-3">
                      <div class="row">
                        <div class="col-6"><?php echo $val['name']; ?></div>
                        <div class="col-6">
                          <span class="heart5_rating" data-rate="<?php echo $val['rating']; ?>"></span>
                          <span class=""><?php echo $val['rating']; ?></span>
                        </div>
                      </div>
                    </li>
                  <?php } ?>
                </ul>
                <div class="border"></div>
              </div>
              
              <div class="u-mb-3">
                <ul class="list-unstyled">
                  <li>
                    <div class="row">
                      <div class="col-6">残業時間</div>
                      <div class="col-6"><?php echo $dbPostData['post']['over_time']; ?>時間</div>
                    </div>
                  </li>
                </ul>
                <div class="border"></div>
              </div>

              <div class="u-mb-3">
                <table class="table">
                  <tbody>
                    <tr>
                      <td>年収</td>
                      <td><?php echo $dbPostData['post']['anual_total_salary']; ?>万円</td>
                    </tr>
                    <tr>
                      <td>月給（総額）</td>
                      <td><?php echo $dbPostData['post']['monthly_total_salary']; ?>万円</td>
                    </tr>
                    <tr>
                      <td>残業代（月額）</td>
                      <td><?php echo $dbPostData['post']['monthly_overtime_salary']; ?>万円</td>
                    </tr>
                    <tr>
                      <td>手当て（月額）</td>
                      <td><?php echo $dbPostData['post']['monthly_allowance']; ?>万円</td>
                    </tr>
                    <tr>
                      <td>賞与（年額）</td>
                      <td><?php echo $dbPostData['post']['anual_bonus_salary']; ?>万円</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </section>
        </div>

        <h2>回答者のクチコミ</h2>
        <?php foreach($dbCategoryData as $key => $category){ ?>
          <?php $show_count = 0; ?>
          <?php foreach($dbPostData['answer'] as $key => $answer){ ?>
            <?php if($category['id'] == $answer['category_id'] && !empty($answer['answer'])){ ?>

              <?php if($show_count === 0){ ?>
                <section class="p-post">
                  <div class="p-post__header">
                    <div class="user-icon">
                      <i class="gg-profile"></i>
                    </div>
  
                    <h3 class="p-post__header__title">
                      <span class="p-post__header__company"><?php echo $dbCompanyData['info']['name']; ?></span><br>
                      <?php echo $category['name']; ?>
                    </h3>
  
                    <div class="p-post__header__user-info">
                      回答者：
                      <a href="">
                        <?php echo SEX[$dbUserData['sex']]; ?>
                        <?php echo '、'.ENTRY_TYPE[$dbPostData['post']['entry_type']]; ?>
                        <?php echo '、'.REGISTRATION[$dbPostData['post']['registration']].'（回答時）'; ?>
                        <?php
                          if($dbPostData['post']['registration'] == 1){
                            echo '、'.getYearDiff($dbPostData['post']['entry_date'], $dbPostData['post']['update_date']).'年在籍';
                          }
                        ?>
                      </a>
                    </div>
  
                    <div class="px-3">
                      <span class="heart5_rating" data-rate="<?php echo number_format($dbPostData['rating'][0]['rating'], 1); ?>"></span>
                      <span class="fs-3 ms-1">
                        <?php echo number_format($dbPostData['rating'][0]['rating'], 1); ?>
                      </span>
                    </div>
                    
                  </div>
  
                  <?php foreach($dbPostData['answer'] as $key => $val){ ?>
                    <?php if($category['id'] == $val['category_id']){ ?>
                      <h4 class="p-post__item-name"><?php echo $val['answer_item']; ?>：</h4>
                      <p><?php echo $val['answer']; ?></p>
                    <?php } ?>
                  <?php } ?>
  
                  <div class="p-post__bottom u-mb-0">
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
                </section>

                <?php $show_count = 1; ?>
              <?php } ?>
            <?php } ?>
          <?php } ?>
        <?php } ?>            

        <h2><?php echo $dbCompanyData['info']['name']; ?>の回答者別クチコミ（<?php echo count($dbPostList); ?>人）</h2>
        <ul class="p-user-list">
          <?php foreach($dbPostList as $key => $val){ ?>
            <li>
              <a href="post.php?c_id=<?php echo $company_id.'&'.'p_id='.$val['id']; ?>" class="c-card c-card--link c-card--shadow">
                <div class="u-mb-3">
                  <?php
                    echo $val['department'];
                    if(!empty($val['department']) && !empty($val['position'])) echo '、';
                    if(!empty($val['position'])) echo $val['position'];
                  ?>
                </div>

                <div class="u-mb-3">
                  <?php
                    echo SEX[$val['sex']];
                    if(isset($dbPostData['post']['entry_type'])){
                      echo '、'.ENTRY_TYPE[$dbPostData['post']['entry_type']];
                    }
                    if($val['registration'] == 1){
                      echo '、'.getYearDiff($val['entry_date'], $val['create_date']).'年在籍';
                    }
                    if(isset($val['employment_type'])){
                      echo '、'.EMP_TYPE[$val['employment_type']];
                    }
                    if(isset($val['registration'])){
                      echo '、'.REGISTRATION[$val['registration']].'（投稿時）';
                    }
                  ?>
                </div>

                <div class="">
                  <span class="heart5_rating" data-rate="<?php echo $val['rating']; ?>"></span>
                  <span class="u-text-black"><?php echo $val['rating']; ?></span>
                </div>

                <div>
                  <span class="post-date">クチコミ投稿：<?php echo date('Y年m月', strtotime($val['create_date'])); ?></span>
                </div>
              </a>
            </li>
          <?php } ?>
        </ul>

        <section class="p-company-section">
          <h2><?php echo $dbCompanyData['info']['name']; ?>のカテゴリ別クチコミ</h2>
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
  </main>

  <?php
    require('footer.php');
  ?>