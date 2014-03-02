<?php include('header_declarations.php'); ?>

<link rel="stylesheet" type="text/css" media="screen and (min-device-width : 480px)" href="/styles/diary_lrg.css"/>
<link rel="stylesheet" type="text/css" media="screen and (max-device-width : 480px)" href="/styles/diary_sml.css"/>

<?php

include('header_navigation.php');

if ($_GET['event'] != "") { //We want to be looking at an events page: deal with this first

	$type = $_GET['event'];
	
	echo "<div class=\"ncol lft\">";
		echo "<h2>".$type;
			if ($type == "Event" || $type == "Visit" || $type == "Meeting" || $type == "Highlight") { echo "s"; } //This sorts out grammar
		echo "</h2>";
		echo "<div class=\"linkbox\">";
			echo "<a href=\"/diary/".date(Y)."/".date(m)."/".date(Ymd)."#".date(Ymd)."\"><h3>Main diary</h3></a>";
		echo "</div>";
		echo "<div class=\"linkbox\">";
			echo "<a href=\"/content_plain/termdates/\"><h3>Term dates</h3></a>";
		echo "</div>";
	echo "</div>";
	
	echo "<div class=\"mcol-rgt\" id=\"diary\">";
		echo "<div class=\"day\">";
			include ('diary_readevent.php');
		echo "</div>";
	echo "</div>";

	} else { //This is the actual calendar output

	if ($_GET['date'] != "") { $focusdate = $_GET['date']; }
	else { $focusdate = date("Ymd"); }

	$weekday = date("N",mktime(0,0,0,substr($focusdate,4,2),substr($focusdate,6,2),substr($focusdate,0,4)))-1;
	$datestamp = date("Ymd",mktime(0,0,0,substr($focusdate,4,2),substr($focusdate,6,2)-$weekday,substr($focusdate,0,4))); //This is the date on the Monday of the selected week.

if (($_GET['device'] == "mobile" && $_GET['display'] == "calendar") || $_GET['device'] == "") { //Don't display the calendar on mobiles, unless the request has been made specifically
	
	echo "<div class=\"ncol lft\">";
		include ('diary_calendar.php');
		echo "<div class=\"linkbox\">";
			echo "<a href=\"/content_plain/termdates/\">";
			if ($_GET['device'] == "mobile") { echo "<h3>See term dates</h3>"; } //Just because this wording makes slightly more sense for the mobile version
			else { echo "<h3>Term dates</h3>"; }
			echo "</a>";
		echo "</div>";
	echo "</div>";
	
	}
else { //Mobile only navigation

	$lastmonday = date("Ymd",mktime(0,0,0,substr($datestamp,4,2),substr($datestamp,6,2)-7,substr($datestamp,0,4)));
	$nextmonday = date("Ymd",mktime(0,0,0,substr($datestamp,4,2),substr($datestamp,6,2)+7,substr($datestamp,0,4)));
	
	echo "<div class=\"browse\">";
		echo "<p><span class=\"lft\"><a href=\"/diary/m/".$lastmonday."\">&#171; Last week</a></span> ";
		echo "<a href=\"/diary/m/".$nextmonday."\">Next week &#187;</a></p>";
	echo "</div>";
	
	echo "<div class=\"linkbox\">";
		echo "<a href=\"/diary/m/c/".date(Y)."/".date(m)."/\"><h3>Browse the calendar</h3></a>";
		echo "<a href=\"/about/termdates/\"><h3>See term dates</h3></a>";
	echo "</div>";
	}
	
if (($_GET['device'] == "mobile" && $_GET['display'] != "calendar") || $_GET['device'] == "") { //Only display dates on mobiles when the calendar is not being viewed

	echo "<div class=\"mcol-rgt\" id=\"diary\">";
	for ($day = 0; $day <= 6;) {
		echo "<a class=\"anchor\" name=\"".$datestamp."\"></a>";
		echo "<div class=\"day\"";
		if($day == 6) { echo " id=\"sun\""; }
		echo ">";
			include ('diary_readdate.php');
			echo "<hr>";
		echo "</div>";
		$day++; $datestamp = date("Ymd",mktime(0,0,0,substr($datestamp,4,2),substr($datestamp,6,2)+1,substr($datestamp,0,4)));
		}
	echo "</div>";
	
	}
	}

include('footer.php');

?>