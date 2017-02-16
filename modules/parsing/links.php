<?php

// Some fallbacks in case users put details in the wrong place
if (!empty($row['url'])) {
  $url = $row['url'];
} else {
  $url = $row['content'];
}
if (!empty($url)) {
  switch ($dataType) {
    case 'link':
      // Gives brand icons to some websites
      if (strpos($url,"twitter.com") !== false) {
        $linkIcon = 'twitter';
      } else {
        $linkIcon = 'link';
      }
      break;
    case 'file':
      $linkIcon = 'file';
      break;
    case 'email':
      $linkIcon = 'envelope-o';
      // Add the 'mailto:' component and some simple robot baffling
      $address = ""; $i = 0;
      for ($i = 0; $i < strlen($url); $i++) { $address .= '&#'.ord($url[$i]).';'; }
      $url = "mailto:".$address; 
      break;
  }
  $content = '<a target="'.mt_rand().'" class="barLink btn btn-default btn-block" href="'.$url.'" role="button">';
  $content .= '<i class="fa fa-'.$linkIcon.'"></i>';
  if (!empty($row['content'])) {
    $content .= formatText($row['content'],0);
  } else {
    $content .= $row['url'];
  }
  $content .= '</a>';
  $output['content'][] = $content;
}

?>