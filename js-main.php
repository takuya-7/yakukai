<script>
  $(function(){
    // ----------------------------------------------------------
    // SPメニュー
    $('.js-toggle-sp-menu').on('click', function () {
      $(this).toggleClass('active');
      $('.js-toggle-sp-menu-target').toggleClass('active');
    });
    
    // ----------------------------------------------------------
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
    var $jsShowMsg = $('#js-show-msg');
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
        $countView.addClass('u-text-green');
        // 送信ボタンの有効化
        $('.js-disabled-submit').prop('disabled', false);
      }else{
        $countView.removeClass('u-text-green');
        // 送信ボタンの無効化
        $('.js-disabled-submit').prop('disabled', true);
      }
      $countView.html(totalCount);
    });

    // ----------------------------------------------------------
    // プロフィール登録、社会人用フォーム表示、非表示処理
    $('[name="user_carrier_type"]:radio').change(function(){
      if($('[id=user_carrier_type_1]').prop('checked')){
        $('.js-worker-form').show();
      }else if($('[id=user_carrier_type_0]').prop('checked')) {
        $('.js-worker-form').hide();
      } 
    });

    // ----------------------------------------------------------
    // survey02.php、survey-list選択時のスタイル変更
    $('.survey-list input[type="radio"]').change(function(){
      $(this).parents('.survey-list').find('label').removeClass('checked');
      $(this).parent().addClass('checked');
    });

    // ----------------------------------------------------------
    // グッド登録・削除
    var $good,
        goodAnswerId;
    $good = $('.js-click-good') || null;
    $good.on('click',function(){
      var $this = $(this);
      var $goodCount = $this.next('.js-good-count');
      var goodCount = Number($goodCount.html());
      if($this.hasClass('is-good-active')){
        $goodCount.html(goodCount-1);
      }else{
        $goodCount.html(goodCount+1);
      }
      goodAnswerId = $this.data('answer_id') || null;
      // DOMが取れないと$goodは自動的にundefinedとなるため、DOMが取れない時はnullを入れておく
      if(goodAnswerId !== undefined && goodAnswerId !== null){
        // ajax処理
        $.ajax({
          type: "POST",
          url: "ajaxGood.php",
          data: {answer_id : goodAnswerId}
        }).done(function( data ){
          console.log('Ajax Success');
          $this.toggleClass('is-good-active');
        }).fail(function( msg ){
          console.log('Ajax Error');
        });
      }
    });
  });
</script>