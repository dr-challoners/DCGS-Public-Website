<?php

	$override = 0;

//Note that if multiple overrides are in the ACTIVE folder, closure will take priority, then snow, then adverts

if (file_exists("./override/ACTIVE/closure.txt")) {

	$message = file_get_contents("./override/ACTIVE/closure.txt", true);
	
	echo "<div class=\"override\" id=\"closure\">";
	echo Parsedown::instance()->parse($message);
	echo "</div>";
	
	$override = 1; //This lets the magazine know there's been an override, so it doesn't display a 'big' news story
	
	}
elseif (file_exists("./override/ACTIVE/snow_amber.txt")) {

	$message = file_get_contents("./override/ACTIVE/snow_amber.txt", true);
	
	echo "<div class=\"override\" id=\"snow_amber\">";
	echo Parsedown::instance()->parse($message);
	echo "</div>";
	
	$override = 1;
	
	}
//As long as there's either some text or a picture, the advert override will display:
elseif (file_exists("./override/ACTIVE/advert.txt") || file_exists("./override/ACTIVE/advert.jpg")) {

	if (file_exists("./override/ACTIVE/advert.txt")) { 
		$message = file_get_contents("./override/ACTIVE/advert.txt", true);
		}
	
	//Advert.jpg will sort itself out as a background image - if there isn't one, it just won't display
	
	if (file_exists("./override/ACTIVE/advert.css")) { //Option for a stylesheet, if you want to get technical!
		echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"screen and (min-device-width : 361px)\" href=\"/rebuild/override/ACTIVE/advert.css\"/>";
		}
	
	echo "<div class=\"override lrg\" id=\"advert\">"; //Note that adverts won't display on phones, as managing this just gets a bit too complicated...
	echo Parsedown::instance()->parse($message);
	echo "</div>";
	
	$override = 1;
	
	}

?>