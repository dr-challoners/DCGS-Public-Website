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
        $details['data-month'] = $month;
        $details['data-url'] = $post;
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
          if (isset($check['extension'])) { $extn = strtolower($check['extension']); }
          // Create the preview text for the story
          if (isset($check['extension']) && $extn == "txt") {
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
          } elseif (isset($check['extension']) && in_array($extn,$imgTypes) == TRUE) {
            $imgs[] = $file;
          } elseif (!isset($check['extension'])) { // This is a folder - look for images inside it
            $files_r = scandir("content_news/".$month."/".$post."/".$file."/", 1);
            $files_r = array_reverse($files_r);
            foreach ($files_r as $file_r) {
              $check_r = pathinfo($file_r);
              if (isset($check_r['extension'])) { $extn = strtolower($check_r['extension']); }
              if (isset($check_r['extension']) && in_array($extn,$imgTypes) == TRUE) {
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

echo '<div id="magazine">';

$r = 0;
foreach ($storyList as $story) {
  if (!isset($story['imgs'])) {
    $boxType = 'non';
    $chars = 160;
  } elseif (!isset($topCheck) && $override != 1) {
    $boxType = 'top';
    $chars = 120;
    $topCheck = 1;
  } elseif (count($story['imgs']) >= 4 && $r == 0) {
    $boxType = 'row';
    $chars = 140;
    $r = 3; // A small counter to prevent these rows happening too often - they'll look messy
  } else {
    $boxType = 'std';
    $chars = 140;
    if ($r > 0) { $r = $r-1; }
  }
  echo '<a href="news/'.$story['link'].'" />';
  echo '<div class="'.$boxType.'">';
    if ($boxType != 'non') { // If there's an image, display it
      if ($boxType == 'top' && count($story['imgs']) > 1) { // If there's more than one image and it's the headline story, make a slideshow
        $slideImgs = array_reverse($story['imgs']); // The slideshow code needs the images going in reverse order
        echo '<div id="slideshow">';
          foreach ($slideImgs as $slide) {
            $imgLink = '/content_news/'.$story['data-month'].'/'.$story['data-url'].'/'.$slide;
            $imgLink = str_replace("'","\'",$imgLink);
            echo '<div class="newsImg" style="background-image:url(\''.$imgLink.'\');"></div>';
          }
        echo '</div>';
      } elseif ($boxType == 'row') { // Otherwise if there are sufficient stories, make a row of them
        for ($i = 0; $i < 4; $i++) {
          $imgLink = '/content_news/'.$story['data-month'].'/'.$story['data-url'].'/'.$story['imgs'][$i];
          $imgLink = str_replace("'","\'",$imgLink);
          echo '<div class="newsImg" style="background-image:url(\''.$imgLink.'\');"></div>';
        }
      } else {
        $imgLink = '/content_news/'.$story['data-month'].'/'.$story['data-url'].'/'.$story['imgs'][0];
        $imgLink = str_replace("'","\'",$imgLink);
        echo '<div class="newsImg" style="background-image:url(\''.$imgLink.'\');"></div>';
      }
    }
    echo '<h1>'.$story['name'].'</h1>';
    echo '<p><em>'.$story['date'].'</em><span class="lrg"><em>:</em> ';
		echo  word_cutoff($story['text'],$chars).'...';
	  echo '</span></p>';
  echo '</div></a>';
}

echo '</div>'; // Closes the main magazine div

?>