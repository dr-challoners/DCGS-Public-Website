<?php

unset ($vID,$pID,$time);
if (strpos($row['url'],'youtube.com') !== false || strpos($row['url'],'youtu.be') !== false) { // YouTube
  $vType = 'youtube';
  if (strpos($row['url'],'v=') !== false) { // There's a video ID (this will be most of them, though you can get playlists without)
    $id = substr($row['url'],strpos($row['url'],'v=')+2,11); // All video IDs seem to be 11 characters long
  } elseif (strpos($row['url'],'youtu.be/') !== false) { // Short URLs have the video ID just past the short domain
    $id = substr($row['url'],strpos($row['url'],'youtu.be/')+9,11);
  }
  if (strpos($row['url'],'list=') !== false) { // We're looking at a playlist of videos
    $pID = substr($row['url'],strpos($row['url'],'list=')+5); // Playlist IDs don't have a fixed length
    $pID = explode('&',$pID)[0]; // In case there's additional parameters
  }     
  if (strpos($row['url'],'?t=') !== false) {   
    $time = explode('?t=',$row['url'])[1];
    $time = rtrim($time,'s');
    if (strpos($time,'m') !== false) {
      $time = explode('m',$time);
      $time = $time[0]*60+$time[1];
    }
  }
  $src = 'https://www.youtube.com/embed/';
  if (isset($id)) {
    $src .= $id;
    if (isset($time)) {
      $src .= '?start='.$time;
    } elseif (isset($pID)) {
      $src .= '?list='.$pID;
    }
  } elseif (isset($pID)) {
    $src .= 'playlist?list='.$pID;
  }
} elseif (strpos($row['url'],'drive.google.com') !== false) { // gDrive
  $vType = 'drive';
  $id = explode('?id=',$row['url'])[1];
  $id = explode('&',$id)[0]; // Just to tidy up
  $src = 'https://drive.google.com/a/challoners.org/file/d/'.$id.'/preview';
  // It doesn't matter that challoners.org is specified here:
  // if the file is from somewhere else it will figure it out, albeit marginally slower
} elseif (strpos($row['url'],'vimeo.com') !== false) { // Vimeo
  $vType = 'vimeo';
  $id = explode('/',$row['url']);
  $id = array_pop($id);
  $src = 'https://player.vimeo.com/video/'.$id.'?color=649DE8&title=0&byline=0&badge=0&portrait=0';
} else {
  $src = ''; // This will just break the iFrame, but it means there won't be php errors on the page
}
$content = makeiFrame($src,'video',$row['content'],$format);
if (!isset($set)) {
  $output['content'][] = $content;
} else {
  $output['content'][$set]['set'][] = $content;
}

?>