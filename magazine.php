<pre>
<?php

function word_cutoff($text, $length) { // Creates the preview text for articles
    if(strlen($text) > $length) {
        $text = substr($text, 0, strpos($text, ' ', $length));
		}
    return $text;
	}

// Create a multi-dimensional array of the first X news articles, by looking recursively through each of the month folders
$months = scandir("content_news/", 1);
$imgTypes = array("jpg","jpeg","gif","png"); 
$x = 0; $max = 16; // Determines the number of stories to display
$storyList = array();
foreach ($months as $month) {
  if (strlen($month) == 6) { // A basic check that the date is formatted correctly - should avoid publishing rogue folders that end up here
    $articles = scandir("content_news/".$month."/", 1);
    foreach ($articles as $post) {
      if (substr($post,2,1) == "~" && $x < $max) { // Checks the date is formatted correctly on the article - this also allows you to hide newsposts
        $details = array();
        $details["link"] = $month.str_replace(" ","_",$post);
        $date = $month.explode("~",$post)[0];
        $date = date("jS F Y",mktime(0,0,0,substr($date,4,2),substr($date,6,2),substr($date,0,4)));
        $details["date"] = $date;
        $details["name"] = explode("~",$post)[1];
        // Need to fetch and format the date as well
        
        // Now fetch text and images from the story to display
        $files = scandir("content_news/".$month."/".$post."/", 1);
        $files = array_reverse($files);
        $text = "";
        $imgs = array();
        foreach ($files as $file) {
          $check = pathinfo($file);
          // Create the preview text for the story
          if (isset($check['extension']) && $check['extension'] == "txt") {
            // This sequence removes formatting elements that would cause problems in the preview
            $lines = file("content_news/".$month."/".$post."/".$file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); 
            foreach ($lines as $line) {
              if (substr($line,0,1) != "#" && substr($line,0,1) != '~') { // If it's a header or a tilde link, it's ignored
                $line = Parsedown::instance()->parse($line);
                $line = strip_tags($line); // Remove other HTML and PHP
                $line = str_replace("_","",$line); // Remove bold and emphasis markdown formatting, in case strip_tags doesn't work
                $line = str_replace("*","",$line);
                $text .= $line." "; // Put the line into the story so far, adding a space afterwards to separate it from the next line
              }
            }
          } elseif (isset($check['extension']) && in_array($check['extension'],$imgTypes) == TRUE) {
            $imgs[] = $file;
          } elseif (!isset($check['extension'])) { // This is a folder - look for images inside it
            $files_r = scandir("content_news/".$month."/".$post."/".$file."/", 1);
            $files_r = array_reverse($files_r);
            foreach ($files_r as $file_r) {
              $check_r = pathinfo($file_r);
              if (isset($check_r['extension']) && in_array($check_r['extension'],$imgTypes) == TRUE) {
                $imgs[] = $file."/".$file_r;
              }
            }
          }
        }
        $details["text"] = $text;
        if (!empty($imgs)) {
          $details["imgs"] = $imgs;
        }
        
        array_push($storyList,$details);
        $x++;
      }
    }
  }
}

// In summary, the code above has produced the storyList array, with the following items for each article:

// link - url to the article, already processed with underscores in place of spcaes
// name - title of the article
// date - already formatted as text to output
// text - already processed as plaintext
// imgs - an array of urls to all images in the article, including those in subfolders

/*
//Finds each text file in the story and outputs them as the story stub
  $story = "";
  foreach ($parts as $part) {
    $checkpart = pathinfo($part);
    if (isset($checkpart['extension']) && $checkpart['extension'] == "txt") {
      // This next sequence creates the story stub, removing formatting elements that would clash in such a short space
      $lines = file('content_news/'.$file."/".$part, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); 
      foreach ($lines as $line) {
        if (substr($line,0,1) != "#" && substr($line,0,1) != '~') { // If it's a header or a tilde link, it's ignored
          $line = Parsedown::instance()->parse($line);
			    $line = strip_tags($line); // Remove other HTML and PHP
			    $line = str_replace("_","",$line); // Remove bold and emphasis markdown formatting, in case strip_tags doesn't work
			    $line = str_replace("*","",$line);
			    $story .= $line." "; // Put the line into the story so far, adding a space afterwards to separate it from the next line
          }
        }
      }
    }*/

?>
</pre>
<?php

/* START AGAIN
$newsposts = scandir("content_news/", 1); //Calls up all the files in the news folder
$newsposts = array_slice($newsposts,0,15);
	
$non = ""; for ($non = 0; $non <= 12;) { //Mark posts that don't have images
  $parts = scandir("content_news/".$newsposts[$non], 1);
  $imagecheck = 0;
  foreach ($parts as $part) {
    $part = pathinfo($part);
    if(isset($part['extension'])) { //This check is needed in case there is a gallery or row folder in the news post
      $part = strtolower($part['extension']);
      }
    else { //If it does, indeed, transpire to be a gallery or row, then that means there's an available picture
      $part = explode("~",$part['filename']);
      if ($part[1] == "GALLERY" || $part[1] == "ROW" || $part[2] == "GALLERY" || $part[2] == "ROW") { $imagecheck++; }
      }
    if ($part == "jpg" || $part == "jpeg" || $part == "gif" || $part == "png") { $imagecheck++; }
    }
	if ($imagecheck == 0) { //First identify the current post as not having an image
		$newsposts[$non] = "NON~".$newsposts[$non]; //Then mark it as being so in the array
		}
	$non++;
	}
$odd = ""; for ($non = 0; $non <= 12;) { // Then mark appropriate neighbours to each post
	if (substr($newsposts[$non],0,4) == "NON~") { // Only perform a check if the current post has no image
		if ($non == 0 && (substr($newsposts[$non+1],0,4) != "NON~")) { // If the first story doesn't have an image and the second one does, match up the second one
			$newsposts[$non+1] = "NON~".$newsposts[$non+1];
			}
		elseif ($non == 1 && (substr($newsposts[$non-1],0,4) != "NON~") && (substr($newsposts[$non+1],0,4) != "NON~")) { // If the second story doesn't have an image and the first and third do, match up the third
			$newsposts[$non+1] = "NON~".$newsposts[$non+1];	
			}
		elseif ($non > 0 && (substr($newsposts[$non-1],0,4) == "NON~") && (substr($newsposts[$non+1],0,4) == "NON~")) { // We're in the middle of a run, so keep switching odd/even tracker
			if ($odd == 0) { $odd = 1; } else { $odd = 0; }
			}
		elseif ($non > 0 && (substr($newsposts[$non-1],0,4) == "NON~") && (substr($newsposts[$non+1],0,4) != "NON~") && $odd == 1) { // We've reached the end of a run, and there's been an odd number of stories with no images so far, so add one more
			$newsposts[$non+1] = "NON~".$newsposts[$non+1];
			$odd = 0;
			}
		elseif ($non > 0 && (substr($newsposts[$non-1],0,4) != "NON~") && (substr($newsposts[$non+1],0,4) != "NON~")) { // It's a news post with no image all on its own
			$plusminus = rand(0,1); // This randomly picks a story either side to match
			if ($plusminus == 0) { $plusminus = -1; }
			$newsposts[$non+$plusminus] = "NON~".$newsposts[$non+$plusminus];
			}
		}
	$non++;
	}
	
if ($override != 1) { // As long as there's not an override happening
$a = 0; $big = ""; while ($big == "") { // Find the first post with an image and mark it as the leading 'big' story
	if (substr($newsposts[$a],0,4) != "NON~") {
		$newsposts[$a] = "BIG~".$newsposts[$a];
		$big++;
		}
	$a++;
	}
	}
	
$runs = array();
for ($bar = 0; $bar < 8; $bar++) { // Find every run of four stories all with images by first finding the key of the first image in each run
	if (substr($newsposts[$bar],3,1) != "~" && substr($newsposts[$bar+1],3,1) != "~" && substr($newsposts[$bar+2],3,1) != "~" && substr($newsposts[$bar+3],3,1) != "~") { // They have neither a NON~ nor BIG~ marker
		array_push($runs,$bar);
		}
	}

shuffle($runs); // Pick one possible starting point at random
$barstart = array_shift($runs); 

if ($barstart != "") { // Provided there's room for a run (and there should be, otherwise you should be taking more photos!), this marks out the run
	for ($plus = 0; $plus <= 3; $plus++) {
		$barpoint = $barstart+$plus;
		$newsposts[$barpoint] = "BAR~".$newsposts[$barpoint];
		}
	}

for ($std = 0; $std <= 12; $std++) { // Finally, mark the remaining posts as being normal (this will make reading them for output easier)
	if (substr($newsposts[$std],3,1) != "~") {
		$newsposts[$std] = "STD~".$newsposts[$std];
		}
	}
	
// AFTER ALL THAT, LET'S DISPLAY SOME CONTENT!

function word_cutoff($text, $length) {
    if(strlen($text) > $length) {
        $text = substr($text, 0, strpos($text, ' ', $length));
		}
    return $text;
	}

echo "<div class=\"magazine\">";	
	
$npic = ""; $bcount = ""; $count = 1; foreach ($newsposts as $post) {
	
	if ($count < 13 || ($count == 13 && $npic%2 == 1)) { //This determines a sensible place to end (12 ideally, or one more if a row with no pictures needs to be finished)
    
  $file = substr($post,4);
  $parts = scandir("content_news/".$file, 1);
  $component = explode("~",$post);
  $parts = array_reverse($parts);
	
	//Pull out necessary components of the story
	if ($component[0] != "NON") { //If there's meant to be an image with the story, then the first image is found and displayed
    foreach ($parts as $part) {
      $checkpart = pathinfo($part);
      if(isset($checkpart['extension'])) { //This check is needed in case there is a gallery or row folder in the news post
        $checkpart = strtolower($checkpart['extension']);
        if ($checkpart == "jpg" || $checkpart == "jpeg" || $checkpart == "gif" || $checkpart == "png") {
          $image = addcslashes($file."/".$part,"'");
          break;
          }
        }
      else { //If it does, indeed, transpire to be a gallery or row, then that means there's an available picture - dig deeper to find it
        $checkpart = explode("~",$checkpart['filename']);
        if ($checkpart[1] == "GALLERY" || $checkpart[1] == "ROW" || $checkpart[2] == "GALLERY" || $checkpart[2] == "ROW") {
          $galleryparts = scandir("content_news/".$file."/".$part, 1);
          array_pop($galleryparts);
          array_pop($galleryparts); // Removes . and .. from the array
          $galleryparts = array_reverse($galleryparts);
          $image = addcslashes($file."/".$part."/".$galleryparts[0],"'");
          break;
          }
        }
      }
    }
    
	$date = date("jS F Y",mktime(0,0,0,substr($component[1],4,2),substr($component[1],6,2),substr($component[1],0,4)));
  
  //Finds each text file in the story and outputs them as the story stub
  $story = "";
  foreach ($parts as $part) {
    $checkpart = pathinfo($part);
    if (isset($checkpart['extension']) && $checkpart['extension'] == "txt") {
      // This next sequence creates the story stub, removing formatting elements that would clash in such a short space
      $lines = file('content_news/'.$file."/".$part, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); 
      foreach ($lines as $line) {
        if (substr($line,0,1) != "#" && substr($line,0,1) != '~') { // If it's a header or a tilde link, it's ignored
          $line = Parsedown::instance()->parse($line);
			    $line = strip_tags($line); // Remove other HTML and PHP
			    $line = str_replace("_","",$line); // Remove bold and emphasis markdown formatting, in case strip_tags doesn't work
			    $line = str_replace("*","",$line);
			    $story .= $line." "; // Put the line into the story so far, adding a space afterwards to separate it from the next line
          }
        }
      }
    }
	
  $file = str_replace(' ','_',$file);
    
	//Format according to type
	if ($component[0] == "BIG") {
		echo "<a href=\"news/".$file."\">";
		echo "<div class=\"big\">";
			echo "<div class=\"newsimg\" style=\"background-image: url('/content_news/".$image."');\"></div>";
			echo "<h2>".$component[2]."</h2>";
			echo "<p><em>".$date."</em><span class=\"lrg\"><em>:</em> ";
			echo word_cutoff($story,120)." ...";
			echo "</span></p>";
		echo "</div>";
		echo "</a>";
		$npic = 0; $count++;
		}
	if ($component[0] == "STD") {
		echo "<a href=\"news/".$file."\">";
		echo "<div class=\"std\">";
			echo "<div class=\"newsimg\" style=\"background-image: url('/content_news/".$image."');\"></div>";
			echo "<h3>".$component[2]."</h3>";
			echo "<p><em>".$date."</em><span class=\"lrg\"><em>:</em> ";
			echo word_cutoff($story,140)." ...";
			echo "</span></p>";
		echo "</div>";
		echo "</a>";
		$npic = 0; $count++;
		}
	if ($component[0] == "NON") {
		echo "<a href=\"news/".$file."\">";
		echo "<div class=\"non\">";
			echo "<h3>".$component[2]."</h3>";
			echo "<p><em>".$date."</em></p>";
		echo "</div>";
		echo "</a>";
		$npic++; $count++;
		}
	if ($component[0] == "BAR") {
    if ($bcount == "") { echo '<div class="bar">'; }
		echo '<a href="news/'.$file.'">';
		echo '<div class="barBlock">';
			echo "<div class=\"newsimg\" style=\"background-image: url('/content_news/".$image."');\"></div>";
			echo '<h3>'.$component[2].'</h3>';
			echo '<p class="sml"><em>'.$date.'</em></p>';
		echo '</div>';
		echo '</a>';
    if ($bcount == 3) { echo '<hr /></div>'; }
		$bcount++; $npic = 0; $count++;
		}
	}
	}

echo "</div>";
	*/
?>