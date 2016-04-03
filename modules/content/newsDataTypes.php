<?php

  switch ($dataType) {
    // Convert deprecated/synonymous dataTypes into their correct equivalents
    case 'newsdate':
      $dataType = 'infodate';
    break;
    case 'newscredit':
    case 'newsauthor':
      $dataType = 'infowriting';
    break;
    case 'newseditor':
    case 'infoeditor':
      $dataType = 'infoediting';
    break;
  }

  switch ($dataType) {
    
    case 'infodate':
      $output['info']['date'] = $row['content'];
      $skipRow = 1;
    break;
    
    case 'newsimage':
    case 'newsvideo':
      $dataType = substr($dataType,4);
      // These dataTypes just identify images and videos for the front page magazine: after that they can be processed normally
    break;
    
    case 'infowriting':
    case 'infoediting':
    case 'infophotos':
    case 'inforecording':
      $content = explode(PHP_EOL, $row['content']);
      foreach ($content as $credit) {
        $credit = trim($credit);
        if (!empty($credit)) {
          if (stripos($credit,', Year ') == true) {
            // This tidies up the way a student's year is displayed, if editors don't follow the style guide
            $credit = str_ireplace(', Year',' (Year',$credit);
            $credit = $credit.')';
          }
          // Stop just the year number from dropping to a new line on long lists of credits
          $credit = str_ireplace('Year ','Year&nbsp;',$credit);
          if (!empty($row['url'])) {
            $credit = '<a href="'.$row['url'].'">'.$credit.'</a>';
          }
          $output['info'][substr($dataType,4)][] = $credit;
        }
      }
      $skipRow = 1;
    break;
    
  }

?>