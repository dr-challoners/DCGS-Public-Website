<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:svg="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"  xmlns:xml="http://www.w3.org/XML/1998/namespace">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-Style-Type" content="text/css" />
		
		<?php		
		// We need to get data from the Learn site config file in order to display the name of the micro-site we're looking at, and get other data
		if (isset($_GET['subject']) && file_exists("../content_learn/".$_GET['subject']."/config.txt")) { // There needs to be a config file in the specified subject folder
			$data = file("../content_learn/".$_GET['subject']."/config.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			foreach ($data as $datum) { // All lines in the config file should be in the form 'Var: Data'. This turns that into $ConfigVar == "Data".
				$datum = explode (": ",$datum);
				$varname = "Config".$datum[0];
				$$varname = $datum[1];
				}
    }  
		?>
    
    <?php
      // Folder and filenames are given underscores in place of spaces when posted to make nicer-looking URLs. This converts them back.
      if (isset($_GET['subject']))   { $getSubject   = $_GET['subject']; }
      if (isset($_GET['folder']))    { $getFolder    = str_replace("_"," ",$_GET['folder']); }
      if (isset($_GET['subfolder'])) { $getSubfolder = str_replace("_"," ",$_GET['subfolder']); }
      if (isset($_GET['page']))      { $getPage      = str_replace("_"," ",$_GET['page']); }
    ?>
		
		<title>
			<?php // Include more informative titles here
      echo "Learn ";
      if(isset($ConfigTitle)) { echo $ConfigTitle; }
      echo " - Dr Challoner's Grammar School"; ?>
		</title>
		
    <?php include('../parsing/config_learn.php'); ?>
    
		<link rel="icon" href="/<?php echo $rootpath; ?>styles/imgs/favicon.png" />
		<link rel="shortcut icon" href="/<?php echo $rootpath; ?>styles/imgs/favicon.png" />
		
		<!-- Homescreen icons for iPhone/iPad. Android should detect these as well. -->
		<link rel="apple-touch-icon" sizes="57x57" href="/<?php echo $rootpath; ?>styles/imgs/apple-icon-60.png" />
		<link rel="apple-touch-icon" sizes="72x72" href="/<?php echo $rootpath; ?>styles/imgs/apple-icon-76.png" />
		<link rel="apple-touch-icon" sizes="114x114" href="/<?php echo $rootpath; ?>styles/imgs/apple-icon-120.png" />
		<link rel="apple-touch-icon" sizes="144x144" href="/<?php echo $rootpath; ?>styles/imgs/apple-icon-152.png" />
		
		<!-- Stylesheets - 'screen_sml' displays on mobiles. If you want something to display on only _lrg or _sml, put 'lrg' or 'sml' as its class. -->
		<link rel="stylesheet" type="text/css" media="screen" href="/<?php echo $rootpath; ?>styles/general.css"/>
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
    <script> // Google Analytics
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-53669990-2', 'auto');
      ga('send', 'pageview');
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
				echo "#banner { background-color: ".$ConfigColour."; }";
				echo "div.navigation h1 a:hover { background-color: ".$ConfigColour."; }";
				}
			?>
		</style>