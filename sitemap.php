<?php
require('config.php');
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「 サイトマップ
');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();
?>

<?php
$siteTitle = 'サイトマップ';
require('head.php');
?>

<body>
  <?php
    require('header.php');
  ?>

  <main>
    <div class="l-content-wrapper">
      <div class="l-container">
        <div class="l-content">
          <div class="l-inner-container">
            <h1 class="u-text-center u-mb-4">サイトマップ</h1>
            <ul>
              <li><a href="index.php">トップページ</a></li>
              <li><a href="search.php">企業検索</a></li>
              <li><a href="signup.php">ユーザー登録</a></li>
              <li><a href="login.php">ログイン</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </main>

  <?php
    require('footer.php');
  ?>