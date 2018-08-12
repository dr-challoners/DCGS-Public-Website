<?php

function view($array) {
  // Just because I'm bored of writing the same thing again and again...
  echo '<pre>';
    print_r($array);
  echo '</pre>';
}

function isImage($url) {
     $params = array('http' => array(
                  'method' => 'HEAD'
               ));
     $ctx = stream_context_create($params);
     $fp = @fopen($url, 'rb', false, $ctx);
     if (!$fp) 
        return false;  // Problem with url

    $meta = stream_get_meta_data($fp);
    if ($meta === false)
    {
        fclose($fp);
        return false;  // Problem reading data from url
    }

    $wrapper_data = $meta["wrapper_data"];
    if(is_array($wrapper_data)){
      foreach(array_keys($wrapper_data) as $hh){
          if (substr($wrapper_data[$hh], 0, 19) == "Content-Type: image") // strlen("Content-Type: image") == 19 
          {
            fclose($fp);
            return true;
          }
      }
    }

    fclose($fp);
    return false;
  }

function makeiFrame($iFrameContent, $iFrameClass = '', $iFrameText = '', $format = array()) {
      $line = '';
      $line .= '<div class="embed-responsive embed-responsive-'.$iFrameClass.' hidden-print">';
        $line .= '<iframe class="embed-responsive-item" src="'.$iFrameContent.'" allowfullscreen="true"></iframe>';
      $line .= '</div>';
      if (!empty($iFrameText) && (!in_array('set',$format) || $iFrameClass != 'video')) {
        $frameID = makeID($iFrameContent.$iFrameText,1);
        switch ($iFrameClass) {
          case 'video':
            $frameIcon = 'tv-retro';
          break;
          case 'audio':
          case 'audioPlaylist':
            $frameIcon = 'headphones';
          break;
          case 'form':
            $frameIcon = 'pencil';
          break;
          case 'geogebra':
            $frameIcon = 'chart-bar';
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
  if (!in_array('set',$format) || $iFrameClass != 'video') {
    // Build a basic container first and then modify it if there's any formatting (videos only, not audio)
    $container = '<div class="row"><div class="embedFeature col-sm-X col-sm-offset-X">';
    $line = $container.$line.'</div></div>';
  }
  $size = '12'; // Default full width for iFrames
  $offset = '0';
  if ($iFrameClass == 'video' && !in_array('set',$format)) {
    if (in_array('left',$format) || in_array('right',$format)) {
      $line = str_replace('<div class="row">','',$line);
      $line = substr($line,0,-6);
      $line = str_replace(' col-sm-offset-X','',$line);
      if (in_array('right',$format)) {
        $line = str_replace('embedFeature','embedFeature pull-right',$line);
      } else {
        $line = str_replace('embedFeature','embedFeature pull-left',$line);
      }
      $size = 6;
    }
    $findSize = preg_grep ("/^(size-)(\d+)$/", $format);
    $findSize = array_values($findSize);
    if (isset($findSize[0])) {
      $size = substr($findSize[0],5);
    } elseif (in_array('tiny',$format)) {
      $size = 4;
    } elseif (in_array('small',$format)) {
      $size = 6;
    } elseif (in_array('medium',$format)) {
      $size = 8;
    } elseif (in_array('wide',$format)) {
      $size = 12;
    }
    $offset = (12 - $size) / 2;
    if ($size % 2 == 1) {
      $offset = floor($offset).'h';
    }
  }
  $line = str_replace('col-sm-X','col-sm-'.$size,$line);
  $line = str_replace('col-sm-offset-X','col-sm-offset-'.$offset,$line);
  return $line;
}

?>