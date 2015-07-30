<?php
  
  include('header_declarations.php');
  echo '</head>';
  echo '<body>';

?>

<style>
</style>

<?php

  echo '<img src="/styles/imgs/logoCrest.png" />';

  $caches = scandir('data_diary/', SCANDIR_SORT_DESCENDING);
  foreach ($caches as $file) {
    if (strpos($file,'generalData') !== false) {
      $diaryArray = file_get_contents('data_diary/'.$file);
      $diaryArray = json_decode($diaryArray, true);
      break;
    }
  }
  
  $date = $_GET['date'];
  $eventID = $_GET['eventID'];
  $matchData = $diaryArray[$date][$eventID];
  
  echo '<pre>';
  print_r($matchData);
  echo '</pre>';

?>