    <footer id="js-footer" class="l-footer">
      
      <ul class="l-container">
        <li><a href="index.php">HOME</a></li>
        <li><a href="">ご利用案内</a></li>
        <li><a href="">プライバシーポリシー</a></li>
        <li><a href="">サイトマップ</a></li>
        <li><a href="">お問い合わせ</a></li>
      </ul>

      <span class="l-footer__copyright">
        Copyright © ヤクカイ. All Rights Reserved.
      </span>
    </footer>
    
    <!-- jQuery読み込み -->
    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <!-- Propper.js読み込み -->
    <script src="node_modules/popper.js/dist/popper.min.js"></script>
    <!-- Bootstrapのjs読み込み -->
    <script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Chart.js読み込み -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.1.0/chart.min.js" integrity="sha512-RGbSeD/jDcZBWNsI1VCvdjcDULuSfWTtIva2ek5FtteXeSjLfXac4kqkDRHVGf1TwsXCAqPTF7/EYITD0/CTqw==" crossorigin="anonymous"></script>
    <!-- bundle.js読み込み -->
    <script src="dist/js/bundle.js"></script>

    <script>
      $(function(){
        // フッターを最下部に固定
        var $ftr = $('#js-footer');
        if( window.innerHeight > $ftr.offset().top + $ftr.outerHeight() ){
            //ウインドウ内コンテンツ部分の高さ　＞　普通に表示した時のコンテンツ部分左上からフッターまで高さ + フッターのボーダー外側の高さ
          $ftr.attr({
              'style': 
                'position:fixed; top:' + (window.innerHeight - $ftr.outerHeight()) +'px;'
            });
        }

        // ----------------------------------------------------------
        // メッセージ表示
        var $jsShowMsg = $('#js-show-msg');   // DOMを入れる変数には先頭に$をつける
        var msg = $jsShowMsg.text();

        if(msg.replace(/^[\s　]+|[\s　]+$/g, "").length){   // スペースを取り除いて文字が入っていることを確認できれば

          // スライドさせて表示させる
          $jsShowMsg.slideToggle('slow');

          // ５秒後にスライドさせて非表示にする
          setTimeout(function(){
            $jsShowMsg.slideToggle('slow');
          }, 5000);
        }

        // ----------------------------------------------------------
        // survey03.php、クチコミ投稿、テキストエリアカウント
        var totalCount = 0;
        var $countUp = $('.js-character-count');
        var $countView = $('.js-count-view');
        $countUp.on('keyup', function(e){
          totalCount = 0;
          for(let i = 0; i < <?php echo (!empty($dbAnswerItems)) ? count($dbAnswerItems) : 1; ?>; i++){
            $count = $('#js-count'+i);
            totalCount += $count.val().length;
          }
          if(totalCount >= 500){
            $countView.addClass('text-green');
            // 送信ボタンの有効化
            $('.js-disabled-submit').prop('disabled', false);
          }else{
            $countView.removeClass('text-green');
            // 送信ボタンの無効化
            $('.js-disabled-submit').prop('disabled', true);
          }
          $countView.html(totalCount);
        });

        // ------------------------------------------------------
        // 画像切り替え
        var $switchImgSubs = $('.js-switch-img-sub'),
            $switchImgMain = $('#js-switch-img-main');

        $switchImgSubs.on('click', function(e){
          $switchImgMain.attr('src', $(this).attr('src'));
        });

        // ------------------------------------------------------
        // お気に入り登録・削除
        var $like,
            likeProductId;

        // DOMが取れないと$likeは自動的にundefinedとなるため、DOMが取れない時はnullを入れておく
        $like = $('.js-click-like') || null;
        likeProductId = $like.data('productid') || null;

        if(likeProductId !== undefined && likeProductId !== null){

          $like.on('click',function(){
            var $this = $(this);

            // ajax処理？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？
            $.ajax({
              type: "POST",
              url: "ajaxFavorite.php",
              data: {productId : likeProductId}
            }).done(function( data ){
              console.log('Ajax Success');
              $this.toggleClass('active');
            }).fail(function( msg ){
              console.log('Ajax Error');
            });
          });
        }

        // プロフィール登録、社会人用フォーム表示、非表示処理
        $('[name="user_carrier_type"]:radio').change(function(){
          if($('[id=user_carrier_type_1]').prop('checked')){
            $('.js-worker-form').show();
          }else if($('[id=user_carrier_type_0]').prop('checked')) {
            $('.js-worker-form').hide();
          } 
        });

        // survey02.php、survey-list選択時のスタイル変更
        $('.survey-list input[type="radio"]').change(function(){
          $(this).parents('.survey-list').find('label').removeClass('checked');
          $(this).parent().addClass('checked');
        });

        // survey03.php、合計文字数500字以上のときにボタンを有効化する

      });
    </script>
  </body>
</html>