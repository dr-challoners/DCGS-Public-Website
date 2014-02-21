<?php

if ($_GET['y'] != "") { $y = $_GET['y']; } else { $y = date("Y"); } //If no year and month are selected, get the current one
if ($_GET['m'] != "") { $m = $_GET['m']; } else { $m = date("m"); }

?>

<div class="calendar">
		<p class="month">
			<?
			$lyear = date("Y",mktime(0,0,0,$m-1,1,$y)); $lmonth = date("m",mktime(0,0,0,$m-1,1,$y));
			$nyear = date("Y",mktime(0,0,0,$m+1,1,$y)); $nmonth = date("m",mktime(0,0,0,$m+1,1,$y));
			?>
			<a class="lmonth" href="?<?
			if ($_GET['device'] == "mobile") { echo "device=mobile&display=calendar&"; } //If we're on a mobile, stay on the mobile view
			echo "date=".$focusdate."&y=".$lyear."&m=".$lmonth."#".$focusdate.""; ?>">&#171;</a>
			<? echo date("F Y",mktime(0,0,0,$m,1,$y)); ?>
			<a class="nmonth" href="?<?
			if ($_GET['device'] == "mobile") { echo "device=mobile&display=calendar&"; }
			echo "date=".$focusdate."&y=".$nyear."&m=".$nmonth."#".$focusdate.""; ?>">&#187;</a>
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
		
<?

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
	if (file_exists("./items/".$fulldate.".xml") && strpos($_SERVER['HTTP_USER_AGENT'],"iPad") == "" && $_GET['device'] == "") { //There's something to preview, so create a preview (but don't do this on the iPad or mobiles, because then you have to click to preview and then again to go to a date...)
		echo " onmouseover=\"mopen('c".$fulldate."')\" onmouseout=\"mclosetime()\""; //Create a JS cue with an appropriate id
		array_push($previews,$fulldate); //Save the date to be acknowledged later
		}
	echo ">";
		echo "<a href=\"?";
		if ($_GET['device'] == "mobile") { echo "device=mobile&"; } //If we're on a mobile, stay on the mobile view
		echo "date=".$fulldate."&y=".substr($fulldate,0,4)."&m=".substr($fulldate,4,2)."#".$fulldate."\">";
		echo date("j",mktime(0,0,0,substr($fulldate,4,2),substr($fulldate,6,2),substr($fulldate,0,4)));
	echo "</a></p>";
	if ($day %7 == 0) { echo "</div>"; } //Shut down a week box at the end of the week
$day++; }

if ($_GET['device'] == "") { //Don't produce for mobiles

$charmax = 30; //Maximum number of letters in a preview title (see below)
foreach($previews as $row) {
	echo "<div id=\"c".$row."\" class=\"preview\">";
		echo "<h3>".date("l jS",mktime(0,0,0,substr($row,4,2),substr($row,6,2),substr($row,0,4)))."</h3>";
		$stubdate = simplexml_load_file("./items/".$row.".xml");
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

