<header class="l-header l-header--bg-theme">
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