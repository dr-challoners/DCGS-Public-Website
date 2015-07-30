<?php
  
  include('header_declarations.php');
  echo '</head><body>';

?>

<style>
  
  img { width: 100%; }
  
  h1,h2 { font-family: Cambria, "Times New Roman", serif; }
  h1 { margin: 0.5em 0 0.2em 0; }
  h2 {
    margin: 0.8em 0 0.4em 0;
    border-top: 1px solid #aaaaaa;
    padding-top: 0.5em;
  }
  
  p {
    font-family: Arial, sans-serif;
    margin: 0;
  }
  p.date {
    float: left; clear: left;
    font-weight: bold;
  }
  p.event { margin-left: 11em; }

  div.day { margin-bottom: 0.5em;}

  p.printLink {
    font-size: 150%; font-weight: bold;
    margin: 0.6em 0 0.6em 0.5em;
  }
  p.printLink a { text-decoration: none; }
  
  @media print {
    p.printLink { display: none; }
  }
  
  div.columns {
    -webkit-column-count: 2; /* Chrome, Safari, Opera */
    -moz-column-count: 2; /* Firefox */
    column-count: 2;

    -webkit-column-gap: 3em; /* Chrome, Safari, Opera */
    -moz-column-gap: 3em; /* Firefox */
    column-gap: 3em;
  }
  
</style>

<?php

  // We need to find the correct place to start listing events
  $y = date('Y',time());
  $m = date('m',time());
  if ($m > 7 && $m <= 12) { // From August onwards we start looking at the new year
    $startY = $y; $endY = $y+1;
  } else { $startY = $y-1; $endY = $y; }

  echo '<img src="/styles/imgs/logo_lrg.png" alt="Dr Challoner\'s Grammar School" />';
  echo '<h1>Events for September '.$startY.' to July '.$endY.'</h1>';
  echo '<p class="printLink"><a href="javascript:window.print()">&#10151; Print</a></p>';
  echo '<p>Warning: subject to changes and additions over the course of the year.</p>';
  if ($m == 7) { echo '<p>Next year\'s calendar will be available from August.</p>'; }

  $start = $startY.'0901'; $end = $endY.'0731';

  $caches = scandir('data_diary/', SCANDIR_SORT_DESCENDING);
    foreach ($caches as $file) {
      if (strpos($file,'generalData') !== false) {
        $diaryArray = file_get_contents('data_diary/'.$file);
        $diaryArray = json_decode($diaryArray, true);
      }
    }

  for ($d = $start; $d <= $end; $d++) {
    if (isset($diaryArray[$d])) {
      $timestamp = mktime(0,0,0,substr($d,4,2),substr($d,6,2),substr($d,0,4));
      $mNow = date('m',$timestamp);
      if (!isset($mCheck) || $mNow > $mCheck || ($mCheck == 12 && $mNow == 1)) {
        $mCheck = $mNow;
        if ($mNow != 9) { echo '</div>'; } // Closes the last columns div
        echo '<h2>'.date('F',$timestamp);
          if ($mNow == 9) { echo ' '.$startY; }
          elseif ($mNow == 1) { echo ' '.$endY; }
        echo '</h2>';
        echo '<div class="columns">';
      }
      echo '<div class="day">';
        echo '<p class="date">'.date('l jS',$timestamp).'</p>';
        foreach ($diaryArray[$d] as $event) {
          if (isset($event['event'])) { echo '<p class="event">'.$event['event'].'</p>'; }
        }
      echo '</div>';
    }
  }

?>