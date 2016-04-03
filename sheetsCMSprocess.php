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
        if (!isset($_GET['learn'])) {
          $mainData['data']['sheets'][$_GET['sheet']]['sheetname'] = $sheetData['meta']['sheetname'];
          $mainData['data']['sheets'][$_GET['sheet']]['pages'] = $pages;
          $mainData['data']['sheets'][$_GET['sheet']]['lastupdate'] = $sheetData['meta']['lastupdate'];
          $prEnd = count($mainData['data']['sheets'][$_GET['sheet']]['pages'])+1;

          if (!file_exists('data/content')) {
            mkdir('data/content',0777,true);
          }
          file_put_contents('data/content/mainData.json', json_encode($mainData));

          $notice .= '<p>Updated: '.$mainData['data']['sheets'][$_GET['sheet']]['section'].'/'.$sheetData['meta']['sheetname'].'</p>';
          $notice .= '<p><strong>Now fetching images and tags - please wait...</strong></p>';
          //echo '<a class="btn btn-danger" href="/sync?sheet='.$_GET['sheet'].'&page=0">Page refresh disabled - refresh manually</a>'; // Debugging
          echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=/sync?sheet='.$_GET['sheet'].'&page=0">';
        } else {
          $learnData['data'][$_GET['learn']]['sheets'][$_GET['sheet']]['sheetname'] = $sheetData['meta']['sheetname'];
          $learnData['data'][$_GET['learn']]['sheets'][$_GET['sheet']]['pages'] = $pages;
          $learnData['data'][$_GET['learn']]['sheets'][$_GET['sheet']]['lastupdate'] = $sheetData['meta']['lastupdate'];
          $prEnd = count($learnData['data'][$_GET['learn']]['sheets'][$_GET['sheet']]['pages'])+1;

          if (!file_exists('data/content')) {
            mkdir('data/content',0777,true);
          }
          file_put_contents('data/content/learnData.json', json_encode($learnData));

          $notice .= '<p>Updated: '.$learnData['data'][$_GET['learn']]['sheets'][$_GET['sheet']]['section'].'/'.$sheetData['meta']['sheetname'].'</p>';
          $notice .= '<p><strong>Now fetching images and tags - please wait...</strong></p>';
          //echo '<a class="btn btn-danger" href="/sync?sheet='.$_GET['sheet'].'&page=0">Page refresh disabled - refresh manually</a>'; // Debugging
          echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=/sync?sheet='.$_GET['sheet'].'&learn='.$_GET['learn'].'&page=0">';
        }
      } else {
        
        $notice .= '<p class="danger"><b>Failed to fetch data!</b></p>';
        $notice .= '<p class="danger">Please <a href="https://drive.google.com/open?id=1n-oqN8rF98ZXqlH7A_eUx6K_5FgK2RUpiCx3aUMg3kM" target="'.mt_rand().'">check the sheet ID</a>, or ask for support.</p>';
        
      }
      
    } else {
      $prNow = $_GET['page']+2;
      if (!isset($_GET['learn'])) {
        $prEnd = count($mainData['data']['sheets'][$_GET['sheet']]['pages'])+1;
        if (file_exists('data/content/'.$_GET['sheet'].'.json')) {
          $sheetData = file_get_contents('data/content/'.$_GET['sheet'].'.json');
          $sheetData = json_decode($sheetData, true);
        }

        $page = $mainData['data']['sheets'][$_GET['sheet']]['pages'][$_GET['page']];
      } else {
        $prEnd = count($learnData['data'][$_GET['learn']]['sheets'][$_GET['sheet']]['pages'])+1;
        if (file_exists('data/content/'.$_GET['sheet'].'.json')) {
          $sheetData = file_get_contents('data/content/'.$_GET['sheet'].'.json');
          $sheetData = json_decode($sheetData, true);
        }

        $page = $learnData['data'][$_GET['learn']]['sheets'][$_GET['sheet']]['pages'][$_GET['page']];
      }
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
      if ((!isset($_GET['learn']) && isset($mainData['data']['sheets'][$_GET['sheet']]['pages'][$_GET['page']])) || (isset($_GET['learn']) && isset($learnData['data'][$_GET['learn']]['sheets'][$_GET['sheet']]['pages'][$_GET['page']]))) {
        $notice .= '<p><strong>Checking next page - please wait...</strong></p>';
        //echo '<a class="btn btn-danger" href="/sync?sheet='.$_GET['sheet'].'&page='.$_GET['page'].'">Page refresh disabled - refresh manually</a>'; // Debugging
        if (!isset($_GET['learn'])) {
          echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=/sync?sheet='.$_GET['sheet'].'&page='.$_GET['page'].'">';
        } else {
          echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=/sync?sheet='.$_GET['sheet'].'&learn='.$_GET['learn'].'&page='.$_GET['page'].'">';
        }
      } else {
        $notice .= '<p><strong>Update complete! Returning to main menu...</strong></p>';
        //echo '<a class="btn btn-danger" href="/sync">Page refresh disabled - refresh manually</a>'; // Debugging
        if (!isset($_GET['learn'])) {
          echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=/sync?tab=content">';
        } else {
          echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=/sync?tab=learn">';
        }
      }
    }
    $progress = round(($prNow/$prEnd)*100);
    echo '<div class="progress">';
      echo '<div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$progress.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$progress.'%">';
    echo '</div></div>';
    echo $notice;
    echo '</div></div>';
?>