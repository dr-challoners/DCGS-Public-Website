<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Work Tracker</title>
  <link rel="stylesheet" href="/css/bootstrap.css" />
  <link rel="stylesheet" href="/css/tracker.css" />
  <?php
    date_default_timezone_set("Europe/London");
		include('modules/functions/miscTools.php');
		include('modules/functions/fetchData.php');
  ?>
</head>
<body>
  <?php
    if (isset($_GET['student'])) {
      $studentIDs = file_get_contents('data/tracker/studentIDs.json');
      $studentIDs = json_decode($studentIDs, true);
      if (isset($studentIDs[$_GET['student']])) {
        $adno = $studentIDs[$_GET['student']]['adno'];
        $name = $studentIDs[$_GET['student']]['name'];
        echo '<h2>'.$name.'</h2>';
        $assignmentData = file_get_contents('data/tracker/assignmentData.json');
        $assignmentData = json_decode($assignmentData, true);
        $assignmentData = $assignmentData[$adno];
        foreach ($assignmentData as $row) {
          $assignment = '<tr>';
          $assignment .= '<td>'.$row['subject'].'</td>';
          $assignment .= '<td class="assignment">'.$row['assignment'].'</td>';
          if (strtotime($row['datedue']) < mktime(0,0,0,date('m'),date('d'),date('Y')) && $row['status'] != 'Complete' && $row['status'] != 'Abandoned') {
            $assignment .= '<td>Due: '.$row['datedue'].'</td>';
            $assignment .= '<td>'.$row['status'].'</td>';
            $assignment .= '</tr>';
            $overdue[] = $assignment;
          }
          if (strtotime($row['datedue']) >= mktime(0,0,0,date('m'),date('d'),date('Y')) && $row['status'] != 'Complete' && $row['status'] != 'Abandoned') {
            $assignment .= '<td>Due: '.$row['datedue'].'</td>';
            $assignment .= '<td></td>';
            $assignment .= '</tr>';
            $upcoming[] = $assignment;
          }
          if (strtotime($row['datedue']) + 604800 > mktime() && $row['status'] == 'Complete') {
            $assignment .= '<td></td>';
            $assignment .= '<td></td>';
            $assignment .= '</tr>';
            $complete[] = $assignment;
          }
        }
        if (isset($overdue)) {
          echo '<div class="panel panel-danger">';
          echo '<div class="panel-heading"><h3 class="panel-title">Overdue</h3></div>';
          echo '<table class="table">';
          foreach ($overdue as $row) {
            echo $row;
          }
          echo '</table>';
          echo '</div>';
        }
        if (isset($upcoming)) {
          echo '<div class="panel panel-info">';
          echo '<div class="panel-heading"><h3 class="panel-title">Upcoming</h3></div>';
          echo '<table class="table">';
          foreach ($upcoming as $row) {
            echo $row;
          }
          echo '</table>';
          echo '</div>';
        }
        if (isset($complete)) {
          echo '<div class="panel panel-success">';
          echo '<div class="panel-heading"><h3 class="panel-title">Completed recently</h3></div>';
          echo '<table class="table">';
          foreach ($complete as $row) {
            echo $row;
          }
          echo '</table>';
          echo '</div>';
        }
      } else {
        echo 'Student not found.';
      }
    } elseif (isset($_GET['form'])) {
      $formIDs = file_get_contents('data/tracker/formIDs.json');
      $formIDs = json_decode($formIDs, true);
      $studentIDs = file_get_contents('data/tracker/studentIDs.json');
      $studentIDs = json_decode($studentIDs, true);
      if (isset($formIDs[$_GET['form']])) {
        $formData = $formIDs[$_GET['form']];
        echo '<h2>'.$formData['form'].' - Overdue Work</h2>';
        foreach ($formData['students'] as $student) {
          unset($assignments);
          if (isset($studentIDs[$student])) {
            $adno = $studentIDs[$student]['adno'];
            $name = $studentIDs[$student]['name'];
            echo '<div class="panel panel-default">';
            $assignmentData = file_get_contents('data/tracker/assignmentData.json');
            $assignmentData = json_decode($assignmentData, true);
            $assignmentData = $assignmentData[$adno];
            foreach ($assignmentData as $row) {
              if (strtotime($row['datedue']) < mktime() && $row['status'] != 'Complete') {
                switch ($row['status']) {
                  case 'Incomplete':
                    $assignment = '<tr class="danger">';
                    break;
                  case 'Partially complete':
                    $assignment = '<tr class="warning">';
                    break;
                  default:
                    $assignment = '<tr>';
                }
                $assignment .= '<td>'.$row['subject'].'</td>';
                $assignment .= '<td class="assignment">'.$row['assignment'].'</td>';
                $assignment .= '<td>Due: '.$row['datedue'].'</td>';
                $assignment .= '<td>'.$row['status'].'</td>';
                $assignment .= '</tr>';
                $assignments[] = $assignment;
              }
            }
            echo '<div class="panel-heading"><h3 class="panel-title">';
              echo '<a href="/tracker.php?student='.$student.'">'.$name.'</a>';
              if (isset($assignments)) {
                echo '<span class="overdueCount red">'.count($assignments).'</span>';
              } else {
                echo '<span class="overdueCount">0</span>';
              }
            echo '</h3></div>';
            if (isset($assignments)) {
              echo '<table class="table">';
              foreach ($assignments as $row) {
                echo $row;
              }
              echo '</table>';
            }
            echo '</div>';
          }
        }
      } else {
        echo 'Form not found.';
      }
    } elseif ($_GET['update'] == 'studentIDs') {
      $studentIDs = sheetToArray('1RSGTRb8nt_Ia3UzoeIesdvbt2piQeBIBtX5VQ6kiIE4','data/tracker',0);
      foreach ($studentIDs['data']['Students'] as $row) {
        $studentData[$row['studentid']]['adno'] = $row['adno'];
        $studentData[$row['studentid']]['name'] = $row['forename']." ".$row['surname'];
      }
      foreach ($studentIDs['data']['Forms'] as $row) {
        $formData[$row['formid']]['form'] = $row['form'];
        foreach ($studentIDs['data']['Students'] as $student) {
          if ($student['form'] == $row['form']) {
            $formData[$row['formid']]['students'][] = $student['studentid'];
          }
        }
      }
      file_put_contents('data/tracker/studentIDs.json', json_encode($studentData));
      file_put_contents('data/tracker/formIDs.json', json_encode($formData));
      echo '<p>Student and form IDs have been updated.</p>';
    } elseif ($_GET['update'] == 'data' && !isset($_GET['page'])) {
      echo '<p>Fetching data...</p>';
      $trackerSheets = sheetToArray('15bQXb7txLThATNt5YOjNaZQLS_Q5ZRsxOiCaM9Yvkf4','data/tracker',0);
      echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=/tracker.php?update=data&page=0&session='.mktime().'">';
    } elseif ($_GET['update'] == 'data') {
      $nextPage = $_GET['page'] + 1;
      $trackerSheets = sheetToArray('15bQXb7txLThATNt5YOjNaZQLS_Q5ZRsxOiCaM9Yvkf4','data/tracker','');
      foreach ($trackerSheets['data']['sheetIDs'] as $row) {
        $trackerSheetIDs[] = $row['spreadsheetid'];
      }
      $trackerData = sheetToArray($trackerSheetIDs[$_GET['page']],'data/tracker',0);
      if (file_exists('data/tracker/assignmentData_'.$_GET['session'].'.json')) {
        $assignmentData = file_get_contents('data/tracker/assignmentData_'.$_GET['session'].'.json');
        $assignmentData = json_decode($assignmentData, true);
      }
      foreach ($trackerData['data'] as $class) {
        array_shift($class);
        array_shift($class);
        foreach ($class as $assignment) {
          foreach ($assignment as $key => $row) {
            if ($key[0] == 'a' && $key != 'assignment') {
              $adno = substr($key,1);
              $assignmentDetails['dateset'] = $assignment['dateset'];
              $assignmentDetails['subject'] = $assignment['subject'];
              $assignmentDetails['assignment'] = $assignment['assignment'];
              $assignmentDetails['datedue'] = $assignment['datedue'];
              switch ($row) {
                case 'y';
                  $assignmentDetails['status'] = 'Complete';
                  break;
                case 'n';
                  $assignmentDetails['status'] = 'Incomplete';
                  break;
                case 'm';
                  $assignmentDetails['status'] = 'Partially complete';
                  break;
                case 'x';
                  $assignmentDetails['status'] = 'Abandoned';
                  break;
                default:
                  $assignmentDetails['status'] = 'Not yet reviewed';
              }
              $assignmentData[$adno][] = $assignmentDetails;
            }
          }
        }
      }
      if ($nextPage == count($trackerSheetIDs)) {
        file_put_contents('data/tracker/assignmentData.json', json_encode($assignmentData));
        unlink('data/tracker/assignmentData_'.$_GET['session'].'.json');
        echo '<p>All data updated.</p>';
        echo '<p>The following entries have been recorded:</p>';
        echo '<ol>';
        foreach ($assignmentData as $key => $row) {
          foreach ($row as $item) {
            echo '<li>(Student ID '.$key.') '.$item['assignment'].'</li>';
          }
        }
        echo '</ol>';
      } else {
        $percent = round(($nextPage / count($trackerSheets['data']['sheetIDs'])) * 100);
        echo '<p>'.$percent.'% complete</p>';
        file_put_contents('data/tracker/assignmentData_'.$_GET['session'].'.json', json_encode($assignmentData));
        echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=/tracker.php?update=data&page='.$nextPage.'&session='.$_GET['session'].'">';
      }
    }
  ?>
</body>
</html>