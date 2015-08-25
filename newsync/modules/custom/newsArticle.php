<?php

  switch ($dataType) {
    
    case 'newsdate':
      echo '<h3>'.$row['content'].'</h3>';
      $skipRow = 1;
    break;
    
    case 'newsimage';
    case 'newsvideo';
      $dataType = substr($dataType,4);
      // These dataTypes just identify images and videos for the front page magazine: after that they can be processed normally
    break;
    
    case 'newscredit';
    case 'newseditor';
      if ($dataType == 'newseditor') {
        $row['content'] = 'Edited by '.$row['content'];
      }
      echo str_replace('<p>','<p class="'.$row['datatype'].'">',Parsedown::instance()->parse($row['content']));
      $skipRow = 1;
    break;
    
  }

?>