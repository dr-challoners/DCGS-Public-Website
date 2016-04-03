<?php
  $houseScores = sheetToArray('1ooT12BlAoiaEJD-cqukrfJ8-Oyxs6AI5qgo7hVudcVw','data/content/',24);
  $current = $houseScores['data']['Past winners'][2];
?>

<div class="row podium">
  <div class="col-xs-3 second">
    <p><?php echo $current['second']; ?></p>
    <h1 id="<?php echo strtolower($current['second']); ?>">2</h1>
  </div>
  <div class="col-xs-3 first">
    <p><?php echo $current['first']; ?></p>
    <h1 id="<?php echo strtolower($current['first']); ?>">1</h1>
  </div>
  <div class="col-xs-3 third">
    <p><?php echo $current['third']; ?></p>
    <h1 id="<?php echo strtolower($current['third']); ?>">3</h1>
  </div>
</div>
<div class="row champions">
  <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
    <h1><?php echo $current['year']; ?> Champions</h1>
    <h3>Captains:</h3>
    <p><?php echo $current['outgoingcaptains']; ?></p>
    <p><?php echo $current['incomingcaptains']; ?></p>
  </div>
</div>

<h2>Previous years</h2>

<?php
  $pastScores = $houseScores['data']['Past winners'];
  unset ($pastScores[2]);
  foreach ($pastScores as $year) {
    echo '<div class="row honourRoll">';
      echo '<div class="col-xs-8">';
      echo '<h3 id="'.strtolower($year['first']).'">'.$year['year'].' Champions: '.$year['first'].'</h3>';
        echo '<p>Captains:</p>';
        echo '<p>'.$year['outgoingcaptains'].'</p>';
        echo '<p>'.$year['incomingcaptains'].'</p>';
      echo '</div>';
      echo '<div class="col-xs-4">';
        echo '<h3 id="'.strtolower($year['second']).'">2nd: '.$year['second'].'</h3>';
        echo '<h3 id="'.strtolower($year['third']).'">3rd: '.$year['third'].'</h3>';
      echo '</div>';
    echo '</div>';
  }
?>

<h2>Overall ranking</h2>
<table class="table overallRanks">
  <thead>
    <tr>
      <th></th>
      <th></th>
      <th>Champions</th>
      <th>2nd place</th>
      <th>3rd place</th>
    </tr>
  </thead>
  <?php
    $overall = array();
    foreach(array('foxell','holman','newman','pearson','rayner','thorne') as $house) {
      $overall[$house] = array('score' => 0, 'champions' => 0, 'second' => 0, 'third' => 0);
    }
    foreach($houseScores['data']['Past winners'] as $year) {
      $overall[clean($year['first'])]['score'] = $overall[clean($year['first'])]['score']+3;
      $overall[clean($year['first'])]['champions']++;
      $overall[clean($year['second'])]['second']++;
      $overall[clean($year['second'])]['score'] = $overall[clean($year['second'])]['score']+2;
      $overall[clean($year['third'])]['third']++;
      $overall[clean($year['third'])]['score']++;
    }
    function compareScores($a, $b) {
      return $a['score'] - $b['score'];
    }
    uasort($overall, 'compareScores');
    $overall = array_reverse($overall);
    $positions = array('1st','2nd','3rd','4th','5th','6th');
    $score = false;
    foreach ($overall as $house => $data) {
      $position = array_shift($positions);
      echo '<tr id="'.$house.'">';
        echo '<td>';
          if ($data['score'] !== $score) {
          echo '<p>'.$position.'</p>';
          } else {
            echo '<p>=</p>';
          }
          $score = $data['score'];
        echo '</td>';
        echo '<td>';
          echo '<p>'.ucwords($house).'</p>';
        echo '</td>';
        echo '<td>';
          echo '<p>'.$data['champions'].'</p>';
        echo '</td>';
        echo '<td>';
          echo '<p>'.$data['second'].'</p>';
        echo '</td>';
        echo '<td>';
          echo '<p>'.$data['third'].'</p>';
        echo '</td>';
      echo '</tr>';
    }
  ?>
</table>