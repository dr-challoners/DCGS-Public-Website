<!DOCTYPE HTML>
<html>
  <head>
    <title>DCGS content management system</title>
  </head>
  <body>
    <style>
      body { width: 100%; }
      .warning { color: red; font-weight: bold; }
      img { position: absolute; bottom: 0; right: 0; }
    </style>

<?php // In development - more options and instructions will appear over time

  include('sheetCMS.php');
  
  echo '<pre>';
  echo '<h1>DCGS content management system</h1>';
  echo '<p class="warning">This system is currently in development.</p>';

  if (!isset($_GET['sheet']) && !isset($_GET['action'])) {
    
    if (!isset($mainData)) {
      $mainData = array();
    }
    $_GET['sync'] = 1;
    $newData = sheetToArray($mainSheet,0);
    $mainData['meta'] = $newData['meta'];
    
    $orderSheets = array();
    foreach ($newData['data'] as $sectionname => $section) {
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
        $newData['data'][$sectionname][$key]['sheetid'] = $sheet['sheetid'];
      }
    }

    echo '<p>The following data has been found. Use the links to add or update this data on the website.</p>';
    echo '<p>The original data can be found in <a href="https://drive.google.com/drive/folders/0ByH41whuUvC_fnpLUVhXTGl6dUV4VWZyWWJCNlRQaGp5d0pDbE90QWlCSVJlVEg2ZURSZ0E" target="'.mt_rand().'">this Google Drive folder</a>. Speak to SBU if you need permission to access.</p>';
    echo '<p><a href="https://drive.google.com/open?id=1n-oqN8rF98ZXqlH7A_eUx6K_5FgK2RUpiCx3aUMg3kM" target="'.mt_rand().'">Modify the master spreadsheet</a>.</p>';
    foreach ($newData['data'] as $sectionName => $section) {
      echo '<h2>Section: '.$sectionName.'</h2>';
      echo '<ul>';
        foreach ($section as $sheet) {
          if (!empty($sheet['sheetid'])) {
            $mainData['data']['sheets'][$sheet['sheetid']]['section'] = $sectionName;
            echo '<li><p>';
              if (isset($mainData['data']['sheets'][$sheet['sheetid']]['lastupdate'])) {
                $name = $mainData['data']['sheets'][$sheet['sheetid']]['sheetname'];
                echo str_pad($name.': ',29,' ',STR_PAD_RIGHT);
                echo '<a href="?sheet='.$sheet['sheetid'].'">Update content</a>';
              } else {
                if (!empty($sheet['sheetname'])) {
                  $name = $sheet['sheetname'];
                } else {
                  $name = 'Unnamed';
                }
                echo '<span class="warning">(New)</span> ';
                echo str_pad($name.': ',23,' ',STR_PAD_RIGHT);
                echo '<a href="?sheet='.$sheet['sheetid'].'">Create content</a>';
              }
              echo ' | ';
              echo '<a href="https://docs.google.com/spreadsheets/d/'.$sheet['sheetid'].'" target="'.mt_rand().'">Edit spreadsheet</a>';
              if (isset($mainData['data']['sheets'][$sheet['sheetid']]['lastupdate'])) {
                echo ' | ';
                echo '<a href="http://'.$_SERVER['SERVER_NAME'].'/c/'.clean($sectionName).'/'.clean($name).'" target="'.mt_rand().'">Visit content</a>';
              }
            echo '</p></li>';
          }
        }
      echo '</ul>';
    }
    
    $orderSheets = array_flip($orderSheets);
    foreach ($orderSheets as $id => $ignore) {
      $orderSheets[$id] = $mainData['data']['sheets'][$id];
    }
    $mainData['data']['sheets'] = $orderSheets;
    
    echo '<h2>Website systems</h2>';
      echo '<ul>';
        echo '<li><p>Intranet links:               <a href="https://drive.google.com/drive/u/0/folders/0ByH41whuUvC_fi1QWkgyMloxM0w1eFdPVWhIa29NcEZ1Sk91UU85X0JGV2tkUzNYRXljWUE" target="'.mt_rand().'">Edit spreadsheets</a> | <a href="https://docs.google.com/document/d/1y3nAKXu7hbfMlp23KctIT3mAJ1lbiIdQK80zmC40mzo/edit" target="'.mt_rand().'">View help file</a></p></li>';
        echo '<li><p>Front page override:          <a href="https://docs.google.com/spreadsheets/d/1icLE9k67sw9gN9dcnZYsWt5QOnUxe7mTQGZk_2EFLZk/edit#gid=0" target="'.mt_rand().'">Edit spreadsheet</a> | <a href="http://www.challoners.com/?overrideSync=1" target="'.mt_rand().'">Force re-sync</a> | <a href="http://www.challoners.com/?overridePreview=1" target="'.mt_rand().'">Preview overrides</a> | <a href="https://docs.google.com/document/d/1vcCNqjPMzeWCm-nQDxLT2OoydrApvOdkJhfBfz6Ji2o/edit" target="'.mt_rand().'">View help file</a></p></li>';
        echo '<li><p>Sports fixtures:              <a href="https://docs.google.com/spreadsheets/d/1nDL3NXiwdO-wfbHcTLJmIvnZPL73BZeF7fYBj_heIyA/edit#gid=0" target="'.mt_rand().'">Edit spreadsheet</a> | <a href="http://www.challoners.com/diary/sync" target="'.mt_rand().'">Force re-sync</a> | <a href="https://docs.google.com/document/d/1BWoJOevcLzb6papnBfiWx4UUvtHsLlxjyHqNv3J-gAQ/edit" target="'.mt_rand().'">View help file</a></p></li>';
        echo '<li><p>House Competition:            <a href="http://www.challoners.com/pages/Student_life/House_Competition/Current_positions&sync=1" target="'.mt_rand().'">Update website</a> | <a href="https://drive.google.com/drive/folders/0ByH41whuUvC_fkt2c0pLTGEyMWhOcHVEeVNtX1pmRjFsRjk2RVZBS2lZcU5DOFp5QlFVWmc" target="'.mt_rand().'">Edit content</a></p></li>';
        echo '<li><p>Clubs and societies:          <a href="http://www.challoners.com/pages/Student_life/Enrichment/Clubs_and_societies_calendar&sync=1" target="'.mt_rand().'">Update website</a> | Edit calendar - <a href="https://docs.google.com/spreadsheets/d/1cRJPvzWoKjVBeoyzgUrt1gq0qYqXFfRgl7STRBkW8KQ/edit#gid=0" target="'.mt_rand().'">Autumn</a> | <a href="https://docs.google.com/spreadsheets/d/1mVNNX_V_3veJC6pAzQeZ6uC48xhO5zJukNMsZhEkEz4/edit#gid=0" target="'.mt_rand().'">Spring</a> | <a href="https://docs.google.com/spreadsheets/d/1CGSyQHppyse_T2xXj3K9-8aKyoR6lzCRXAsDMM7mG9c/edit#gid=0" target="'.mt_rand().'">Summer</a></p></li>';
      echo '</ul>';
      
      echo '<h2>Other options</h2>';
      echo '<ul>';
        echo '<li><p><a href="?action=clean">Clean stored data</a> - try this if any content is appearing incorrectly on the website.</p></li>';
      echo '</ul>';
      
      if (isset($mainData['data']['tags'])) {
        ksort($mainData['data']['tags']);
        echo '<h2>Tags</h2>';
        echo '<p class="warning">In the future, tags will be used to make a new search facility for the website - you should start adding them<br />to your articles in preparation for this.</p>';
        echo '<p>Pages should have a small number of tags. Tags should be broad in scope, so that more articles can be matched up.</p>';
        echo '<p>Use \'Key Stage 3\', \'Key Stage 4\' and \'Sixth Form\' instead of referring to years or to GCSEs or A Levels.</p>';
        echo 'In the case of subjects that are part of a broader subject group (Languages with French, German and Spanish;<br />Humanities with History, Geography and so on; Sports and each individual sport) tag both the individual subject<br />and the subject group.</p>';
        echo '<p>The following tags have been recorded:</p>';
        echo '<ul>';
          foreach ($mainData['data']['tags'] as $tag => $content) {
            echo '<li>';
              $tagdetails = '<b>'.$tag.'</b> in '.count($content).' article';
              if (count($content) != 1) { $tagdetails .= 's'; }
              echo str_pad($tagdetails,54,' ',STR_PAD_RIGHT);
              //echo '<a href="?action=droptag&tag='.$tag.'">Delete</a>';
            echo '</li>';
          }
        echo '</ul>';
      }

    if (!file_exists('../'.$dataSrc)) {
      mkdir('../'.$dataSrc,0777,true);
    }
    file_put_contents('../'.$dataSrc.'/mainData.json', json_encode($mainData));
    
  } elseif (isset($_GET['sheet'])) {
    if (!isset($_GET['page'])) {
   
      $_GET['sync'] = 1;
      $sheetData = sheetToArray($_GET['sheet'],'../'.$dataSrc,'manual');

      if ($sheetData != 'ERROR') {
      
      $pages = array();

      foreach ($sheetData['data'] as $page => $content) {
        $pages[] = $page;
      }

      $mainData['data']['sheets'][$_GET['sheet']]['sheetname'] = $sheetData['meta']['sheetname'];
      $mainData['data']['sheets'][$_GET['sheet']]['pages'] = $pages;
      $mainData['data']['sheets'][$_GET['sheet']]['lastupdate'] = $sheetData['meta']['lastupdate'];
      
      if (!file_exists('../'.$dataSrc)) {
        mkdir('../'.$dataSrc,0777,true);
      }
      file_put_contents('../'.$dataSrc.'/mainData.json', json_encode($mainData));

      echo '<p>Updated: '.$mainData['data']['sheets'][$_GET['sheet']]['section'].'/'.$sheetData['meta']['sheetname'].'</p>';
      echo '<p>Now fetching images and tags - please wait...</p>';
      echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=./?sheet='.$_GET['sheet'].'&page=0">';
        
      } else {
        
        echo '<p class="warning"><b>Failed to fetch data!</b></p>';
        echo '<p class="warning">Please <a href="https://drive.google.com/open?id=1n-oqN8rF98ZXqlH7A_eUx6K_5FgK2RUpiCx3aUMg3kM" target="'.mt_rand().'">check the sheet ID</a>, or ask for support.</p>';
        
      }
      
    } else {
      
      if (file_exists('../'.$dataSrc.'/'.$_GET['sheet'].'.json')) {
        $sheetData = file_get_contents('../'.$dataSrc.'/'.$_GET['sheet'].'.json');
        $sheetData = json_decode($sheetData, true);
      }
      
      $page = $mainData['data']['sheets'][$_GET['sheet']]['pages'][$_GET['page']];
      
      $imgsSrc = '../'.$imgsSrc;
      $c = 0; $files = array();
      foreach ($sheetData['data'][$page] as $row) {
        $isImage = strtolower($row['datatype']);
        if (strpos($isImage,'image') !== false) { // Note that this gets custom datatypes such as 'newsimage' as well
          if (!empty($row['content'])) {
            $imageName = makeID($row['url'],1).'-'.clean($row['content']);                      
          } else {
            $imageName = makeID($row['url']);
          }
          $check = fetchImage($row['url'],$imageName);
          if ($check != 'ERROR') {
            $c++;
            $files[] = $imageName;
          }
        } elseif (strtolower($row['datatype']) == 'tags' || strtolower($row['datatype']) == 'tag') {
          $tagReport = array();
          $tags = explode(',',$row['content']);
          foreach ($tags as $tag) {
            $tag = trim($tag);
            if (!empty($tag)) {
              if (in_array($tag,$acronyms)) { // The acronyms list is in the config file to make it easier to get to for modification
                $tag = strtoupper($tag);
              } else {
                $tag = ucwords($tag);
              }
              $section = $mainData['data']['sheets'][$_GET['sheet']]['section'];
              $sheet   = $sheetData['meta']['sheetname'];
              $pageID  = makeID($_GET['sheet'],1).str_pad($_GET['page'],3,'0',STR_PAD_LEFT);
              $mainData['data']['tags'][$tag][$pageID] = array($section,$sheet,$page);
              $tagReport[] = $tag;
            }
          }
        }
      }
      
      echo '<p>Fetched '.$c.' image';
        if ($c != 1) {
          echo 's';
        }
      echo ' from \''.$page.'\'...';
      echo '<ul>';
      foreach ($files as $file) {
        echo '<li>'.$file.'</li>';
      }
      echo '</ul>';
      
      if (isset($tagReport)) {
        echo '<p>Found the following tags:</p>';
        echo '<ul>';
        foreach ($tagReport as $tag) {
          echo '<li>'.$tag.'</li>';
        }
        echo '</ul>';
        
        if (!file_exists('../'.$dataSrc)) {
          mkdir('../'.$dataSrc,0777,true);
        }
        file_put_contents('../'.$dataSrc.'/mainData.json', json_encode($mainData));
      }
      
      $_GET['page']++;
      if (isset($mainData['data']['sheets'][$_GET['sheet']]['pages'][$_GET['page']])) {
        echo '<p>Checking next page - please wait...</p>';
       echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=./?sheet='.$_GET['sheet'].'&page='.$_GET['page'].'">';
      } else {
        echo '<p>Update complete! Returning to main menu...</p>';
       echo '<META HTTP-EQUIV="Refresh" CONTENT="1;URL=./">';
      }
      
      $gitm = preg_replace('/[^0-9]/', '', $_GET['sheet']);
      $gitm = $gitm+date('j',time());
      $gitm = $gitm%5;
      if ($gitm == 0 && $mainData['data']['sheets'][$_GET['sheet']]['section'] == 'News') {
        echo '<img src="modules/gitm/gitm'.($_GET['page']%6).'.png" />';
      }
      
    }
  } elseif (isset($_GET['action'])) {
    switch ($_GET['action']) {
      
      case 'clean';
        if (!isset($mainData)) {
          $mainData = array();
        }
        $_GET['sync'] = 1;
        $newData = sheetToArray($mainSheet,0);
        $mainData['meta'] = $newData['meta'];
      
        foreach ($mainData['data']['sheets'] as $id => $sheet) {
          if (!file_exists('../'.$dataSrc.'/'.$id.'.json')) {
            unset($mainData['data']['sheets'][$id]);
          }     
        }
        if (!file_exists('../'.$dataSrc)) {
          mkdir('../'.$dataSrc,0777,true);
        }
        file_put_contents('../'.$dataSrc.'/mainData.json', json_encode($mainData));
        $files = scandir('../'.$dataSrc);
        $ignore = array('.','..','mainData.json');
        foreach ($files as $file) {
          if (!in_array($file,$ignore)) {
            $file = pathinfo($file);
            if ($file['extension'] != 'json' || !isset($mainData['data']['sheets'][$file['filename']])) {
              unlink('../'.$dataSrc.'/'.$file['basename']);
            }
          }
        }
        echo '<p><b>The data has been cleaned.</b></p>';
        echo '<p>If this doesn\'t resolve your problem, please ask for support.</p>';
      break;
      
      case 'droptag';
        $tag = $_GET['tag'];
        unset($mainData['data']['tags'][$tag]);
        echo '<p><b>'.$tag.' has been removed from the stored list of tags.</b></p>';
        echo '<p>This only removes the tag from the website, not from the sheets the page content is stored in.</p>';
        echo '<p>You need to remove the tag there as well, otherwise it will reappear next time you sync the data.</p>';
      break;
      
      default:
        echo '<p class="warning">That\'s an invalid action.</p>';
        echo '<p class="warning">Returning you to the main menu...</p>';
        echo '<META HTTP-EQUIV="Refresh" CONTENT="3;URL=./">';
      break;
      
    }
    if (!file_exists('../'.$dataSrc)) {
      mkdir('../'.$dataSrc,0777,true);
    }
    file_put_contents('../'.$dataSrc.'/mainData.json', json_encode($mainData));
    echo '<p><a href="./">Return to the main menu</a>.</p>';
  }

  // view($mainData);

?></pre>
    
  </body>
</html>