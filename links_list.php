<?php 

$links = scandir($directory);
$boxes = count($links);
$boxes = $boxes-2;
$boxes = $boxes/3;

$current = 1;

	echo "<div class=\"column\">";

foreach ($links as $row) {
	if (strpos($directory.$row,".txt") !== false) { //It's a text file (thereby containing links in markdown)
		
		if (($current >= $boxes && $col_count < 2) || ($current >= 2*$boxes && $col_count < 3)) { //Breaks the content into three columns for better organisation
			echo "</div><div class=\"column\">";
			$col_count++; }
		
		echo "<div class=\"linksbox\">";
			$linksbox = file_get_contents($directory.$row, true);
			echo Parsedown::instance()->parse($linksbox);
		echo "</div>";
		
		$current++; }
	}
	
echo "</div>";

?>