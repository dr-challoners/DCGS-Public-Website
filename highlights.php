<?php
if ((!isset($override) && rand(1,10) >= 7) || isset($_GET['highlight']) || isset($_GET['sync'])) {
  $highlightData = sheetToArray('1A2ZDoOM57fcVAADHgU9aUf1G9IAIGkz3wWXBiazYPoE','data/highlights');
  if (isset($_GET['highlight'])) {
    $highlightData = $highlightData['data']['Highlights'][$_GET['highlight']];
  } else {
    $highlightData = $highlightData['data']['Highlights'];
    shuffle($highlightData);
    $highlightData = array_shift($highlightData);
  }
  if (!isset($section)) {
    $highlightBkgd = 'Banner';
  } else {
    $highlightBkgd = 'Column';
  }
  $highlightBkgd .= rand(1,5);

  echo '<div class="row highlightBox">';
  echo '<div class="col-xs-12">';
  echo '<a style="background-image:url(/img/highlight/highlight'.$highlightBkgd.'.jpg)" ';
  if (substr($highlightData['url'],0,4) == 'http') {
    echo 'target="'.mt_rand().'"';
  }
  echo ' href="'.$highlightData['url'].'">'.$highlightData['strapline'];
  if (!isset($section)) {
  echo ' <i class="far fa-arrow-alt-circle-right"></i>';
  }
  echo '</a>';
  echo '</div>';
  echo '</div>';
}
?>