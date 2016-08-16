<?php
      echo '<div class="row panel panel-default">';
      echo '<div class="panel-body">';
      $notice = '';
    if (!isset($_GET['page'])) {
      $prNow = 1;
      $_GET['sync'] = 1;
      $sheetData = sheetToArray($_GET['sheet'],'data/content','manual');

      if ($sheetData != 'ERROR') {
      
        $pages = array();
        foreach ($sheetData['data'] as $page => $content) {
          if (stripos($page,'[link]') !== false) {
            $page = str_ireplace('[link]','[link:'.$content[2]['url'].']',$page);
          }
          $pages[] = $page;
        }
          $mainData_{$_GET['site']}['data']['sheets'][$_GET['sheet']]['sheetname'] = $sheetData['meta']['sheetname'];
          $mainData_{$_GET['site']}['data']['sheets'][$_GET['sheet']]['pages'] = $pages;
          $mainData_{$_GET['site']}['data']['sheets'][$_GET['sheet']]['lastupdate'] = $sheetData['meta']['lastupdate'];
          $prEnd = count($mainData_{$_GET['site']}['data']['sheets'][$_GET['sheet']]['pages'])+1;

          if (!file_exists('data/content')) {
            mkdir('data/content',0777,true);
          }
          file_put_contents('data/content/mainData_'.$_GET['site'].'.json', json_encode($mainData_{$_GET['site']}));

          $notice .= '<p>Updated: '.$mainData_{$_GET['site']}['data']['sheets'][$_GET['sheet']]['section'].'/'.$sheetData['meta']['sheetname'].'</p>';
          $notice .= '<p><strong>Now fetching images and tags - please wait...</strong></p>';
          //echo '<a class="btn btn-danger" href="/sync?site='.$_GET['site'].'&sheet='.$_GET['sheet'].'&page=0">Page refresh disabled - refresh manually</a>'; // Debugging
          echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=/sync?site='.$_GET['site'].'&sheet='.$_GET['sheet'].'&page=0">';
      } else {
        
        $notice .= '<p class="danger"><b>Failed to fetch data!</b></p>';
        $notice .= '<p class="danger">Please <a href="https://drive.google.com/open?id='.$mainSheets[$_GET['site']].'" target="'.mt_rand().'">check the sheet ID</a>, or ask for support.</p>';
        
      }
      
    } else {
      $prNow = $_GET['page']+2;
        $prEnd = count($mainData_{$_GET['site']}['data']['sheets'][$_GET['sheet']]['pages'])+1;
        if (file_exists('data/content/'.$_GET['sheet'].'.json')) {
          $sheetData = file_get_contents('data/content/'.$_GET['sheet'].'.json');
          $sheetData = json_decode($sheetData, true);
        }

        $page = $mainData_{$_GET['site']}['data']['sheets'][$_GET['sheet']]['pages'][$_GET['page']];
      $c = 0; $files = array();
      foreach ($sheetData['data'][$page] as $row) {
        $isImage = strtolower($row['datatype']);
        if (strpos($isImage,'image') !== false) { // Note that this gets custom datatypes such as 'newsimage' as well
          $image = fetchImageFromURL('/data/images',$row['url'],$row['content']);
          if ($image != false) {
            $c++;
            $files[] = $image;
          }
        }
      }
      
      $notice .= '<p>Fetched '.$c.' image';
        if ($c != 1) {
          $notice .= 's';
        }
      $notice .= ' from \''.$page.'\'...';
      $notice .= '<ul>';
      foreach ($files as $file) {
        $notice .= '<li>'.$file.'</li>';
      }
      $notice .= '</ul>';
      
      $_GET['page']++;
      if ((isset($mainData_{$_GET['site']}['data']['sheets'][$_GET['sheet']]['pages'][$_GET['page']]))) {
        $notice .= '<p><strong>Checking next page - please wait...</strong></p>';
        //echo '<a class="btn btn-danger" href="/sync?site='.$_GET['site'].'&sheet='.$_GET['sheet'].'&page='.$_GET['page'].'">Page refresh disabled - refresh manually</a>'; // Debugging
        echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=/sync?site='.$_GET['site'].'&sheet='.$_GET['sheet'].'&page='.$_GET['page'].'">';
      } else {
        $notice .= '<p><strong>Update complete! Returning to main menu...</strong></p>';
        //echo '<a class="btn btn-danger" href="/sync">Page refresh disabled - refresh manually</a>'; // Debugging
        echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=/sync?tab=content_'.$_GET['site'].'">';
      }
    }
    $progress = round(($prNow/$prEnd)*100);
    echo '<div class="progress">';
      echo '<div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$progress.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$progress.'%">';
    echo '</div></div>';
    echo $notice;
    echo '</div></div>';
?>