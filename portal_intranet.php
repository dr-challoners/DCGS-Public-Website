<?php

include('header_declarations.php');
include('header_navigation.php');

echo "<h1 class=\"intranet\">Staff intranet</h1>";

$directory = "content_plain/portal/intranet/links/";

include ('php/links_list.php');

echo "<h1 class=\"clear lrg\">Subject resources</h1>";
echo "<div class=\"intranet lrg\">";
	$directory = "content_plain/portal/intranet/subjects/";
	$links = scandir($directory);
	
	$count = 0;

foreach ($links as $row) {
	if (strpos($directory.$row,".txt") !== false) { //It's a text file (thereby containing links in markdown)
		
		$subject = explode(".",$row);
		$subject = $subject[0];
		
		$id = str_replace(" ","",$subject);
		$id = strtolower($id); //For the CSS
		
		if ($count%3 == 0) { echo "<div class=\"subjectbar\">"; $open = 1; }
		
		echo "<div class=\"subjectbox\" id=\"".$id."\">";
			echo "<h2>".$subject."</h2>";
			$linkslist = file_get_contents($directory.$row, true);
			echo Parsedown::instance()->parse($linkslist);
		echo "</div>";
		
		if ($count%3 == 2) { echo "</div>"; $open = 0; }
		
		$count++; }
	}
	
if ($open == 1) { echo "</div>"; }
	
echo "</div>";

include('footer.php'); ?>