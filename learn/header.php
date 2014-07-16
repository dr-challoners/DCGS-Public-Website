<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:svg="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"  xmlns:xml="http://www.w3.org/XML/1998/namespace">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-Style-Type" content="text/css" />
		
		<?php $rootpath = "learn/"; // Set this if this system is not in the main directory. Include a "/" at the end of the path. ?>
		
		<?php // We need to get data from the config file in order to display the name of the micro-site we're looking at, and get other data
		
		if (isset($_GET['subject']) && file_exists("../content_learn/".$_GET['subject']."/config.txt")) { // There needs to be a config file in the specified subject folder
			$data = file("../content_learn/".$_GET['subject']."/config.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			foreach ($data as $datum) { // All lines in the config file should be in the form 'Var: Data'. This turns that into $ConfigVar == "Data".
				$datum = explode (": ",$datum);
				$varname = "Config".$datum[0];
				$$varname = $datum[1];
				}
			}
			
		?>
		
		<title>
			<?php // Include more informative titles here
      echo "Learn ";
      if(isset($ConfigTitle)) { echo $ConfigTitle; }
      echo " - Dr Challoner's Grammar School"; ?>
		</title>
		
		<link rel="icon" href="/<?php echo $rootpath; ?>styles/imgs/favicon.png" />
		<link rel="shortcut icon" href="/<?php echo $rootpath; ?>styles/imgs/favicon.png" />
		
		<!-- Homescreen icons for iPhone/iPad. Android should detect these as well. -->
		<link rel="apple-touch-icon" sizes="57x57" href="/<?php echo $rootpath; ?>styles/imgs/apple-icon-60.png" />
		<link rel="apple-touch-icon" sizes="72x72" href="/<?php echo $rootpath; ?>styles/imgs/apple-icon-76.png" />
		<link rel="apple-touch-icon" sizes="114x114" href="/<?php echo $rootpath; ?>styles/imgs/apple-icon-120.png" />
		<link rel="apple-touch-icon" sizes="144x144" href="/<?php echo $rootpath; ?>styles/imgs/apple-icon-152.png" />
		
		<!-- Stylesheets - 'screen_sml' displays on mobiles. If you want something to display on only _lrg or _sml, put 'lrg' or 'sml' as its class. -->
		<link rel="stylesheet" type="text/css" media="screen" href="/<?php echo $rootpath; ?>styles/general.css"/>
		<link rel="stylesheet" type="text/css" media="screen and (min-device-width : 480px)" href="/<?php echo $rootpath; ?>styles/screen_lrg.css"/>
		<link rel="stylesheet" type="text/css" media="screen and (max-device-width : 480px)" href="/<?php echo $rootpath; ?>styles/screen_sml.css"/>
		<!-- <link rel="stylesheet" type="text/css" href="/styles/screen_sml.css"/> For testing/re-styling -->
		<?php
			//Dirty rotten browser hacks
			if(strpos($_SERVER['HTTP_USER_AGENT'],"Trident") != "") { //Hits IE by spotting the IE rendering engine
				echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"/".$rootpath."styles/screen_lrg_ie.css\"/>";
				}
			if(strpos($_SERVER['HTTP_USER_AGENT'],"iPad") != "") { //Hits iPads by spotting... that they're iPads
				echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"/".$rootpath."styles/screen_lrg_ipad.css\"/>";
				echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"screen	and (orientation : portrait)\" href=\"/".$rootpath."styles/screen_lrg_ipad_portrait.css\"/>";
				}
			
		?>
		
		<link href='http://fonts.googleapis.com/css?family=PT+Sans:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
		
		<!-- This parses mathematical code. See http://www.mathjax.org/ for documentation. -->
		<script type="text/javascript"
			src="https://c328740.ssl.cf1.rackcdn.com/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML">
		</script>
		
		<!-- Pop-up images in galleries -->
		<script src="/<?php echo $rootpath; ?>js/jquery-1.11.0.min.js"></script>
		<script src="/<?php echo $rootpath; ?>js/lightbox.min.js"></script>
		<link rel="stylesheet" type="text/css" media="screen" href="/<?php echo $rootpath; ?>styles/lightbox.css"/>
		
		<script type="text/javascript">
			function openClose(divId,boxType) {
				if(document.getElementById(divId).className.match(/(?:^|\s)open(?!\S)/)) { var open = 1; } // Check to see if the specific item is currently open
				
				var inputs = document.getElementsByName(boxType);
				for(var i = 0; i < inputs.length; i++) { // Close everything
					inputs[i].className = document.getElementById(divId).className.replace( /(?:^|\s)open(?!\S)/g , '' );
					}
				
				if(open != 1) { // Only open the selected item if it was originally closed
					document.getElementById(divId).className += " open";
					}
				}
		</script>
		
	</head>
	<body>
		
		<style>
			<?php // Styles specific to the micro-site being looked at
			
			// Adding a background image or changing the background colour
			if (isset($_GET['subject']) && file_exists("../content_learn/".$_GET['subject']."/background.jpg")) {
				echo "body { background-image: url(/".$rootpath."../content_learn/".$_GET['subject']."/background.jpg); }";
				}
			if (isset($ConfigBackgroundColour)) { echo "body { background-color: ".$ConfigBackgroundColour."; }"; }
				
			// The default colours are a variety of shades of grey - this changes all the appropriate ones to the theme colour for the micro-site
			if (isset($ConfigColour)) {
				echo "a { color: ".$ConfigColour."; }";
				echo "div.header { background-color: ".$ConfigColour."; }";
				echo "div.navigation h1 a:hover { background-color: ".$ConfigColour."; }";
				echo "img.icon { background-color: ".$ConfigColour."; }";
				echo "a.imagelink img:hover { outline-color: ".$ConfigColour."; }";
				echo "div.photostub a:hover { border-color: ".$ConfigColour."; }";
				}
			?>
		</style>