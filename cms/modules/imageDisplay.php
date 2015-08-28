<?php // This has been separated from the main parsing process simply because it is huge and made the other file difficult to navigate

if (!empty($row['url'])) {
  if (!empty($row['content'])) {
    $imageName = makeID($row['url'],1).'-'.clean($row['content']);                      
  } else {
    $imageName = makeID($row['url']);
  }
  $check = fetchImage($row['url'],$imageName);
  if ($check != 'ERROR') {
    
    // Constructing sets
    if (strpos($row['format'],'set') !== false && !isset($set)) {
      $set = 1;
      for ($i = 1; $i >= 1; $i++) {
        if (isset($pageArray[$key+$i]) && strpos($pageArray[$key+$i]['format'],'set') !== false && strpos($pageArray[$key+$i]['format'],'new') === false) {
          $set++;
        } else {
          break;
        }
      }
      if ($set > 1) {
        echo '<div class="imageSet ';
        if ($set >= 5 || strpos($row['format'],'gallery') !== false) {
          echo 'gallery';
          if ($set == 4 || $set == 7 || $set == 8 || $set >= 10) {
            echo ' large';
          }
          // If it's a gallery, then we need to figure out what type of boxes we're going to have
          $n = $set;
          $boxes = array();
          while ($n > 0) {
            if ($n > 12) {
	            $boxTypes = array(2,3,5,6,9);
            }
            else {
              switch($n) {
                case 12: $boxTypes = array(3,6,6,9); break;
                case 11: $boxTypes = array(2,3,5,6); break;
                case 10: $boxTypes = array(2,3,3,5); break;
                case 9:  $boxTypes = array(2,3,6,9); break;
                case 8:  $boxTypes = array(2,3,5,6); break;
                case 7:  $boxTypes = array(2,3,3,5); break;
                case 6:  $boxTypes = array(3,6);     break;
                case 5:  $boxTypes = array(2,3,3,5); break;
                case 4:  $boxTypes = array(2);       break;
                case 3:  $boxTypes = array(3);       break;
                case 2:  $boxTypes = array(2);       break;
              }
            }
            $i = preg_replace('/[^0-9]/', '', $imageName);
            $b = $i%count($boxTypes);
            $b = $boxTypes[$b];
            $layout   = array();
            switch($b) {
              case 2:
                $layout[] = array("med","wde");
                $layout[] = array("wde","med");
              break;
              case 3: $layout[] = array("med","med","med"); break;
              case 5:
                $layout[] = array("tny","wde");
                $layout[] = array("wde","tny");
              break;
              case 6:
                $layout[] = array("med","med","tny");
                $layout[] = array("med","tny","med");
                $layout[] = array("tny","med","med");
              break;
              case 9:
                $layout[] = array("med","tny","tny");
                $layout[] = array("tny","tny","med");
                $layout[] = array("tny","med","tny");
              break;
            }
            $n = $n-$b;
            $b = $i%count($layout);
            $b = $layout[$b];
            foreach ($b as $box) {
              $boxes[] = $box;
            }
          }
        } else {
          $numWords = array(2 => 'two', 3 => 'three', 4 => 'four');
          echo $numWords[$set];
        }
        echo '">'."\n\n";
      } else {
        unset($set);
      }
    }
    // Displaying the image
    if (isset($boxes[0]) && $boxes[0] == 'tny') {
      echo '<div class="tinyBox">'."\n\n";
      $tiny = 4;
      array_shift($boxes);
    }
    echo '<a href="/'.$imgsSrc.'/'.$imageName.'" data-lightbox="page" class="img'; // Setting data-lightbox to page includes ALL images on the page as part of a Lightbox set
    if (!isset($set)) {    
      $imgFormats = array('wide','left','right');
      if (in_array($row['format'],$imgFormats)) {
        echo ' '.$row['format'].'"';
      }
    } elseif (isset($boxes) && !isset($tiny)) {
      if (array_shift($boxes) == 'wde') {
        echo ' wide'.'"';
      }
    } else {
      echo '"';
    }
    if (!empty($row['content'])) {
      echo ' data-title="'.str_replace('=','-',$row['content']).'"';
    }
    echo '>'."\n";
    echo '<img src="/'.$imgsSrc.'/'.$imageName.'" ';
    if (!empty($row['content'])) {
      echo 'alt="'.str_replace('=','-',$row['content']).'" ';
    }
    echo '/>'."\n";
    if (!empty($row['content']) && isset(explode('=',$row['content'])[1])) {
      echo '<p>';
      echo trim(explode('=',$row['content'])[1]);
      echo '</p>'."\n";
    }
    echo '</a>';
    if (isset($tiny)) {
      $tiny--;
      if ($tiny == 0) {
        echo "\n\n".'</div>';
        unset($tiny);
      }
    }
    // Ending a set
    if (isset($set)) {
      $set--;
      if ($set == 0) {
        echo "\n\n".'</div>';
        unset($set,$boxes);
      }
    }
  }
}

?>