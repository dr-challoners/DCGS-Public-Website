<?php

  include('header_declarations.php');

  // Meta tags for Facebook sharing, provided there's a story to display
  if ($_GET['section'] == 'News' && isset($_GET['page'])) {
    echo '<meta property="og:type" content ="article" />';
    echo '<meta property="og:title" content ="'.str_replace('-',' ',$_GET['page']).' - News from Challoner\'s" />';
    echo '<meta property="og:site_name" content ="Dr Challoner\'s Grammar School" />';
    echo '<meta property="og:image" content ="http://'.$_SERVER['SERVER_NAME'].'/styles/imgs/fb-shared-post.png" />';
  }

  include('header_navigation.php');

  if (isset($_GET['section'])) {
    // This is necessary - otherwise there's an error
    $sheets = array();
    foreach ($mainData['data']['sheets'] as $id => $sheet) {
      if ($sheet['section'] == $_GET['section']) {
        $sheets[$id] = $sheet;
        if (isset($_GET['sheet']) && clean($sheet['sheetname']) == clean($_GET['sheet'])) {
          $sheetID = $id;
          if (!isset($_GET['page'])) {
            $page = $sheet['pages'][0];
          } else {
            $page = $_GET['page'];
          }
        }    
      }
    }
    if (empty($sheets)) {
      $error = 1;
    }
    if (isset($_GET['sheet'])) {
      // Display the sidebar navigation at the very least
      echo '<!--googleoff: all-->'."\n\n";
      echo '<div class="pageNav lrg">'."\n\n";
        navigatePagesSheet($sheets,$contentURL,'nav');
      echo '</div>'."\n\n";
      echo '<!--googleon: all-->'."\n\n";
      if (isset($sheetID)) {
      echo '<div class="sheetCMS">'."\n\n";
        if ($_GET['section'] == 'News') {
          $error = parsePagesSheet($sheetID,$page,1,1,'newsArticle.php');

          if (!isset($error)) {
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
        } else {
          $error = parsePagesSheet($sheetID,$page,1);
        }
      
      echo '</div>'."\n\n";
      } else {
        $error = 1;
      }
    } else {
      // With no specified subsection, display the big version of the navigation menu
      echo '<div class="ncol frontmenuImgs lrg">';
        $photos = scandir("content_system/sidebarImgs/", 1);
        array_pop($photos);
        array_pop($photos); // Removes . and .. from the array in order to get a proper count
        shuffle($photos);
        $r = rand(1,2);
        if ($r == 1) { echo '<div class="photostub med" style="background-image:url(\'/content_system/sidebarImgs/'.array_pop($photos).'\');"></div>'; } 
          echo '<div class="tny-box">';
            echo '<div class="photostub tny" style="background-image:url(\'/content_system/sidebarImgs/'.array_pop($photos).'\');"></div>';
            echo '<div class="photostub tny" style="background-image:url(\'/content_system/sidebarImgs/'.array_pop($photos).'\');"></div>';
            echo '<div class="photostub tny" style="background-image:url(\'/content_system/sidebarImgs/'.array_pop($photos).'\');"></div>';
            echo '<div class="photostub tny" style="background-image:url(\'/content_system/sidebarImgs/'.array_pop($photos).'\');"></div>';
          echo '</div>';
        if ($r == 2) { echo '<div class="photostub med" style="background-image:url(\'/content_system/sidebarImgs/'.array_pop($photos).'\');"></div>'; } 
      echo '</div>';
      echo '<div class="mcol frontmenu lrg">';
        echo '<h1>'.$_GET['section'].'</h1>';
        navigatePagesSheet($sheets,$contentURL);
      echo '</div>';
    }
  }

  if (isset($error)) {
    echo "<style> body { background-image: url('/styles/imgs/error.png'); background-position: right bottom; background-repeat: no-repeat; background-attachment: fixed; background-size: 980px auto; } </style>"."\n\n";;
    echo '<div class="sheetCMS">'."\n\n";;
      echo '<h1>Oh dear!</h1>'."\n";;
      echo '<p>This page seems to be lost. You could go back to the home page and try again, or check down the back of sofa. If you think there\'s an error, you could <a href="/pages/Information/General information/Contact us">contact us</a> to report the problem.</p>'."\n\n";;
    echo '</div>'."\n\n";;
  }

  include('footer.php');

?>