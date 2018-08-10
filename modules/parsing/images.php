<?php

  $image = fetchImageFromURL($directory,$currentImage['url'],$currentImage['content']);
  if ($image != false) {
    $format = $currentImage['format'];
    $content = '<a class="fancyBox" rel="page" href="/'.$image.'"><img class="img-responsive" src="/'.$image.'" /></a>';
    // Setting a constant group includes ALL images on the page as part of a fancyBox set
    if (!empty($currentImage['content'])) {
      $imageTitle = str_replace('=','-',formatText($currentImage['content'],0));
      $content = str_replace('<a','<a title="'.$imageTitle.'"',$content);
      $content = str_replace('/>','alt="'.$imageTitle.'" />',$content);
      $imageTitle = explode('=',$currentImage['content']);
      if (isset($imageTitle[1])) {
        $credit = trim($imageTitle[1]);
        if (stripos($credit,', Year ') == true) {
          // This tidies up the way a student's year is displayed, if editors don't follow the style guide
          $credit = str_ireplace(', Year',' (Year',$credit);
          $credit = $credit.')';
        }
        // Stop just the year number from dropping to a new line on long lists of credits
        $credit = str_ireplace('Year ','Year&nbsp;',$credit);
        if (isset($imageTitle[2])) {
          $credit = '<a href="'.$imageTitle[2].'">'.$credit.'</a>';
        }
      }
    }
    if (!in_array('set',$format) && !in_array('gallery',$format)) {
      // Build a basic container first and then modify it if there's any formatting
      $container = '<div class="row"><div class="embedFeature col-sm-X col-sm-offset-X">';
      $content = $container.$content.'</div></div>';
      $size = '8'; // Default full width for iFrames
      $offset = '2';
      if (in_array('left',$format) || in_array('right',$format)) {
        $content = str_replace('<div class="row">','',$content);
        $content = substr($content,0,-6);
        $content = str_replace(' col-sm-offset-X','',$content);
        if (in_array('right',$format)) {
          $content = str_replace('embedFeature','embedFeature pull-right',$content);
        } else {
          $content = str_replace('embedFeature','embedFeature pull-left',$content);
        }
        $size = 6;
      }
      if (in_array('tiny',$format)) {
        $size = '4';
        $offset = '4';
      } elseif (in_array('small',$format)) {
        $size = '6';
        $offset = '3';
      } elseif (in_array('medium',$format)) {
        $size = '8';
        $offset = '2';
      } elseif (in_array('wide',$format)) {
        $size = '12';
        $offset = '0';
      }
      $content = str_replace('col-sm-X','col-sm-'.$size,$content);
      $content = str_replace('col-sm-offset-X','col-sm-offset-'.$offset,$content);
    }
  }

?>