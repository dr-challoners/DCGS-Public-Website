<?php

  // Anything in here can be used outside the sheetCMS program with no additional parameters required

function sheetToArray($sheetKey,$cacheFolder,$refreshTime = 24) {
  
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

function formatText($text,$paragraphs = 1) {
  $text = htmlentities($text);
  $text = Parsedown::instance()->parse($text);
  if ($paragraphs == 0) {
    $text = str_replace(array('<p>','</p>'),'',$text);
  }
  return $text;
}

function clean($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
   $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
   return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
}

function makeID($string, $short = '') {
    $string = md5($string);
    if (!empty($short)) {
      $string = substr($string,0,8);
    }
    return $string;
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

function view($array) {
  // Just because I'm bored of writing the same thing again and again...
  echo '<pre>';
    print_r($array);
  echo '</pre>';
}

?>