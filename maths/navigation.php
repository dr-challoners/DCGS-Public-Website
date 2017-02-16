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
          if (scandir($_SERVER['DOCUMENT_ROOT'].'/maths/pages/') === false) {
            echo 'not working';
          } else {
            view(scandir($_SERVER['DOCUMENT_ROOT'].'/maths/pages/'));
          }
          $dir = scandir($_SERVER['DOCUMENT_ROOT'].'/maths/pages/');
          $dirData = array();
          foreach ($dir as $subdir) {
            if ($subdir != '.' && $subdir != '..') {
              $subdirData = scandir($_SERVER['DOCUMENT_ROOT'].'/maths/pages/'.$subdir);
              $subdirData = array_reverse($subdirData);
              foreach ($subdirData as $row) {
                if (strpos($row,'navDir-') !== false && strpos($row,'.json') !== false) {
                  $row = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/maths/pages/'.$subdir.'/'.$row);
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
              echo '</ul></div>';
              echo '</div>';
              echo '</div>';
            }
            echo '</div>';
          }
          ?>
        </ul>
      </div>
    </div>
  </nav>
</div>