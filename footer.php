    <footer id="js-footer" class="l-footer">
      
      <div class="l-container">
        <div class="l-footer-container">
          <ul>
            <li><a href="index.php">HOME</a></li>
            <li><a href="privacy.php">プライバシーポリシー</a></li>
            <li><a href="sitemap.php">サイトマップ</a></li>
            <li><a href="contact.php">お問い合わせ</a></li>
          </ul>
        </div>
      </div>

      <span class="l-footer__copyright">
        Copyright © ヤクカイ. All Rights Reserved.
      </span>
    </footer>
    
    <!-- jQuery読み込み -->
    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <!-- Propper.js読み込み -->
    <!-- <script src="node_modules/popper.js/dist/popper.min.js"></script> -->
    <!-- Bootstrapのjs読み込み -->
    <script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Chart.js読み込み -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.1.0/chart.min.js" integrity="sha512-RGbSeD/jDcZBWNsI1VCvdjcDULuSfWTtIva2ek5FtteXeSjLfXac4kqkDRHVGf1TwsXCAqPTF7/EYITD0/CTqw==" crossorigin="anonymous"></script>
    <!-- js-main.php読み込み -->
    <?php require('js-main.php'); ?>
    <!-- 自身のファイルがcompany.phpもしくはcategory.phpだった場合（$dbCompanyRatingsの中身があるとき）はjs-rader-chart.php読み込み -->
    <?php
      if(isset($dbCompanyRatings)){
        require('js-rader-chart.php');
      }
    ?>

    </div>
  </body>
</html>