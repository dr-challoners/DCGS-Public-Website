<div class="col-sm-4 col-md-3">
  <nav class="navbar navbar-learnMenu navbar-fixed-side">
    <div class="container">
      <div class="navbar-header">
        <button class="navbar-toggle" data-target=".navbar-collapse" data-toggle="collapse">
          Menu <i class="fa fa-chevron-down"></i>
        </button>    
        <a class="navbar-brand visible-xs-block" href="/maths">Learn Mathematics</a>
      </div>
      <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
          <li><a href="/maths"><i class="fa fa-home fa-fw"></i> Home</a></li>
          <li><a href="/"><i class="fa fa-shield fa-fw"></i> DCGS</a></li>
          <?php
          $dir = scandir($_SERVER['DOCUMENT_ROOT'].'/maths/pages');
          echo $_SERVER['DOCUMENT_ROOT'];
          view($dir);
          $dirData = array();
          foreach ($dir as $subdir) {
            if ($subdir != '.' && $subdir != '..') {
              $subdirData = scandir($_SERVER['DOCUMENT_ROOT'].'maths/pages/'.$subdir);
              $subdirData = array_reverse($subdirData);
              foreach ($subdirData as $row) {
                if (strpos($row,'navDir-') !== false && strpos($row,'.json') !== false) {
                  $row = file_get_contents($_SERVER['DOCUMENT_ROOT'].'maths/pages/'.$subdir.'/'.$row);
                  $row = json_decode($row, true);
                  $dirData[$subdir] = $row;
                  break;
                }
              }
            }
          }
          foreach ($dirData as $key1 => $data) {
            echo '<h3>'.revert($key1).'</h3>';
            echo '<div class="panel-group" id="learnMenu" role="tablist" aria-multiselectable="true">';
            foreach ($data as $key2 => $pages) {
              echo '<div class="panel">';
              echo '<div class="panel-heading" role="tab" id="heading-'.$key1.'-'.clean($key2).'">';
              echo '<h4 class="panel-title">';
              echo '<a role="button" data-toggle="collapse" data-parent="#learnMenu" href="#collapse-'.$key1.'-'.clean($key2).'" aria-expanded="true" aria-controls="collapse-'.$key1.'-'.clean($key2).'">';
              echo $key2;
              echo '</a>';
              echo '</h4>';
              echo '</div>';
              echo '<div id="collapse-'.$key1.'-'.clean($key2).'" class="panel-collapse collapse';
              if ($section == $key1 && $sheet == clean($key2)) {
                echo ' in';
              }
              echo '" role="tabpanel" aria-labelledby="heading-'.$key1.'-'.clean($key2).'">';
              echo '<div class="panel-body"><ul>';
              foreach ($pages as $title => $page) {
                echo '<li><a href="'.$page['link'].'">'.$title.'</a></li>';
              }
              //foreach ($sheet['pages'] as $page) {
                /*
                if (stripos($page,'[hidden]') === false) {
                  if (stripos($page,'[link:')) === false) {
                    echo '<li><a href="/learn/'.clean($_GET['subject']).'/'.clean($section).'/'.clean($sheet['sheetname']).'/'.clean($page).'">'.$page.'</a></li>';
                  } else {
                    $link = explode('[link:',$page);
                    $linkName = trim($link[0]);
                    $linkURL  = explode(']',$link[1])[0];
                    echo '<li><a href="'.$linkURL.'">'.formatText($linkName,0).'</a></li>';
                  }
                }
                */
              //}
              echo '</ul></div>';
              echo '</div>';
              echo '</div>';
            }
            echo '</div>';
          }
          /*
                    foreach($siteData['data']['sheets'] as $sheet) {
                      if (!isset($section) || $section != $sheet['section']) {
                        if (isset($section)) { echo '</div>'; } // Finishes up the previous panel group
                        $section = $sheet['section'];
                        echo '<h3>'.$section.'</h3>';
                        echo '<div class="panel-group" id="learnMenu" role="tablist" aria-multiselectable="true">';
                      }
                      if (isset($sheet['sheetname'])) {
                        echo '<div class="panel">';
                          echo '<div class="panel-heading" role="tab" id="heading-'.clean($section).'-'.clean($sheet['sheetname']).'">';
                            echo '<h4 class="panel-title">';
                              echo '<a role="button" data-toggle="collapse" data-parent="#learnMenu" href="#collapse-'.clean($section).'-'.clean($sheet['sheetname']).'" aria-expanded="true" aria-controls="collapse-'.clean($section).'-'.clean($sheet['sheetname']).'">';
                                echo $sheet['sheetname'];
                              echo '</a>';
                            echo '</h4>';
                          echo '</div>';
                          echo '<div id="collapse-'.clean($section).'-'.clean($sheet['sheetname']).'" class="panel-collapse collapse';
                            if (isset($_GET['sheet']) && clean($_GET['sheet']) == clean($sheet['sheetname']) && clean($_GET['section']) == clean($section)) {
                              echo ' in';
                            }
                          echo '" role="tabpanel" aria-labelledby="heading-'.clean($section).'-'.clean($sheet['sheetname']).'">';
                            echo '<div class="panel-body"><ul>';
                              foreach ($sheet['pages'] as $page) {
                                if (stripos($page,'[hidden]') === false) {
                                  if (stripos($page,'[link:') === false) {
                                    echo '<li><a href="/learn/'.clean($_GET['subject']).'/'.clean($section).'/'.clean($sheet['sheetname']).'/'.clean($page).'">'.$page.'</a></li>';
                                  } else {
                                    $link = explode('[link:',$page);
                                    $linkName = trim($link[0]);
                                    $linkURL  = explode(']',$link[1])[0];
                                    echo '<li><a href="'.$linkURL.'">'.formatText($linkName,0).'</a></li>';
                                  }
                                }
                              }
                            echo '</ul></div>';
                          echo '</div>';
                        echo '</div>';
                      }
                    }
                  */ ?>
        </ul>
      </div>
    </div>
  </nav>
</div>