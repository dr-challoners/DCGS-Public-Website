<?php

  $dataFolder = 'data/override';

  if ((isset($_GET['sync']) || isset($_GET['preview'])) && file_exists($dataFolder.'/')) {
    // You can automatically re-sync the override messages on the website by deleting the old cache, thereby forcing it to start again
    $data = scandir($dataFolder.'/', 1);
    foreach ($data as $datum) {
      if (strpos($datum,'.json') !== false) {
        unlink($dataFolder.'/'.$datum);
      }
    }
  }

  $overrideData = sheetToArray('1icLE9k67sw9gN9dcnZYsWt5QOnUxe7mTQGZk_2EFLZk',$dataFolder,1);

  foreach ($overrideData['data']['Messages'] as $key => $row) {
    unset($archived,$start,$end,$image);
    
    // Only display the message if it is still within time
    $bounds = array('start','end');
    foreach ($bounds as $bound) {
      if (!empty($row[$bound.'date'])) { 
        $$bound = explode('/',$row[$bound.'date']);
        if (!empty($row[$bound.'time'])) { 
          $time = explode(':',$row[$bound.'time']);
          foreach ($time as $part) {
             ${$bound}[] = $part;
          } 
        } elseif ($bound == 'start') { array_push($$bound,0,0); }
          elseif ($bound == 'end') { array_push($$bound,23,59); }
        $$bound = mktime(${$bound}[3],${$bound}[4],0,${$bound}[1],${$bound}[0],${$bound}[2]);
      } 
    }
    if ((isset($start) && $start > time()) || (isset($end) && $end < time())) { $archived = 1; }
    
    // Make the code for the icon - anything from Font Awesome may be specified, but there are some defaults
    $icon = '<i class="fas fa-info-circle"></i>';
    if (!empty($row['icon'])) {
      if (clean($row['icon']) == 'alert') {
        $icon = str_replace('info','exclamation',$icon);
      } elseif (clean($row['icon']) == 'travel') {
        $icon = '<i class="fas fa-bus"></i>';
      } else {
        $icon = str_replace('info-circle',clean($row['icon']),$icon);
      }
    }
    
    if (!empty($row['image'])) {
      $positions = array('banner','border','border-noicon');
      $imageName = makeID($row['image'],1).'-'.clean($row['title']);
      $check = fetchImage($row['image'],$imageName);
      if ($check !== 'ERROR' && in_array(clean($row['imagedisplay']),$positions)) {
        $image = array('data/images/'.$imageName,clean($row['imagedisplay']));
      }
    }
    
    if (!empty($row['message']) && (isset($_GET['preview']) || (empty($row['preview'])) && !isset($archived))) {
      if (!empty($row['colour']) || !empty($row['iconcolour']) || (isset($image) && $image[1] == 'border')) {
        echo '<style>';
          if (!empty($row['colour'])) {
            echo '#message'.$key.' { background-color: '.$row['colour'].'; }';
            echo '#message'.$key.' h1, #message'.$key.' h2 { color: '.$row['colour'].'; }';
          }
          if (!empty($row['iconcolour'])) {
            echo '#message'.$key.' .iconPanel { color: '.$row['iconcolour'].'; }';
          }
          if (isset($image) && $image[1] == 'border') {
            echo '#message'.$key.' { background-image: url('.$image[0].'); }';
          }
        echo '</style>';
      }
      echo '<div class="row overrideMessage" id="message'.$key.'">';
        if (isset($image) && $image[1] == 'banner') {
          echo '<img class="img-responsive" src="'.$image[0].'" alt="'.$row['message'].'" />';
        } else {
          echo '<div class="iconPanel col-xs-2">';
            echo $icon;
          echo '</div>';
          echo '<div class="messagePanel col-xs-10">';
            if (!empty($row['title'])) {
              echo '<h1>'.$row['title'].'</h1>';
            }
            echo formatText($row['message']);
          echo '</div>';
        }
      echo '</div>';
      /*
      if (isset($image) && $image[1] == 'full') { echo '<img src="'.$image[0].'" class="override" alt="'.$row['message'].'"/>'; }
      echo '<div class="override';
        if (isset($image) && $image[1] == 'full') { echo ' sml'; }
      echo '"';
        if (!empty($row['type'])) { echo ' id="'.strtolower($row['type']).'"'; }
        if (!empty($row['bordercolour']) || !empty($row['backgroundcolour']) || !empty($row['textcolour'])) {
          echo ' style="';
            if (!empty($row['bordercolour'])) { echo 'border-color:'.$row['bordercolour'].';'; }
            if (!empty($row['backgroundcolour'])) { echo 'background-color:'.$row['backgroundcolour'].';'; }
            if (!empty($row['textcolour'])) { echo 'color:'.$row['textcolour'].';'; }
          echo '"';
        }
      echo '>';
        if (!empty($row['title'])) {
          echo '<h1';
            if (isset($image) && $image[1] == 'title') { echo ' class="sml"'; }
            if (!empty($row['bordercolour']) || !empty($row['titletextcolour'])) {
              echo ' style="';
                if (!empty($row['bordercolour'])) { echo 'background-color:'.$row['bordercolour'].';'; }
                if (!empty($row['titletextcolour'])) { echo 'color:'.$row['titletextcolour'].';'; }
              echo '"';
            }
          echo '>'.$row['title'].'</h1>';
        }
      
        if (isset($image) && $image[1] == 'title') { echo '<img src="'.$image[0].'" class="wide" alt="'.$row['title'].'"/>'; }
        if (isset($image) && $image[1] == 'top')   { echo '<img src="'.$image[0].'" class="wide" />'; }
        if (isset($image) && ($image[1] == 'left' || $image[1] == 'right')) {
          echo '<img src="'.$image[0].'" class="side '.$image[1].'" />';
        }
      
        $message = Parsedown::instance()->parse($row['message']);
        $message = str_replace('\\','</p><p>',$message); // This means that \\ can be used to indicate new paragraphs in a spreadsheet cell
        echo $message;
      
        if (isset($image) && $image[1] == 'bottom')   { echo '<img src="'.$image[0].'" class="wide bottom" />'; }  
      
      echo '</div>'; */
    $override = 1; // This lets the magazine know there's been an override, so it doesn't display a 'big' news story
    }
  }

?>