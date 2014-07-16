<?php

$files = scandir("content_main/Information/1~General information/");

$termdates = array();
foreach ($files as $row) { //From the general files in information/, take only the files related to termdates (which should all be csv files full of dates)
	if (substr($row,0,9) == "termdates") {
		array_push($termdates,$row);
		}
	}

$count = 0;
foreach ($termdates as $row) { //Processing whatever termdates files there are, one by one
	$title = explode("~",$row); //Output the title (note this is going to break if there are files in the folder starting with 'termdates' that aren't csv files)
	$title = chop($title[1],".csv");
	echo "<h1";
		if ($count > 0) { echo " class=\"second\""; } //To space out headers after the first
	echo ">Term and holiday dates for ".$title."</h1>";
	
	$dates = array(); //Now turn the file into an array, in order to parse as content
	
	$file = fopen($_SERVER['DOCUMENT_ROOT'].'/content_main/Information/1~General information/'.$row,"r");

	while(! feof($file)) {
		$line = fgetcsv($file);
		if ($line[1] != "") { //Only takes lines that actually have dates on them
			array_push($dates,$line);
			}
		}

	fclose($file);
	
	echo "<p class=\"termdate\" id=\"toprow\"><span id=\"firstcol\"></span><span>Open on the morning of:</span><span>Close at the end of the afternoon of:</span></p>";
	
	$remains = array();
	
	foreach ($dates as $row) { //This will just work through the array in order, so the file wants to have its lines in the right order
		if ($row[0] != "Bank holidays during term" && $row[0] != "Staff training days") { //Only take the actual term dates at first
			echo "<p class=\"termdate";
			if (substr($row[0],-1) == "2") { echo " noline"; } //This allows the second half of a term to be put closer to the first in the styling
			echo "\"><span id=\"firstcol\">";
				echo $row[0]; //The title
			echo "</span><span>";
				echo date("l, jS F Y",mktime(0,0,0,substr($row[1],4,2),substr($row[1],6,2),substr($row[1],0,4)));
			echo "</span><span>";
				echo date("l, jS F Y",mktime(0,0,0,substr($row[2],4,2),substr($row[2],6,2),substr($row[2],0,4)));
			echo "</span></p>";
			}
		else { array_push($remains,$row); } //Keep hold of non term dates to process in a moment
		}
	
	foreach ($remains as $row) { //Now put the other details in
		foreach ($row as $row2) { //Goes through each type of detail one at a time
			if (strlen($row2) > 8) { //A quick check to get the detail at the beginning
				echo "<h2>".$row2."</h2><ul>"; //Gives title and starts list of dates
				}
			elseif (strlen($row2) != 0) { //Stops outputting blank cells
				echo "<li>".date("l, jS F Y",mktime(0,0,0,substr($row2,4,2),substr($row2,6,2),substr($row2,0,4)))."</li>";
				}
			echo "</ul>";
			}
		}
		$count++;
	}



?>