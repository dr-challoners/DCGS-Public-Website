<?php
  
  include('header_declarations.php');
  echo '</head>';
  echo '<body>';

?>

<style>
  
  * {
    font-family: Arial, sans-serif!important;
  }
  
  p,li {
    margin: 0; padding: 0;
    font-size: 1.15em;
    line-height: 1.3em;
  }
  
  @media screen {
    body { margin: 1.5em; }
    li { margin-left: 1.5em; }
  }

  h1,h2 {
    font-weight: bold;
    margin-bottom: 0.2em;
  }

  p.links {
    font-size: 1.2em; font-weight: bold;
    padding-bottom: 1em;
    border-bottom: 1px solid #aaaaaa;
    margin-bottom: 1.4em;
  }
  p.links a { text-decoration: none;}
  p.links span {
    float: right;
    word-spacing: 1.4em;
  }
  p.links span a { word-spacing: 0; }

  @media print {
    p.links { display: none; }
    li { margin-left: -0.8em; }
  }

  img {
    width: 16%;
    float: left;
  }

  div.match {
    text-align: right;
    margin-bottom: 1.2em;
  }
  
  p#break { margin-top: 0.6em; }
  p#times { word-spacing: 1.8em; }
  p#times span { word-spacing: 0; }

  div.list {
    clear: left; float: left;
    width: 46%;
    margin: 0 2% 1.8em;
  }
  div.list:nth-of-type(3) {
    clear: none;
  }

  div.otherDetails {
    clear: left;
    text-align: center;
    font-size: 1.2em;
    border-top: 1px solid #aaaaaa;
    padding-top: 0.4em;
  }
  
</style>

<?php

  $date = $_GET['date'];
  $_date = substr($date,0,4).'-'.substr($date,4,2);
  $eventID = $_GET['eventID'];

  if (file_exists('data/diary/data-'.$_date.'.json')) {
    $matchData = file_get_contents('data/diary/data-'.$_date.'.json');
    $matchData = json_decode($matchData, true);
    $matchData = $matchData['events'][$date][$eventID];
    
    $pos = $_GET['sheet']*2-1;
    
    if (isset($matchData['otherdetails'])) {
      $details = array();
      $matchData['otherdetails'] = $matchData['otherdetails'];
      $matchData['otherdetails'] = preg_split('/[;:]/',$matchData['otherdetails']);
      foreach ($matchData['otherdetails'] as $key => $line) {
        if (stripos($line,'[display only]') == false) {
          if (stripos($line,'[repeat]') == false) {
            unset($lastLine);
            $line = str_ireplace('[display]','',$line);
            $line = htmlentities($line);
            $line = trim($line);
            $lastLine = $line;
          } else {
            if (isset($lastLine)) {
              $line = $lastLine;
            } else {
              $line = '';
            }
          }
          $details[] = $line;
        }
      }
    }

    $teams = str_replace(':','[BRK]:',$matchData['teams']);
    $teams = preg_split('/[;:]/',$teams);
    $players = preg_split('/[;:]/',$matchData['players']);
    $teamLists = array();
    $c = 0;
    foreach ($teams as $key => $row) {
      if (!empty($row) && !empty(trim($players[$key]))) {
        $teamLists[] = array(trim(str_replace('[BRK]','',$row)),$players[$key]);
        if (strpos($row,'[BRK]') !== false && $c%2 == 0) {
          $teamLists[] = '[BRK]';
          $c++;
        }
      }
      $c++;
    }

    // Check to see if next/previous pages need to be made and create the variables to do so
    if (isset($teamLists[$pos+1])) { $npos = $_GET['sheet']+1; }
    if ($pos > 1) { $lpos = $_GET['sheet']-1; }

    foreach ($teamLists as $key => $list) {
      if ($key != $pos && $key != $pos-1) { unset($teamLists[$key]); }
    }

    echo '<p class="links">';
      echo '<a href="javascript:window.print()">&#10151; Print</a>';
      echo ' <span>';
      if (isset($lpos)) {
        echo '<a href="/teamsheet/'.$date.'-'.$eventID.'-'.$lpos.'">&#171; Last page</a> ';
      }
      if (isset($npos)) {
        echo '<a href="/teamsheet/'.$date.'-'.$eventID.'-'.$npos.'">&#187; Next page</a> ';
      }
      echo '<a href="/diary/'.date('d/m/Y',mktime(0,0,0,substr($date,4,2),substr($date,6,2),substr($date,0,4))).'/">Back to diary</a>';
      echo '</span>';
    echo '</p>';

    echo '<img src="/styles/imgs/logoCrest.png" />';

    echo '<div class="match">';
      echo '<h1>';
        if (isset($matchData['sport'])) {
          echo $matchData['sport'];
        } else {
          echo $matchData['event'];
        }
        echo ': '.$teamLists[$pos-1][0];
        if (isset($teamLists[$pos][0]) && $teamLists[$pos] !== '[BRK]') {
          echo ' and '.$teamLists[$pos][0];
        }
      echo '</h1>';
      if (isset($matchData['sport'])) {
        echo '<p>Opponents: '.$matchData['event'].'</p>';
      }
      if (isset($matchData['venue'])) {
        echo '<p>'.$matchData['venue'];
        if (isset($matchData['venuename'])) { echo ' - '.$matchData['venuename']; }
        echo '</p>';
      }
      echo '<p id="break">'.date('l jS F Y',mktime(0,0,0,substr($date,4,2),substr($date,6,2),substr($date,0,4))).'</p>';
      if (isset($matchData['meettime']) || isset($matchData['matchtime'])) {
        echo '<p id="times">';
          if (isset($matchData['meettime']))  { echo ' <span>Meeting at '.$matchData['meettime'].'</span>'; }
          if (isset($matchData['matchtime'])) { echo ' <span>Start at '.$matchData['matchtime'].'</span>';  }
          if (isset($matchData['timeend']))   { echo ' <span>Finish at '.$matchData['timeend'].'</span>';   }
        echo '</p>';
      }
    echo '</div>';

    foreach ($teamLists as $list) {
      if ($list !== '[BRK]') {
        echo '<div class="list">';
          echo '<h2>'.$list[0].'</h2>';
          $players = explode(',',$list[1]);
          echo '<ol>';
          foreach ($players as $player) {
            echo '<li>'.trim($player).'</li>';
          }
          echo '</ol>';
        echo '</div>';
      }
    }

    if (isset($details)) {
      echo '<div class="otherDetails">';
        if (isset($details[1]) && isset($details[$_GET['sheet']-1])) {
          echo Parsedown::instance()->parse($details[$_GET['sheet']-1]);
        } else {
          echo Parsedown::instance()->parse($details[0]);
        }
      echo '</div>';
    }
  } else {
    echo '<style> body { background-image: url(\'/styles/imgs/error.png\'); background-position: right bottom; background-repeat: no-repeat; background-attachment: fixed; background-size: 980px auto; } </style>';
    echo '<h1>Well, that hasn\'t worked</h1>';
    echo '<p>From some reason, we can\'t find this team sheet. Perhaps they\'ve gone off for the half time scones?</p>';
    echo '<p>You would probably be best <a href="/">going back to the start</a> and trying again, and if that doesn\'t work then <a href="/pages/Information/General information/Contact us">contact us</a> to report the problem.</p>';
  }



?>