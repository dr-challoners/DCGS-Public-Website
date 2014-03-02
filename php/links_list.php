<?php

$links = scandir($directory);
$boxes = count($links);
$boxes = $boxes-2;
$boxes = $boxes/4;

$current = 1;
$col_count = 1;

echo "<div class=\"intranet\">";
	echo "<div class=\"column\">";

foreach ($links as $row) {
	if (strpos($directory.$row,".txt") !== false) { //It's a text file (thereby containing links in markdown)
		
		if (($current >= $boxes && $col_count < 2) || ($current >= 2*$boxes && $col_count < 3) || ($current >= 3*$boxes && $col_count < 4)) { //Breaks the content into four columns for better organisation
			echo "</div><div class=\"column\">";
			$col_count++; }
		
		echo "<div class=\"linksbox\">";
			$linksbox = file_get_contents($directory.$row, true);
			echo Parsedown::instance()->parse($linksbox);
		echo "</div>";
		
		$current++; }
	}
	
echo "</div></div>";

?>