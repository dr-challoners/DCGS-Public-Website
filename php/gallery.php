<?php

if (!isset($_GET['image'])) { $get_image = ""; } else { $get_image = $_GET['image']; }

//Find all the images in a gallery
foreach ($dir as $subdir) { // In content_pages, all the submenu links are made into human-readable titles. This finds the actual names of the folders and files, in order to access the content.
        if (strpos($subdir,$get_subfolder) !== false) {
            $this_subdir = $subdir;
            $files = scandir("content_plain/".$get_folder."/".$subdir, 1);
            foreach ($files as $page) {
              if (strpos($page,$get_gallery) !== false) {
                $gallery = $page;
                }
              }
            }
          }

$photos = scandir("content_plain/".$get_folder."/".$subdir."/".$gallery, 1);
array_pop($photos);
array_pop($photos);

if(count($photos) != 0) { //Check to make sure gallery contains photos

if ($get_image != "") { //We're looking at a specific image, so go to that view mode
	$boxtypes = array("tny"); //Only tiny boxes if we're focused on an image
	$hplace = array("center"); //Don't move the images around in this mode because it looks weird
	$vplace = array("center");
	
	echo "<div class=\"viewphoto\">";
		echo "<h2>".str_replace("_"," ",strchr($get_image,".",true))."</h2>";
		echo "<div class=\"photo\" style=\" background-image: url('/content_plain/".$get_folder."/".$subdir."/".$gallery."/".$get_image."');\"></div>";
		echo "<div class=\"stublist\">";
	}
else { //Otherwise it's the gallery preview
	shuffle($photos);
	$boxtypes = array("med","med","med","wde","wde","tny"); //The different types of boxes, to be randomly selected later. To weight the chances of getting certain types of box, add them as repeated elements to this array
	$hplace = array("left","center","right"); //To randomly align the snapshot of the image in its box
	$vplace = array("left","center","right");
	
	if (file_exists("content_plain/".$get_folder."/".$gallery."/".$subdir."/description.txt")) { //If there's a description file accompanying the gallery, then display it on the main gallery page
		$description = file_get_contents("content_plain/".$get_folder."/".$subdir."/".$gallery."/description.txt", true);
		echo "<div class=\"description\">";
			echo Parsedown::instance()->parse($description);
		echo "</div>";
		}
	}
	
//Set up variables for gallery creation
$pos = 1; //This counts the current position of the box, for determining where wide boxes can be placed
$tiny = 1; //Sets up the tiny counter - this stops the first boxes always being tiny
$lastwide = "";

foreach($photos as $photo) {
	if (strpos($photo,".txt") == 0) { //Checks to ensure a box isn't being created for the text file
	
		if($tiny != 1) { //A set of tiny boxes is being created
			$thisbox = "tny";
			include('image.php');
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
			
			include('image.php');
		
			if ($thisbox == "wde") { $lastwide = $pos; $pos = $pos+2; }
			else { $pos++; }
			}
		}
	}
	if($tiny != 1) { echo "</div>"; } //Closes up a half-finished set of tiny boxes
	
if ($get_image != "") { echo "</div></div>"; }
echo "<hr />";

	}
	else { //If the folder has been found, but is empty
	echo "<style> body { background-image: url('/main_imgs/error.png'); background-position: center bottom; background-repeat: no-repeat; background-attachment: fixed; background-size: 980px auto; } </style>";
	echo "<h2>Oh dear!</h2>";
	echo "<p>This gallery cannot be found. Perhaps it is still being built - or perhaps you only dreamed that it was real. You could try again later, or you could <a href=\"/content_plain/contact/\">contact us</a> to report the problem.</p>";
	}

?>