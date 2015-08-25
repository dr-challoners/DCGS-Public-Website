<?php // IN DEVELOPMENT

  include('header_declarations.php');

  // Meta tags for Facebook sharing, provided there's a story to display
  if ($_GET['section'] == 'News' && isset($_GET['page'])) {
    echo '<meta property="og:type" content ="article" />';
    echo '<meta property="og:title" content ="'.str_replace('-',' ',$_GET['page']).' - News from Challoner\'s" />';
    echo '<meta property="og:site_name" content ="Dr Challoner\'s Grammar School" />';
    echo '<meta property="og:image" content ="http://'.$_SERVER['SERVER_NAME'].'/styles/imgs/fb-shared-post.png" />';
  }

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
      
      if ($_GET['section'] == 'News') {
        echo '<div class="share lrg">';  

          // Share on Facebook
          echo '<iframe src="//www.facebook.com/plugins/share_button.php?href=';
          $shareurl = 'http://'.$_SERVER['SERVER_NAME'].'/c/News/'.$_GET['sheet'].'/'.$_GET['page'];
          echo urlencode($shareurl);
          echo '&amp;layout=button" scrolling="no" frameborder="0" style="border:none; overflow:hidden;" allowTransparency="true"></iframe>';

          // Share on Twitter
          echo '<a href="https://twitter.com/share" class="twitter-share-button" data-text="';
          echo 'News from DCGS: '.str_replace('-',' ',$_GET['page']);
          echo '" data-via="ChallonersNews" data-count="none">Tweet</a>';
          echo "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>";

        echo '</div>';
      }
      
      echo '</div>'."\n\n";
    }
    
  } else {
    echo 'ERROR';
  }

  include('footer.php');

?>