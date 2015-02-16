<?php

function word_cutoff($text, $length) { // Creates the preview text for articles
    if(strlen($text) > $length) {
        $text = substr($text, 0, strpos($text, ' ', $length));
		}
    return $text;
	}

// Create a multi-dimensional array of the first X news articles, by looking recursively through each of the month folders
if (file_exists('content_news/')) {
  $months = scandir("content_news/", 1);
  $imgTypes = array("jpg","jpeg","gif","png"); 
  $x = 0; $max = 10; // Determines the number of stories to display
  $storyList = array();
  foreach ($months as $month) {
    if (strlen($month) == 6) { // A basic check that the date is formatted correctly - should avoid publishing rogue folders that end up here
      $articles = scandir("content_news/".$month."/", 1);
      foreach ($articles as $post) {
        unset ($c);
        if (substr($post,2,1) == "~" && $x < $max) { // Checks the date is formatted correctly on the article - this also allows you to hide newsposts
          $details = array();
          $details['data-month'] = $month;
          $details['data-url'] = $post;
          $details["link"] = $month.str_replace(" ","_",$post);

          $date = $month.explode("~",$post)[0];
          $date = date("jS F Y",mktime(0,0,0,substr($date,4,2),substr($date,6,2),substr($date,0,4)));
          $details["date"] = $date;

          $details["name"] = explode("~",$post)[1];

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
          // To determine what images are available to use, check to see if any have been designated as cover images. If any have, only use those, otherwise use all the available images.
          if (!empty($imgs)) {
            foreach ($imgs as $img) {
              if (strpos(strtolower($img),'newscover') !== false) {
                $c = 1;
              }
            }
            if (isset($c)) {
              foreach ($imgs as $img) {
                if (strpos(strtolower($img),'newscover') !== false) {
                  $details["imgs"][] = $img;
                }
              }
            } else {
              $details["imgs"] = $imgs;
            }
          }

          array_push($storyList,$details);
          $x++;
        }
      }
    }
  }
} else { $error = 1; }

// In summary, the code above has produced the storyList array, with the following items for each article:

// data-month and data-url - presently used only to construct the full image url
// link - url to the article, already processed with underscores in place of spcaes
// name - title of the article
// date - already formatted as text to output
// text - already processed as plaintext
// imgs - an array of urls to all images in the article, including those in subfolders

echo '<div id="magazine">';

if (!isset($error)) {
  $s = 0;
  $r = 0;
  $a = 0;
  if (count($storyList) < $max) { $error = 1; }
  foreach ($storyList as $story) {
    if ($story['text'] == "") { $error = 1; }
    if (isset($error) && !isset($errorCheck)) { // This puts the error message at the top of the page, but only displays it once
      echo '<div class="error">';
        echo '<div class="newsImg" style="background-image:url(\'styles/imgs/error_magazine.jpg\');"></div>';
        echo '<h1>Upload in progress</h1>';
        echo '<p>The news stories are being refreshed. Thank you for your patience - normal service should resume soon.</p>';
      echo '</div>';
      $errorCheck = 1;
    }
    if ($a >= 2 && $r == 0 && !isset($error) && !isset($audio)) {
      $sc = file_get_contents('http://api.soundcloud.com/users/88582271/tracks.json?client_id=59f4a725f3d9f62a3057e87a9a19b3c6');
      $sc = json_decode($sc);
      $audioName = $sc[0]->title;
      $audioIcon = $sc[0]->artwork_url;
      $audioText = $sc[0]->description;
      $audioDate = $sc[0]->last_modified;
      $audioDate = date("jS F Y",mktime(0,0,0,substr($audioDate,5,2),substr($audioDate,8,2),substr($audioDate,0,4)));
      $id = $sc[0]->id;
      echo '<iframe id="latestAudio" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/'.$id.'&ampauto_play=false&amp;"></iframe>';
      echo '<div class="audio lrg" id="audioToggle">';
        echo '<div class="newsImg" style="background-image:url(\''.$audioIcon.'\'),url(\'styles/imgs/latestAudio_icon.jpg\');">';
          echo '<p>Latest audio</p>';
          echo '<a href="https://soundcloud.com/dcgs-music"><img src="styles/imgs/scLogo.png" alt="SoundCloud" /></a>';
        echo '</div>';
        echo '<h1>'.$audioName.'</h1>';
        echo '<p><em>'.$audioDate.':</em> '.$audioText.'</p>';
      echo '</div>';
      $audio = 1;
    }
    if (!isset($story['imgs'])) {
      $boxType = 'non';
      $chars = 160;
    } elseif (!isset($topCheck) && $override != 1 && !isset($error)) {
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
      $a++; // This is to determine when to place the 'latest audio' box
      if ($r > 0) { $r = $r-1; }
    }
    echo '<a ';
    if ($s >= 8) { echo 'class="lrg" '; } // Limits the number of articles displayed on mobiles, to speed up browsing
    echo 'href="news/'.$story['link'].'" />';
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
          // The slideshow shouldn't be displayed on the mobile site, so show this image instead
          $imgLink = '/content_news/'.$story['data-month'].'/'.$story['data-url'].'/'.$story['imgs'][0];
          $imgLink = str_replace("'","\'",$imgLink);
          echo '<div class="newsImg sml" style="background-image:url(\''.$imgLink.'\');"></div>';
        } elseif ($boxType == 'row') { // Otherwise if there are sufficient stories, make a row of them
          for ($i = 0; $i < 4; $i++) {
            $imgLink = '/content_news/'.$story['data-month'].'/'.$story['data-url'].'/'.$story['imgs'][$i];
            $imgLink = str_replace("'","\'",$imgLink);
            echo '<div class="newsImg';
            if ($i > 0) { echo ' lrg'; } // So only one image is displayed on mobiles
            echo '" style="background-image:url(\''.$imgLink.'\');"></div>';
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
    $s++;
  }
}

echo '</div>'; // Closes the main magazine div

?>