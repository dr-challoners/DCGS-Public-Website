<?php

  $image = fetchImageFromURL($directory,$imagesArray['url'],$imagesArray['content']);
  if ($image != false) {
    $format = $imagesArray['format'];
    $content = '<a class="fancyBox" rel="page" href="/'.$image.'"><img class="img-responsive" src="/'.$image.'" /></a>';
    // Setting a constant group includes ALL images on the page as part of a fancyBox set
    if (!empty($imagesArray['content'])) {
      $imageTitle = str_replace('=','-',formatText($imagesArray['content'],0));
      $content = str_replace('<a','<a title="'.$imageTitle.'"',$content);
      $content = str_replace('/>','alt="'.$imageTitle.'" />',$content);
      $imageTitle = explode('=',$imagesArray['content']);
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
    if (strpos($format,'set') === false) {
      // Build a basic container first and then modify it if there's any formatting
      $container = '<div class="row"><div class="embedFeature col-sm-X col-sm-offset-X">';
      $content = $container.$content.'</div></div>';
      $size = '8'; // Default full width for iFrames
      $offset = '2';
      if (strpos($format,'left') !== false || strpos($format,'right') !== false) {
        $content = str_replace('<div class="row">','',$content);
        $content = substr($content,0,-6);
        $content = str_replace(' col-sm-offset-X','',$content);
        if (strpos($format,'right') !== false) {
          $content = str_replace('embedFeature','embedFeature pull-right',$content);
        } else {
          $content = str_replace('embedFeature','embedFeature pull-left',$content);
        }
        $size = 6;
      }
      if (strpos($format,'tiny') !== false) {
        $size = '4';
        $offset = '4';
      } elseif (strpos($format,'small') !== false) {
        $size = '6';
        $offset = '3';
      } elseif (strpos($format,'medium') !== false) {
        $size = '8';
        $offset = '2';
      } elseif (strpos($format,'wide') !== false) {
        $size = '12';
        $offset = '0';
      }
      $content = str_replace('col-sm-X','col-sm-'.$size,$content);
      $content = str_replace('col-sm-offset-X','col-sm-offset-'.$offset,$content);
    }
  }

?>