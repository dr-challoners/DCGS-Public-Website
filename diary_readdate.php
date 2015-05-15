<?php

$makedate = mktime(0,0,0,substr($datestamp,4,2),substr($datestamp,6,2),substr($datestamp,0,4));
$checkdate = date("D M j, Y",$makedate); // For reading the Google Calendar date format
$calday = date("j",$makedate); //calday and calweekday check the entry's position in the month and week
$calweekday = date("N",$makedate);
$caldate = date("l jS",$makedate); //Puts the date in a friendly format for display, eg 'Saturday 15th'
$calmonth = date("F Y",$makedate); //For displaying month and year

$eventdata = array(); // This will be the array containing every detail for the selected day

foreach ($events -> entry as $entry) { // Go through the XML file and process all the events
  $date = $entry -> summary; // Get just the date information
  $date = explode ("\n",$date);
  $date = substr($date[0],6); // Date is now isolated as a single line
  if (strlen($date) <= 20) { // Just a start date, no time
    $startdate = rtrim(substr($date,0,16),"<"); // Chops out a rogue line break
    $finaldate = "";
    $time = "";
  } else { // If there's more, take out the first date anyway and then process the rest
    $startdate = rtrim(substr($date,0,16));
    $date = trim(substr($date,16)," &nbsp;");
    if (substr_count($date,",") == 0) { // There's no final date, so time is the only remaining information
      $date = explode(" to ",$date);
      $time = array();
      foreach ($date as $point) {
        if (substr($point,-2) == "pm") { // To convert to 24 hour
          $add = 12;
        } else {
          $add = 0;
        }
        // Various processes to transform each timepoint to a standard HH:MM format
        $point = substr($point,0,-2);
        $point = explode(":",$point);
        $point[0] = $point[0]+$add;
        $point = implode(":",$point);
        if (strlen($point) < 3) {
          $point .= ":00";
        }
        if (strlen($point) < 5) {
          $point = str_pad($point,5,"0",STR_PAD_LEFT);
        }
        array_push($time,$point);
      }
      $time = implode(" - ",$time); // And finally bring it all together!
      $finaldate = "";
    } elseif (substr($date,0,2) == "to") {
      $finaldate = substr($date,3);
      $time = "";
    } else {
      $date = explode("to ",$date);
      $finaldate = rtrim(substr($date[1],0,16));
    }
  }
  
  // Now convert the dates to YYYYMMDD format to compare with $datestamp
  $wmonths = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
  $nmonths = array("01","02","03","04","05","06","07","08","09","10","11","12");
  
  $startdate = str_replace($wmonths,$nmonths,$startdate);
  $startdate = explode(" ",$startdate);
  if (isset($startdate[2])) {
    $startdate = $startdate[3].$startdate[1].str_pad(substr($startdate[2],0,-1),2,"0",STR_PAD_LEFT);
    if ($finaldate != "") {
      $finaldate = str_replace($wmonths,$nmonths,$finaldate);
      $finaldate = explode(" ",$finaldate);
      if (isset($finaldate[2])) {
        $finaldate = $finaldate[3].$finaldate[1].str_pad(substr($finaldate[2],0,-1),2,"0",STR_PAD_LEFT);
      }
    }
  }
  
  // If the event date matches the current date, or the current date falls within the range of dates for the event, pull relevant details for display
  if ($startdate == $datestamp || ($finaldate != "" && ($startdate <= $datestamp && $finaldate >= $datestamp))) {
    $title = (string)$entry -> title;
    // Pull the description from the content
    $description = (string)$entry -> content;
    $description = explode ("\n",$description);
    $line = count($description);
    $line = $line-1;
    $description = explode("Event Description:",$description[$line]);
    if (isset($description[1])) {
      $description = $description[1];
    } else { $description = ""; }
    // Put all the details in an array, to be read for display
    $event = array("time" => $time, "title" => $title, "description" => $description);
    array_push($eventdata, $event);
  }
}

if (!empty($eventdata)) {
  echo "<h2>".$caldate; // Put the date first (note that this is from the datestamp itself, not the xml - but it needs to be here as there's a change of style if there's no events)
	if ($calday == "1" || $calweekday == "1") { // If it's the start of the month or the first entry displayed (ie, a Monday), then give the month
		echo "<span>".$calmonth."</span>";
		}
	echo "</h2>";
  foreach($eventdata as $event) { // Work through each event one at a time
    if ($event["time"] != "") {
			echo "<p class=\"time\">".$event["time"]."</p>";
		}	else { echo "<p class=\"time\"></p>"; }
    echo "<h3>".$event["title"]."</h3>";
    if ($event["description"] != "") {
			echo "<p class=\"details\">";
			echo $event["description"];
			echo "</p>";
		}
  }
}

else { //If there are no events at all, just give the date (for completeness of the diary)
  echo "<h2 class=\"noevents\">".$caldate;
	if ($calday == "1" || $calweekday == "1") { //If it's the start of the month or the first entry displayed (ie, a Monday), then give the month
		echo "<span>".$calmonth."</span>";
		}
	echo "</h2>";
} 



?>