<script>
  $(function(){
    // レーダーチャート処理
    var canvas = document.getElementById('header-chart');
  
    if(canvas.getContext){
      var ctx = document.getElementById("header-chart");
      var myRadarChart = new Chart(ctx, {
        type: 'radar', 
        data: { 
          labels: [
            "<?php echo $dbCompanyRatings[1]['name']; ?>：<?php echo number_format($dbCompanyRatings[1]['AVG(rating)'], 1); ?>",
            "<?php echo $dbCompanyRatings[2]['name']; ?>：<?php echo number_format($dbCompanyRatings[2]['AVG(rating)'], 1); ?>",
            "<?php echo $dbCompanyRatings[3]['name']; ?>：<?php echo number_format($dbCompanyRatings[3]['AVG(rating)'], 1); ?>",
            "<?php echo $dbCompanyRatings[4]['name']; ?>：<?php echo number_format($dbCompanyRatings[4]['AVG(rating)'], 1); ?>",
            "<?php echo $dbCompanyRatings[5]['name']; ?>：<?php echo number_format($dbCompanyRatings[5]['AVG(rating)'], 1); ?>",
            "<?php echo $dbCompanyRatings[6]['name']; ?>：<?php echo number_format($dbCompanyRatings[6]['AVG(rating)'], 1); ?>",
          ],
  
          datasets: [{
            label: '<?php echo $dbCompanyData['info']['name']; ?>の評価値',
            data: [
              <?php echo number_format($dbCompanyRatings[1]['AVG(rating)'], 1); ?>,
              <?php echo number_format($dbCompanyRatings[2]['AVG(rating)'], 1); ?>,
              <?php echo number_format($dbCompanyRatings[3]['AVG(rating)'], 1); ?>,
              <?php echo number_format($dbCompanyRatings[4]['AVG(rating)'], 1); ?>,
              <?php echo number_format($dbCompanyRatings[5]['AVG(rating)'], 1); ?>,
              <?php echo number_format($dbCompanyRatings[6]['AVG(rating)'], 1); ?>,
            ],
            backgroundColor: 'RGBA(225,95,150, 0.2)',
            // backgroundColor: '#f88dc8',
            borderColor: 'RGBA(225,95,150, 1)',
            borderWidth: 1,
            pointBackgroundColor: 'RGB(46,106,177,0)',
            // borderCapStyle: 'round',
            pointRadius: 0,
          }, 
          ]
        },
        options: {
          title: {
            display: true,
            text: ''
          },
          scale:{
            min: 0,
            max: 5,
            // display: false,
            ticks:{
              // suggestedMin: 0,
              // suggestedMax: 5,
              stepSize: 1,
              callback: function(value, index, values){
                return  value +  ''
              }
            }
          }
        }
      });
    }
  });
</script>