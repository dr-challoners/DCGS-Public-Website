<?php // IN DEVELOPMENT

  include('header_declarations.php');
  include('header_navigation.php');

  // Find a cleaner way to do all this, or to express it in a CMS function
  if (isset($_GET['section'])) {
    if (isset($_GET['sheet']) && isset($_GET['page'])) {
      if (!isset($mainSheetArray)) {
        $mainSheetArray = file_get_contents($dataSrc.'/'.$mainSheet.'.json');
        $mainSheetArray = json_decode($mainSheetArray, true);
      }
      foreach ($mainSheetArray['data'] as $key => $section) {
        if (clean($key) == clean($_GET['section'])) {
          foreach ($mainSheetArray['data'][$key] as $row) {
            if (clean($row['sheetname']) == clean($_GET['sheet'])) {
              $sheetKey = $row['sheetid'];
              break;
            }
          }
          break;
        }
      }
      if (isset($sheetKey)) {
        if (!isset($sheetArray)) {
          $sheetArray = file_get_contents($dataSrc.'/'.$sheetKey.'.json');
          $sheetArray = json_decode($sheetArray, true);
        }
        foreach ($sheetArray['data'] as $key => $row) {
          if (clean($key) == clean($_GET['page'])) {
            $pageName = $key;
            break;
          }
        }
        if (!isset($pageName)) {
          // Sort out these error functions. This one should call up the section-only navigation with the nice pictures.
          echo 'ERROR';
        }
      } else {
        echo 'ERROR';
      }
    } else {
      echo 'ERROR';
    }
    
    echo '<!--googleoff: all-->'."\n\n";
    echo '<div class="pageNav lrg">'."\n\n";
      navigatePagesSheet($_GET['section'],'/c/[SECTION]/[SHEET]/[PAGE]','nav');
    echo '</div>'."\n\n";
    echo '<!--googleon: all-->'."\n\n";

    if (isset($pageName)) {
      echo '<div class="sheetCMS">'."\n\n";
        parsePagesSheet($sheetKey,$pageName,1,1,'newsArticle.php');
      // Need to add back in Facebook and Twitter for news stories
      echo '</div>'."\n\n";
    }
    
  } else {
    echo 'ERROR';
  }

  include('footer.php');

?>