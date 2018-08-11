<?php

$section = $_GET['section'];
include('header.php');
echo '<div class="sectionNavigation">';
if ($section != 'news') {
  echo '<h1>'.revert($section).'</h1>';
}
include('navigationSide.php');
echo '</div>';
include('footer.php');

?>