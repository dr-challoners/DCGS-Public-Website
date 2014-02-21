<?php

$count = 0;

foreach ($links as $row) {
	if (strpos($row,".txt") !== false) { //It's a text file (thereby containing links in markdown)
		
		$subject = explode(".",$row);
		$subject = $subject[0];
		
		$id = str_replace(" ","",$subject);
		$id = strtolower($id); //For the CSS
		
		if ($count%3 == 0) { echo "<div class=\"subjectbar\">"; $open = 1; }
		
		echo "<div class=\"subjectbox\" id=\"".$id."\">";
			echo "<h2>".$subject."</h2>";
			$linkslist = file_get_contents($row, true);
			echo Parsedown::instance()->parse($linkslist);
		echo "</div>";
		
		if ($count%3 == 2) { echo "</div>"; $open = 0; }
		
		$count++; }
	}
	
if ($open == 1) { echo "</div>"; }
	
?>
