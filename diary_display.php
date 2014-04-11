<?php include('header_declarations.php');

if (!isset($_GET['event'])) { $get_event = ""; } else { $get_event = $_GET['event']; }
if (!isset($_GET['date'])) { $get_date = ""; } else { $get_date = $_GET['date']; }
if (!isset($_GET['device'])) { $get_device = ""; } else { $get_device = $_GET['device']; }
if (!isset($_GET['display'])) { $get_display = ""; } else { $get_display = $_GET['display']; }

?>

<link rel="stylesheet" type="text/css" media="screen and (min-device-width : 480px)" href="/styles/diary_lrg.css"/>
<link rel="stylesheet" type="text/css" media="screen and (max-device-width : 480px)" href="/styles/diary_sml.css"/>

<?php if ($get_device != "mobile") { ?>
<script type="text/javascript" language="javascript"> // Jumps the page to the actual day being navigated
	function moveWindow (){window.location.hash="<?php echo $get_date; ?>";}
</script>

<?php }

include('header_navigation.php');

if ($get_event != "") { //We want to be looking at an events page: deal with this first

	$type = $get_event;
	
	echo "<div class=\"ncol lft\">";
		echo "<h2 class=\"event_title\">".$type;
			if ($type == "Event" || $type == "Visit" || $type == "Meeting" || $type == "Highlight") { echo "s"; } //This sorts out grammar
		echo "</h2>";
		echo "<div class=\"linkbox\">";
			echo "<a href=\"/diary/".date('d')."/".date('m')."/".date('Y')."\"><h3>Main diary</h3></a>";
		echo "</div>";
		echo "<div class=\"linkbox\">";
			echo "<a href=\"/rich/Information/General information/Term dates\"><h3>Term dates</h3></a>";
		echo "</div>";
	echo "</div>";
	
	echo "<div class=\"mcol-rgt\" id=\"diary\">";
		echo "<div class=\"day\">";
			include ('diary_readevent.php');
		echo "</div>";
	echo "</div>";

	} else { //This is the actual calendar output

	if ($get_date != "") { $focusdate = $get_date; }
	else { $focusdate = date("Ymd"); }

	$weekday = date("N",mktime(0,0,0,substr($focusdate,4,2),substr($focusdate,6,2),substr($focusdate,0,4)))-1;
	$datestamp = date("Ymd",mktime(0,0,0,substr($focusdate,4,2),substr($focusdate,6,2)-$weekday,substr($focusdate,0,4))); //This is the date on the Monday of the selected week.

if (($get_device == "mobile" && $get_display == "calendar") || $get_device == "") { //Don't display the calendar on mobiles, unless the request has been made specifically
	
	echo "<div class=\"ncol lft\">";
		include ('diary_calendar.php');
		echo "<div class=\"linkbox lrg\">";
			echo "<a href=\"/rich/Information/General information/Term dates\"><h3>Term dates</h3></a>";
		echo "</div>";
	echo "</div>";
	
	}
else { //Mobile only navigation

	$lastmonday = date("Ymd",mktime(0,0,0,substr($datestamp,4,2),substr($datestamp,6,2)-7,substr($datestamp,0,4)));
	$nextmonday = date("Ymd",mktime(0,0,0,substr($datestamp,4,2),substr($datestamp,6,2)+7,substr($datestamp,0,4)));
	
	echo "<div class=\"browse\">";
		echo "<p><span class=\"lft\"><a href=\"/diary/".substr($lastmonday,6,2)."/".substr($lastmonday,4,2)."/".substr($lastmonday,0,4)."/&device=mobile\">&#171; Last week</a></span> ";
		echo "<a href=\"/diary/".substr($nextmonday,6,2)."/".substr($nextmonday,4,2)."/".substr($nextmonday,0,4)."/&device=mobile\">Next week &#187;</a></p>";
	echo "</div>";
	
	}
	
if (($get_device == "mobile" && $get_display != "calendar") || $get_device == "") { //Only display dates on mobiles when the calendar is not being viewed

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