<?php

function word_cutoff($text, $length) { // Creates the preview text for articles
    if(strlen($text) > $length) {
        $text = substr($text, 0, strpos($text, ' ', $length));
		}
    return $text;
	}

// Create a multi-dimensional array of the first X news articles
$storyList = array();
$x = 0; $max = 10; // Determines the number of stories to display

// view($mainData);

foreach ($mainData['data']['sheets'] as $id => $sheet) {
  if ($sheet['section'] = 'News') {
    $sheetArray = file_get_contents($dataSrc.'/'.$id.'.json');
    $sheetArray = json_decode($sheetArray, true);
    foreach ($sheetArray['data'] as $key => $page) {
      if (strpos(strtolower($key),'[hidden]') === false) {
        $storyList[$key] = $page;
        $storyList[$key]['section'] = $sheetArray['meta']['sheetname'];
        $x++;
        if ($x >= $max) { break; }
      }
    }
    if ($x >= $max) { break; }
  }
}

foreach ($storyList as $key => $row) {
  $details = array();
  $details['name'] = $key;
  $details['link'] = '/c/News/'.clean($row['section']).'/'.clean($key);
  $urlID_start = makeID($row['section']).makeID($key);
  unset($row['section']);
  $text = '';
  $image = array();
  $newsImage = array();
  foreach ($row as $datum) {
    //view($datum);
    if ($datum['datatype'] == '' || $datum['datatype'] == 'text') {
      $text .= $datum['content'];
    }
    if (strtolower($datum['datatype']) == 'newsdate' && !isset($details['date'])) {
      $details['date'] = $datum['content'];
    }
    if (strtolower($datum['datatype']) == 'image' || strtolower($datum['datatype']) == 'newsimage') {
      if (!empty($datum['content'])) {
        $imageName = makeID($datum['url'],1).'-'.clean($datum['content']);                      
      } else {
        $imageName = makeID($datum['url']);
      }
      fetchImage($datum['url'],$imageName);
      ${$datum['datatype']}[] = '/'.$imgsSrc.'/'.$imageName;
    }
    if (strtolower($datum['datatype']) == 'newsvideo' && !isset($details['videoID'])) {
      if (strpos($datum['url'],"youtu.be") !== false) {
        $details['videoType'] = 'youtube';
        $id = strpos($datum['url'],"e/");
        $id = substr($datum['url'],$id+2);
      } elseif (strpos($datum['url'],"youtube") !== false && strpos($datum['url'],"watch")) {
        $details['videoType'] = 'youtube';
        $id = strpos($datum['url'],"v=");
        $id = substr($datum['url'],$id+2);
      } elseif (strpos($datum['url'],"youtube") !== false && strpos($datum['url'],"edit")) {
        $details['videoType'] = 'youtube';
        $id = strpos($datum['url'],"d=");
        $id = substr($datum['url'],$id);
      } elseif (strpos($datum['url'],"vimeo") !== false) {
        $details['videoType'] = 'vimeo';
        $id = strrpos($datum['url'],'/');
        $id = substr($datum['url'],$id+1);
      }
      $details['videoID'] = $id;
    }
  }
  $text = formatText($text);
  $text = strip_tags($text); // Remove other HTML and PHP
  $details['text'] = $text;
  if (count($newsImage) > 0) {
    $details['imgs'] = $newsImage;
  } elseif (count($image) > 0) {
    $details['imgs'] = $image;
  }
  $storyList[] = $details;
  unset($storyList[$key]);
}

// In summary, the code above has produced the storyList array, with the following items for each article:

// link - url to the article, already processed with underscores in place of spaces
// name - title of the article
// date - already formatted as text to output
// text - already processed as plaintext
// imgs - an array of urls to all images in the article, including those in subfolders

// videoID
// videoType - these items will appear if the first line in the article is a link to a video, to play it on the front page

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
    if ($a >= 2 && !isset($error) && !isset($audio)) {
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
    if ((!isset($story['imgs']) && !isset($story['videoID']))) {
      $boxType = 'non';
      $chars = 160;
    } elseif (!isset($topCheck) && !isset($override) && !isset($error)) {
      $boxType = 'top';
      $chars = 120;
      $topCheck = 1;
    } elseif (isset($story['imgs']) && count($story['imgs']) >= 4 && $r == 0) {
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
    echo 'href="'.$story['link'].'" />';
    echo '<div class="'.$boxType.'">';
      if ($boxType != 'non') { // If there's an image, display it
        if ($boxType == 'top' && isset($story['videoID'])) { // If it's the headline story and there's a video specified, embed the video
          if ($story['videoType'] == 'youtube') {
            echo '<iframe class="videoPreview lrg" src="https://www.youtube-nocookie.com/embed/'.$story['videoID'].'?rel=0&amp;showinfo=0" allowfullscreen></iframe>';
            echo '<div class="newsImg sml" style="background-image:url(\'http://img.youtube.com/vi/'.$story['videoID'].'/0.jpg\');"></div>';
          }
          elseif ($story['videoType'] == 'vimeo') {
            echo '<iframe class="videoPreview lrg" src="https://player.vimeo.com/video/'.$story['videoID'].'?color=2358A3&byline=0&badge=0&title=0&portrait=0" allowfullscreen></iframe>';
            $thumbnail = unserialize(file_get_contents('http://vimeo.com/api/v2/video/'.$story['videoID'].'.php'));
            echo '<div class="newsImg sml" style="background-image:url(\''.$thumbnail[0]['thumbnail_large'].'\');"></div>';
          }
        }
        elseif ($boxType == 'top' && count($story['imgs']) > 1) { // If there's more than one image and it's the headline story, make a slideshow
          $slideImgs = array_reverse($story['imgs']); // The slideshow code needs the images going in reverse order
          echo '<div id="slideshow">';
            foreach ($slideImgs as $slide) {
              $imgLink = $slide;
              $imgLink = str_replace("'","\'",$imgLink);
              echo '<div class="newsImg" style="background-image:url(\''.$imgLink.'\');"></div>';
            }
          echo '</div>';
          // The slideshow shouldn't be displayed on the mobile site, so show this image instead
          $imgLink = $story['imgs'][0];
          echo '<div class="newsImg sml" style="background-image:url(\''.$imgLink.'\');"></div>';
        } elseif ($boxType == 'row') { // Otherwise if there are sufficient stories, make a row of them
          for ($i = 0; $i < 4; $i++) {
            $imgLink = $story['imgs'][$i];
            echo '<div class="newsImg';
            if ($i > 0) { echo ' lrg'; } // So only one image is displayed on mobiles
            echo '" style="background-image:url(\''.$imgLink.'\');"></div>';
          }
        } elseif (!isset($story['imgs']) && isset($story['videoID'])) { // If there's no images, but there's been a headline video, we can use its thumbnail
          if ($story['videoType'] == 'youtube') {
            echo '<div class="newsImg" style="background-image:url(\'http://img.youtube.com/vi/'.$story['videoID'].'/0.jpg\');"></div>';
          }
          elseif ($story['videoType'] == 'vimeo') {
            $thumbnail = unserialize(file_get_contents('http://vimeo.com/api/v2/video/'.$story['videoID'].'.php'));
            echo '<div class="newsImg" style="background-image:url(\''.$thumbnail[0]['thumbnail_large'].'\');"></div>';
          }
        } else {
          $imgLink = $story['imgs'][0];
          echo '<div class="newsImg" style="background-image:url(\''.$imgLink.'\');"></div>';
        }
      }
      echo '<h1>'.formatText($story['name'],0).'</h1>';
      echo '<p><em>'.$story['date'].'</em><span><em>:</em> ';
      echo  word_cutoff($story['text'],$chars).'...';
      echo '</span></p>';
    echo '</div></a>';
    $s++;
  }
}

echo '</div>'; // Closes the main magazine div

?>