<?php

$photos = scandir($filedir, 1);
array_pop($photos);
array_pop($photos);

if(count($photos) != 0) { //Check to make sure gallery contains photos

	echo "<div class=\"gallery\">";

	// ADD A POP-OUT VIEW MODE FOR IMAGES

	shuffle($photos);
	
	$boxtypes = array("med","med","med","wde","wde","tny"); //The different types of boxes, to be randomly selected later. To weight the chances of getting certain types of box, add them as repeated elements to this array
	$hplace = array("left","center","right"); //To randomly align the snapshot of the image in its box
	$vplace = array("left","center","right");
	
	//Set up variables for gallery creation
	$pos = 1; //This counts the current position of the box, for determining where wide boxes can be placed
	$tiny = 1; //Sets up the tiny counter - this stops the first boxes always being tiny
	$lastwide = "";

	foreach($photos as $photo) {	
		if($tiny != 1) { //A set of tiny boxes is being created
			$thisbox = "tny";
			
			include ('gallery_image.php');
			
			if ($tiny == 4) { echo "</div>"; $tiny = 1; } //Closes the set of tiny boxes at the fourth box
			else { $tiny++; }
			}
		else {
		
			shuffle($boxtypes);
			$thisbox = $boxtypes[0];
			if ($thisbox == "wde" && ($pos%3 == 0 || $pos-$lastwide == 3)) { $thisbox = "med"; } //If a wide box would be placed poorly, this swaps it for a medium box
		
			if ($thisbox == "tny") { //A set of tiny boxes is about to begin
				echo "<div class=\"tny-box\">";
				$tiny++;
				}
			
			include ('gallery_image.php');
		
			if ($thisbox == "wde") { $lastwide = $pos; $pos = $pos+2; }
			else { $pos++; }
			}
	}
	if($tiny != 1) { echo "</div>"; } //Closes up a half-finished set of tiny boxes
	
echo "<hr class=\"clear\" /></div>";

	}

?>