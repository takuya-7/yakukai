<?php
require('config.php');
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「 マイページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');

//================================
// 画面処理
//================================

// 画面表示用データ取得
//================================
// ユーザーID取得
$user_id = $_SESSION['user_id'];
// ユーザー情報取得
$dbUserData = getUser($user_id);
// ユーザーのクチコミ投稿情報取得
$dbAnswerData = getAnswer($user_id);

debug('取得したユーザー情報：' . print_r($dbUserData, true));
?>

<?php
$siteTitle = 'マイページ';
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
        <div class="row">
          <h1 class="c-page-title">マイページ</h1>

          <!-- <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
              <a class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">プロフィール</a>
              <a class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">アカウント管理</a>
            </div>
          </nav> -->
          <!-- <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab"> -->
              <div class="profile-container col-md-8">
                
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
                
                <h2>あなたのプロフィール</h2>
                <a href="profRegist.php" class="c-edit-button">編集する</a>
                <div class="p-profile">
                  <div>
                    <div class="p-profile__field">
                      <span>性別</span>
                      <p><?php echo SEX[$dbUserData['sex']]; ?></p>
                    </div>
    
                    <div class="p-profile__field">
                      <span>現住所</span>
                      <p><?php echo PREF[$dbUserData['addr']]; ?></p>
                    </div>
    
                    <div class="p-profile__field">
                      <span>生まれ年</span>
                      <p><?php echo $dbUserData['birth_year'].'年'; ?></p>
                    </div>
  
                    <div class="p-profile__field">
                      <span>薬剤師免許</span>
                      <p><?php echo ($dbUserData['ph_license']) ? '保有' : '無し'; ?></p>
                    </div>
    
                    <div class="p-profile__field">
                      <span>キャリア状況</span>
                      <p><?php echo CARRI_TYPE[$dbUserData['carrier_type']]; ?></p>
                    </div>
    
                    <div class="<?php if(!$dbUserData['carrier_type']) echo 'student-hidden'; ?>">
                      <div class="p-profile__field">
                        <span>ご経験</span>
                        <p><?php if(!empty($dbUserData['i_name'])) echo $dbUserData['i_name'].'を'.$dbUserData['ex_year'].'年経験'; ?></p>
                      </div>
      
                      <div class="p-profile__field">
                        <span>雇用状況</span>
                        <p><?php if(!empty($dbUserData['e_name'])) echo $dbUserData['e_name']; ?></p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <!-- </div> -->
            <!-- <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab"> -->
              <div class="menus-container col-md-4">
                <h2>個人設定</h2>
                <ul class="u-bg-white">
                  <li><a href="emailEdit.php">メールアドレス変更</a></li>
                  <li><a href="passEdit.php">パスワード変更</a></li>
                </ul>
                <h2>ユーザー情報</h2>
                <ul class="u-bg-white">
                  <li><a href="logout.php">ログアウト</a></li>
                  <li><a href="withdraw.php">退会</a></li>
                </ul>
              </div>
            <!-- </div> -->
          </div>
        </div>
      </div>
    </div>
  </main>
    

  <?php
    require('footer.php');
  ?>