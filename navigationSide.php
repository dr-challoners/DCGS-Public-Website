<?php

function makeNav ($sheetName, $pages, $sheet) {
  // This creates the navigation both for the side bar and for archive pages
  if (isset($sheet)) {
    echo '<div class="panel panel-default">';
    echo '<div class="panel-heading" role="tab" id="'.clean($sheetName).'">';
    echo '<h4 class="panel-title">';
    echo '<a ';
    if (clean($sheetName) != $sheet) {
      echo 'class="collapsed" ';
    }
    echo 'role="button" data-toggle="collapse" data-parent="#'.$section.'Nav" href="#collapse-'.clean($sheetName).'" aria-expanded="';
    if (clean($sheetName) != $sheet) {
      echo 'false';
    } else {
      echo 'true';
    }
    echo '" aria-controls="collapse-'.clean($sheetName).'">'.$sheetName.'</a>';
    echo '</h4>';
    echo '</div>';
    echo '<div id="collapse-'.clean($sheetName).'" class="panel-collapse collapse';
    if (clean($sheetName) == $sheet) {
      echo ' in';
    }
    echo '" role="tabpanel" aria-labelledby="'.clean($sheetName).'">';
    echo '<ul class="list-group">';
    foreach ($pages as $pageName => $data) {
      if (!isset($data['show']) || $data['show'] < mktime()) {
        echo '<li class="list-group-item">';
        echo '<a href="'.$data['link'].'">'.formatText($pageName,0).'</a>';
        echo '</li>';
      }
    }
    echo '</ul>';
    echo '</div>';
    echo '</div>';
  } else {
    echo '<div class="row">';
    echo '<div class="col-xs-12">';
    echo '<h2>'.$sheetName.'</h2>';
    echo '</div>';
    foreach ($pages as $title => $page) {
      if (!isset($page['show']) || $page['show'] < mktime()) {
        echo '<div class="col-xs-6">';
        echo '<a href="'.$page['link'].'">';
        echo '<p>'.formatText($title,0).'</p>';
        echo '</a>';
        echo '</div>';
      }
    }
    echo '</div>';
  }
}

if (isset($sheet)) {
  echo '<div class="hidden-xs col-sm-4 hidden-print">';
  include('highlights.php');
  echo '<div class="panel-group sideNav" id="'.$section.'Nav" role="tablist" aria-multiselectable="true">';
}
$sec = array();
$nav = array();
$dir = scandir($_SERVER['DOCUMENT_ROOT'].'/pages/'.$section);
foreach ($dir as $row) {
  // Finds the latest navigation file, and pulls all the folder names into another array - this second array is purely a backup for the News section
  if (strpos($row,'navDir-') !== false && strpos($row,'.json') !== false) {
    $nav = $row;
  } elseif ($row !== '.' && $row !== '..') {
    $sec[] = $row;
  }
}
$nav = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/pages/'.$section.'/'.$nav);
$nav = json_decode($nav, true);
if ($section == 'news') {
  // Occasionally, the navigation files can drop out, requiring the content in that section to be re-synced
  // This isn't a huge problem for most of the sections, but is an utter pain for the massive News section
  // The following failsafe allows the News section to display regardless of that
  // The failsafe can't be applied to every section, as it would revert the ordering of all the subsections to alphabetical order
  foreach ($sec as $row) {
    if (preg_match("/\A([a-zA-Z]+)-(\d{4})\z/", $row)) { // Ignores anything not in the form month-yyyy
      // News does need reordering, but it follows a predictable pattern
      $dateParts = explode('-',$row);
      switch ($dateParts[0]) {
        case 'january':
          $month = 1;
          break;
        case 'february':
          $month = 2;
          break;
        case 'march':
          $month = 3;
          break;
        case 'april':
          $month = 4;
          break;
        case 'may':
          $month = 5;
          break;
        case 'june':
          $month = 6;
          break;
        case 'july':
          $month = 7;
          break;
        case 'august':
          $month = 8;
          break;
        case 'september':
          $month = 9;
          break;
        case 'october':
          $month = 10;
          break;
        case 'november':
          $month = 11;
          break;
        case 'december':
          $month = 12;
          break;
      }
      if (!($ordering[$dateParts[1]])) {
        $ordering[$dateParts[1]] = array(12 => '', 11 => '', 10 => '', 9 => '', 8 => '', 7 => '', 6 => '', 5 => '', 4 => '', 3 => '', 2 => '', 1 => '');
      }
      $ordering[$dateParts[1]][$month] = $row;
    }
  }
  krsort($ordering);
  $sec = array();
  foreach($ordering as $y) {
    foreach ($y as $m) {
      if (!empty($m)) {
        $sec[] = $m;
      }
    }
  }
  foreach ($sec as $row) {
    if ((!isset($newYear) || explode('-',$row)[1] !== $newYear) && !isset($sheet)) {
      // Creates a nice display of photos at the beginning of each new year in the archives section
      $newYear = explode('-',$row)[1];
      $displayPhotos = array();
      foreach ($sec as $pRow) {
        if (explode('-',$pRow)[1] === $newYear) {
          $allPhotos = scandir($_SERVER['DOCUMENT_ROOT'].'/pages/news/'.$pRow);
          foreach($allPhotos as $photo) {
            if (substr($photo,-3) == 'jpg') {
              $displayPhotos[] = $pRow.'/'.$photo;
            }
          }
        }
      }
      shuffle($displayPhotos);
      $displayPhotos = array_slice($displayPhotos,0,6);
      //view($displayPhotos);
      echo '<div class="archiveH1Banner">';
        echo '<h1>';
        if (!isset($firstYear)) {
          echo 'News: ';
        }
        echo $newYear.'</h1>';
        for ($p = 0; $p <= 5; $p++) {
          echo '<div class="bannerPhoto" style="background-image:url(';
          if (isset($displayPhotos[$p])) {
            echo '/pages/news/'.$displayPhotos[$p];
          } else {
            echo '/img/navigation/'.$p.'.jpg';
          }
          echo ')"></div>';
        }
      echo '</div>';
      $firstYear = 1;
    }
    $sheetName = revert($row);
    if (isset($nav[$sheetName])) {
      $pages = $nav[$sheetName];
    } else {
      $pageDir = scandir($_SERVER['DOCUMENT_ROOT'].'/pages/news/'.clean($sheetName));
      foreach ($pageDir as $page) {
        $page = explode('.',$page);
        if ($page[1] == 'php') {
          $pageName = revert($page[0]);
          $pages[$pageName] = array('link' => '/c/news/'.clean($sheetName).'/'.$page[0]);
        }
      }
    }
    makeNav ($sheetName, $pages, $sheet);
    unset($pages);
    if (isset($sheet)) {
      // On News pages, only display the most recent 12 months in the sidebar
      if (isset($c)) {
        $c++;
      } else {
        $c = 1;
      }
      if ($c == 12) {
        echo '<p class="newsArchiveLink"><a href="/c/news/"><i class="fas fa-history"></i> News archives</a></p>';
        break;
      }
    }
  }
} else {
  // If it isn't the News section, just work on whatever navigation information is available.
  foreach ($nav as $sheetName => $pages) {
    makeNav ($sheetName, $pages, $sheet);
  }
}
if (isset($sheet)) {
  echo '</div>';
  echo '</div>';
}
?>