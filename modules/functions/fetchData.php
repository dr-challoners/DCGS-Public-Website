<?php

function sheetToArray($sheetKey, $cacheFolder, $refreshTime = 24, $debug = 0) {
  
  // When called, this function reads the JSON from the specified Google Sheet, stores it in the specified folder as a JSON file and also returns it to the page as a multidimensional array.
  // The stored JSON file also includes a timestamp - to limit slow load times, data is only fetched from Google periodically, otherwise the cached version is used.
  // The refreshTime can be specified (in hours) or will default to every 24 hours. It can also be set to the string 'manual', which means it will only refresh when the GET variable 'sync' is set.
  // Sheets must be published in Google Drive (when in the sheet, go to File > Publish to the web), and the sheetKey is then the sheet ID given in the URL (the long alphanumeric string).
  // Sheet data must be entered in a format where the first row is considered to be header information and all subsequent rows are data that fall under that header. Do not leave blank rows.
  // Note that keys for individual rows match the row numbers of the worksheet itself, should this be needed.
  
  // First call up the cache folder and see if there's already stored data for this sheet
  
  if (!empty($cacheFolder)) {
    $stored = $cacheFolder.'/'.$sheetKey.'.json';
  }
  if (is_numeric($refreshTime)) {
    $refreshTime = $refreshTime*3600;
    // Converts from hours to seconds - allows easier user input
  } else {
    $refreshTime = time(); // This means it never refreshes (the last update time cannot be less than zero - see below)
  }
  if (isset($stored) && file_exists($stored)) {
    $sheetArray = file_get_contents($stored);
    $sheetArray = json_decode($sheetArray, true);
    if (isset($sheetArray['meta']['lastupdate'])) {
      $lastUpdate = $sheetArray['meta']['lastupdate'];
    }
  }
    
  if (!isset($lastUpdate) || (isset($lastUpdate) && $lastUpdate < (time()-$refreshTime)) || isset($_GET['sync'])) {
    // Either this sheet has never been fetched before, or the record is stale, or we're being forced to refresh
  
    // Create an array of all the worksheets within the specified sheet

    $worksheetFile = 'https://spreadsheets.google.com/feeds/worksheets/'.$sheetKey.'/public/basic?alt=json';
    if (@file_get_contents($worksheetFile) !== false) {
    
      $worksheetList = file_get_contents($worksheetFile);
      $worksheetList = json_decode($worksheetList, true);
      $sheetArray = array();
      $sheetArray['meta']['lastupdate'] = time();
      $sheetArray['meta']['sheetname'] = $worksheetList['feed']['title']['$t'];
      $sheetArray['meta']['sheetid'] = $sheetKey;

      $worksheetList = $worksheetList['feed']['entry'];

      $worksheets = array();

      foreach ($worksheetList as $row) {
        $worksheets[] = $row['title']['$t'];
      }

      // Now work through the spreadsheet one worksheet at a time, creating a multi-dimensional array of the link list data for the whole spreadsheet
      $worksheetKey  = 1;

      foreach ($worksheets as $worksheet) {
        $worksheetData = file_get_contents('https://spreadsheets.google.com/feeds/list/'.$sheetKey.'/'.$worksheetKey.'/public/values?alt=json');
        $worksheetData = json_decode($worksheetData, true);
        
        if ($debug == 1) {
          view ($worksheetData);
        }

        if (isset($worksheetData['feed']['entry'])) { // Ignores empty worksheets and worksheets that only have header information
          $worksheetData = $worksheetData['feed']['entry'];

          $worksheetContents = array();
          $rowNum = 2; // This gives each row entry in the array the same number as the row number in the worksheet

          foreach ($worksheetData as $rowData) {
            $worksheetRow = array();
            foreach ($rowData as $key => $data) {
              if (strpos($key, 'gsx$') !== false) {
                $header = substr($key,4);
                $worksheetRow[$header] = $data['$t'];
              }
            }
            $worksheetContents[$rowNum] = $worksheetRow;
            $rowNum++;
          }
          $sheetArray['data'][$worksheet] = $worksheetContents;
        }
        $worksheetKey++;
      }

      if (!empty($cacheFolder)) {
        // Cache the array as JSON into the specified caching folder
        if (!file_exists($cacheFolder)) {
          mkdir($cacheFolder,0777,true);
        }
        file_put_contents($stored, json_encode($sheetArray));
      }
    } else { $sheetArray = 'ERROR'; }   
  }
  
  // Finally, output the sheet data as an array
  return $sheetArray;
    
  }

function directoryToArray($dir) { 
  $result = array(); 
  $cdir = scandir($dir); 
  foreach ($cdir as $key => $value) { 
    if (!in_array($value,array(".",".."))) { 
      if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) { 
        $result[$value] = directoryToArray($dir . DIRECTORY_SEPARATOR . $value); 
      } 
      else { 
        $result[] = $value; 
      } 
    } 
  } 
  return $result; 
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