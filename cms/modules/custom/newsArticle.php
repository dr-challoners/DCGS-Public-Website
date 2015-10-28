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
    case 'newsauthor':
    case 'newseditor';
      if ($dataType == 'newsauthor') {
        $dataType = 'newscredit';
        // author and credit are synonyms, but one word may feel more natural than the other to some people
      }
      if ($dataType == 'newseditor') {
        $row['content'] = 'Edited by '.$row['content'];
      }
      echo str_replace('<p>','<p class="'.strtolower($row['datatype']).'">',Parsedown::instance()->parse($row['content']));
      $skipRow = 1;
    break;
    
  }

?>