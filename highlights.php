<?php
if ((!isset($override) && rand(1,10) >= 8) || isset($_GET['highlight']) || isset($_GET['sync'])) {
  $highlightData = sheetToArray('1A2ZDoOM57fcVAADHgU9aUf1G9IAIGkz3wWXBiazYPoE','data/highlights');
  if (isset($_GET['highlight'])) {
    $highlightData = $highlightData['data']['Highlights'][$_GET['highlight']];
  } else {
    $highlightData = $highlightData['data']['Highlights'];
    shuffle($highlightData);
    $highlightData = array_shift($highlightData);
  }
  
  $hlBkgds = array (
    '97CC04,2D7DD2',
    '0BAB64,3BB78F',
    'F5D020,F53803',
    '864BA2,FC5296',
    'AD1DEB,6E72FC',
    'EE9617,FE5858',
    '26F596,0499F2',
    'F08EFC,EE5166'
  );
  shuffle($hlBkgds);
  
  $hlBkgd  = array_shift($hlBkgds);
  $hlBkgd  = explode(',',$hlBkgd);
  $hlBkgd1 = $hlBkgd[0];
  $hlBkgd2 = $hlBkgd[1];
  
  echo '<div class="row highlightBox">';
  echo '<div class="col-xs-12">';
  echo '<a style="background-color: #'.$hlBkgd1.'; background-image: linear-gradient(315deg, #'.$hlBkgd1.' 0%, #'.$hlBkgd2.' 74%);" ';
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