<?php

include('../../header_declarations.php');
include('../../header_navigation.php');

echo "<h1 class=\"intranet\">Staff intranet</h1>";

$links = scandir("./");
$boxes = count($links);
$boxes = $boxes-3;
$boxes = $boxes/4;

$current = 1;
$col_count = 1;

echo "<div class=\"intranet\">";
	echo "<div class=\"column\">";

foreach ($links as $row) {
	if (strpos($row,".txt") !== false) { //It's a text file (thereby containing links in markdown)
		
		if (($current >= $boxes && $col_count < 2) || ($current >= 2*$boxes && $col_count < 3) || ($current >= 3*$boxes && $col_count < 4)) { //Breaks the content into four columns for better organisation
			echo "</div><div class=\"column\">";
			$col_count++; }
		
		echo "<div class=\"linksbox\">";
			$linksbox = file_get_contents($row, true);
			echo Parsedown::instance()->parse($linksbox);
		echo "</div>";
		
		$current++; }
	}
	
echo "</div></div>";

echo "<h1 class=\"clear lrg\">Subject resources</h1>";
echo "<div class=\"intranet lrg\">";
	$links = scandir("../subjects/");
	include('../subjects/subject_list.php');
echo "</div>";

include('../../footer.php'); ?>
