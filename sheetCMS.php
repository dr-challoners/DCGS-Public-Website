<?php
  include('sheetsCMSheader.php');
  if (!isset($_GET['sheet']) && !isset($_GET['action']) && !isset($_GET['learn'])) {
    include('sheetsCMSnavigation.php');
  } elseif (isset($_GET['sheet'])) {
    include('sheetsCMSprocess.php');
  } elseif (isset($_GET['learn'])) {
    include('sheetsCMSprocessLearn.php');
  } elseif (isset($_GET['action'])) {
    echo '<div class="row panel panel-default">';
      echo '<div class="panel-body">';
    switch ($_GET['action']) {
      
      case 'clean';
        if (!isset($mainData)) {
          $mainData = array();
        }
        $_GET['sync'] = 1;
        $newData = sheetToArray($mainSheet,0);
        $mainData['meta'] = $newData['meta'];
      
        foreach ($mainData['data']['sheets'] as $id => $sheet) {
          if (!file_exists('data/content/'.$id.'.json')) {
            unset($mainData['data']['sheets'][$id]);
          }     
        }
        if (!file_exists('data/content')) {
          mkdir('data/content',0777,true);
        }
        file_put_contents('data/content/mainData.json', json_encode($mainData));
        $files = scandir('data/content');
        $ignore = array('.','..','mainData.json');
        foreach ($files as $file) {
          if (!in_array($file,$ignore)) {
            $file = pathinfo($file);
            if ($file['extension'] != 'json' || !isset($mainData['data']['sheets'][$file['filename']])) {
              unlink('data/content/'.$file['basename']);
            }
          }
        }
        echo '<p><b>The data has been cleaned.</b></p>';
        echo '<p>If this doesn\'t resolve your problem, please ask for support.</p>';
      break;
      
      default:
        echo '<p class="warning">That\'s an invalid action.</p>';
        echo '<p class="warning">Returning you to the main menu...</p>';
        echo '<META HTTP-EQUIV="Refresh" CONTENT="3;URL=/sync">';
      break;
      
    }
    if (!file_exists('data/content')) {
      mkdir('data/content',0777,true);
    }
    file_put_contents('data/content/mainData.json', json_encode($mainData));
    echo '<a class="btn btn-primary" href="/sync">Return to the main menu</a>';
    echo '</div></div>';
  }

?>
  </div> <!-- .container -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="/modules/js/bootstrap.min.js"></script>
</body>
</html>