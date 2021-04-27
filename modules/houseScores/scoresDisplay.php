<?php // Processing the data for display
  $houseScores = sheetToArray('1ooT12BlAoiaEJD-cqukrfJ8-Oyxs6AI5qgo7hVudcVw','data/content/',24);
  $totals = array('foxell' => 0,'holman' => 0,'newman' => 0,'pearson' => 0,'rayner' => 0,'thorne' => 0);
  $trends = array();
  $houseScores['data']['Scores'] = array_reverse($houseScores['data']['Current Scores']);
  foreach ($houseScores['data']['Scores'] as $event) {
    if (preg_match('/[0-9.]+/',$event['foxell'])) {
      foreach ($event as $house => $score) {
        if (isset($totals[$house])) {
          $totals[$house] = $totals[$house]+$score;
        }
      }
        // The following sequence gets the data to put into the trendline chart
        $average = 0;
        foreach ($totals as $score) {
          $average = $average+$score;

        }
        $average = $average/6;
        $absolute = array();
        foreach ($totals as $house => $score) {      
          $absolute[$house] = abs($score-$average);
          //
        }
        $absolute = max($absolute);
        $trendPoints = "['',foxell,holman,newman,pearson,rayner,thorne]";
        foreach ($totals as $house => $score) {
          $trendPoint  = round((($score-$average)/$absolute)*100);
          $trendPoints = str_replace($house,$trendPoint,$trendPoints);
        }
        $trends[] = $trendPoints;
    }
  }
?>

<ul class="nav nav-pills" role="tablist">
  <li role="presentation"><a href="#trends" aria-controls="trends" role="tab" data-toggle="tab">Trends</a></li>
  <li role="presentation" class="active"><a href="#totals" aria-controls="totals" role="tab" data-toggle="tab">Totals</a></li>
</ul>
<div class="tab-content houseDisplay">
  <div role="tabpanel" class="tab-pane fade in active" id="totals">
  <?php
    $biggest = max($totals['foxell'],$totals['holman'],$totals['newman'],$totals['pearson'],$totals['rayner'],$totals['thorne']); // Finds the highest score, to make this full width
    foreach ($totals as $house => $total) {
      $width = floor(100*$total/$biggest);
      echo '<div class="progress houseTotals';
        if ($total == $biggest) { echo ' leader'; } // Mark out the house that's currently leading
      echo '">';
        echo '<div class="progress-bar" id="'.$house.'" role="progressbar"';
        echo ' aria-valuenow="'.$width.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$width.'%;">';
          echo ucwords($house).' <span>'.$total.'</span>';
        echo '</div>';
      echo '</div>';
    }
  ?>
  </div>
  <div role="tabpanel" class="tab-pane fade" id="trends">
    <div id="houseTrends"></div>
    <div class="row houseKey">
      <div class="col-xs-2" id="foxell"><p>Foxell</p></div>
      <div class="col-xs-2" id="holman"><p>Holman</p></div>
      <div class="col-xs-2" id="newman"><p>Newman</p></div>
      <div class="col-xs-2" id="pearson"><p>Pearson</p></div>
      <div class="col-xs-2" id="rayner"><p>Rayner</p></div>
      <div class="col-xs-2" id="thorne"><p>Thorne</p></div>
      <div class="col-xs-12">
        <p><em>Shown are comparative total scores for houses as the year has progressed.</em></p>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
  google.charts.load('current', {packages: ['corechart', 'line']});
  google.charts.setOnLoadCallback(drawCurveTypes);

  function drawCurveTypes() {
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'X');
    data.addColumn('number', 'Foxell');
    data.addColumn('number', 'Holman');
    data.addColumn('number', 'Newman');
    data.addColumn('number', 'Pearson');
    data.addColumn('number', 'Rayner');
    data.addColumn('number', 'Thorne');

    data.addRows([
      <?php echo implode(",",$trends); ?>
    ]);

    var options = {
      lineWidth: 5,
      height: 150,
      chartArea: {
        width: '100%',
        height: '100%'
      },
      vAxis: {
        viewWindow: { min: -110, max: 110 },
        gridlines: { color: '#fff' },
        baselineColor: '#fff'
      },
      series: {
        0: {color: '#fac800'},
        1: {color: '#e87b19'},
        2: {color: '#d32918'},
        3: {color: '#96c4f8'},
        4: {color: '#2542a1'},
        5: {color: '#247703'}
      },
      enableInteractivity: false
    };

    var chart = new google.visualization.LineChart(document.getElementById('houseTrends'));
    chart.draw(data, options);
  }
</script>

<h2>Scores Breakdown</h2>

<div class="panel-group" id="houseBreakdown" role="tablist" aria-multiselectable="true">
<?php // Relies on the scores array having been created above for the total scores
  $houseScores['data']['Scores'] = array_reverse($houseScores['data']['Scores']);
  //view($houseScores['data']['Scores']);
  foreach ($houseScores['data']['Scores'] as $event) {
    if (strtolower($event['event']) == 'new term') {
      if (isset($eventTable)) { echo '</table></div></div></div>'; }
      echo '<div class="panel">';
      echo '<div class="panel-heading" role="tab" id="heading-'.clean($event['foxell']).'">';
      echo '<h4 class="panel-title">';
      echo '<a class="collapsed" role="button" data-toggle="collapse" data-parent="#houseBreakdown" href="#collapse-'.clean($event['foxell']).'" aria-expanded="false" aria-controls="collapse-'.clean($event['foxell']).'">';
      echo $event['foxell'].'</a></h4></div>';
      echo '<div id="collapse-'.clean($event['foxell']).'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-'.clean($event['foxell']).'">';
      echo '<div class="panel-body">';
      echo '<table class="table table-striped table-condensed">';
      echo '<thead><tr><th><h4>Event</h4></th><th><h4 class="place">1st place</h4></th><th><h4 class="place">2nd place</h4></th><th><h4 class="place">3rd place</h4></th></tr></thead>';
        $eventTable = 1;
    }
    if (preg_match('/[0-9.]+/',$event['foxell'])) { // Skips lines that don't have scores in them
      echo '<tr>';
        echo '<td><p>'.$event['event'].'</p></td>';
        // This process sequentially pulls out highest scoring houses, then second and third highest. It also accounts for tied positions
        array_shift($event);
        for ($place = 1; $place <= 3; $place++) {
          $position = array_keys($event, max($event));
          foreach ($position as $house) {
            unset($event[$house]);
          }
          echo '<td>';
          foreach ($position as $house) {
            echo '<p class="place" id="'.$house.'">'.ucwords($house).'</p>';
          }
          echo '</td>';
        }
      echo '</tr>';
      }
  }
  echo '</table></div></div></div>'; // Ends the last table of scores
?>
</div>