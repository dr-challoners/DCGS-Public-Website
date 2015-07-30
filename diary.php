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

  echo '<script type="text/javascript" language="javascript">'; // Jumps the page to the actual day being navigated
	  echo 'function moveWindow (){window.location.hash='.date('Ymd',$curTimestamp).'";}';
  echo '</script>';

  include('header_navigation.php');

  // First up: building the array from which all the data will be read. This is going to take the separate data from the Google Calendar XML file and the sports calendar Google Sheet, and re-work them as a single multidimensional array, with each date being subdivided into ordered events and then into details for each event.

  if (isset($_GET['diarySync'])) { $refreshTime = 0; } else { $refreshTime = 24; } // To allow a forced resync on the data

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
      $eventID = makeID($eventDetails['event']);
      if (isset($time{start})) {
        $eventID = str_replace(':','',$time{start}).$eventID;
      } else {
        $eventID = '0000'.$eventID;
      }
      $eventID = 'X';
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
      if (isset($entry['event'])) {
        // Get rid of unused elements so you only need to do an isset check, not also a !empty check
        foreach ($entry as $key => $item) {
          if ($item == '') { unset($entry[$key]); }
        }
        // Generate a unique, orderable ID for the event, as above
        $eventID = makeID($entry['event']);
        if (isset($entry['meettime'])) {
          $eventID = str_replace(':','',$entry['meettime']).$eventID;
        } elseif (isset($entry['matchtime'])) {
          $eventID = str_replace(':','',$entry['matchtime']).$eventID;
        } else {
          $eventID = '0000'.$eventID;
        }
        // Take the date from a human-readable format to an orderable one
        $date = explode('/',$entry['date']);
        $d = str_pad($date[0],2,'0',STR_PAD_LEFT);
        $m = str_pad($date[1],2,'0',STR_PAD_LEFT);
        $y = str_pad($date[2],4,'20',STR_PAD_LEFT); // If someone has left the year in YY form, it assumes it's in this millenium
        unset($entry['date']); // As we won't need it in the final array
        $generalData[$y.$m.$d][$eventID] = $entry;
      }
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

  // The calendar widget
  echo '<!--googleoff: all--><div class="ncol">';
    echo '<div class="calendar">';
      echo '<p class="month">';
        echo  '<a class="lmonth">&#171;</a> ';
        echo date('F Y',$curTimestamp);
        echo ' <a class="nmonth">&#187;</a>';
      echo '</p>';
      echo '<div class="weekdays">';
        echo '<p>Mon</p>';
        echo '<p>Tue</p>';
        echo '<p>Wed</p>';
        echo '<p>Thu</p>';
        echo '<p>Fri</p>';
        echo '<p>Sat</p>';
        echo '<p>Sun</p>';
      echo '</div>';
    echo '</div>';
    echo '<div class="diarylinks lrg">';
      echo '<p><a href="/diary/year/">Year summary</a></p>';
			echo '<p><a href="/pages/Information/General information/Term dates">Term dates</a></p>';
      echo '<p><a target="page'.mt_rand().'" href="https://www.google.com/calendar/embed?src=challoners.org_1e3c7g4qn1kic52usnlbrn63ts%40group.calendar.google.com&ctz=Europe/London">View in Google Calendar</a></p>';
      echo '<p><a href="https://www.google.com/calendar/ical/challoners.org_1e3c7g4qn1kic52usnlbrn63ts%40group.calendar.google.com/public/basic.ics">Download iCal format</a></p>';
		echo "</div>";
  echo '</div><!--googleon: all-->';

  // Display the selected week's events
  echo '<div class="diary mcol-rgt">';
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
        foreach ($diaryArray[date('Ymd',$curDay)] as $id => $event) { // ID not needed for much, except picking out teamsheets to print
          echo '<h3>';
            if (isset($event['sport'])) { echo $event['sport'].': '; }
            echo $event['event'];
          echo '</h3>';
          echo '<p class="time">';
            if (isset($event['timestart'])) {
              echo $event['timestart'];
              if (isset($event['timeend'])) { echo ' - '.$event['timeend']; }
            } elseif (isset($event['meettime'])) {
              echo $event['meettime'];
              if (isset($event['timeend'])) { echo ' - '.$event['timeend']; }
            } elseif (isset($event['matchtime'])) {
              echo $event['matchtime'];
              if (isset($event['timeend'])) { echo ' - '.$event['timeend']; }
            }
          echo '</p> ';
          if (isset($event['venue']) || isset($event['teams']) || isset($event['results'])) {
            echo '<p class="details">';
              if (isset($event['venue'])) {
                echo $event['venue'];
                if (isset($event['teams']) || isset($event['results'])) { echo ' - '; }
              }
              if (isset($event['teams'])) {
                if (isset($event['results'])) {
                  $results = explode(';',$event['results']);
                  foreach ($results as $key => $result) {
                    $result = trim($result);
                    if ($result == '') { unset($results[$key]); }
                    elseif (preg_match('/^[0-9]+-[0-9]+$/',$result)) { // Football-type scores
                      unset($outcome);
                      $scores = explode('-',$result);
                      if ($scores[0] >  $scores[1]) { $outcome = 'win';  }
                      if ($scores[0] <  $scores[1]) { $outcome = 'loss'; }
                      if ($scores[0] == $scores[1]) { $outcome = 'draw'; }
                      $results[$key] = '&nbsp;<span id="'.$outcome.'">('.$result.' '.$outcome.')</span>';
                    } elseif (preg_match('/^[0-9]+-[0-9]{1,2}F?,[0-9]+-[0-9]{1,2}F?$/i',$result)) { // Cricket scores
                      unset($outcome);
                      $scores = explode(',',$result);
                      foreach ($scores as $side => $score) {
                        $score = trim($score);
                        $score = strtolower($score);
                        $score = explode('-',$score);
                        if (strpos($score[1],'f') == true) {
                          $scores[$side] = array($score[0],chop($score[1],'f'),'first');
                        } else {
                          $scores[$side] = array($score[0],$score[1]);
                        }
                      }
                      $runsDiff = $scores[0][0] - $scores[1][0];
                      if ($runsDiff == 0) { $result = 'draw'; $outcome = 'draw'; }
                      if ($runsDiff > 0 && isset($scores[0][2])) { $result = 'Challoner\'s win by '.$runsDiff.' runs'; $outcome = 'win'; }
                      elseif ($runsDiff > 0) { $result = 'Challoner\'s win by '.(10 - $scores[0][1]).' wickets'; $outcome = 'win'; }
                      if ($runsDiff < 0 && isset($scores[1][2])) { $result = 'opponents win by '.($runsDiff*-1).' runs'; $outcome = 'loss'; }
                      elseif ($runsDiff < 0) { $result = 'opponents win by '.(10 - $scores[1][1]).' wickets'; $outcome = 'loss'; }
                      $result = str_replace(' ','&nbsp;',$result); // This is just to make the code above a little easier to read!
                      $results[$key] = '&nbsp;<span id="'.$outcome.'">('.$result.')</span>';
                    } else { // Any other input for the scores not covered by the above
                      $results[$key] = '&nbsp;<span>('.$result.')</span>';
                    }
                  }
                }
                $teams = explode(';',$event['teams']);
                foreach ($teams as $key => $team) {
                  $tCount = $key+2;
                  $team = trim($team);
                  $team = str_replace(' ','&nbsp;',$team); // This, and in the results processing above, keeps team names and scores neatly together
                  echo $team;
                  if (isset($results[$key])) { echo $results[$key]; }
                  if ($tCount == count($teams)) { echo ' and '; }
                  elseif ($tCount < count($teams)) { echo ', '; }
                }
              } elseif (isset($event['results'])) {
                echo '<span>'.$event['results'].'</span>';
              }
            echo '</p>';
            if (isset($event['teams']) && isset($event['players'])) {
              echo '<p class="details"><a href="/teamsheet/'.date('Ymd',$curDay).'-'.$id.'-1">View printable team sheets</a>.</p>';
            }
          }
          if (isset($event['venuename']) || isset($event['venuepostcode'])) {
            echo '<p class="details">';
            if (isset($event['venuename'])) {
              echo $event['venuename'].'. ';
            }
            if (isset($event['venuepostcode'])) {
              echo '<a href="https://www.google.co.uk/maps?q='.$event['venuepostcode'].'" target="'.mt_rand().'">See the location on Google Maps</a>.';
            }
            echo '</p>';
          }
          if (isset($event['otherdetails'])) { echo '<p class="details">'.str_replace('||','<br />',$event['otherdetails']).'</p>'; }
        }
      }
    }
  echo '</div>';

  include('footer.php');

?>