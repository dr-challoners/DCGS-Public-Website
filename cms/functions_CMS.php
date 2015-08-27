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
  
  $sheetArray = file_get_contents($dataSrc.'/'.$sheetKey.'.json');
  $sheetArray = json_decode($sheetArray, true);
  
  foreach ($sheetArray['data'] as $page => $data) {
    if (clean($pageName) == clean($page)) {
      $pageArray = $data;
      break;
    }
  }
  if (!isset($pageArray)) {
    $error = 1;
    return $error;
  } else {
    
    if ($CMSdiv == 0) {
      echo '<div class="sheetCMS">'."\n\n";
    }
    
    if ($titleDisplay == 1) {
      echo '<h1>'.$page.'</h1>'."\n\n";
    }
    
    foreach ($pageArray as $key => $row) {
      unset($imageName,$dataType,$file,$skipRow);
      
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
            include ($sheetCMS.'/modules/imageDisplay.php');
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
          
          case 'tags':
          case 'tag':  // The instruction is to write 'tags' for this datatype, but this is a failsafe
            // Just ignore them... for now. Consider building a 'similar articles' feature.
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

function fetchImage($imageURL,$imageName) {
  //urlID is three makeIDs concatenated: section name, page name and image url
  
  global $imgsSrc;
  
  if (!file_exists($imgsSrc.'/'.$imageName)) {
    if (strpos($imageURL,'drive.google.com') !== false) {
      while (@file_get_contents($file) === false) { // This should (hopefully) make the program persevere if it struggles to pull the image from Drive
        if (strpos($imageURL,'/file/d/') !== false) {
          $file = strpos($imageURL,'/file/d/');
        } elseif (strpos($imageURL,'open?id=') !== false) {
          $file = strpos($imageURL,'open?id=');
        }
        $file = $file+8;
        $file = substr($imageURL,$file);
        $file = explode('/',$file)[0];
        $file = 'http://drive.google.com/uc?export=view&id='.$file;
      }
    } elseif (isImage($imageURL)) {
      $file = $imageURL;
    }
    if (isset($file)) {
      $file = file_get_contents($file);
      if (!file_exists($imgsSrc)) {
        mkdir($imgsSrc,0777,true);
      }
      file_put_contents($imgsSrc.'/'.$imageName,$file);
    } else { return 'ERROR'; }
  }
}

function navigatePagesSheet($sheetsToNavigate, $variablesAs = '?section=[SECTION]&sheet=[SHEET]&page=[PAGE]', $dropdownMenus = '') {
  
  // variablesAs gives control over the url given by each link in the navigation menu, particularly useful for URL re-writing
  // It can take any format as long as [SECTION], [SHEET] and [PAGE] are present
  
  foreach ($sheetsToNavigate as $id => $sheet) {
    if (!empty($dropdownMenus)) {
      echo '<div class="simpleOpenClose';
      if (isset($_GET['sheet']) && clean($sheet['sheetname']) == clean($_GET['sheet'])) {
        echo ' open';
      }
      echo '" id="'.$id.'" name="'.$dropdownMenus.'">'."\n";
      echo '<h2>';
        echo '<a href="javascript:simpleOpenClose(\''.$id.'\',\''.$dropdownMenus.'\')">';
          echo $sheet['sheetname'];
        echo '</a>';
      echo '</h2>'."\n";
    } else {
      echo '<h2>'.$sheet['sheetname'].'</h2>'."\n";
    }
    echo '<ul>'."\n";
    foreach ($sheet['pages'] as $page) {
      
      $pageURL = str_replace('[PAGE]',clean($page),$variablesAs);
      $pageURL = str_replace('[SHEET]',clean($sheet['sheetname']),$pageURL);
      $pageURL = str_replace('[SECTION]',clean($sheet['section']),$pageURL);
      
      echo '<li>';
        echo '<a href="'.$pageURL.'">'.$page.'</a>';
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