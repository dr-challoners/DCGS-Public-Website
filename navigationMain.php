<?php

$section = $_GET['section'];
include('header.php');
echo '<div class="sectionNavigation">';
echo '<h1>'.revert($section).'</h1>';
$dir = scandir('pages/'.$section);
$dir = array_reverse($dir);
foreach ($dir as $row) {
  if (strpos($row,'navDir-') !== false && strpos($row,'.json') !== false) {
    $dir = file_get_contents('pages/'.$section.'/'.$row);
    $dir = json_decode($dir, true);
    break;
  }
}
$l = 0; $t = 0;
foreach($dir as $sheet => $pages) {
  if ($l == $t) {
    echo '<div class="row navDecor">';
    for ($p = 1; $p <= 6; $p++) {
      if (!isset($navDecor) || count($navDecor) == 0) {
        $navDecor = scandir('img/navDecor');
        array_shift($navDecor);
        array_shift($navDecor);
        shuffle($navDecor);
      }
      if ($p < 5) {
        echo '<div class="col-xs-3 col-md-2">';
      } else {
        echo '<div class="col-md-2 hidden-sm hidden-xs">';
      }
      echo '<img class="img-responsive" src="/img/navDecor/'.array_shift($navDecor).'" /></div>';
    }
    echo '</div>';
    $t = $t+mt_rand(3,5);
  }
  $l++;
  echo '<div class="row">';
  echo '<div class="col-xs-12">';
  echo '<h2>'.$sheet.'</h2>';
  echo '</div>';
  foreach ($pages as $title => $page) {
    echo '<div class="col-xs-6 col-md-4">';
    echo '<a href="'.$page['link'].'">';
    echo '<p>'.$title.'</p>';
    echo '</a>';
    echo '</div>';
  }
  echo '</div>';
}
echo '</div>';
include('footer.php');

?>