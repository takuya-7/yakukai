    <footer id="footer">
      
      <ul class="container">
        <li><a href="index.php">HOME</a></li>
        <li><a href="">ご利用案内</a></li>
        <li><a href="">プライバシーポリシー</a></li>
        <li><a href="">サイトマップ</a></li>
        <li><a href="">お問い合わせ</a></li>
      </ul>

      <span class="copyright">
        Copyright © ヤクカイ. All Rights Reserved.
      </span>
    </footer>
    
    <!-- jQuery読み込み -->
    <script src="js/vender/jquery-3.5.1.min.js"></script>
    <!-- Chart.js読み込み -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.1.0/chart.min.js" integrity="sha512-RGbSeD/jDcZBWNsI1VCvdjcDULuSfWTtIva2ek5FtteXeSjLfXac4kqkDRHVGf1TwsXCAqPTF7/EYITD0/CTqw==" crossorigin="anonymous"></script>
    <!-- 自作のmain.jsを読み込み -->
    <script src="js/main.js"></script>

    <script>
      $(function(){
        // フッターを最下部に固定
        var $ftr = $('#footer');
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

        // ---------------------------------------------------------
        // 画像ライブビュー
        var $dropArea = $('.area-drop');
        var $fileInput = $('.input-file');

        // 画像をドラッグしてarea-dropに乗せたとき、破線の枠を表示させる
        $dropArea.on('dragover', function(e){
          e.stopPropagation();
          e.preventDefault();
          $(this).css('border', '3px #ccc dashed');
        });

        // area-dropからドラッグを離したとき、枠線を消す
        $dropArea.on('dragleave', function(e){
          e.stopPropagation();
          e.preventDefault();
          $(this).css('border', 'none');
        });

        // input-fileの中にファイルが入った（changeした）とき、prev-imgのsrcに画像をセットする
        $fileInput.on('change', function(e){
          $dropArea.css('border', 'none');  // とりあえず線を消す

          var file = this.files[0],     // files配列にファイルが入っている
              $img = $(this).siblings('.prev-img'),   // 兄弟要素でprev-imgのclassを持つimgのDOMを取得
              fileReader = new FileReader();    // ファイルを読み込むFileReaderオブジェクトを生成

          // 読み込み完了後、読み込んだデータをimgのsrcにセット
          fileReader.onload = function(event){                // onload？？？？？？？？？？？？？？？？？？？？
            $img.attr('src', event.target.result).show();     // event.target.result？？？？？？？？？？？？
          }

          // dataURLとして読み込み
          fileReader.readAsDataURL(file);
        });

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
            $('.worker-form').fadeIn();
          }else if($('[id=user_carrier_type_0]').prop('checked')) {
            $('.worker-form').fadeOut();
          } 
        });

        // survey-list選択時のスタイル変更
        $('.survey-list input[type="radio"]').change(function(){
          $(this).parents('.survey-list').find('label').removeClass('checked');
          $(this).parent().addClass('checked');
        });

        // survey03.php、合計文字数500字以上のときにボタンを有効化する

      });
    </script>
  </body>
</html>