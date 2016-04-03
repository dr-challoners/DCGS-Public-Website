<?php

function word_cutoff($text, $length) { // Creates the preview text for articles
    if(strlen($text) > $length) {
        $text = substr($text, 0, strpos($text, ' ', $length));
		}
    return $text;
	}

// Create a multi-dimensional array of the first X news articles
$storyList = array();
$x = 0; $max = 11; // Determines the number of stories to display

// view($mainData);

foreach ($mainData['data']['sheets'] as $id => $sheet) {
  if ($sheet['section'] == 'News' && file_exists('data/content/'.$id.'.json')) {
    $sheetArray = file_get_contents('data/content/'.$id.'.json');
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
  $details['link'] = '/c/news/'.clean($row['section']).'/'.clean($key);
  $urlID_start = makeID($row['section']).makeID($key);
  unset($row['section']);
  $text = '';
  $image = array();
  $newsimage = array();
  foreach ($row as $datum) {
    $dataType = clean($datum['datatype']);
    if ($dataType == '' || $dataType == 'text') {
      // Need to clear out bits of the text that won't work in the snippet, like headings
      $datum['content'] = formatText($datum['content']);
      $datum['content'] = preg_replace("/<h[0-9]>[^<]+<\/h[0-9]>/",'',$datum['content']);
      $datum['content'] = strip_tags($datum['content']); 
      $text .= $datum['content'];
    }
    if (($dataType == 'newsdate' || $dataType == 'infodate') && !isset($details['date'])) {
      $details['date'] = $datum['content'];
    }
    if ($dataType == 'image' || $dataType == 'newsimage') {
      ${$dataType}[] = fetchImageFromURL('/data/images',$datum['url'],$datum['content']);
    }
    if ($dataType == 'newsvideo' && !isset($details['videoID'])) {
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
      } elseif (strpos($datum['url'],"drive.google.com") !== false) {
        $details['videoType'] = 'drive';
        $id = explode('?id=',$datum['url'])[1];
        $id = explode('&',$id)[0];
      }
      $details['videoID'] = $id;
    }
  }
  $details['text'] = $text;
  if (count($newsimage) > 0) {
    $details['imgs'] = $newsimage;
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

if (!isset($error)) {
  $s = 0;
  $r = 0;
  $a = 0;
  if (count($storyList) < $max) { $error = 1; }
  foreach ($storyList as $story) {
    if ($story['text'] == "") { $error = 1; }
    if (isset($error) && !isset($errorCheck)) { // This puts the error message at the top of the page, but only displays it once
      echo '<div class="row newsError">';
        echo '<div class="col-xs-5 newsImgBox">';
          echo '<div style="background-image:url(\'img/errorNews.jpg\');"></div>';
        echo '</div>';
        echo '<div class="col-xs-7">';
          echo '<h3>Upload in progress</h3>';
          echo '<p class="hidden-xs">The news stories are being refreshed. Thank you for your patience - normal service should resume soon.</p>';
        echo '</div>';
      echo '</div>';
      $errorCheck = 1;
    }
    if ($a >= 2 && !isset($error) && !isset($audio)) {
      
      $ab = 'https://audioboom.com/users/4576749/boos.rss';
      $ab = simplexml_load_file($ab);
      $ab = (array) $ab->channel;
      $ab = $ab['item'];
      
      $track1 = rand(0,24);
      $track2 = rand(0,24);
      if ($track1 == $track2) {
        $track2++;
      }
      $tracks = array($track1,$track2);
      echo '<div class="row">';
        foreach ($tracks as $track) {
          $src = $ab[$track]->link;
          $src = str_replace('https://','//embeds.',$src);
          echo '<div class="col-sm-6 embed-responsive embed-responsive-audioFront">';
            echo '<iframe class="embed-responsive-item" src="'.$src.'/embed/v3?link_color=%232358A3&amp;image_option=none" scrolling="no"></iframe>';
          echo '</div>';
        }
      echo '</div>';
      $audio = 1;
    }
    unset ($featured);
    echo '<a class="newsLink" href="'.$story['link'].'">';
      echo '<div class="row">';
          if ((isset($story['imgs']) || isset($story['videoID']))) {
            if (!isset($continue) && !isset($override) && !isset($error)) { // Headline story
              echo '<div class="col-sm-8 newsImgBox">';
              if (isset($story['videoID'])) { // If there's a video specified, embed the video
                if ($story['videoType'] == 'youtube') {
                  echo '<div class="embed-responsive" style="background-image:url(\'http://img.youtube.com/vi/'.$story['videoID'].'/0.jpg\');">';
                    echo '<iframe class="embed-responsive-item hidden-xs" src="https://www.youtube-nocookie.com/embed/'.$story['videoID'].'?rel=0&amp;showinfo=0" allowfullscreen="true"></iframe>';
                  echo '</div>';
                } elseif ($story['videoType'] == 'vimeo') {
                  $thumbnail = unserialize(file_get_contents('http://vimeo.com/api/v2/video/'.$story['videoID'].'.php'));
                  echo '<div class="embed-responsive" style="background-image:url(\''.$thumbnail[0]['thumbnail_large'].'\');">';
                    echo '<iframe class="embed-responsive-item hidden-xs" src="https://player.vimeo.com/video/'.$story['videoID'].'?color=2358A3&byline=0&badge=0&title=0&portrait=0" allowfullscreen="true"></iframe>';
                  echo '</div>';
                } elseif ($story['videoType'] == 'drive') {
                  echo '<div class="embed-responsive" style="background-image:url(\'https://drive.google.com/thumbnail?id='.$story['videoID'].'\');">';
                    echo '<iframe class="embed-responsive-item hidden-xs" src="https://drive.google.com/a/challoners.org/file/d/'.$story['videoID'].'/preview" allowfullscreen="true"></iframe>';
                  echo '</div>';
                }
              } elseif (count($story['imgs']) > 1) { // If there's more than one image, make a slideshow
                $slideImgs = array_reverse($story['imgs']); // The slideshow code needs the images going in reverse order
                echo '<div id="slideshow">';
                foreach ($slideImgs as $slide) {
                  $imgLink = $slide;
                  $imgLink = str_replace("'","\'",$imgLink);
                  echo '<div style="background-image:url(\''.$imgLink.'\');"></div>';
                }
                echo '</div>';
              } else {
                echo '<div style="background-image:url(\''.$story['imgs'][0].'\');"></div>';
              }
              echo '</div>';
              $featured = 1;
              $continue = 1;
              $chars = 100;
              echo '<div class="col-sm-4">';
            } else {
              if (isset($story['imgs']) && count($story['imgs']) >= 4 && $r == 0) {
                for ($i = 0; $i < 4; $i++) {
                  $imgLink = $story['imgs'][$i];
                  echo '<div class="col-sm-3 ';
                  if ($i > 0) {
                    echo 'hidden-xs'; // So only one image is displayed on mobiles
                  } else {
                    echo 'col-xs-5';
                  }
                  echo ' newsImgBox">';
                    echo '<div style="background-image:url(\''.$imgLink.'\');"></div>';
                  echo '</div>';
                }
                $r = 3; // A small counter to prevent these rows happening too often - they'll look messy
                $chars = 160;
                echo '<div class="col-sm-12 col-xs-7">';
              } elseif (isset($story['imgs'])) {
                echo '<div class="col-xs-5 newsImgBox">';
                  echo '<div style="background-image:url(\''.$story['imgs'][0].'\');"></div>';
                echo '</div>';
                $a++; // This is to determine when to place the 'latest audio' box
                if ($r > 0) { $r = $r-1; }
                $chars = 120;
                echo '<div class="col-xs-7">';
              } else { // No images, so there must be a video thumbnail we can use
                echo '<div class="col-xs-5 newsImgBox">';
                  if ($story['videoType'] == 'youtube') {
                    echo '<div style="background-image:url(\'http://img.youtube.com/vi/'.$story['videoID'].'/0.jpg\');"></div>';
                  } elseif ($story['videoType'] == 'vimeo') {
                    $thumbnail = unserialize(file_get_contents('http://vimeo.com/api/v2/video/'.$story['videoID'].'.php'));
                    echo '<div style="background-image:url(\''.$thumbnail[0]['thumbnail_large'].'\');"></div>';
                  } elseif ($story['videoType'] == 'drive') {
                    echo '<div style="background-image:url(\'https://drive.google.com/thumbnail?id='.$story['videoID'].'\');"></div>';
                  }
                echo '</div>';
                if ($r > 0) { $r = $r-1; }
                $chars = 120;
                echo '<div class="col-xs-7">';
              }
            }
          } else {
            $chars = 180;
            echo '<div class="col-xs-12">';
          }
          echo '<h3';
            if (isset($featured)) {
              echo ' class="feature"';
            }
          echo '>'.formatText($story['name'],0).'</h3>';
          echo '<p><strong>'.$story['date'].'</strong>';
          $stub = ': '.word_cutoff($story['text'],$chars).'...';
          if (!isset($featured)) {
            $stub = '<span class="hidden-xs">'.$stub.'</span>';
          }
          echo $stub.'</p>';
        echo '</div>';
      echo '</div>';
    echo '</a>';
  }
}

?>