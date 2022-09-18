<?php
  $houseScores = sheetToArray('1ooT12BlAoiaEJD-cqukrfJ8-Oyxs6AI5qgo7hVudcVw','data/content/',24);
  $current = $houseScores['data']['Roll of Honour'][2];
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
    <p><?php echo $current['outgoing-captains']; ?></p>
    <p><?php echo $current['incoming-captains']; ?></p>
  </div>
</div>

<h2>Previous Years</h2>

<?php
//view($houseScores);  
$pastScores = $houseScores['data']['Roll of Honour'];
  unset ($pastScores[2]);
  foreach ($pastScores as $year) {
    echo '<div class="row honourRoll">';
      echo '<div class="col-xs-8">';
      echo '<h3 id="'.strtolower($year['first']).'">'.$year['year'].' Champions: '.$year['first'].'</h3>';
        echo '<p>Captains:</p>';
        echo '<p>'.$year['outgoing-captains'].'</p>';
        echo '<p>'.$year['incoming-captains'].'</p>';
      echo '</div>';
      echo '<div class="col-xs-4">';
        echo '<h3 id="'.strtolower($year['second']).'">2nd: '.$year['second'].'</h3>';
        echo '<h3 id="'.strtolower($year['third']).'">3rd: '.$year['third'].'</h3>';
      echo '</div>';
    echo '</div>';
  }
?>
<!--</table>-->