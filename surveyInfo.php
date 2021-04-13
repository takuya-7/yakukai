<?php
require('config.php');
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「 クチコミ投稿について');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');

// 画面遷移処理!!!!!!!!未実装!!!!!!!!!!!!!!!!!!!!!!!!!!
  // $dbPostData = getPost($result['id']);
  // if(empty($dbPostData[0]['emp_type'])){
  //   header('Location:survey01.php');
  //   exit();
  // }elseif(empty($dbPostData[0]['q_001'])){
  //   header('Location:survey02.php');
  //   exit();
  // }elseif(empty($dbPostData[0]['c_001'])){
  //   header('Location:survey03.php');
  //   exit();
  // }else{
  //   debug('マイページへ遷移します。');
  //   header('Location:mypage.php');
  //   exit();
  // }

//================================
// 画面処理
//================================

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

  $c_id = $_POST['c_id'];
  $u_id = $_SESSION['user_id'];

  // クチコミレコード新規作成
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
        <div class="bg-white">
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
                    <form action="" method="post">
                      <?php foreach($dbCompanyData['data'] as $key => $val){ ?>
                        <li>
                          <input type="hidden" name="c_id" value="<?php echo $val['id']; ?>">
                          <button type="submit">
                            <?php echo sanitize($val['name']); ?>
                            <br>
                            <span>本社所在地：<?php echo sanitize($val['prefecture_name'].$val['city_name']); ?></span>
                          </button>
                        </li>
                      <?php } ?>
                    </form>
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