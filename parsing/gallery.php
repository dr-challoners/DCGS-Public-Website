<?php

$photos = scandir($filedir, 1);
array_pop($photos);
array_pop($photos); // Removes . and .. from the array in order to get a proper count

$hplace = array("left","center","right"); // Used later to randomly align the snapshot of the image in its box
$vplace = array("top","center","bottom");

if (count($photos) != 0) { // Check to make sure gallery contains photos
	echo '<div class="gallery">';
	shuffle($photos);
	
	while (count($photos) > 0) { // Keep taking photos from the gallery until it's empty
	  unset($error);
	  // First see how many photos remain and decide how many to take
	  $num = count($photos);
	  if ($num > 12) {
	    $boxcount = array(2,3,5,6,9);
	  }
	  else {
	    switch($num) {
	      case 12: $boxcount = array(3,6,6,9); break;
	      case 11: $boxcount = array(2,3,5,6); break;
	      case 10: $boxcount = array(2,3,3,5); break;
	      case 9:  $boxcount = array(2,3,6,9); break;
	      case 8:  $boxcount = array(2,3,5,6); break;
	      case 7:  $boxcount = array(2,3,3,5); break;
	      case 6:  $boxcount = array(3,6);     break;
	      case 5:  $boxcount = array(2,3,3,5); break;
	      case 4:  $boxcount = array(2);       break;
	      case 3:  $boxcount = array(3);       break;
	      case 2:  $boxcount = array(2);       break;
	      
	      // It shouldn't be possible to have just one image left over, but have a check here anyway
	      default: $error = 1;
	    }
	  }
	  shuffle($boxcount);
	  $boxcount = $boxcount[0];
	  
	  if (!isset($error)) {
	    // Now decide the layout for the photos being taken
	    switch($boxcount) {
	      case 2: $layout = array("med","wde");       break;
	      case 3: $layout = array("med","med","med"); break;
	      case 5: $layout = array("tny","wde");       break;
	      case 6: $layout = array("tny","med","med"); break;
	      case 9: $layout = array("tny","tny","med"); break;
	    }
	    shuffle($layout);
	    foreach ($layout as $box) {
	      if ($box == "med" || $box == "wde") {
	        include ('gallery_image.php');
	      } else { // It's a tiny box - make a series of four of these
	      echo '<div class="tny-box">';
	      for ($b = 1; $b <= 4; $b++) {
	        include ('gallery_image.php');
	      }
	      echo '</div>';
	      }
	    }
	  }
	}
	echo '<hr class="clear" /></div>';
}

?>