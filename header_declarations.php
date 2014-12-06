<?php 
  echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:svg="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"  xmlns:xml="http://www.w3.org/XML/1998/namespace">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-Style-Type" content="text/css" />
		
		<title>
		<?php // Creating more informative titles
		
		if (isset($_GET['subfolder'])) { echo str_replace('_',' ',$_GET['subfolder'])." - "; } // General content pages
		if (isset($_GET['page'])) { echo str_replace('_',' ',str_replace('[plus]','+',$_GET['page']))." - "; }
		
		if (isset($_GET['story'])) { // News stories
			$news_title = explode ("~",$_GET['story']);
			echo "News - ".str_replace('_',' ',$news_title[1])." - ";
			}
			
		if (isset($intranet)) { echo "Intranet - "; }
		
		if (isset($_GET['date']) || isset($_GET['event'])) { echo "Diary - "; } // Diary and events pages
		
		?>
		Dr Challoner's Grammar School</title>
		
		<link rel="icon" href="/styles/imgs/favicon.png" />
		<link rel="shortcut icon" href="/styles/imgs/favicon.png" />
		
		<!-- Homescreen icons for iPhone/iPad. Android should detect these as well. -->
		<link rel="apple-touch-icon" sizes="57x57" href="/styles/imgs/apple-icon-60.png" />
		<link rel="apple-touch-icon" sizes="72x72" href="/styles/imgs/apple-icon-76.png" />
		<link rel="apple-touch-icon" sizes="114x114" href="/styles/imgs/apple-icon-120.png" />
		<link rel="apple-touch-icon" sizes="144x144" href="/styles/imgs/apple-icon-152.png" />
		
		<link rel="stylesheet" type="text/css" media="screen" href="/styles/general.css"/>
    <?php if (!preg_match('/(?i)msie [4-8]/',$_SERVER['HTTP_USER_AGENT'])) { // IE 8 or earlier can't handle media queries - and as such is AN UTTER PAIN
		  echo '<link rel="stylesheet" type="text/css" media="screen and (min-device-width : 481px)" href="/styles/screen_lrg.css"/>';
    } else {
      echo '<link rel="stylesheet" type="text/css" href="/styles/screen_lrg.css"/>';
    } ?>
		
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
      include('parsing/config_dcgs.php');
		?>
    
    <!-- Put the small styles after the ParseBox styles to make them easier to override. This is all very messy, but it can be tidied up later... for now, let's get the job done! -->
    <?php if (!preg_match('/(?i)msie [4-8]/',$_SERVER['HTTP_USER_AGENT'])) {
      echo '<link rel="stylesheet" type="text/css" media="screen and (max-device-width : 480px)" href="/styles/screen_sml.css"/>';
      } ?>
		
		<!-- The head tag concludes in header_navigation.php: this allows more code to be added to the head tag. -->