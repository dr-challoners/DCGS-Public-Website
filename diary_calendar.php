<?php

if (!isset($_GET['y'])) { $get_y = ""; } else { $get_y = $_GET['y']; }
if (!isset($_GET['m'])) { $get_m = ""; } else { $get_m = $_GET['m']; }

if ($get_y != "") { $y = $get_y; } else { $y = date("Y"); } //If no year and month are selected, get the current one
if ($get_m != "") { $m = $get_m; } else { $m = date("m"); }

?>

<div class="calendar">
		<p class="month">
			<?php
			$lyear = date("Y",mktime(0,0,0,$m-1,1,$y)); $lmonth = date("m",mktime(0,0,0,$m-1,1,$y));
			$nyear = date("Y",mktime(0,0,0,$m+1,1,$y)); $nmonth = date("m",mktime(0,0,0,$m+1,1,$y));
			?>
			
			<a class="lmonth" href="/diary/<?php
				if ($get_device == "mobile") { echo "m/c/"; } //If we're on a mobile, stay on the mobile view
				echo $lyear."/".$lmonth."/";
				if ($get_device != "mobile") { echo $focusdate."#".$focusdate; } //Mobile view does not use the current date for the calendar
			?>">&#171;</a>
			
			<?php echo date("F Y",mktime(0,0,0,$m,1,$y)); ?>
			
			<a class="nmonth" href="/diary/<?php
				if ($get_device == "mobile") { echo "m/c/"; }
				echo $nyear."/".$nmonth."/";
				if ($get_device != "mobile") { echo $focusdate."#".$focusdate; } //Mobile view does not use the current date for the calendar
			?>">&#187;</a> 
		</p>
		<div class="weekdays">
			<p>Mon</p>
			<p>Tue</p>
			<p>Wed</p>
			<p>Thu</p>
			<p>Fri</p>
			<p>Sat</p>
			<p>Sun</p>
		</div>
		
<?php

$startday = date("N",mktime(0,0,0,$m,1,$y)); //Determines at what point in the week the month starts
$previews = array(); //We'll put things in this later (dates for which previews are needed - ie there's events on that day)
for ($day = 1; $day <= 42;) {
	$fulldate = date("Ymd",mktime(0,0,0,$m,$day-($startday-1),$y));
	if (($day-1)%7 == 0) {
		echo "<div class=\"week\""; //At the start of the week, begin a new week box
		if($fulldate == $datestamp) { echo " id=\"selected\""; } //Highlight the week we're looking at
		echo ">";
	} 
	echo "<p class=\"";
	if ($day < $startday || $day >= date("t",mktime(0,0,0,$m,1,$y))+$startday ) { echo "xmonth"; } //If we're not displaying a day from the current month, grey it out.
	if ($fulldate == date("Ymd")) { echo " today"; } //Mark today
	echo "\"";
	if (file_exists("content_plain/diary/".$fulldate.".xml") && strpos($_SERVER['HTTP_USER_AGENT'],"iPad") == "" && $get_device == "") { //There's something to preview, so create a preview (but don't do this on the iPad or mobiles, because then you have to click to preview and then again to go to a date...)
		echo " onmouseover=\"mopen('c".$fulldate."')\" onmouseout=\"mclosetime()\""; //Create a JS cue with an appropriate id
		array_push($previews,$fulldate); //Save the date to be acknowledged later
		}
	echo ">";
		echo "<a href=\"/diary/";
		if ($get_device == "mobile") { echo "m/"; } //If we're on a mobile, stay on the mobile view
		if ($get_device != "mobile") { echo substr($fulldate,0,4)."/".substr($fulldate,4,2)."/"; } //Mobiles don't take calendar date and month when displaying actual days' events
		echo $fulldate."#".$fulldate."\">";
		echo date("j",mktime(0,0,0,substr($fulldate,4,2),substr($fulldate,6,2),substr($fulldate,0,4)));
	echo "</a></p>";
	if ($day %7 == 0) { echo "</div>"; } //Shut down a week box at the end of the week
$day++; }

if ($get_device == "") { //Don't produce for mobiles

$charmax = 30; //Maximum number of letters in a preview title (see below)
foreach($previews as $row) {
	echo "<div id=\"c".$row."\" class=\"preview\">";
		echo "<h3>".date("l jS",mktime(0,0,0,substr($row,4,2),substr($row,6,2),substr($row,0,4)))."</h3>";
		$stubdate = simplexml_load_file("content_plain/diary/".$row.".xml");
		foreach($stubdate->events->event as $event) { //Work through each event in the previewed date one at a time
			$stubtitle = $event -> title;
			if(strlen($stubtitle) > $charmax) { $stubtitle = substr($stubtitle,0,$charmax)."..."; } //Gives a shortened version of the full title
			echo "<p>".$stubtitle."</p>";
			}
		echo "<p class=\"more\">Click the date to see more detail.</p>";
	echo "</div>";
	}
	
	}

?>

</div>

