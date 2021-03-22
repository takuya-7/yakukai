<header class="header-bg">
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