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

  foreach ($overrideData['Messages'] as $row) {
    if (!empty($row['message']) && (isset($_GET['overridePreview']) || empty($row['preview']))) {
      echo '<div class="override"';
        if (!empty($row['type'])) { echo ' id="'.$row['type'].'"'; }
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
            if (!empty($row['bordercolour']) || !empty($row['titletextcolour'])) {
              echo ' style="';
                if (!empty($row['bordercolour'])) { echo 'background-color:'.$row['bordercolour'].';'; }
                if (!empty($row['titletextcolour'])) { echo 'color:'.$row['titletextcolour'].';'; }
              echo '"';
            }
          echo '>'.$row['title'].'</h1>';
        }
        $message = Parsedown::instance()->parse($row['message']);
        $message = str_replace('||','</p><p>',$message); // This means that || can be used to indicate new paragraphs in a spreadsheet cell
        echo $message;
      echo '</div>';
    $override = 1; // This lets the magazine know there's been an override, so it doesn't display a 'big' news story
    }
  }

echo '<pre>'; print_r($overrideData); echo '</pre>'; ?>