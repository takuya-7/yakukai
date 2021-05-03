<?php
require('config.php');
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「 クチコミ投稿について');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');

//================================
// 画面処理
//================================
$u_id = $_SESSION['user_id'];
$dbPostData = getPost($u_id);
$dbRatingData = getRatingByUserId($_SESSION['user_id']);
$dbAnswerData = getAnswer($_SESSION['user_id']);

// 画面遷移処理
if(empty($dbPostData[0]['company_id'])){
  debug('会社登録がありません。画面遷移を行いません。');
}elseif(empty($dbPostData[0]['employment_type'])){
  debug('クチコミ登録がありません。クチコミ投稿ページ（1/3）へ遷移します。');
  header('Location:survey01.php');
  exit();
}elseif(empty($dbRatingData[0]['rating'])){
  debug('評価値が投稿されていません。クチコミ投稿ページ（2/3）へ遷移します。');
  header('Location:survey02.php');
  exit();
}elseif(empty($dbAnswerData[0]['answer'])){
  debug('フリー回答がありません。クチコミ投稿ページ（3/3）へ遷移します。');
  header('Location:survey03.php');
  exit();
}else{
  debug('クチコミ情報が全て登録されているためマイページへ遷移します。');
  header('Location:mypage.php');
  exit();
}

// 画面表示用データ取得
//================================
// GETパラメータを取得
//----------------------------
if(!empty($_GET)){
  // 企業名
  $companyName = (!empty($_GET['src_str'])) ? $_GET['src_str'] : '';
  // DBから企業データ（total:企業総数、total_page:総ページ数、data:表示する企業データ）を取得
  $dbCompanyData = getCompanyList(0, $companyName, NULL, NULL, NULL, 20);
}

// POSTパラメーター処理
if(!empty($_POST)){
  debug('POST送信があります。');
  debug('POST情報：' . print_r($_POST, true));
  // ユーザーがクチコミレコード作成しており、company_idにデータがなければレコードを削除
  if(empty($dbPostData[0]['company_id'])){
    debug('クチコミが完成していないレコードを削除します。');
    try{
      $dbh = dbConnect();
      $sql = 'DELETE FROM posts WHERE user_id = :user_id AND company_id IS NULL LIMIT 1';
      $data = array(':user_id' => $u_id);
      $stmt = queryPost($dbh, $sql, $data);
      if($stmt){
        debug('不要のクチコミレコードが１件削除されました。');
      }
    } catch (Exeption $e) {
      error_log('エラー発生：' . $e->getMessage());
    }
  }

  // 新たにINSERT
  $c_id = $_POST['c_id'];
  try{
    $dbh = dbConnect();
    $sql = 'INSERT INTO posts (company_id, user_id, create_date) VALUES (:company_id, :user_id, :create_date)';
    $data = array(
      ':company_id' => $c_id,
      ':user_id' => $u_id,
      ':create_date' => date('Y-m-d H:i:s'),
    );
    $stmt = queryPost($dbh, $sql, $data);

    if($stmt){
      debug('クチコミレコードを作成し、ユーザーIDと企業IDを登録しました！');
      header('Location:survey01.php');
      exit();
    }
  } catch (Exeption $e) {
    error_log('エラー発生：' . $e->getMessage());
  }
}

?>

<?php
$siteTitle = 'クチコミ投稿について';
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
        <div class="bg-white pt-3">
          <h1 class="page-title mb-4">クチコミ投稿について</h1>
    
          <p class="mb-4">クチコミを投稿していただくと、半年間クチコミを読み放題になります。</p>
    
          <div class="border mx-2 p-1">
            <h2 class="mb-2">確認事項</h2>
            <ul class="mb-4">
              <li>個人情報に関しては適切に収集、管理し、法律で定められた場合や裁判所からの開示命令を除き、投稿者の同意なく第三者に個人情報を開示することはございません。</li>
              <li>投稿内容についてはガイドラインに遵守していただくようお願いいたします。</li>
            </ul>
          </div>

          <div class="py-4">
            <h2>在籍中または在籍していた企業を教えてください。</h2>
            <form action="" method="get" class="search-box">
              <input type="text" name="src_str" class="" placeholder="会社名を入力してください">
              <button class="btn-blue" type="submit">検索</button>
            </form>

            <?php if(!empty($_GET)){ ?>
              <?php if($dbCompanyData['total'] >= 20){ ?>
                <p>検索結果が20件以上あります。再度検索し直してください。</p>

              <?php }else{ ?>
              
                <div class="search-company-list">
                  <h3>検索結果</h3>
                  <p>該当の会社を選択してください</p>
                  <ul>
                    <?php foreach($dbCompanyData['data'] as $key => $val){ ?>
                      <li>
                        <form action="" method="post">
                          <input type="hidden" name="c_id" value="<?php echo $val['id']; ?>">
                          <button type="submit">
                            <?php echo sanitize($val['name']); ?>
                            <br>
                            <span>本社所在地：<?php echo sanitize($val['prefecture_name'].$val['city_name']); ?></span>
                          </button>
                        </form>
                      </li>
                    <?php } ?>
                  </ul>
                </div>
              <?php } ?>
            <?php } ?>
          </div>
        </div>
        
      </div>
    </div>
  </main>

  <?php
    require('footer.php');
  ?>