<?php
//共通変数・関数ファイルを読込み
require('config.php');
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
// DBから企業情報を取得
$dbCompanyData = getCompanyRanking();
?>

<?php
$siteTitle = 'HOME';
require('head.php'); 
?>

  <body>
    <div class="l-all-wrapper">
    <!-- セッション変数内のメッセージを表示 -->
    <p id="js-show-msg" style="display:none;" class="msg-slide">
      <?php echo getSessionFlash('msg_success'); ?>
    </p>
    <main>
      <div class="top-wrap">
        <header class="l-header">
          <div class="l-container l-header__inner">
            <a class="l-header__icon" href="index.php">ヤクカイ</a>

            <div class="l-menu-trigger js-toggle-sp-menu">
              <span></span>
              <span></span>
              <span></span>
            </div>
            <nav class="l-nav-menu js-toggle-sp-menu-target">
              <ul class="l-menu">
                <?php if(empty($_SESSION['user_id'])){ ?>
                  <li><a class="l-menu__link" href="signup.php">ユーザー登録</a></li>
                  <li><a class="l-menu__link" href="login.php">ログイン</a></li>
                <?php }else{ ?>
                  <li><a class="l-menu__link" href="mypage.php">マイページ</a></li>
                  <li><a class="l-menu__link" href="logout.php">ログアウト</a></li>
                <?php } ?>
              </ul>
            </nav>
          </div>
        </header>
        <div class="l-container">
          <div class="top-head">
            <h1>
              test<br>
              会社クチコミで、<br>
              薬剤師により良い選択肢を。
            </h1>
          </div>
  
          <div class="p-top-search-box">
            <form method="get" action="search.php" class="p-top-search-box__form">
              <input class="p-top-search-box__input" type="text" placeholder="企業名で検索" name="src_str" value="<?php if(!empty($_GET['src_str'])) echo $_GET['src_str']; ?>">
              <button class="p-top-search-box__button" type="submit">検索</button>
            </form>
          </div>
        </div>
      </div>
      
      <div class="l-content-wrapper">
        <div class="l-container">
          <section class="p-top-section">
            <div class="p-top-section__about">
              <h2 class="p-top-section__title">はじめよう、薬剤師のキャリア会議</h2>
              <img src="dist/img/4380.jpg" alt="" class="p-top-section__image">
              <span class="p-top-section__subtitle">ヤクカイ - 薬剤師のためのキャリア会議</span>
              <p>ヤクカイは、「働く人の幸福度」という視点を取り入れた薬剤師に特化したクチコミ情報プラットフォームです。</p>
              <p>薬剤師同士が会社クチコミ・仕事経験を共有する場を提供し、就職・転職時のより良い選択を応援します。</p>
            </div>
          </section>
                    
          <section class="p-top-section">
            <h2 class="p-top-section__title">幸福度の高い企業</h2>
            <div class="p-card-slider-wrapper">
              <ul class="p-card-slider">
                <?php foreach($dbCompanyData['rating'] as $key => $val){ ?>
                  <li class="p-card-slider__item">
                      <a href="company.php?c_id=<?php echo $val['id']; ?>">
                        <h3 class="p-card-slider__item-name"><?php echo $val['name']; ?></h3>
                        <span class="heart5_rating p-card-slider__heart" data-rate="<?php echo number_format($val['AVG(ratings.rating)'], 1); ?>"></span>
                        <span class=""><?php echo number_format($val['AVG(ratings.rating)'], 1); ?></span>
                        <span class="p-card-slider__param">クチコミ数：<?php echo $val['COUNT(answers.answer)']; ?>件</span>
                      </a>
                  </li>
                <?php } ?>                
              </ul>
            </div>
          </section>

          <section class="p-top-section">
            <h2 class="p-top-section__title">クチコミの多い企業</h2>
            <div class="p-card-slider-wrapper">
              <ul class="p-card-slider">

                <?php foreach($dbCompanyData['answer'] as $key => $val){ ?>
                  <li class="p-card-slider__item">
                      <a href="company.php?c_id=<?php echo $val['id']; ?>">
                        <h3 class="p-card-slider__item-name"><?php echo $val['name']; ?></h3>
                        <span class="heart5_rating p-card-slider__heart" data-rate="<?php echo number_format($val['AVG(ratings.rating)'], 1); ?>"></span>
                        <span class=""><?php echo number_format($val['AVG(ratings.rating)'], 1); ?></span>
                        <span class="p-card-slider__param">クチコミ数：<?php echo $val['COUNT(answers.answer)']; ?>件</span>
                      </a>
                  </li>
                <?php } ?>
                
              </ul>
            </div>
          </section>

          <section class="p-top-section">
            <h2 class="p-top-section__title">企業名や業種からクチコミを探す</h2>
            <div class="p-search-box">
              <form method="get" action="search.php">
                <div class="p-search-box__item">
                  <input class="p-search-box__textarea" type="text" placeholder="企業名" name="src_str" value="<?php if(!empty($_GET['src_str'])) echo $_GET['src_str']; ?>">
                </div>
                
                <div class="p-search-box__item">
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
                
                <div class="p-search-box__item">
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
                
                <div class="p-search-box__item">
                  <span class="p-search-box__item-name">表示順</span>
                  <div class="cp_ipselect cp_sl01 w-100">
                    <select name="sort">
                      <option value="0" <?php if(getFormData('sort', true) == 0) echo 'selected'; ?>>表示順</option>
                      <option value="1" <?php if(getFormData('sort', true) == 1) echo 'selected'; ?>>クチコミ数</option>
                      <option value="2" <?php if(getFormData('sort', true) == 2) echo 'selected'; ?>>幸福度</option>
                    </select>
                  </div>
                </div>

                <button class="c-button c-button--blue c-button--width100" type="submit">クチコミを検索する</button>
              </form>
            </div>
          </section>

          <section class="p-top-section">
            <h2 class="p-top-section__title">ユーザー登録で全てのクチコミが閲覧可能になります</h2>
            <a href="signup.php" class="c-button c-button--green c-button--radius100">ユーザー登録する（無料）</a>
            <a href="login.php" class="p-top-section__login-link">ログインする</a>
          </section>
        </div>
      </div>
    </main>

    <!-- footer -->
    <?php require('footer.php'); ?>
