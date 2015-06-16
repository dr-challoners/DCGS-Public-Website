<?php

function sheetToArray($sheetKey,$cacheFolder) {
  
  // When called, this function reads the JSON from the specified Google Sheet, stores it in the specified folder as a JSON file and also returns it to the page as a multidimensional array.
  // The stored JSON file also includes a datestamp - to limit slow load times, data is only fetched from Google every half hour, otherwise the cached version is used.
  // Sheets must be published in Google Drive (when in the sheet, go to File > Publish to the web), and the sheetKey is then the sheet ID given in the URL (the long alphanumeric string).
  // Sheet data must be entered in a format where the first row is considered to be header information and all subsequent rows are data that fall under that header. Do not leave blank rows.
  // Note that keys for individual rows match the row numbers of the worksheet itself, should this be needed.
  
  // First call up the cache folder and see if there's already stored data for this sheet
  
  if (file_exists($cacheFolder.'/')) {
  
    $caches = scandir($cacheFolder.'/', 1);

    foreach ($caches as $file) {
      if (strpos($file,'sheet'.$sheetKey) !== false) {
        $oldFile   = $file;
        $syncCheck = explode('[',$file);
        if (isset($syncCheck[1])) {
          $syncCheck = substr($syncCheck[1],0,-6);
        }
      }
    }
    
  } else { mkdir($cacheFolder.'/'); }
  
  if (!isset($syncCheck) || $syncCheck < (time()-1800)) { // Either this sheet has never been fetched before, or the record is stale
  
    // Create an array of all the worksheets within the specified sheet

    $worksheetFile = 'https://spreadsheets.google.com/feeds/worksheets/'.$sheetKey.'/public/basic?alt=json';
    if (@file_get_contents($worksheetFile) !== false) {
    
      $worksheetList = file_get_contents($worksheetFile);
      $worksheetList = json_decode($worksheetList, true);

      $worksheetList = $worksheetList['feed']['entry'];

      $worksheets = array();

      foreach ($worksheetList as $row) {
        $worksheets[] = $row['title']['$t'];
      }

      // Now work through the spreadsheet one worksheet at a time, creating a multi-dimensional array of the link list data for the whole spreadsheet

      $sheetContents = array();
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
          $sheetContents[$worksheet] = $worksheetContents;
        }
        $worksheetKey++;
      }

      // Cache the array as JSON into the specified caching folder and record the time of syncing; remove the old cache
      $newFile = 'sheet'.$sheetKey.'['.time().'].json';
      file_put_contents($cacheFolder.'/'.$newFile, json_encode($sheetContents));
      if (isset($oldFile)) { unlink($cacheFolder.'/'.$oldFile); }
      
    } else { $newFile = 'ERROR'; }
    
  } else { $newFile = $oldFile; }
  
  // Finally, output the sheet data as an array
  
  if ($newFile !== 'ERROR' && file_exists($cacheFolder.'/'.$newFile)) {
  
    $sheetArray = file_get_contents($cacheFolder.'/'.$newFile);
    $sheetArray = json_decode($sheetArray, true);
    return $sheetArray;
    
  } else {
   
    echo '<style> body { background-image: url(\'/styles/imgs/error.png\'); background-position: right bottom; background-repeat: no-repeat; background-attachment: fixed; background-size: 980px auto; } </style>';
      echo '<h1>Uh oh</h1>';
      echo '<p>Whatever should be on this bit of the page... isn\'t. We\'ve probably seen this and are working to fix it, but feel free to <a href="/pages/Information/General information/Contact us">contact us</a> to report the problem.</p>';
      echo '</div>'; // Closes the parsebox div
    include('footer.php');
    
    break;
    
  }
  
}

?>