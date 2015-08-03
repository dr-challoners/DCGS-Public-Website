<?php

  $dataFolder = 'data_override';

  if ((isset($_GET['overrideSync']) || isset($_GET['overridePreview'])) && file_exists($dataFolder.'/')) {
    // You can automatically re-sync the override messages on the website by deleting the old cache, thereby forcing it to start again
    $data = scandir($dataFolder.'/', 1);
    foreach ($data as $datum) {
      if (strpos($datum,'.json') !== false) {
        unlink($dataFolder.'/'.$datum);
      }
    }
  }

  $overrideData = sheetToArray('1icLE9k67sw9gN9dcnZYsWt5QOnUxe7mTQGZk_2EFLZk',$dataFolder,1);

  function isImage($url) {
     $params = array('http' => array(
                  'method' => 'HEAD'
               ));
     $ctx = stream_context_create($params);
     $fp = @fopen($url, 'rb', false, $ctx);
     if (!$fp) 
        return false;  // Problem with url

    $meta = stream_get_meta_data($fp);
    if ($meta === false)
    {
        fclose($fp);
        return false;  // Problem reading data from url
    }

    $wrapper_data = $meta["wrapper_data"];
    if(is_array($wrapper_data)){
      foreach(array_keys($wrapper_data) as $hh){
          if (substr($wrapper_data[$hh], 0, 19) == "Content-Type: image") // strlen("Content-Type: image") == 19 
          {
            fclose($fp);
            return true;
          }
      }
    }

    fclose($fp);
    return false;
  }

  foreach ($overrideData['Messages'] as $row) {
    unset($archived,$start,$end,$image);
    
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
    
    $positions = array('left','right','top','bottom','title','full');
    if (!empty($row['image']) && in_array(strtolower($row['imageposition']),$positions) && isImage($row['image'])) {
      $image = array($row['image'],strtolower($row['imageposition']));
    }
    
    if (!empty($row['message']) && (isset($_GET['overridePreview']) || (empty($row['preview'])) && !isset($archived))) {
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
        $message = str_replace('\\','</p><p>',$message); // This means that || can be used to indicate new paragraphs in a spreadsheet cell
        echo $message;
      
        if (isset($image) && $image[1] == 'bottom')   { echo '<img src="'.$image[0].'" class="wide bottom" />'; }  
      
      echo '</div>';
    $override = 1; // This lets the magazine know there's been an override, so it doesn't display a 'big' news story
    }
  }

?>