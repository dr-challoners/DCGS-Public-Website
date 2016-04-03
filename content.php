<?php include('header.php');

  // Check the URL is valid and pull out necessary data from it
  if (isset($_GET['section'])) {
    foreach ($mainData['data']['sheets'] as $id => $sheet) {
      if (clean($sheet['section']) == clean($_GET['section'])) {
        if (isset($sheet['sheetname'])) {
          $section = $sheet['section'];
          $sheets[$id] = $sheet;
          if (isset($_GET['sheet']) && !isset($sheetID)) {
            if (clean($sheet['sheetname']) == clean($_GET['sheet'])) {
              $currentSheet = $sheet['sheetname'];
              $sheetID = $id;
              if (isset($_GET['page'])) {
                foreach ($sheets[$id]['pages'] as $title) {
                  if (clean(str_ireplace('[hidden]','',$title)) == clean(str_ireplace('[hidden]','',$_GET['page']))) {
                    $page = $title;
                    break;
                  }
                }
                if (!isset($page)) {
                  $error = 1;
                }
              } else {
                $page = $sheet['pages'][0];
              }
            }
          }
        }
      }
    }
    if (!isset($section) || (isset($_GET['sheet']) && !isset($sheetID))) {
      $error = 1;
    }
  } else {
    $error = 1;
  }

  // If there isn't an error, then we'll either get just $section (and the $sheets) array, or $section, $sheets array, $currentSheet, $sheetID and $page
  if (!isset($error)) {
    echo '<div class="row">';
    if (isset($page)) {
      $pageURL = 'http://www.challoners.com/c/'.$_GET['section'].'/'.$_GET['sheet'].'/'.clean(str_ireplace('[hidden]','',$page));
      echo '<div class="hidden-xs col-sm-4 hidden-print">';
        echo '<div class="panel-group sideNav" id="'.clean($section).'Nav" role="tablist" aria-multiselectable="true">';
        foreach ($sheets as $pagesList) {
          if (clean($section) == 'news') {
            if (!isset($c)) {
              $c = 1;
            } else {
              $c++;
            }
          }
          $headingID  = clean($pagesList['sheetname']);
          $collapseID = 'collapse-'.clean($pagesList['sheetname']);
          echo '<div class="panel panel-default">';
            echo '<div class="panel-heading" role="tab" id="'.$headingID.'">';
              echo '<h4 class="panel-title">';
                echo '<a ';
                  if ($pagesList['sheetname'] != $currentSheet) {
                    echo 'class="collapsed" ';
                  }
                echo 'role="button" data-toggle="collapse" data-parent="#'.clean($section).'Nav" href="#'.$collapseID.'" aria-expanded="';
                  if ($pagesList['sheetname'] == $currentSheet) {
                    echo 'true';
                  } else {
                    echo 'false';
                  }
                echo '" aria-controls="'.$collapseID.'">';
                  echo $pagesList['sheetname'];
                echo '</a>';
              echo '</h4>';
            echo '</div>';
            echo '<div id="'.$collapseID.'" class="panel-collapse collapse';
              if ($pagesList['sheetname'] == $currentSheet) {
                echo ' in';
              }
            echo '" role="tabpanel" aria-labelledby="'.$headingID.'">';
              echo '<ul class="list-group">';
                foreach ($pagesList['pages'] as $row) {
                  if (stripos($row,'[hidden]') === false) {
                    echo '<li class="list-group-item">';
                      if (stripos($row,'[link:') === false) {
                        echo '<a href="/c/'.clean($section).'/'.clean($pagesList['sheetname']).'/'.clean($row).'/">';
                          echo formatText($row,0);
                        echo '</a>';
                      } else {
                        $link = explode('[link:',$row);
                        $linkName = trim($link[0]);
                        $linkURL  = explode(']',$link[1])[0];
                        echo '<a href="'.$linkURL.'">'.formatText($linkName,0).'</a>';
                      }
                    echo '</li>';
                  }
                }
              echo '</ul>';
            echo '</div>';
          echo '</div>';
          if (isset($c) && $c >= 12) {
            echo '<p class="newsArchiveLink"><a href="/c/news/"><i class="fa fa-clock-o"></i> News archives</a></p>';
            break;
          }
        }
        echo '</div>';
      echo '</div>';
      echo '<div class="col-sm-8">';
        if (clean($section) == 'news' || clean($currentSheet) == 'showcase') {
          parsePagesSheet($sheetID,$page,1);
        } else {
          parsePagesSheet($sheetID,$page);
        }
      echo '</div>';
    } else {
      echo '<div class="sectionNavigation">';
        echo '<h1>'.$section.'</h1>';
        $l = 1; $t = mt_rand(2,5);
        foreach ($sheets as $pagesList) {
          echo '<div class="row">';
            echo '<div class="col-xs-12">';
              echo '<h2>'.$pagesList['sheetname'].'</h2>';
            echo '</div>';
            foreach ($pagesList['pages'] as $row) {
              if (stripos($row,'[hidden]') === false) {
                echo '<div class="col-xs-6 col-md-4">';
                if (stripos($row,'[link:') === false) {
                  echo '<a href="/c/'.clean($section).'/'.clean($pagesList['sheetname']).'/'.clean($row).'/">';
                    echo '<p>'.formatText($row,0).'</p>';
                  echo '</a>';
                } else {
                  $link = explode('[link:',$row);
                  $linkName = trim($link[0]);
                  $linkURL  = explode(']',$link[1])[0];
                  echo '<a href="'.$linkURL.'"><p>'.formatText($linkName,0).'</p></a>';
                }
                echo '</div>';
              }
            }
          echo '</div>';
          if ($l == $t) {
            echo '<div class="row navDecor">';
              for ($p = 1; $p <= 6; $p++) {
                if (!isset($navDecor) || count($navDecor) == 0) {
                  $navDecor = scandir('img/navDecor');
                  array_shift($navDecor);
                  array_shift($navDecor);
                  shuffle($navDecor);
                }
                if ($p < 5) {
                  echo '<div class="col-xs-3 col-md-2">';
                } else {
                  echo '<div class="col-md-2 hidden-sm hidden-xs">';
                }
                echo '<img class="img-responsive" src="/img/navDecor/'.array_shift($navDecor).'" /></div>';
              }
            echo '</div>';
            $t = $t+mt_rand(3,5);
          }
          $l++;
        }
      echo '</div>';
    }
    echo '</div>';
  }
?>

<?php include('footer.php'); ?>