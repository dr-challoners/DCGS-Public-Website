<?php echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"; ?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:svg="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"  xmlns:xml="http://www.w3.org/XML/1998/namespace">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-Style-Type" content="text/css" />
		
		<title>
		<?php // Creating more informative titles
		
		if (isset($_GET['subfolder'])) { echo $_GET['subfolder']." - "; } // General content pages
		if (isset($_GET['page'])) { echo $_GET['page']." - "; }
		if (isset($_GET['gallery'])) { echo $_GET['gallery']." - "; }
		
		if (isset($_GET['story'])) { // News stories
			$news_title = explode ("~",$_GET['story']);
			echo "News - ".$news_title[1]." - ";
			}
			
		if (isset($intranet)) { echo "Intranet - "; }
		
		if (isset($_GET['date']) || isset($_GET['event'])) { echo "Diary - "; } // Diary and events pages
		
		?>
		Dr Challoner's Grammar School</title>
		
		<link rel="icon" href="/main_imgs/favicon.png" />
		<link rel="shortcut icon" href="/main_imgs/favicon.png" />
		
		<!-- Homescreen icons for iPhone/iPad. Android should detect these as well. -->
		<link rel="apple-touch-icon" sizes="57x57" href="/main_imgs/apple-icon-60.png" />
		<link rel="apple-touch-icon" sizes="72x72" href="/main_imgs/apple-icon-76.png" />
		<link rel="apple-touch-icon" sizes="114x114" href="/main_imgs/apple-icon-120.png" />
		<link rel="apple-touch-icon" sizes="144x144" href="/main_imgs/apple-icon-152.png" />
		
		<link rel="stylesheet" type="text/css" media="screen" href="/styles/general.css"/>
		<link rel="stylesheet" type="text/css" media="screen and (min-device-width : 480px)" href="/styles/screen_lrg.css"/>
		<link rel="stylesheet" type="text/css" media="screen and (max-device-width : 480px)" href="/styles/screen_sml.css"/>
		<!-- <link rel="stylesheet" type="text/css" href="/styles/screen_sml.css"/> For testing/re-styling -->
		<?php //Dirty rotten browser hacks
			if(strpos($_SERVER['HTTP_USER_AGENT'],"Trident") != "") { //Hits IE by spotting the IE rendering engine
				echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"/styles/screen_lrg_ie.css\"/>";
				}
			if(strpos($_SERVER['HTTP_USER_AGENT'],"iPad") != "") { //Hits iPads by spotting... that they're iPads
				echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"/styles/screen_lrg_ipad.css\"/>";
				echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"screen	and (orientation : portrait)\" href=\"/styles/screen_lrg_ipad_portrait.css\"/>";
				}
		?>
		
		<?php
			include('php/parsedown.php'); //See parsedown.org
			date_default_timezone_set("Europe/London");
		?>
		
		<!-- The head tag concludes in header_navigation.php: this allows more code to be added to the head tag. -->