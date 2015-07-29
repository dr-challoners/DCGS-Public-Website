<?php include('header_declarations.php');

if (!isset($_GET['event'])) { $get_event = ""; } else { $get_event = $_GET['event']; }
if (!isset($_GET['date'])) { $get_date = ""; } else { $get_date = $_GET['date']; }
if (!isset($_GET['device'])) { $get_device = ""; } else { $get_device = $_GET['device']; }
if (!isset($_GET['display'])) { $get_display = ""; } else { $get_display = $_GET['display']; }

if ($get_device != "mobile") {
	echo '<meta name="robots" content="noindex">'; // Prevents mobile versions of the page from appearing in Google searches, so it isn't broken when you click on it (breaks in mobiles then, of course, but oh well)
	}
	
?>
<?php if (!preg_match('/(?i)msie [4-8]/',$_SERVER['HTTP_USER_AGENT'])) { // IE 8 or earlier can't handle media queries
  echo '<link rel="stylesheet" type="text/css" media="screen and (min-device-width : 481px)" href="/styles/diary_lrg.css"/>';
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

// Every half an hour, cache the XML file from Google Calendar, so that requests can be made locally to speed up load times
if (file_exists('data_diary/diary_lastupdate.txt')) {
  $lastupdate = file_get_contents('data_diary/diary_lastupdate.txt');
} else { $lastupdate = ""; }

$updatetime = time();
$updatetime = $updatetime-1800;
if ($lastupdate < $updatetime) {
  $data = new DOMDocument();
  $data->load("https://www.google.com/calendar/feeds/challoners.org_1e3c7g4qn1kic52usnlbrn63ts%40group.calendar.google.com/public/basic?start-index=1&max-results=10000&hl=en_US");
  $data->save('data_diary/diary.xml');
  file_put_contents('data_diary/diary_lastupdate.txt',time());
}

$events = simplexml_load_file('data_diary/diary.xml');

	if ($get_date != "") { $focusdate = $get_date; }
	else { $focusdate = date("Ymd"); }

	$weekday = date("N",mktime(0,0,0,substr($focusdate,4,2),substr($focusdate,6,2),substr($focusdate,0,4)))-1;
	$datestamp = date("Ymd",mktime(0,0,0,substr($focusdate,4,2),substr($focusdate,6,2)-$weekday,substr($focusdate,0,4))); // This is the date on the Monday of the selected week.

if (($get_device == "mobile" && $get_display == "calendar") || $get_device == "") { // Don't display the calendar on mobiles, unless the request has been made specifically
	
	echo "<!--googleoff: all--><div class=\"ncol lft\">";
		include ('diary_calendar.php');
		echo '<div class="diarylinks lrg">';
			echo '<p id="terms"><a href="/pages/Information/General information/Term dates">Term dates</a></p>';
      echo '<p><a target="page'.mt_rand().'" href="https://www.google.com/calendar/embed?src=challoners.org_1e3c7g4qn1kic52usnlbrn63ts%40group.calendar.google.com&ctz=Europe/London">View in Google Calendar</a></p>';
      echo '<p><a href="https://www.google.com/calendar/ical/challoners.org_1e3c7g4qn1kic52usnlbrn63ts%40group.calendar.google.com/public/basic.ics">Download iCal format</a></p>';
		echo "</div>";   
          
	echo "<!--googleon: all--></div>";
	
	}
else { // Mobile only navigation

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

include('footer.php');
?>

<script type="text/javascript">
	var _refresh = 300000; // 1 Hour = 3600000, 5 Minutes = 300000, 1 Minute = 60000
    	window.onload = function() { init() };
    	
    	function init() {
        	
        	__LoadGoogle("1zss_IMzdyj7RV_kknkEP0qZWepC3EI7A0n_9U-PH5M4", _refresh); // Live Version
        
    	}
  </script>
