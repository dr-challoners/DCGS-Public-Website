<?php
    echo '<div class="row panel panel-default">';
      echo '<div class="panel-body">';
        if (!isset($learnData)) {
          $learnData = array();
        }
        $_GET['sync'] = 1;
        $newData = sheetToArray($learnSites[$_GET['learn']],0);
        $learnData['meta']['lastupdate'] = $newData['meta']['lastupdate'];

        $orderSheets = array();
        $orderSections = array();
        foreach ($newData['data'] as $sectionname => $section) {
          if ($sectionname == 'config') {
            $learnData['data'][$_GET['learn']]['config'] = $section[2];
          } elseif ($sectionname == 'index') {
            $learnData['data'][$_GET['learn']]['index'] = $section;
          } else {
            if (!in_array($sectionname,$orderSections)) {
              $orderSections[] = $sectionname;
            }
            foreach ($section as $key => $sheet) {
              if (strpos($sheet['sheetid'],'spreadsheets/d/') !== false) {
                $cutoff = strpos($sheet['sheetid'],'spreadsheets/d/');
                $cutoff = $cutoff+15;
                $sheet['sheetid'] = substr($sheet['sheetid'],$cutoff);
                $sheet['sheetid'] = explode('/',$sheet['sheetid'])[0];
              }
              if (strlen($sheet['sheetid']) == 44) {
                $orderSheets[$sheet['sheetid']] = array('section' => $sectionname,'sheetname' => $sheet['sheetname']);
              }
            }
          }
        }
        foreach($orderSheets as $id => $sheet) {
          if (isset($learnData['data'][$_GET['learn']]['sheets'][$id])) {
            $orderSheets[$id] = $learnData['data'][$_GET['learn']]['sheets'][$id];
          }
        }
        $learnData['data'][$_GET['learn']]['sheets'] = $orderSheets;
        $learnData['data'][$_GET['learn']]['sections'] = $orderSections;

        if (!file_exists('data/content')) {
          mkdir('data/content',0777,true);
        }
        file_put_contents('data/content/learnData.json', json_encode($learnData));
        echo '<p><strong>'.ucwords($_GET['learn']).' main data has been updated! Returning to main menu...</strong></p>';
        echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=/sync?tab=learn">';
    echo '</div></div>';
?>