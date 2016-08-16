    <div class="row">
      <div class="col-xs-12">
        <h1><a href="./sync" style="color:black";><i class="fa fa-cog"></i></a> DCGS content management system</h1>
      </div>
    </div>
    <div class="row">
    <ul class="nav nav-tabs" role="tablist">
      <?php
        if (isset($_GET['tab'])) {
          $tab = $_GET['tab'];
        } else {
          $tab = 'main';
        }
        $tabs =  '<li role="presentation"><a href="#main" aria-controls="main" role="tab" data-toggle="tab">Main options</a></li>';
        $tabs .= '<li role="presentation"><a href="#sjt" aria-controls="sjt" role="tab" data-toggle="tab">SJT</a></li>';
        $tabs .= '<li role="presentation"><a href="#content_dcgs" aria-controls="content_dcgs" role="tab" data-toggle="tab">Update DCGS</a></li>';
        foreach ($mainSheets as $site => $sheetID) {
          if ($site != 'dcgs') {
            $tabs .= '<li role="presentation"><a href="#content_'.$site.'" aria-controls="content_'.$site.'" role="tab" data-toggle="tab">Learn '.ucwords($site).'</a></li>';
          }
        }
        $tabs .= '<li role="presentation"><a href="#viewdata" aria-controls="viewdata" role="tab" data-toggle="tab">View data</a></li>';
        $tabs = str_replace('><a href="#'.$tab,' class="active"><a href="#'.$tab,$tabs);
        echo $tabs;
      ?>
    </ul>
    <div class="tab-content">
      <?php
        if ($tab == 'main') {
          echo '<div role="tabpanel" class="tab-pane fade in active" id="main">';
        } else {
          echo '<div role="tabpanel" class="tab-pane fade" id="main">';
        }
      ?>
      <div class="row buttonLine">
        <div class="col-xs-12 col-sm-5 col-md-4"><a class="btn btn-default btn-block" href="https://drive.google.com/drive/folders/0ByH41whuUvC_fnpLUVhXTGl6dUV4VWZyWWJCNlRQaGp5d0pDbE90QWlCSVJlVEg2ZURSZ0E" target="<?php echo mt_rand(); ?>"><i class="fa fa-folder-open fa-fw"></i> Main content folder on Drive</a></div>
        <div class="col-xs-12 col-sm-7 col-md-8"><p>Contact SBU if you need access.</p></div>
      </div>
      <div class="row buttonLine">
          <div class="col-xs-12 col-sm-5 col-md-4"><a class="btn btn-default btn-block" href="https://drive.google.com/open?id=1n-oqN8rF98ZXqlH7A_eUx6K_5FgK2RUpiCx3aUMg3kM" target="<?php echo mt_rand(); ?>"><i class="fa fa-table fa-fw"></i> Modify the master spreadsheet</a></div>
      </div>
      <div class="row buttonLine">
          <div class="col-xs-12 col-sm-5 col-md-4"><a class="btn btn-default btn-block" href="https://sites.google.com/a/challoners.org/dcgs-publishing/" target="<?php echo mt_rand(); ?>"><i class="fa fa-question-circle fa-fw"></i> Open the support wiki</a></div>
      </div>
      <div class="row buttonLine">
          <div class="col-xs-12 col-sm-5 col-md-4"><a class="btn btn-default btn-block" href="/" target="<?php echo mt_rand(); ?>"><i class="fa fa-shield fa-fw"></i> Go to the school website</a></div>
      </div>
        <h3>Website systems</h3>
        <div class="row options">
          <div class="col-xs-12 col-sm-3 col-md-2">
            <p>Intranet links:</p>
          </div>
          <div class="col-xs-12 col-sm-9 col-md-10 btn-group" role="group" aria-label="...">
            <a class="btn btn-default" href="https://drive.google.com/drive/u/0/folders/0ByH41whuUvC_fi1QWkgyMloxM0w1eFdPVWhIa29NcEZ1Sk91UU85X0JGV2tkUzNYRXljWUE" target="<?php echo mt_rand(); ?>">Edit spreadsheets</a>
            <div class="btn-group" role="group">
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Force re-sync
                <span class="caret"></span>
              </button>
              <ul class="dropdown-menu">
                <li><a href="/intranet/students/update" target="<?php echo mt_rand(); ?>">Students</a></li>
                <li><a href="/intranet/staff/update" target="<?php echo mt_rand(); ?>">Staff</a></li>
                <li><a href="/intranet/parents/update" target="<?php echo mt_rand(); ?>">Parents</a></li>
              </ul>
            </div>
            <a class="btn btn-default" href="https://sites.google.com/a/challoners.org/dcgs-publishing/managing-the-website/intranet-links" target="<?php echo mt_rand(); ?>">View help file</a>
          </div>
        </div>
        <div class="row options">
          <div class="col-xs-12 col-sm-3 col-md-2">
            <p>Front page override:</p>
          </div>
          <div class="col-xs-12 col-sm-9 col-md-10 btn-group" role="group" aria-label="...">
            <a class="btn btn-default" href="https://drive.google.com/drive/folders/0ByH41whuUvC_fklEcU5ZakNocS1LVW9lTHNQaWNUYzV4Z29pbkFfWno3S0VYdnVaQXNZdlk" target="<?php echo mt_rand(); ?>">Open content folder</a>
            <a class="btn btn-default" href="/update" target="<?php echo mt_rand(); ?>">Force re-sync</a>
            <a class="btn btn-default" href="/preview" target="<?php echo mt_rand(); ?>">Preview overrides</a>
            <a class="btn btn-default" href="https://sites.google.com/a/challoners.org/dcgs-publishing/managing-the-website/override-messages" target="<?php echo mt_rand(); ?>">View help file</a>
          </div>
        </div>
        <div class="row options">
          <div class="col-xs-12 col-sm-3 col-md-2">
            <p>Sports fixtures:</p>
          </div>
          <div class="col-xs-12 col-sm-9 col-md-10 btn-group" role="group" aria-label="...">
            <a class="btn btn-default" href="https://docs.google.com/spreadsheets/d/1nDL3NXiwdO-wfbHcTLJmIvnZPL73BZeF7fYBj_heIyA/edit#gid=0" target="<?php echo mt_rand(); ?>">Edit spreadsheet</a>
            <a class="btn btn-default" href="/diary/update" target="<?php echo mt_rand(); ?>">Force re-sync</a>
            <a class="btn btn-default" href="https://docs.google.com/document/d/1BWoJOevcLzb6papnBfiWx4UUvtHsLlxjyHqNv3J-gAQ/edit" target="<?php echo mt_rand(); ?>">View help file</a>
          </div>
        </div>
        <div class="row options">
          <div class="col-xs-12 col-sm-3 col-md-2">
            <p>House Competition:</p>
          </div>
          <div class="col-xs-12 col-sm-9 col-md-10 btn-group" role="group" aria-label="...">
            <a class="btn btn-default" href="/c/community/house-competition/current-positions/update" target="<?php echo mt_rand(); ?>">Update website</a>
            <a class="btn btn-default" href="https://drive.google.com/drive/folders/0ByH41whuUvC_fkt2c0pLTGEyMWhOcHVEeVNtX1pmRjFsRjk2RVZBS2lZcU5DOFp5QlFVWmc" target="<?php echo mt_rand(); ?>">Edit content</a>
          </div>
        </div>
        <div class="row options">
          <div class="col-xs-12 col-sm-3 col-md-2">
            <p>Clubs and societies:</p>
          </div>
          <div class="col-xs-12 col-sm-9 col-md-10 btn-group" role="group" aria-label="...">
            <a class="btn btn-default" href="/c/community/clubs-and-societies/whats-on-this-term/update" target="<?php echo mt_rand(); ?>">Update website</a>
            <div class="btn-group" role="group">
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Edit calendar
                <span class="caret"></span>
              </button>
              <ul class="dropdown-menu">
                <li><a href="https://docs.google.com/spreadsheets/d/1cRJPvzWoKjVBeoyzgUrt1gq0qYqXFfRgl7STRBkW8KQ/edit#gid=0" target="<?php echo mt_rand(); ?>">Autumn</a></li>
                <li><a href="https://docs.google.com/spreadsheets/d/1mVNNX_V_3veJC6pAzQeZ6uC48xhO5zJukNMsZhEkEz4/edit#gid=0" target="<?php echo mt_rand(); ?>">Spring</a></li>
                <li><a href="https://docs.google.com/spreadsheets/d/1CGSyQHppyse_T2xXj3K9-8aKyoR6lzCRXAsDMM7mG9c/edit#gid=0" target="<?php echo mt_rand(); ?>">Summer</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <?php
        if ($tab == 'sjt') {
          echo '<div role="tabpanel" class="tab-pane fade in active" id="sjt">';
        } else {
          echo '<div role="tabpanel" class="tab-pane fade" id="sjt">';
        }
      ?>
      <div class="row buttonLine">
        <div class="col-xs-12 col-sm-5 col-md-4"><a class="btn btn-default btn-block" href="https://sites.google.com/a/challoners.org/dcgstv/bookings" target="<?php echo mt_rand(); ?>"><i class="fa fa-camera fa-fw"></i> Book camera and TV Studio time</a></div>
      </div>
      <hr />
      <div class="row buttonLine">
        <div class="col-xs-12 col-sm-5 col-md-4"><a class="btn btn-default btn-block" href="https://sites.google.com/a/challoners.org/dcgs-publishing/dcgs-style-guide" target="<?php echo mt_rand(); ?>"><i class="fa fa-pencil-square-o fa-fw"></i> Read the style guide</a></div>
        <div class="col-xs-12 col-sm-7 col-md-8"><p>You must know and follow this for all articles.</p></div>
      </div>
      <div class="row buttonLine">
        <div class="col-xs-12 col-sm-5 col-md-4"><a class="btn btn-default btn-block" href="https://sites.google.com/a/challoners.org/dcgs-publishing/managing-the-website/datatypes" target="<?php echo mt_rand(); ?>"><i class="fa fa-pencil-square-o fa-fw"></i> Read article formatting instructions</a></div>
      </div>
      <div class="row buttonLine">
        <div class="col-xs-12 col-sm-5 col-md-4"><a class="btn btn-default btn-block" href="https://docs.google.com/spreadsheets/d/1oDZmEOsifN8iQpEE_XI1QBNGiiIMNk-Cont-UsLyFwU/edit#gid=0" target="<?php echo mt_rand(); ?>"><i class="fa fa-table fa-fw"></i> View SJT contributor records</a></div>
        <div class="col-xs-12 col-sm-7 col-md-8"><p>Use the filter icon to change which students you can see.</p></div>
      </div>
      <div class="row buttonLine">
        <div class="col-xs-12 col-sm-5 col-md-4"><a class="btn btn-default btn-block" href="https://docs.google.com/a/challoners.org/forms/d/12Sc9coOfWbPRpHQe6C2SATad_wk_wPpai4SWj9bd_Ho/viewform" target="<?php echo mt_rand(); ?>"><i class="fa fa-star fa-fw"></i> Credit contributors for their work</a></div>
      </div>
      <div class="row buttonLine">
        <div class="col-xs-12 col-sm-5 col-md-4"><a class="btn btn-default btn-block" href="https://statcounter.com/" target="<?php echo mt_rand(); ?>"><i class="fa fa-line-chart fa-fw"></i> Review website statistics</a></div>
        <div class="col-xs-12 col-sm-7 col-md-8"><p>Speak to Mr Burn if you want access to this.</p></div>
      </div>
      <hr />
      <div class="row buttonLine">
        <div class="col-xs-12 col-sm-5 col-md-4"><a class="btn btn-default btn-block" href="https://trello.com/b/rEUcWF5b/website" target="<?php echo mt_rand(); ?>"><i class="fa fa-trello fa-fw"></i> Trello: Main SJT board</a></div>
      </div>
      <div class="row buttonLine">
        <div class="col-xs-12 col-sm-5 col-md-4"><a class="btn btn-default btn-block" href="https://trello.com/b/GHzwVAhu/editorial" target="<?php echo mt_rand(); ?>"><i class="fa fa-trello fa-fw"></i> Trello: SJT editors' board</a></div>
      </div>
      <hr />
      <div class="row buttonLine">
        <div class="col-xs-12 col-sm-5 col-md-4"><a class="btn btn-default btn-block" href="https://docs.google.com/spreadsheets/d/1MsTHzwGu2dRMV80R44d4Fs4ZJC33dwttH3isN4hA05s/edit#gid=0" target="<?php echo mt_rand(); ?>"><i class="fa fa-puzzle-piece fa-fw"></i> Add puzzles to the weekly puzzle app</a></div>
        <div class="col-xs-12 col-sm-7 col-md-8"><p>Speak to Mr Burn if you want access to this.</p></div>
      </div>
    </div>
  
    <?php
    foreach ($mainSheets as $site => $sheetID) {
      if (!isset($mainData_{$site})) {
        $mainData_{$site} = array();
      }
      $_GET['sync'] = 1;
      $newData_{$site} = sheetToArray($sheetID,0);
      $mainData_{$site}['meta'] = $newData_{$site}['meta'];
      if (isset($newData_{$site}['data']['config'])) {
        $mainData_{$site}['config'] = $newData_{$site}['data']['config'][2];
      }
      if (isset($newData_{$site}['data']['index'])) {
        $mainData_{$site}['index'] = $newData_{$site}['data']['index'];
      }

      $orderSheets = array();
      foreach ($newData_{$site}['data'] as $sectionname => $section) {
        if ($sectionname != 'config' && $sectionname != 'index') {
          foreach ($section as $key => $sheet) {
            if (strpos($sheet['sheetid'],'spreadsheets/d/') !== false) {
              $cutoff = strpos($sheet['sheetid'],'spreadsheets/d/');
              $cutoff = $cutoff+15;
              $sheet['sheetid'] = substr($sheet['sheetid'],$cutoff);
              $sheet['sheetid'] = explode('/',$sheet['sheetid'])[0];
            }
            if (strlen($sheet['sheetid']) == 44) {
              $orderSheets[] = $sheet['sheetid'];
            } else {
              $sheet['sheetid'] = '';
            }
            $newData_{$site}['data'][$sectionname][$key]['sheetid'] = $sheet['sheetid'];
          }
        }
      }
      echo '<div role="tabpanel" class="tab-pane fade';
      if ($tab == 'content_'.$site) {
        echo ' in active';
      }
      echo '" id="content_'.$site.'">';
      echo '<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">';
      foreach ($newData_{$site}['data'] as $sectionName => $section) {
        if ($sectionName != 'config' && $sectionName != 'index') {
          echo '<div class="panel panel-default content"><div class="panel-heading" role="tab" id="heading-'.clean($sectionName).'">';
            echo '<h4 class="panel-title">';
              echo '<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-'.clean($sectionName).'" aria-expanded="false" aria-controls="collapse-'.clean($sectionName).'">'.$sectionName.'</a>';
            echo '</h4>';
          echo '</div>';
          echo '<div id="collapse-'.clean($sectionName).'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-'.clean($sectionName).'">';
            echo '<div class="panel-body">';
            foreach ($section as $sheet) {
              if (!empty($sheet['sheetid'])) {
                $mainData_{$site}['data']['sheets'][$sheet['sheetid']]['section'] = $sectionName;
                  if (isset($mainData_{$site}['data']['sheets'][$sheet['sheetid']]['lastupdate'])) {
                    $name = $mainData_{$site}['data']['sheets'][$sheet['sheetid']]['sheetname'];
                    $button1 = '<a class="btn btn-default" href="/sync?site='.$site.'&sheet='.$sheet['sheetid'].'">Update content';
                  } else {
                    if (!empty($sheet['sheetname'])) {
                      $name = $sheet['sheetname'];
                    } else {
                      $name = 'Unnamed';
                    }
                    $name = '<strong class="text-success">(New)</strong> '.$name;
                    $button1 = '<a class="btn btn-success" href="/sync?site='.$site.'&sheet='.$sheet['sheetid'].'">Create content';
                  }
                echo '<div class="row options"><div class="col-xs-12 col-sm-3"><p>'.$name.':</p></div>';
                echo '<div class="col-xs-12 col-sm-9 btn-group" role="group" aria-label="...">';
                  echo $button1.'</a>';
                  echo '<a class="btn btn-default" href="https://docs.google.com/spreadsheets/d/'.$sheet['sheetid'].'" target="'.mt_rand().'">Edit spreadsheet</a>';
                  if (isset($mainData_{$site}['data']['sheets'][$sheet['sheetid']]['lastupdate'])) {
                    if ($site == 'dcgs') {
                      echo '<a class="btn btn-default" href="http://'.$_SERVER['SERVER_NAME'].'/c/'.clean($sectionName).'/'.clean($name).'" target="'.mt_rand().'">Visit content</a>';
                    } else {
                      echo '<a class="btn btn-default" href="http://'.$_SERVER['SERVER_NAME'].'/learn/'.$site.'/'.clean($sectionName).'/'.clean($name).'" target="'.mt_rand().'">Visit content</a>';
                    }
                  }
                echo '</div></div>';
              }
            }
          echo '</div></div></div>';
        }
      }
      echo '</div>';
      echo '</div>';

      $orderSheets = array_flip($orderSheets);
      foreach ($orderSheets as $id => $ignore) {
        $orderSheets[$id] = $mainData_{$site}['data']['sheets'][$id];
      }
      $mainData_{$site}['data']['sheets'] = $orderSheets;

      if (!file_exists('data/content')) {
        mkdir('data/content',0777,true);
      }
      file_put_contents('data/content/mainData_'.$site.'.json', json_encode($mainData_{$site}));
    }
    
    /*// Hit counter
    echo '<div role="tabpanel" class="tab-pane fade';
      if ($tab == 'hits') {
        echo ' in active';
      }
    echo '" id="hits">';
      if (file_exists('data/stats')) {
        $hitsData = array();
        foreach(scandir('data/stats') as $file) {
          if (substr($file,0,4) == 'hits' && pathinfo($file,PATHINFO_EXTENSION) == 'json') {
            $file = file_get_contents('data/stats/'.$file);
            $file = json_decode($file, true);
            foreach($file['data'] as $page => $stats) {
              if (isset($hitsData[$page])) {
                $hitsData[$page] = array_merge($hitsData[$page],$stats);
              } else {
                $hitsData[$page] = $stats;
              }
            }
          }
        }
        $hitsResults = array();
        foreach($hitsData as $page => $stats) {
          $userIDs = array();
          if (!isset($hitsResults[$page])) {
            $hitsResults[$page] = array('last' => 0, 'all' => 0);
          }
          foreach ($stats as $line) {
            if ($line['timestamp'] > time()-2419200) { // 2419200 for four weeks
              if (!in_array($line['userID'],$userIDs)) {
                $hitsResults[$page]['last']++;
              }
            }
            if (!in_array($line['userID'],$userIDs)) {
              $hitsResults[$page]['all']++;
              $userIDs[] = $line['userID'];
            }
          }
        }
        function compareLast($a, $b) { return $a['last'] - $b['last']; }
        function compareAll($a, $b)  { return $a['all'] - $b['all']; }
        uasort($hitsResults, 'compareAll');
        uasort($hitsResults, 'compareLast');
        $hitsResults = array_reverse($hitsResults);
        echo '<table class="table table-striped">';
          echo '<thead>';
          echo '<tr><th>Page</th><th class="text-center">Last month</th><th class="text-center">All records</th></tr>';
        echo '</thead>';
        foreach ($hitsResults as $page => $stats) {
          echo '<tr>';
            echo '<td>'.$page.'</td>';
            echo '<td class="text-center">'.$stats['last'].'</td>';
            echo '<td class="text-center">'.$stats['all'].'</td>';
          echo '</tr>';
        }
        echo '</table>';
      } else {
        echo '<p>No hits have been recorded yet.</p>';
      }
    echo '</div>'; */
    ?>

      <div role="tabpanel" class="tab-pane fade" id="viewdata">
        <?php 
          foreach ($mainSheets as $site => $sheetID) {
            echo '<h4>'.$site.'</h4>';
            view($mainData_{$site});
          }
        ?>
      </div>
    </div>
  </div>