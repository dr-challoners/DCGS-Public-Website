<?php

  // These functions will only work if the config parameters have been correctly set up in sheetCMS.
  // The config parameters must specify:
  //   - the path to the directory that the data is stored in
  //   - the path to the directory that any images are stored in
  //   - the sheet ID for the main content sheet, which specifies the structure for all other sheets

function parsePagesSheet($sheetKey, $pageName, $CMSdiv = 0, $titleDisplay = 1, $customModule = 'none') {
  
  // This is the big one. This function goes through the collected array and converts each row into HTML according to the content found there.
  // sheetKey need just be the sheet key for the data (as the function must be used with the config file that gives the rest)
  // pageName is presumed to have spaces and none-url friendly characters stripped from it (it does a clean anyway).
  // Setting CMSdiv to 1 tells the function that the div with class 'sheetCMS' has already been included, and so won't be made here
  // Make sure that this div exists if you wish to use the built-in sheetCMS styles
  // If titleDisplay is set to 0 then the first line won't be an h1 header of the page's name
  // Where possible, this system creates HTML only and leaves styling to the website
  
  global $dataSrc, $imgsSrc, $sheetCMS, $colour;
  global $sheetArray;
  
  if (!isset($sheetArray)) {
    $sheetArray = file_get_contents($dataSrc.'/'.$sheetKey.'.json');
    $sheetArray = json_decode($sheetArray, true);
  }
  
  foreach ($sheetArray['data'] as $page => $data) {
    if (clean($pageName) == clean($page)) {
      $pageArray = $data;
      break;
    }
  }
  
  if (!isset($pageArray)) {
    return 'ERROR';
  } else {
    
    if ($CMSdiv == 0) {
      echo '<div class="sheetCMS">'."\n\n";
    }
    
    if ($titleDisplay == 1) {
      echo '<h1>'.$page.'</h1>'."\n\n";
    }
    
    foreach ($pageArray as $key => $row) {
      unset($urlID,$dataType,$file,$skipRow);
      $urlID = makeID($sheetArray['meta']['sheetName']).makeID($page).makeID($row['url']);
      
      if (!empty($row['datatype'])) {
        $dataType = strtolower(clean($row['datatype']));
      } else {
        // If the data type has been specified, then the program can format accordingly.
        // If it hasn't been specified, the program makes a basic guess as to what it might be.
        if (!empty($row['url'])) {
          $dataType = 'link';
        } else {
          $dataType = 'text';
        }
      }
      
      if ($customModule != 'none' && file_exists($sheetCMS.'/modules/custom/'.$customModule)) {
        include ($sheetCMS.'/modules/custom/'.$customModule);
        // This file should be just a switch ($dataType) function
        // The ordinary functions below will still process unless you set $skipRow
      }
      if (!isset($skipRow) && $row['format'] != 'hidden') {
        switch ($dataType) {

          case 'text':
          default:
            $row['content'] = htmlentities($row['content']);
            echo Parsedown::instance()->parse($row['content']);
          break;

          case 'link':
          case 'file':
            echo '<p class="link';
              if ($dataType = 'file') {
                echo ' file';
              }
            echo '">';
              echo '<a href="'.$row['url'].'">'.$row['content'].'</a>';
            echo '</p>';
          break;

          case 'image':
            // Make or fetch the image
            if (!file_exists($imgsSrc.'/'.$urlID)) {
              if (strpos($row['url'],'drive.google.com') !== false) {
                while (@file_get_contents($file) === false) { // This should (hopefully) make the program persevere if it struggles to pull the image from Drive
                  if (strpos($row['url'],'/file/d/') !== false) {
                    $file = strpos($row['url'],'/file/d/');
                  } elseif (strpos($row['url'],'open?id=') !== false) {
                    $file = strpos($row['url'],'open?id=');
                  }
                  $file = $file+8;
                  $file = substr($row['url'],$file);
                  $file = explode('/',$file)[0];
                  $file = 'http://drive.google.com/uc?export=view&id='.$file;
                }
              } elseif (isImage($row['url'])) {
                $file = $row['url'];
              }
              $file = file_get_contents($file);
              if (!file_exists($imgsSrc)) {
                mkdir($imgsSrc,0777,true);
              }
              file_put_contents($imgsSrc.'/'.$urlID,$file);
            }
            // Constructing sets
            if (strpos($row['format'],'set') !== false && !isset($set)) {
              $set = 1;
              for ($i = 1; $i >= 1; $i++) {
                if (isset($pageArray[$key+$i]) && strpos($pageArray[$key+$i]['format'],'set') !== false && strpos($pageArray[$key+$i]['format'],'new') === false) {
                  $set++;
                } else {
                  break;
                }
              }
              if ($set > 1) {
                echo '<div class="imageSet ';
                  if ($set >= 5 || strpos($row['format'],'scrolling') !== false) {
                    echo 'scrolling';
                  } else {
                    $numWords = array(2 => 'two', 3 => 'three', 4 => 'four');
                    echo $numWords[$set];
                  }
                echo '">'."\n\n";
              } else {
                unset($set);
              }
            }
            // Displaying the image
            echo '<a href="/'.$imgsSrc.'/'.$urlID.'" data-lightbox="gallery" class="img'; // Setting data-lightbox to gallery includes ALL images on the page as part of a Lightbox set
              if (!isset($set)) {    
                $imgFormats = array('wide','left','right');
                if (in_array($row['format'],$imgFormats)) {
                  echo ' '.$row['format'];
                }
              }
              echo '"';
              if (!empty($row['content'])) {
                echo ' data-title="'.str_replace('=','-',$row['content']).'"';
              }
            echo '>'."\n";
              echo '<img src="/'.$imgsSrc.'/'.$urlID.'" ';
                if (!empty($row['content'])) {
                  echo 'alt="'.str_replace('=','-',$row['content']).'" ';
                }
              echo '/>'."\n";
              if (!empty($row['content']) && isset(explode('=',$row['content'])[1])) {
                echo '<p>';
                  echo trim(explode('=',$row['content'])[1]);
                echo '</p>'."\n";
              }
            echo '</a>';
            // Ending a set
            if (isset($set)) {
              $set--;
              if ($set == 0) {
                echo "\n\n".'</div>';
                unset($set);
              }
            }
          break;
          
          case 'video':
            // YouTube videos
            if (strpos($row['url'],"youtu.be") !== false) {
              $id = strpos($row['url'],"e/");
              $id = substr($row['url'],$id+2);
            } elseif (strpos($row['url'],"youtube") !== false && strpos($row['url'],"watch")) {
              $id = strpos($row['url'],"v=");
              $id = substr($row['url'],$id+2);
            } elseif (strpos($row['url'],"youtube") !== false && strpos($row['url'],"edit")) {
              $id = strpos($row['url'],"d=");
              $id = substr($row['url'],$id);
            }
            makeiFrame("//www.youtube-nocookie.com/embed/$id?rel=0",'video',$row['content']);
          break;
          
          case 'audio':
            // Currently only SoundCloud audio embedding is supported (and this is unlikely to change without good reason)
            if (strpos($row['url'],"soundcloud") !== false && substr_count($row['url'],"/") == 4) {
              $sc = file_get_contents('http://api.soundcloud.com/resolve.json?url='.$row['url'].'&client_id=59f4a725f3d9f62a3057e87a9a19b3c6');
              $sc = json_decode($sc);
              $id = $sc->id;
              if (isset($colour)) { $colour = strtolower(str_replace("hex","",$colour)); } else { $colour = "666666"; }
              $iFrameContent  = "https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/$id&amp;color=$colour&amp;auto_play=false&amp;hide_related=false&amp;show_artwork=false";
              makeiFrame($iFrameContent,'soundcloud',$row['content']);
            }
          break;

        }
      }

      echo "\n\n"; 
    }
        
    if ($CMSdiv == 0) {
      echo '</div>'."\n\n";
    }
    
  }
  
}

function makeiFrame($iFrameContent, $iFrameClass = '', $iFrameTitle = '') {
  $line = '';
  $iFrameID = makeID($iFrameContent);
  if (!empty($iFrameClass)) {
    $iFrameName = $iFrameClass;
  } else {
    $iFrameName = $iFrameID;
  }
  if (!empty($iFrameTitle)) {
    $line .= '<div class="simpleOpenClose" name="'.$iFrameName.'" id="'.$iFrameID.'">'."\n";
    $line .= '<p class="link '.$iFrameName.'"><a href="javascript:simpleOpenClose(\''.$iFrameID.'\',\''.$iFrameName.'\')">';
    $line .= $iFrameTitle.'</a></p>'."\n";
  }
  $line .= '<iframe ';
  if (!empty($iFrameClass)) {
    $line .= 'class="'.$iFrameClass.'" ';
  }
  $line .= 'src="'.$iFrameContent.'" ';
  $line .= 'frameborder="no" allowfullscreen></iframe>';
  if (!empty($iFrameTitle)) {
    $line .= "\n".'<p class="close"><a href="javascript:simpleOpenClose(\''.$iFrameID.'\',\''.$iFrameName.'\')">&#x2715; Close</a></p>'."\n".'</div>';
  }
  echo $line;
}

function navigatePagesSheet($sectionName, $variablesAs = '?section=[SECTION]&sheet=[SHEET]&page=[PAGE]', $dropdownMenus = '', $giveSheetAsKey = '') {
  
  // variablesAs gives control over the url given by each link in the navigation menu, particularly useful for URL re-writing
  // It can take any format as long as [SECTION], [SHEET] and [PAGE] are present
  // Change giveSheetAsKey to yes for less pretty URLs but ones that have the sheetKey as a variable to pick out
  
  global $dataSrc, $mainSheet;
  global $mainSheetArray, $sheetArray;
  
  if (!isset($mainSheetArray)) {
    $mainSheetArray = file_get_contents($dataSrc.'/'.$mainSheet.'.json');
    $mainSheetArray = json_decode($mainSheetArray, true);
  }
  
  $sectionArray = $mainSheetArray['data'][$sectionName];
  
  foreach ($sectionArray as $sheet) {
    if (!isset($sheetArray) || $sheetArray['meta']['sheetID'] != $sheet['sheetid']) {
      $thisSheetArray = file_get_contents($dataSrc.'/'.$sheet['sheetid'].'.json');
      $thisSheetArray = json_decode($thisSheetArray, true);
    } else {
      $thisSheetArray = $sheetArray;
    }
    if (!empty($dropdownMenus)) {
      echo '<div class="simpleOpenClose';
      if (isset($_GET['sheet']) && clean($sheet['sheetname']) == clean($_GET['sheet'])) {
        echo ' open';
      }
      echo '" id="'.$sheet['sheetid'].'" name="'.$dropdownMenus.'">'."\n";
      echo '<h2>';
        echo '<a href="javascript:simpleOpenClose(\''.$sheet['sheetid'].'\',\''.$dropdownMenus.'\')">';
          echo $sheet['sheetname'];
        echo '</a>';
      echo '</h2>'."\n";
    } else {
      echo '<h2>'.$sheet['sheetname'].'</h2>'."\n";
    }
    echo '<ul>'."\n";
    foreach ($thisSheetArray['data'] as $pageName => $array) {
      
      $pageURL  = clean($pageName);
      $sheetURL = clean($sheet['sheetname']);
      $pageURL = str_replace('[PAGE]',$pageURL,$variablesAs);
      $pageURL = str_replace('[SHEET]',$sheetURL,$pageURL);
      $pageURL = str_replace('[SECTION]',$sectionName,$pageURL);
      
      echo '<li>';
        echo '<a href="'.$pageURL.'">'.$pageName.'</a>';
      echo '</li>'."\n";
      
    }
    echo '</ul>'."\n";
    if (!empty($dropdownMenus)) {
      echo '</div>'."\n";
    }
    echo "\n";
  }
  
}

?>