<?php

// First extract data from the Google Sheet as JSON and process relevant details
// Need to make this into a common function at some point...

function getSheetAsArray($file_id,$n) {
  
  // $file_id can be found in the URL of the sheet you want to extract JSON from. $n is the value of the sheet you wish to parse.
  // Your sheet needs to be published to the web first - find this option in the file menu.
  // This is a very basic program that will handle sheets no more complicated than CSV files. The first row is assumed to be a header and is dropped.

  $file = file_get_contents('https://spreadsheets.google.com/feeds/list/'.$file_id.'/'.$n.'/public/values?alt=json');
  $file = json_decode($file);
  if(isset($file->feed->entry)) {
    $file = $file->feed->entry;

    $data = array();
    foreach ($file as $row) {
      $currentLine = array();
      unset($row->id,$row->updated,$row->category,$row->title,$row->content,$row->link);
      echo "<pre>";
    echo "</pre>";
      foreach ($row as $cell) {
        $cell = get_object_vars($cell);
        $currentLine[] = $cell['$t'];
      }
     $data[] = $currentLine;
    }
    return($data);
  } else {
    $data = "No entries!";
    return($data);
  }
}

$travelTable = '1vsFiAzQt1STTv77cLGwmae840QZ4H_NsAJW37nP_Viw';
$alerts = getSheetAsArray($travelTable,1);
if ($alerts != "No entries!") {
  $alerts = array_reverse($alerts);
  $data = getSheetAsArray($travelTable,2);
  $auth = array();
  foreach ($data as $row) {
    $auth[] = $row[0];
  }
  $active = array(); // Dump staff usernames in here as you go to mean each staff member can only display one message (this way they can edit their own message just by submitting a new one)

  foreach ($alerts as $alert) { 
    // Convert the timestamp recorded in the Google Sheet into a Unix timestamp, to compare with the current time
    $time = explode(" ",$alert[0]);
    $time[0] = explode("/",$time[0]);
    $time[1] = explode(":",$time[1]);
    $timestamp = array();
    foreach ($time[0] as $row) {
      $timestamp[] = $row;
    }
    foreach ($time[1] as $row) {
      $timestamp[] = $row;
    }
    $timestamp = mktime($timestamp[3],$timestamp[4],$timestamp[5],$timestamp[1],$timestamp[0],$timestamp[2]);
    $hours = 3; // How many hours you want an alert to display for
    $checktime = time() - $hours * 60 * 60;

    // If the alert is still in date, the submitting user is authorised, and they haven't already displayed their alert, then put the alert up
    if ($timestamp >= $checktime && in_array($alert[1],$auth) && !in_array($alert[1],$active)) {
      echo '<div class="override" id="travel">';
        echo '<h1>'.$alert[2].'</h1>';
        echo Parsedown::instance()->parse($alert[3]);
        echo '<p style="text-align:right;font-style:italic;">Last updated '.$alert[0].'</p>';
      echo '</div>';
      $active[] = $alert[1];
      $override = 1; // This lets the magazine know there's been an override, so it doesn't display a 'big' news story
    }
  }
}
?>