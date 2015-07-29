<?php

  include('header_declarations.php');
  if (!preg_match('/(?i)msie [4-8]/',$_SERVER['HTTP_USER_AGENT'])) { // IE 8 or earlier can't handle media queries
    echo '<link rel="stylesheet" type="text/css" media="screen and (min-device-width : 481px)" href="/styles/diary_lrg.css"/>';
    echo '<link rel="stylesheet" type="text/css" media="screen and (max-device-width : 480px)" href="/styles/diary_sml.css"/>';
  } else {
    echo '<link rel="stylesheet" type="text/css" href="/styles/diary_lrg.css"/>';
  }

  // Create a timestamp for the currently selected day (or today)
  if (isset($_GET['y'])) {
    $curTimestamp = mktime(0,0,0,$_GET['m'],$_GET['d'],$_GET['y']);
  } else {
    $curTimestamp = time();
  }

  if ($_GET['device'] != "mobile") {
  echo '<script type="text/javascript" language="javascript">'; // Jumps the page to the actual day being navigated
	  echo 'function moveWindow (){window.location.hash='.date('Ymd',$curTimestamp).'";}';
  echo '</script>';
  }

  include('header_navigation.php');

  // First up: building the array from which all the data will be read. This is going to take the separate data from the Google Calendar XML file and the sports calendar Google Sheet, and re-work them as a single multidimensional array, with each date being subdivided into ordered events and then into details for each event.

  if (isset($_GET['diarySync'])) { $refreshTime = 0; } else { $refreshTime = 0.5; } // To allow a forced resync on the data

  // This is the Google Sheet for the sports calendar
  $sportsData = sheetToArray('1nDL3NXiwdO-wfbHcTLJmIvnZPL73BZeF7fYBj_heIyA','data_diary',$refreshTime);

  $caches = scandir('data_diary/', SCANDIR_SORT_DESCENDING);

    foreach ($caches as $file) {
      if (strpos($file,'generalData') !== false) {
        $oldFile   = $file;
        $syncCheck = explode('[',$file);
        if (isset($syncCheck[1])) {
          $syncCheck = explode(']',$syncCheck[1]);
          $syncCheck = $syncCheck[0];
          break;
        }
      }
    }

  $refreshTime = $refreshTime*3600;
  if (!isset($syncCheck) || $syncCheck < (time()-$refreshTime)) {

    // This is the Google Calendar data
    $data = 'https://www.googleapis.com/calendar/v3/calendars/challoners.org_1e3c7g4qn1kic52usnlbrn63ts%40group.calendar.google.com/events?key=AIzaSyAutsgnyl2Qf4qEJqnPo5U3OEoRow49h6M&maxResults=2500'; // You cannot get more than 2500 events on a results page, which as a limit will take a little while to reach but after that will get interesting. You can find help here: https://developers.google.com/google-apps/calendar/v3/reference/events/list
    $data = file_get_contents($data);
    $data = json_decode($data, true);
    $data = $data['items'];

    $generalData = array();

    // Take all the information that we're interested in that we're interested, and put it into the final calendar array by date
    foreach ($data as $entry) {
      unset($description,$location,$time{start},$time{end},$date{start},$date{end});
      $eventDetails = array();

      // Get the title of the event
      $eventDetails['event'] = $entry['summary'];

      // Get the start and end times for the event, if any are given
      $bounds = array('start','end');
      foreach ($bounds as $bound) {
        if (isset($entry[$bound]['dateTime'])) {
          $time{$bound} = $entry[$bound]['dateTime'];
          $timeArray = explode('T',$time{$bound});
          $time{$bound} = substr($timeArray[1],0,5);
          $date{$bound} = str_replace('-','',$timeArray[0]);
          $eventDetails['time'.$bound] = $time{$bound};
        }
      }
      // And a little tidying up to give nicer results
      if (isset($eventDetails['timestart']) && isset($eventDetails['timeend']) && $eventDetails['timeend'] == $eventDetails['timestart']) { unset($eventDetails['timeend']); }
      if (isset($eventDetails['timestart']) && $eventDetails['timestart'] == '00:00') { unset($eventDetails['timestart']); }

      // Make a description of the event, if there are any appropriate details given
      if (isset($entry['description'])) {
        $description = trim($entry['description']);
      } else { $description = ''; } // Makes it easier to concatentate in a moment without lots of additional checks
      if (isset($entry['location'])) {
        $location = $entry['location'];
        $location = str_replace('DCGS-Room ','',$location);
        $location = 'Location: '.$location;
        if ($description !== '') { $description .= ' '; }
      } else { $location = ''; }
      $description = $description.$location;
      if ($description !== '') {
        $eventDetails['otherdetails'] = $description;
      }

      // Now generate an ID for this event and work out which dates it occurs on
      // This first sequence means that the event both has a unique identifier AND can be sorted by time
      if (isset($time{start})) {
        $eventID = str_replace(':','',$time{start}).mt_rand();
      } else {
        $eventID = '0000'.mt_rand();
      }
      foreach ($bounds as $bound) {
        if (!isset($date{$bound})) { $date{$bound} = str_replace('-','',$entry[$bound]['date']); }
      }

      // Keep adding the event to the generalData array until it has been added for each relevant date
      while ($date{start} <= $date{end}) {
        $generalData[$date{start}][$eventID] = $eventDetails;
        $date{start}++;
      }
    }

    foreach ($sportsData['data'] as $entry) {
      // Generate a unique, orderable ID for the event, as above
      if (isset($entry['meettime'])) {
        $eventID = str_replace(':','',$entry['meettime']).mt_rand();
      } elseif (isset($entry['matchtime'])) {
        $eventID = str_replace(':','',$entry['matchtime']).mt_rand();
      } else {
        $eventID = '0000'.mt_rand();
      }
      // Take the date from a human-readable format to an orderable one
      $date = explode('/',$entry['date']);
      $d = str_pad($date[0],2,'0',STR_PAD_LEFT);
      $m = str_pad($date[1],2,'0',STR_PAD_LEFT);
      $y = str_pad($date[2],4,'20',STR_PAD_LEFT); // If someone has left the year in YY form, it assumes it's in this millenium
      unset($entry['date']); // As we won't need it in the final array
      $generalData[$y.$m.$d][$eventID] = $entry;
    }

    // Now just put all the data in chronological order
    ksort($generalData);
    foreach ($generalData as $key => $day) {
      ksort($day);
      $generalData[$key] = $day;
    }

    // Cache this final array as JSON and record the time of syncing; remove the old cache
      $newFile = 'generalData['.time().'].json';
      file_put_contents('data_diary/'.$newFile, json_encode($generalData));
      if (isset($oldFile)) { unlink('data_diary/'.$oldFile); }
    
  } else { $newFile = $oldFile; }

  // Now output the array for use
  $diaryArray = file_get_contents('data_diary/'.$newFile);
  $diaryArray = json_decode($diaryArray, true);

  // Now to make the diary display itself

  // Display the selected week's events
  echo '<div class="mcol-rgt" id="diary">';
    $curWeek = $curTimestamp-(date('N',$curTimestamp)-1)*86400;
    for ($d = 0; $d < 7; $d++) {
      $curDay = $curWeek + $d*86400;
      echo '<a class="anchor" name="'.$curDay.'"></a>';
      echo '<h2>'.date('l jS',$curDay);
      if (date("j",$curDay) == 1 || $d == 0) { // If it's the start of the month or the first entry displayed (ie, a Monday), then give the month
        echo '<span>'.date('F Y',$curDay).'</span>';
        }
      echo '</h2>';
      if (isset($diaryArray[date('Ymd',$curDay)])) {
        foreach ($diaryArray[date('Ymd',$curDay)] as $event) {
          echo '<p class="time">';
            if (isset($event['timestart'])) {
              echo $event['timestart'];
              if (isset($event['timeend'])) { echo ' - '.$event['timeend']; }
            }
          echo '</p>';
          echo '<h3>'.$event['event'].'</h3>';
          if (isset($event['sport'])) {
            
          }
          if (isset($event['otherdetails'])) { echo '<p class="details">'.$event['otherdetails'].'</p>'; }
        }
      }
    }
  echo '</div>';

  echo '<pre>';
    print_r($diaryArray);
  echo '</pre>';

  include('footer.php');

?>