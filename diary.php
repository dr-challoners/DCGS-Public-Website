<?php

  include('header_declarations.php');
  if (!preg_match('/(?i)msie [4-8]/',$_SERVER['HTTP_USER_AGENT'])) { // IE 8 or earlier can't handle media queries
    echo '<link rel="stylesheet" type="text/css" media="screen and (min-device-width : 481px)" href="/styles/diary_lrg.css"/>';
    echo '<link rel="stylesheet" type="text/css" media="screen and (max-device-width : 480px)" href="/styles/diary_sml.css"/>';
  } else {
    echo '<link rel="stylesheet" type="text/css" href="/styles/diary_lrg.css"/>';
  }
  include('header_navigation.php');

  // First up: building the array from which all the data will be read. This is going to take the separate data from the Google Calendar XML file and the sports calendar Google Sheet, and re-work them as a single multidimensional array, with each date being subdivided into ordered events and then into details for each event.

  // This is the Google Calendar data
  $data = 'https://www.googleapis.com/calendar/v3/calendars/challoners.org_1e3c7g4qn1kic52usnlbrn63ts%40group.calendar.google.com/events?key=AIzaSyAutsgnyl2Qf4qEJqnPo5U3OEoRow49h6M';
  $data = file_get_contents($data);
  $data = json_decode($data, true);
  $data = $data['items'];

  $generalData = array();

  // Take all the information that we're interested in that we're interested, and put it into the final calendar array by date
  foreach ($data as $entry) {
    unset($description,$location);
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
      //$eventDetails['date'.$bound] = $date{$bound};
    }

    // Keep adding the event to the generalData array until it has been added for each relevant date
    while ($date{start} <= $date{end}) {
      $generalData[$date{start}][$eventID] = $eventDetails;
      $date{start}++;
    }
  }

  // This is the Google Sheet for the sports calendar
  $sportsData = sheetToArray('1nDL3NXiwdO-wfbHcTLJmIvnZPL73BZeF7fYBj_heIyA','data_diary',0.5);
  
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

  echo '<pre>';
    print_r($generalData);
  echo '</pre>';

  include('footer.php');

?>