<?php

  if (isset($_GET['preview'])) { $_GET['sync'] = 1; }
  $dataFolder = 'data/override';
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
    if (!empty($row['message']) && (isset($_GET['preview']) || (!empty($row['live'])) && !isset($archived))) {
      // Make the code for the icon - anything from Font Awesome (except brands) may be specified, but there are some defaults
      // This also chooses default colours for alert and travel messages
      switch ($row['icon']) {
        case '':
          $icon = 'info-circle';
          break;
        case 'alert':
          $icon = 'exclamation-circle';
          if (empty($row['colour'])) {
            $row['colour'] = 'OrangeRed';
          }
          break;
        case 'travel':
          $icon = 'bus';
          if (empty($row['colour'])) {
            $row['colour'] = 'RebeccaPurple';
          }
          break;
        default:
          $icon = $row['icon'];
          break;
      }
      // If any colours have been specified, set up relevant styles
      if (!empty($row['colour']) || !empty($row['iconcolour'])) {
        echo '<style>';
          if (!empty($row['colour'])) {
            echo '#message'.$key.' { background-color: '.$row['colour'].'; }';
            echo '#message'.$key.' h1, #message'.$key.' h2 { color: '.$row['colour'].'; }';
          }
          if (!empty($row['iconcolour'])) {
            echo '#message'.$key.' .iconPanel { color: '.$row['iconcolour'].'; }';
          }
        echo '</style>';
      }
      // This is the actual output for the message
      echo '<div class="row overrideMessage';
      if (!empty($row['image'])) {
        echo ' visible-xs-block';
      }
      echo '" id="message'.$key.'">';
        echo '<div class="iconPanel col-xs-2">';
          echo '<i class="fas fa-'.$icon.'"></i>';
        echo '</div>';
        echo '<div class="messagePanel col-xs-10">';
          if (!empty($row['title'])) {
            echo '<h1>'.$row['title'].'</h1>';
          }
          echo formatText($row['message']);
        echo '</div>';
      echo '</div>';
      if (!empty($row['image'])) {
        $image = fetchImageFromURL('data/override',$row['image']);
        echo '<div class="row overrideBanner hidden-xs">';
          echo '<div class="col-sm-12">';
            echo '<img class="img-responsive" src="'.$image.'" />';
          echo '</div>';
        echo '</div>';
      }
      $override = 1; // This lets the magazine know there's been an override, so it doesn't display a 'big' news story
    }
  }

?>