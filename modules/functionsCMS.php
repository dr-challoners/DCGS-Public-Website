<?php

function makeiFrame($iFrameContent, $iFrameClass = '', $iFrameText = '', $format = '') {
      $line = '';
      $line .= '<div class="embed-responsive embed-responsive-'.$iFrameClass.' hidden-print">';
        $line .= '<iframe class="embed-responsive-item" src="'.$iFrameContent.'" allowfullscreen="true"></iframe>';
      $line .= '</div>';
      if (!empty($iFrameText) && (strpos($format,'set') === false || $iFrameClass != 'video')) {
        $frameID = makeID($iFrameContent.$iFrameText,1);
        switch ($iFrameClass) {
          case 'video':
            $frameIcon = 'television';
          break;
          case 'audio':
          case 'audioPlaylist':
            $frameIcon = 'music';
          break;
          case 'form':
            $frameIcon = 'pencil';
          break;
          case 'geogebra':
            $frameIcon = 'bar-chart';
          break;
          case 'document':
          case 'presentation':
          case 'spreadsheets':
            $frameIcon = 'file';
          break;
          default:
            $frameIcon = 'chevron-down';
          break;
        }
        $button = '<a class="barLink btn btn-default btn-block hidden-print" role="button" data-toggle="collapse" href="#'.$frameID.'" aria-expanded="false" aria-controls="collapseExample">';
          $button .= '<i class="fa fa-'.$frameIcon.'"></i>';
          $button .= $iFrameText;
        $button .= '</a>';
        $line = '<div class="collapse" id="'.$frameID.'">'.$line.'</div>';
        $line = $button.$line;
      }
      $line .= '<p class="visible-print-block"><strong>'.ucwords($iFrameClass).': '.$iFrameContent.'</strong></p>';
  if (strpos($format,'set') === false || $iFrameClass != 'video') {
    // Build a basic container first and then modify it if there's any formatting (videos only, not audio)
    $container = '<div class="row"><div class="embedFeature col-sm-X col-sm-offset-X">';
    $line = $container.$line.'</div></div>';
  }
  $size = '12'; // Default full width for iFrames
  $offset = '0';
  if ($iFrameClass == 'video') {
    if (strpos($format,'left') !== false || strpos($format,'right') !== false) {
      $line = str_replace('<div class="row">','',$line);
      $line = substr($line,0,-6);
      $line = str_replace(' col-sm-offset-X','',$line);
      if (strpos($format,'right') !== false) {
        $line = str_replace('embedFeature','embedFeature pull-right',$line);
      } else {
        $line = str_replace('embedFeature','embedFeature pull-left',$line);
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
  }
  $line = str_replace('col-sm-X','col-sm-'.$size,$line);
  $line = str_replace('col-sm-offset-X','col-sm-offset-'.$offset,$line);
  return $line;
}

function fetchImageFromURL($localPath,$imageURL,$imageName = null) {
  // Takes a url for an image, checks to see if the image is already in the system
  // (at the specified path), and makes it if not. Then returns the location of the image.
  // The $imageName parameter creates more human-readable image filenames.
  if (!empty($imageName)) {
    $imageName = makeID($imageURL,1).'-'.clean($imageName);                      
  } else {
    $imageName = makeID($imageURL);
  }
  $path = $_SERVER['DOCUMENT_ROOT'].$localPath;
  if (!file_exists($path)) {
    mkdir($path,0777,true);
  }
  $pathFiles = scandir($path);
  foreach ($pathFiles as $entry) {
    if (explode('.',$entry)[0] == $imageName) {
      $found = 1;
      return $localPath.'/'.$entry;
      break;
    }
  }
  if (!isset($found)) {
    if (strpos($imageURL,'drive.google.com') !== false) {
      if (strpos($imageURL,'/file/d/') !== false) {
        $file = strpos($imageURL,'/file/d/');
      } elseif (strpos($imageURL,'open?id=') !== false) {
        $file = strpos($imageURL,'open?id=');
      }
      if (isset($file)) {
        $file = $file+8;
        $file = substr($imageURL,$file);
        $file = explode('/',$file)[0];
        $file = 'http://drive.google.com/uc?export=view&id='.$file;
        if (@file_get_contents($file) === false) {
          // This means if the image doesn't fetch, it just drops out (hopefully)
          unset ($file);
        }
      }
    } elseif (isImage($imageURL)) {
      $file = $imageURL;
    }
    if (isset($file)) {
      $fileDetails = getimagesize($file);
      $fileWidth  = $fileDetails[0];
      $fileHeight = $fileDetails[1];
      $fileType   = $fileDetails['mime'];
      switch($fileType) {
          
        case 'image/jpeg':
        case 'image/pjpeg':
          $fileType = '.jpg';
        break;
          
        case 'image/png':
          $fileType = '.png';
        break;
          
        case 'image/png':
          $fileType = '.png';
        break;
          
        case 'image/gif':
          $fileType = '.gif';
        break;
          
        default:
          $fileType = false;
        break;
          
      }
      
      if ($fileType != false) {
      
      $file = file_get_contents($file);
      
      // Image resizing
      if ($fileWidth > 1200 || $fileHeight > 1200) {
        if ($fileWidth >= $fileHeight) {
          $newWidth  = 1200;
          $newHeight = $fileHeight*1200/$fileWidth;
        } else {
          $newHeight = 1200;
          $newWidth  = $fileWidth*1200/$fileHeight;
        }
        $src = imagecreatefromstring($file);
        $dst = imagecreatetruecolor($newWidth, $newHeight);
        $imageName = $imageName.'.jpg';
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $fileWidth, $fileHeight);
        imagejpeg($dst,$path.'/'.$imageName,60);
      } else {
        $imageName = $imageName.$fileType;
        file_put_contents($path.'/'.$imageName,$file);
      }
      
      return $localPath.'/'.$imageName;
        
      } else { return false; }

    } else { return false; }
  }
}

?>