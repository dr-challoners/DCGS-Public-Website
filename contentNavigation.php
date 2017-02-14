<?php

echo '<div class="hidden-xs col-sm-4 hidden-print">';
echo '<div class="panel-group sideNav" id="'.$section.'Nav" role="tablist" aria-multiselectable="true">';
$pages = directoryToArray($_SERVER['DOCUMENT_ROOT'].'pages/visible/'.$section);
foreach ($pages as $key => $rows) {
  /*
  if (clean($section) == 'news') {
    if (!isset($c)) {
      $c = 1;
    } else {
      $c++;
    }
  }
  */
  echo '<div class="panel panel-default">';
  echo '<div class="panel-heading" role="tab" id="'.$key.'">';
  echo '<h4 class="panel-title">';
  echo '<a ';
  if ($key != $sheet) {
    echo 'class="collapsed" ';
  }
  echo 'role="button" data-toggle="collapse" data-parent="#'.$section.'Nav" href="#collapse-'.$key.'" aria-expanded="';
  if ($key != $sheet) {
    echo 'false';
  } else {
    echo 'true';
  }
  echo '" aria-controls="collapse-'.$key.'">'.revert($key).'</a>';
  echo '</h4>';
  echo '</div>';
  echo '<div id="collapse-'.$key.'" class="panel-collapse collapse';
  if ($key == $sheet) {
    echo ' in';
  }
  echo '" role="tabpanel" aria-labelledby="'.$key.'">';
  echo '<ul class="list-group">';
  foreach ($rows as $page) {
    $page = substr($page,0,-4);
    echo '<li class="list-group-item">';
    echo '<a href="/c/'.$section.'/'.$key.'/'.$page.'/">'.revert($page).'</a>';
    echo '</li>';
    }
  echo '</ul>';
  echo '</div>';
  echo '</div>';
  /*
  if (isset($c) && $c >= 12) {
    echo '<p class="newsArchiveLink"><a href="/c/news/"><i class="fa fa-clock-o"></i> News archives</a></p>';
    break;
  }
  */
}
echo '</div>';
echo '</div>';

?>