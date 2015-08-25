<?php // This has been separated from the main parsing process simply because it is huge and made the other file difficult to navigate

if (!empty($row['url'])) {
  $check = fetchImage($row['url'],$urlID);
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
        if ($set >= 5 || strpos($row['format'],'scrolling') !== false) {
          echo 'scrolling';
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
    echo '<a href="/'.$imgsSrc.'/'.$urlID.'" data-lightbox="gallery" class="img'; // Setting data-lightbox to gallery includes ALL images on the page as part of a Lightbox set
    if (!isset($set)) {    
      $imgFormats = array('wide','left','right');
      if (in_array($row['format'],$imgFormats)) {
        echo ' '.$row['format'];
      }
    }
    echo '"';
    if (!empty($row['content'])) {
      echo ' data-title="'.str_replace('=','-',$row['content']).'"';
    }
    echo '>'."\n";
    echo '<img src="/'.$imgsSrc.'/'.$urlID.'" ';
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
    // Ending a set
    if (isset($set)) {
      $set--;
      if ($set == 0) {
        echo "\n\n".'</div>';
        unset($set);
      }
    }
  }
}

?>