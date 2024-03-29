<?php

  // Create a timestamp for the currently selected day (or today)
  if (isset($_GET['y'])) {
    $curTimestamp = mktime(0,0,0,$_GET['m'],$_GET['d'],$_GET['y']);
  } else {
    $curTimestamp = time();
  }

  $section = 'diary';
  include('header.php');

?>

<?php

  // First up: building the array from which all the data will be read. This is going to take the separate data from the Google Calendar XML file and the sports calendar Google Sheet, and re-work them as a single multidimensional array, with each date being subdivided into ordered events and then into details for each event.

  // These variables allow us to create JSON files that have only the current month and the two months either side of it, for quicker processing
  $getMonthYear = date('Y-m',$curTimestamp);
  $getLastMonth = date('Y-m',mktime(0,0,0,substr($getMonthYear,5,2)-1,1,substr($getMonthYear,0,4)));
  $getNextMonth = date('Y-m',mktime(0,0,0,substr($getMonthYear,5,2)+1,1,substr($getMonthYear,0,4)));
  $getDaysNextMonth = date('t',mktime(0,0,0,substr($getMonthYear,5,2)+1,1,substr($getMonthYear,0,4)));

  $filename = 'data-'.$getMonthYear;

  if (isset($_GET['diarySync'])) { $refreshTime = 0; } else { $refreshTime = 24; } // To allow a forced resync on the data

  // This is the Google Sheet for the sports calendar
  $sportsData = sheetToArray('1nDL3NXiwdO-wfbHcTLJmIvnZPL73BZeF7fYBj_heIyA','data/diary',$refreshTime);

  $caches = scandir('data/diary/', SCANDIR_SORT_DESCENDING);

    foreach ($caches as $file) {
      if (strpos($file,$filename) !== false) {
        $oldFile   = $file;
        $syncCheck = explode('[',$file);
        if (isset($syncCheck[1])) {
          $syncCheck = explode(']',$syncCheck[1]);
          $syncCheck = $syncCheck[0];
          break;
        } else { unset($syncCheck); }
      }
    }

  $refreshTime = $refreshTime*3600;
  if (!isset($syncCheck) || $syncCheck < (time()-$refreshTime)) {

    // This is the Google Calendar data
    $data = 'https://www.googleapis.com/calendar/v3/calendars/challoners.org_1e3c7g4qn1kic52usnlbrn63ts%40group.calendar.google.com/events?key=AIzaSyAHDKPyZwt9dn5ZiW0WAOCya7b2mgN0hrE&maxResults=2500&timeMin='.$getLastMonth.'-01T00:00:00-00:00&timeMax='.$getNextMonth.'-'.$getDaysNextMonth.'T23:59:59-00:00';
    $data = file_get_contents($data);
    $data = json_decode($data, true);
    $data = $data['items'];

    $generalData = array();

    // Take all the information that we're interested in, and put it into the final calendar array by date
    foreach ($data as $entry) {
      unset($description,$location);
      $bounds = array('start','end');
      foreach ($bounds as $bound) {
        if (isset($time{$bound})) { unset($time{$bound}); }
        if (isset($date{$bound})) { unset($date{$bound}); }
      }
      $eventDetails = array();

      // Get the title of the event
      $eventDetails['event'] = $entry['summary'];

      // Get the start and end times for the event, if any are given
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
        $eventDetails['other-details'] = $description;
      }

      // Now generate an ID for this event and work out which dates it occurs on
      // This first sequence means that the event both has a unique identifier AND can be sorted by time
      $eventID = makeID($eventDetails['event']);
      if (isset($time{'start'})) {
        $eventID = str_replace(':','',$time{'start'}).$eventID;
      } else {
        $eventID = '0000'.$eventID;
      }
      foreach ($bounds as $bound) {
        if (!isset($date{$bound})) { $date{$bound} = str_replace('-','',$entry[$bound]['date']); }
      }

      // Keep adding the event to the generalData array until it has been added for each relevant date
      $dateNow = $date{'start'};
      while ($dateNow <= $date{'end'}) {
        $generalData['events'][$dateNow][$eventID] = $eventDetails;
        $dateNow = date('Ymd',mktime(0,0,0,substr($dateNow,4,2),substr($dateNow,6,2)+1,substr($dateNow,0,4)));
      }
    }

    foreach ($sportsData['data']['data'] as $i => $entry) {
      if (isset($entry['event']) && !empty($entry['date'])) {
        // Take the date from a human-readable format to an orderable one
        $date = explode('/',$entry['date']);
        $d = str_pad($date[0],2,'0',STR_PAD_LEFT);
        $m = str_pad($date[1],2,'0',STR_PAD_LEFT);
        $y = str_pad($date[2],4,'20',STR_PAD_LEFT); // If someone has left the year in YY form, it assumes it's in this millenium
        
        $lastMonth = str_replace('-','',$getLastMonth);
        $nextMonth = str_replace('-','',$getNextMonth);
        $thisEventMonth = $y.$m;
        
        if ($thisEventMonth >= $lastMonth && $thisEventMonth <= $nextMonth) {
          
          $entry['sportCheck'] = 1; // So that the diary can easily recognise sporting events, to make specific responses
        
          // Get rid of unused elements so you only need to do an isset check, not also a !empty check
          foreach ($entry as $key => $item) {
            if ($item == '') { unset($entry[$key]); }
          }
          // Generate a unique, orderable ID for the event, as above
          $eventID = makeID($entry['event'],1).$i;
          if (isset($entry['meet-time'])) {
            $eventID = str_replace(':','',$entry['meet-time']).$eventID;
          } elseif (isset($entry['match-time'])) {
            $eventID = str_replace(':','',$entry['match-time']).$eventID;
          } else {
            $eventID = '0000'.$eventID;
          }
          unset($entry['date']); // As we won't need it in the final array
          $generalData['events'][$y.$m.$d][$eventID] = $entry;
        }
      }
    }

    if (!empty($generalData['events'])) {
      // Now just put all the data in chronological order
      ksort($generalData['events']);
      foreach ($generalData['events'] as $key => $day) {
        ksort($day);
        $generalData['events'][$key] = $day;
      }
    }
    
    $generalData['meta']['retrieved'] = time();

    // Cache this final array as JSON and record the time of syncing; remove the old cache
      $newFile = $filename.'.json';
      file_put_contents('data/diary/'.$newFile, json_encode($generalData));
    
  } else { $newFile = $oldFile; }

  // Now output the array for use
  $diaryArray = file_get_contents('data/diary/'.$newFile);
  $diaryArray = json_decode($diaryArray, true);

  // Now to make the actual display

  // The calendar widget
  echo '<!--googleoff: all-->';
  echo '<div class="col-sm-4 calendarSidebar';
    if (!isset($_GET['calendar'])) { echo ' hidden-xs'; } // We're on a mobile and viewing just the calendar right now
  echo '">';
    echo '<div id="diaryCalendar"></div>';
    echo '<div id="diaryPreview"></div>';
    echo '<div id="diaryLinks" class="hidden-xs">';
      echo '<h4 class="panel-title"><a href="/diary/year/">Year summary</a></h4>';
			echo '<h4 class="panel-title"><a href="'.$hardLink_termdates.'">Term dates</a></h4>';
      echo '<p class="panel-title"><a target="page'.mt_rand().'" href="https://www.google.com/calendar/embed?src=challoners.org_1e3c7g4qn1kic52usnlbrn63ts%40group.calendar.google.com&ctz=Europe/London">View in Google Calendar</a></p>';
      echo '<p class="panel-title"><a href="https://www.google.com/calendar/ical/challoners.org_1e3c7g4qn1kic52usnlbrn63ts%40group.calendar.google.com/public/basic.ics">Download iCal format</a></p>';
		echo "</div>";
  echo '</div><!--googleon: all-->';

  // Display the selected week's events
  echo '<div class="col-sm-8 diaryEvents';
    if (isset($_GET['calendar'])) { echo ' hidden-xs'; } // We're on a mobile and viewing just the calendar right now
  echo '">';
    $curWeek = $curTimestamp-(date('N',$curTimestamp)-1)*86400;
    $lastWeek = date('d/m/Y',mktime(0,0,0,date('m',$curWeek),date('d',$curWeek)-7,date('Y',$curWeek)));
    $nextWeek = date('d/m/Y',mktime(0,0,0,date('m',$curWeek),date('d',$curWeek)+7,date('Y',$curWeek)));
    echo '<div class="row weekNav visible-xs">';
      echo '<div class="col-xs-6">';
        echo '<p><a class="last" href="/diary/'.$lastWeek.'/"><i class="fas fa-caret-left"></i> Last week</a></p>';
      echo '</div>';
      echo '<div class="col-xs-6">';
        echo '<p><a class="next" href="/diary/'.$nextWeek.'/">Next week <i class="fas fa-caret-right"></i></a></p>';
      echo '</div>';
    echo '</div>';
    for ($d = 0; $d < 7; $d++) {
      echo '<div class="row eventDay">';
        $curDay = $curWeek + $d*86400;
        echo '<div class="col-xs-5"><h3 class="hidden-xs">'.date('l jS',$curDay).'</h3><h3 class="visible-xs">'.date('D jS',$curDay).'</h3></div>';
        echo '<div class="col-xs-7 text-right">';
        if (date("j",$curDay) == 1 || $d == 0) { // If it's the start of the month or the first entry displayed (ie, a Monday), then give the month
          echo '<h3>'.date('F Y',$curDay).'</h3>';
          }
        echo '</div>';
      echo '</div>';
      echo '<div class="row">';
      if (isset($diaryArray['events'][date('Ymd',$curDay)])) {
        foreach ($diaryArray['events'][date('Ymd',$curDay)] as $id => $event) { // ID not needed for much, except picking out teamsheets to print
          echo '<div class="col-xs-4 col-sm-3 eventTime"><p>';
            if (isset($event['timestart']))     { echo $event['timestart']; }
            elseif (isset($event['match-time'])) { echo $event['match-time']; }
            if ((isset($event['timestart']) || isset($event['match-time']))) {
              if (isset($event['timeend'])) { echo ' - '.$event['timeend']; }
              if (isset($event['pick-up-time'])) { echo ' - '.$event['pick-up-time']; }
            }
          echo '</p></div>';
          echo '<div class="col-xs-8 col-sm-9 eventDetails">';
            echo '<h4>';
              if (isset($event['sport'])) { echo $event['sport'].': '; }
              echo $event['event'];
            echo '</h4>';
            if (isset($event['venue']) || isset($event['teams']) || isset($event['results'])) {
              echo '<p>';
                if (isset($event['venue'])) {
                  echo $event['venue'];
                  if (isset($event['teams']) || isset($event['results'])) { echo ' - '; }
                }
                if (isset($event['teams'])) {
                  if (isset($event['results'])) {
                    $results = preg_split('/[;:]/',$event['results']);
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
                  $teams = preg_split('/[;:]/',$event['teams']);
                  foreach ($teams as $key => $team) {
                    $tCount = $key+2;
                    $team = trim($team);
                    $team = str_replace(' ','&nbsp;',$team); // This, and in the results processing above, keeps team names and scores neatly together
                    echo $team;
                    if (isset($results[$key])) { echo $results[$key]; }
                    if ($tCount == count($teams)) { echo ' and '; }
                    elseif ($tCount < count($teams)) { echo ', '; }
                  }
                  unset($results);
                } elseif (isset($event['results'])) {
                  echo '<span>'.$event['results'].'</span>';
                }
              echo '</p>';
              if (isset($event['teams']) && isset($event['players'])) {
                echo '<p class="details lrg"><a href="/teamsheet/'.date('Ymd',$curDay).'-'.$id.'-1">View printable team sheets</a>.</p>';
              }
            }
            if (isset($event['venue-name']) || isset($event['venue-postcode'])) {
              echo '<p class="details">';
              if (isset($event['venue-name'])) {
                echo $event['venue-name'].'. ';
              }
              if (isset($event['venue-postcode'])) {
                echo '<a href="https://www.google.co.uk/maps?q='.$event['venue-postcode'].'" target="'.mt_rand().'">See the location on Google Maps</a>.';
              }
              echo '</p>';
            }
            if (isset($event['other-details'])) {
              $remove = array('[display]','[display only]','[repeat]');
              if (isset($event['sportCheck'])) {
                $details = '';
                $event['other-details'] = preg_split('/[;:]/',$event['other-details']);
                foreach ($event['other-details'] as $line) {
                  if (stripos($line,'[display]') !== false || stripos($line,'[display only]') !== false) {
                    $line = str_ireplace($remove,'',$line);
                    $line = trim($line);
                    $details .= $line.'</p><p class="details">';
                  }
                }
                $details = str_replace('<p class="details"></p>','',$details);
              } else {
                $details = $event['other-details'];
              }
              $details = Parsedown::instance()->parse($details);
              $details = str_replace('<p>','<p class="details">',$details);
              echo $details;
            }
          echo '</div>';
          }
        }
      echo '</div>';
    }
  echo '</div>';

?>

<?php include('footer.php'); ?>

