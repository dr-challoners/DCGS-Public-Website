<?php

switch ($dataType) {
  case 'link':
    // Gives brand icons to some websites
    if (!empty($row['url'])) {
      if (strpos($row['url'],"twitter.com") !== false) {
        $linkIcon = 'twitter';
      } elseif (strpos($row['url'],"instagram.com") !== false) {
        $linkIcon = 'instagram';
      } else {
        $linkIcon = 'link';
      }
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
    if (!empty($row['url'])) {
      for ($i = 0; $i < strlen($row['url']); $i++) { $address .= '&#'.ord($row['url'][$i]).';'; }
      $row['url'] = "mailto:".$address; 
    }
    break;
}

if (!empty($row['url'])) {
  $content = '<a target="'.mt_rand().'" class="barLink btn btn-default btn-block" href="'.$row['url'].'" role="button">';
} else {
  $content = '<a class="barLink-disabled btn btn-default btn-block" disabled role="button">';
}
$content .= '<i class="fa fa-'.$linkIcon.'"></i>';
if (!empty($row['content'])) {
  $content .= formatText($row['content'],0);
} else {
  $content .= $row['url'];
}
$content .= '</a>';
if (isset($set)) {
  $output['content'][$set]['set'][] = $content; 
} else {
  $output['content'][] = $content;
}

?>