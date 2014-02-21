<?php

$makedate = mktime(0,0,0,substr($datestamp,4,2),substr($datestamp,6,2),substr($datestamp,0,4));
$calday = date("j",$makedate); //calday and calweekday check the entry's position in the month and week
$calweekday = date("N",$makedate);
$caldate = date("l jS",$makedate); //Puts the date in a friendly format for display, eg 'Saturday 15th'
$calmonth = date("F Y",$makedate); //For displaying month and year

if (file_exists("./items/".$datestamp.".xml")) { $date = simplexml_load_file("./items/".$datestamp.".xml"); //If there are events this date then it loads them
	echo "<h2>".$caldate; //Put the date first (note that this is from the datestamp itself, not the xml - but it needs to be here as there's a change of style if there's no events)
	if ($calday == "1" || $calweekday == "1") { //If it's the start of the month or the first entry displayed (ie, a Monday), then give the month
		echo "<span>".$calmonth."</span>";
		}
	echo "</h2>";
	foreach($date->events->event as $event) { //Work through each event one at a time
		//Search for the previous title in this title to see if it's a sub-event first
		
		//Process general event details:
		$start = $event -> timed -> start; $end = $event -> timed -> end; //Start and end times
		$title = str_replace(array(" #","#"),array(" <strong>","</strong>"),$event -> title); //Event title - placeholder formatting characters are converted to HTML formatting so that it can be styled appropriately
		$type = $event -> categories -> category[0]; //Event type
		if ($type == "Match") { $type = $event -> categories -> category[1]; } //If it's sport, find the type of sport
		$depart = $event -> timed -> {'envelope-start'}; $pickup = $event -> timed -> {'envelope-end'}; //Away game details
		if ($event -> location != "") { $where = $event -> location -> attributes(); } //Driving directions to an away game (checks if the location node exists first)
		
		if (strpos($title,$comparetitle) !== false && "$type" == "$comparetype" && "$where" == "$comparewhere") { //If it's a sporting fixture with the same opponent and in the same place as the previous event, just detail the title alongside the previous information
			echo "<p class=\"details\">".$title."</p>";
			} 
		else { //Otherwise, display a full event
		
			//Basic event time, title and type
			if ($start != "") {
				echo "<p class=\"time\">".$start." - ".$end."</p>";
				}
				else { echo "<p class=\"time\"></p>"; }
			echo "<h3>".$title."</h3>";
			echo "<p class=\"allevents\"><a id=\"".$type."\" href=\"./?event=".$type."\"> See all ".strtolower($type)."";
			if ($type == "Event" || $type == "Visit" || $type == "Meeting" || $type == "Highlight") { echo "s"; } //Just sorts out grammar
			echo "</a></p>";
		
			//Further details
			if ($depart != "") { //This indicates that it's an away sporting fixture, which then prompts further details
				echo "<p class=\"details\">";
				echo "Depart at ".$depart.". Pick-up at ".$pickup.".</p>";
				echo "<p class=\"details\"><a class=\"detaillink\" href=\"".$where[1]."\">Driving directions</a>.</p>";
				}
		
			if ($type != "") { //Provides a fix for when a type has not been provided (there should always be a type, but this avoids errors if it's been left out by mistake)
				$comparetitle = explode($type,$title);
				$comparetitle = $comparetitle[1];
				$comparetype = $type;
				}
			else {
				$comparetitle = $title;
				$comparetype = "None";
				}
			$comparewhere = $where;
			//This sets up a check for sporting events fixtures
		
			}
			
		}
	}
else {
	echo "<h2 class=\"noevents\">".$caldate;
	if ($calday == "1" || $calweekday == "1") { //If it's the stop of the month or the first entry displayed (ie, a Monday), then give the month
		echo "<span>".$calmonth."</span>";
		}
	echo "</h2>";
	} //If there are no events at all, just give the date (for completeness of the diary)

?>
