<?php

echo '<div class="hidden-xs col-sm-4 hidden-print">';
echo '<div class="panel-group sideNav" id="'.$section.'Nav" role="tablist" aria-multiselectable="true">';
$dir = scandir($_SERVER['DOCUMENT_ROOT'].'/pages/'.$section);
$dir = array_reverse($dir);
foreach ($dir as $row) {
  if (strpos($row,'navDir-') !== false && strpos($row,'.json') !== false) {
    $dir = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/pages/'.$section.'/'.$row);
    $dir = json_decode($dir, true);
    break;
  }
}
foreach ($dir as $sheetName => $pages) {
  echo '<div class="panel panel-default">';
  echo '<div class="panel-heading" role="tab" id="'.clean($sheetName).'">';
  echo '<h4 class="panel-title">';
  echo '<a ';
  if (clean($sheetName) != $sheet) {
    echo 'class="collapsed" ';
  }
  echo 'role="button" data-toggle="collapse" data-parent="#'.$section.'Nav" href="#collapse-'.clean($sheetName).'" aria-expanded="';
  if (clean($sheetName) != $sheet) {
    echo 'false';
  } else {
    echo 'true';
  }
  echo '" aria-controls="collapse-'.clean($sheetName).'">'.$sheetName.'</a>';
  echo '</h4>';
  echo '</div>';
  echo '<div id="collapse-'.clean($sheetName).'" class="panel-collapse collapse';
  if (clean($sheetName) == $sheet) {
    echo ' in';
  }
  echo '" role="tabpanel" aria-labelledby="'.clean($sheetName).'">';
  echo '<ul class="list-group">';
  foreach ($pages as $pageName => $data) {
    echo '<li class="list-group-item">';
    echo '<a href="'.$data['link'].'">'.formatText($pageName,0).'</a>';
    echo '</li>';
  }
  echo '</ul>';
  echo '</div>';
  echo '</div>';
  if ($section == 'news') {
    if (isset($c)) {
      $c++;
    } else {
      $c = 1;
    }
    if ($c == 12) {
      echo '<p class="newsArchiveLink"><a href="/c/news/"><i class="fa fa-clock-o"></i> News archives</a></p>';
      break;
    }
  }
}
echo '</div>';
echo '</div>';

?>