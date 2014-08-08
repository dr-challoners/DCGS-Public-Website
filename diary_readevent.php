<?php

if (file_exists("content_plain/diary/".$type.".xml")) { $eventread = simplexml_load_file("content_plain/diary/".$type.".xml"); //If there are events this date then it loads them
	$count = 1;
	$notblank = 0;
	foreach($eventread->events->event as $event) { //Work through each event one at a time
		//Search for the previous title in this title to see if it's a sub-event first
		
		//Process general event details:
		$date = $event -> date;
		$datecheck = substr($date,6,4).substr($date,3,2).substr($date,0,2);
		$date = mktime(0,0,0,substr($date,3,2),substr($date,0,2),substr($date,6,4));
		
		if ($datecheck >= date('Ymd')) { //Only display the event if it hasn't happened yet
		$displayday = date("l jS",$date);
		$displaymonth = date("F Y",$date);
			
		$start = $event -> timed -> start; $end = $event -> timed -> end; //Start and end times
		$title = str_replace(array(" #","#"),array(" <strong>","</strong>"),$event -> title); //Event title - placeholder formatting characters are converted to HTML formatting so that it can be styled appropriately
		$type = $event -> categories -> category[0]; //Event type
		if ($type == "Match") { $type = $event -> categories -> category[1]; } //If it's sport, find the type of sport
		$depart = $event -> timed -> {'envelope-start'}; $pickup = $event -> timed -> {'envelope-end'}; //Away game details
		if ($event -> location != "") { $where = $event -> location -> attributes(); } //Driving directions to an away game (checks if the location node exists first)
		
		if ((isset($comparetitle) && strpos($title,$comparetitle) !== false) && "$type" == "$comparetype" && "$where" == "$comparewhere") { //If it's a sporting fixture with the same opponent and in the same place as the previous event, just detail the title alongside the previous information
			echo "<p class=\"details\">".$title."</p>";
			} 
		else { //Otherwise, display a full event
		
			if ((isset($compareday) && $displayday !== $compareday) || !isset($compareday)) { //If it's a new day, show this
				if ($count > 1) { echo "<hr>"; }
				echo "<h2 class=\"events\">".$displayday;
				if ((isset($comparemonth) && $displaymonth !== $comparemonth) || !isset($comparemonth)) { //And if it's a new month, show this too
					echo "<span>".$displaymonth."</span>";
					}
				echo "</h2>";
				$count++;
				}
		
			//Basic event time, title and type
			if ($start != "") {
				echo "<p class=\"time\">".$start." - ".$end."</p>";
				}
				else { echo "<p class=\"time\"></p>"; }
			echo "<h3>".$title."</h3>";
			echo "<p class=\"allevents\"><a href=\"/diary/".substr(date('Ymd',$date),6,2)."/".substr(date('Ymd',$date),4,2)."/".substr(date('Ymd',$date),0,4)."\">See in the diary</a></p>";
		
			//Further details
			if ($depart != "") { //This indicates that it's an away sporting fixture, which then prompts further details
				echo "<p class=\"details\">";
				echo "Depart at ".$depart.". Pick-up at ".$pickup.".</p>";
				echo "<p class=\"details\"><a class=\"detaillink\" href=\"".$where[1]."\">Driving directions</a>.</p>";
				}
		
      if ($type != "") { //Provides a fix for when a type has not been provided (there should always be a type, but this avoids errors if it's been left out by mistake)
        if (strpos($type,$title) !== false) { //Checks to see if the event type is in the title itself (typically if it's a sporting fixture) and then provides just the detail after it
			    $comparetitle = explode($type,$title);
			    $comparetitle = $comparetitle[1];
          }
        else { $comparetitle = $title; } //Otherwise, gives the whole title to compare
			  $comparetype = $type;
        }
      else {
				$comparetitle = $title;
				$comparetype = "None";
				}
			if (isset($where)) { $comparewhere = $where; } else { $comparewhere = ""; }
			//This sets up a check for sporting events fixtures
			
			$compareday = $displayday;
			$comparemonth = $displaymonth;
			//This sets up a check for showing the day
			
			$notblank++; //Confirms that an event has been displayed
			}
			}
		}
	if ($notblank == 0) { echo "<p>Sorry, there are no upcoming events of this type yet scheduled. Perhaps try again later.</p>"; }
	else { echo "<hr>"; }
	}
else { //Displays an error if the event file can't be found
	echo "<style> body { background-image: url('/styles/imgs/error.png'); background-position: right bottom; background-repeat: no-repeat; background-attachment: fixed; background-size: 980px auto; } </style>";
	echo '<div class="parsebox">';
  echo "<h1>Oh dear!</h1>";
	echo "<p>It seems the file for this type of event has been misplaced... or perhaps <em>it never existed at all.</em><br />You could go back to the diary to try again, or you could <a href=\"/about/contact/\">contact us</a> to report the problem.</p>";
  echo '</div>';
	} 

?>