<?php

$dir = scandir($_SERVER['DOCUMENT_ROOT'].'/pages/news/');
$dir = array_reverse($dir);
foreach ($dir as $row) {
  if (strpos($row,'navDir-') !== false && strpos($row,'.json') !== false) {
    $dir = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/pages/news/'.$row);
    $dir = json_decode($dir, true);
    break;
  }
}
$c = 0;
$stories = array();
foreach ($dir as $month => $pages) {
  $monthDir = scandir($_SERVER['DOCUMENT_ROOT'].'/pages/news/'.clean($month).'/');
  foreach ($pages as $key => $data) {
    if ($c < 12) {
      $stories[$key] = $data;
      foreach ($stories[$key]['preview']['images'] as $n => $file) {
        $file = $file['file'];
        foreach ($monthDir as $line) {
          if (strpos($line,$file) !== false) {
            $file = 'pages/news/'.clean($month).'/'.$line;
            break;
          }
        }
        $stories[$key]['preview']['images'][$n] = $file;
      }
      $c++;
    } else {
      break;
    }
  }
}
$s = 0;
$r = 0;
$a = 0; // Keeping track of the position in order to put the audio feature in
foreach ($stories as $title => $story) {
  if ($a >= 2 && !isset($audio)) {
    $scfeed = file_get_contents('http://api.soundcloud.com/users/316458242/tracks.json?client_id=59f4a725f3d9f62a3057e87a9a19b3c6'); //Get a feed from Soundcloud
    $scjson = json_decode($scfeed, true); //Decode feed to JSON
    $track1 = rand(0,24);
    $track2 = rand(0,24);
    if ($track1 == $track2) {
      $track2++;
    }
    $tracks = [$scjson[$track1],$scjson[$track2]]; //Insert two randomly chosen tracks to new array
    echo '<div class="row">';
    foreach ($tracks as $track) {
      $src  = 'https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/'.$track['id'].'&amp;color=2358A3&amp;auto_play=false&amp;hide_related=false&amp;show_artwork=false'; //iFrame Embed source
      echo '<div class="col-sm-6 embed-responsive embed-responsive-audioFront">';
      echo '<iframe class="embed-responsive-item" src="' . $src. '" scrolling="no"></iframe>';
      echo '</div>';
    }
    echo '</div>';
    $audio = 1;
  }
  unset ($featured);
  echo '<a class="newsLink" href="'.$story['link'].'">';
  echo '<div class="row">';
  if ((isset($story['preview']['images']) || isset($story['preview']['videos']))) {
    if (!isset($continue) && !isset($override)) { // Headline story
      echo '<div class="col-sm-8 newsImgBox">';
      if (isset($story['preview']['videos'])) { // If there's a video specified, embed the video
        switch ($story['preview']['videos'][0]['type']) {
          case 'youtube':
            $story['preview']['videos'][0]['src'] = $story['preview']['videos'][0]['src'].'?rel=0&amp;showinfo=0';
            $vidBkgd = 'http://img.youtube.com/vi/'.$story['preview']['videos'][0]['id'].'/0.jpg';
            break;
          case 'drive':
            $vidBkgd = 'https://drive.google.com/thumbnail?id='.$story['preview']['videos'][0]['id'];
            break;
          case 'vimeo':
            $thumbnail = unserialize(file_get_contents('http://vimeo.com/api/v2/video/'.$story['preview']['videos'][0]['id'].'.php'));
            $vidBkgd = $thumbnail[0]['thumbnail_large'];
            break;
        }
        echo '<div class="embed-responsive" style="background-image:url(\''.$vidBkgd.'\');">';
        echo '<iframe class="embed-responsive-item hidden-xs" src="'.$story['preview']['videos'][0]['src'].'" allowfullscreen="true"></iframe>';
      echo '</div>';
              } elseif (count($story['preview']['images']) > 1) { // If there's more than one image, make a slideshow
                $slideImgs = array_reverse($story['preview']['images']); // The slideshow code needs the images going in reverse order
                echo '<div id="slideshow">';
                foreach ($slideImgs as $slide) {
                  echo '<div style="background-image:url(\''.$slide.'\');"></div>';
                }
                echo '</div>';
              } else {
                echo '<div style="background-image:url(\''.$story['preview']['images'][0].'\');"></div>';
              }
              echo '</div>';
              $featured = 1;
              $continue = 1;
              $chars = 100;
              echo '<div class="col-sm-4">';
            } else {
              if (isset($story['preview']['images']) && count($story['preview']['images']) >= 4 && $r == 0) {
                for ($i = 0; $i < 4; $i++) {
                  echo '<div class="col-sm-3 ';
                  if ($i > 0) {
                    echo 'hidden-xs'; // So only one image is displayed on mobiles
                  } else {
                    echo 'col-xs-5';
                  }
                  echo ' newsImgBox">';
                    echo '<div style="background-image:url(\''.$story['preview']['images'][$i].'\');"></div>';
                  echo '</div>';
                }
                $r = 3; // A small counter to prevent these rows happening too often - they'll look messy
                $chars = 160;
                echo '<div class="col-sm-12 col-xs-7">';
              } elseif (isset($story['preview']['images'])) {
                echo '<div class="col-xs-5 newsImgBox">';
                  echo '<div style="background-image:url(\''.$story['preview']['images'][0].'\');"></div>';
                echo '</div>';
                $a++; // This is to determine when to place the 'latest audio' box
                if ($r > 0) { $r = $r-1; }
                $chars = 120;
                echo '<div class="col-xs-7">';
              } else { // No images, so there must be a video thumbnail we can use
                echo '<div class="col-xs-5 newsImgBox">';
                  if ($story['preview']['videos'][0]['type'] == 'youtube') {
                    echo '<div style="background-image:url(\'http://img.youtube.com/vi/'.$story['preview']['videos'][0]['id'].'/0.jpg\');"></div>';
                  } elseif ($story['preview']['videos'][0]['type'] == 'vimeo') {
                    $thumbnail = unserialize(file_get_contents('http://vimeo.com/api/v2/video/'.$story['preview']['videos'][0]['id'].'.php'));
                    echo '<div style="background-image:url(\''.$thumbnail[0]['thumbnail_large'].'\');"></div>';
                  } elseif ($story['preview']['videos'][0]['type'] == 'drive') {
                    echo '<div style="background-image:url(\'https://drive.google.com/thumbnail?id='.$story['preview']['videos'][0]['id'].'\');"></div>';
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
          echo '>'.formatText($title,0).'</h3>';
          echo '<p><strong>'.$story['preview']['date'].'</strong>';
          $stub = ': '.word_cutoff($story['preview']['text'],$chars).'...';
          if (!isset($featured)) {
            $stub = '<span class="hidden-xs">'.$stub.'</span>';
          }
          echo $stub.'</p>';
        echo '</div>';
      echo '</div>';
    echo '</a>';
  }
?>
