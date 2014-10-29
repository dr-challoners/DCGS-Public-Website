<?php include('header_declarations.php');

if (!isset($_GET['event'])) { $get_event = ""; } else { $get_event = $_GET['event']; }
if (!isset($_GET['date'])) { $get_date = ""; } else { $get_date = $_GET['date']; }
if (!isset($_GET['device'])) { $get_device = ""; } else { $get_device = $_GET['device']; }
if (!isset($_GET['display'])) { $get_display = ""; } else { $get_display = $_GET['display']; }

if ($get_device != "mobile") {
	echo "<meta name=\"robots\" content=\"noindex\">"; // Prevents mobile versions of the page from appearing in Google searches, so it isn't broken when you click on it (breaks in mobiles then, of course, but oh well)
	}
	
?>
<?php if (!preg_match('/(?i)msie [4-8]/',$_SERVER['HTTP_USER_AGENT'])) { // IE 8 or earlier can't handle media queries
  echo '<link rel="stylesheet" type="text/css" media="screen and (min-device-width : 480px)" href="/styles/diary_lrg.css"/>';
  echo '<link rel="stylesheet" type="text/css" media="screen and (max-device-width : 480px)" href="/styles/diary_sml.css"/>';
  } else {
    echo '<link rel="stylesheet" type="text/css" href="/styles/diary_lrg.css"/>';
  } ?>

<?php if ($get_device != "mobile") { ?>
<script type="text/javascript" language="javascript"> // Jumps the page to the actual day being navigated
	function moveWindow (){window.location.hash="<?php echo $get_date; ?>";}
</script>

<?php }

include('header_navigation.php');

if ($get_event != "") { //We want to be looking at an events page: deal with this first

	$type = $get_event;
	
	echo "<!--googleoff: all--><div class=\"ncol lft\">";
		echo "<h2 class=\"event_title\">".$type;
			if ($type == "Event" || $type == "Visit" || $type == "Meeting" || $type == "Highlight") { echo "s"; } //This sorts out grammar
		echo "</h2>";
		echo "<div class=\"linkbox\">";
			echo "<a href=\"/diary/".date('d')."/".date('m')."/".date('Y')."\"><h3>Main diary</h3></a>";
		echo "</div>";
		echo "<div class=\"linkbox\">";
			echo "<a href=\"/pages/Information/General information/Term dates\"><h3>Term dates</h3></a>";
		echo "</div>";
	echo "<!--googleon: all--></div>";
	
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
	
	echo "<!--googleoff: all--><div class=\"ncol lft\">";
		include ('diary_calendar.php');
		echo "<div class=\"linkbox lrg\">";
			echo "<a href=\"/pages/Information/General information/Term dates\"><h3>Term dates</h3></a>";
		echo "</div>";
	echo "<!--googleon: all--></div>";
	
	}
else { //Mobile only navigation

	$lastmonday = date("Ymd",mktime(0,0,0,substr($datestamp,4,2),substr($datestamp,6,2)-7,substr($datestamp,0,4)));
	$nextmonday = date("Ymd",mktime(0,0,0,substr($datestamp,4,2),substr($datestamp,6,2)+7,substr($datestamp,0,4)));
	
	echo "<div class=\"browse\">";
		echo "<p class=\"weeknav\"><span class=\"lft\"><a href=\"/diary/".substr($lastmonday,6,2)."/".substr($lastmonday,4,2)."/".substr($lastmonday,0,4)."/&device=mobile\">&#171; Last week</a></span> ";
		echo "<a href=\"/diary/".substr($nextmonday,6,2)."/".substr($nextmonday,4,2)."/".substr($nextmonday,0,4)."/&device=mobile\">Next week &#187;</a></p>";
	echo "</div>";
	
	}
	
if (($get_device == "mobile" && $get_display != "calendar") || $get_device == "") { //Only display dates on mobiles when the calendar is not being viewed

	echo "<div class=\"mcol-rgt\" id=\"diary\">";
	for ($day = 0; $day <= 6;) {
		echo "<a class=\"anchor\" name=\"".$datestamp."\"></a>";
		echo "<div class=\"day\" id=\"".$datestamp."\"";
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

<script type="text/javascript">
		//<![CDATA[
		var _refresh = 300000; // 1 Hour = 3600000, 5 Minutes = 300000, 1 Minute = 60000
    window.onload = function() { init() };
    function init() {
        //console.log("Hello World");
        __LoadGoogle("1zss_IMzdyj7RV_kknkEP0qZWepC3EI7A0n_9U-PH5M4", _refresh); // Live Version
        
<a herf="https://www.google.com/calendar/embed?src=challoners.org_8h6ikktg6vv0haq6squ06r1je8%40group.calendar.google.com&ctz=Europe/London"</a herf>
      




//__LoadGoogleCalendar("http://www.google.com/calendar/feeds/challoners.org_8h6ikktg6vv0haq6squ06r1je8@group.calendar.google.com/public/full?orderby=starttime&sortorder=ascending&futureevents=true&alt=json",_refresh); 
        // __LoadGoogle("1lY3vb4KP1skCcY2LeR5KdMhIGt3P3s1uOZTb098UgjY", _refresh); // JD Dev Version
    }
  
		//]]>
</script>