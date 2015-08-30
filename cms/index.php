<!DOCTYPE HTML>
<html>
  <head>
    <title>DCGS content management system</title>
  </head>
  <body>
    <style>
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

    echo '<p>The following data has been found. Use the links to add or update this data on the website.</p>';
    echo '<p>The original data can be found in <a href="https://drive.google.com/open?id=0ByH41whuUvC_fnpLUVhXTGl6dUV4VWZyWWJCNlRQaGp5d0pDbE90QWlCSVJlVEg2ZURSZ0E" target="'.mt_rand().'">this Google Drive folder</a>. Speak to SBU if you need permission to access.</p>';
    echo '<p><a href="https://drive.google.com/open?id=1n-oqN8rF98ZXqlH7A_eUx6K_5FgK2RUpiCx3aUMg3kM" target="'.mt_rand().'">Modify the master spreadsheet</a>.</p>';
    foreach ($newData['data'] as $sectionName => $section) {
      echo '<h2>Section: '.$sectionName.'</h2>';
      echo '<ul>';
        foreach ($section as $sheet) {
          if (!empty($sheet['sheetid'])) {
            if (strpos($sheet['sheetid'],'spreadsheets/d/') !== false) {
              $cutoff = strpos($sheet['sheetid'],'spreadsheets/d/');
              $cutoff = $cutoff+15;
              $sheet['sheetid'] = substr($sheet['sheetid'],$cutoff);
              $sheet['sheetid'] = explode('/',$sheet['sheetid'])[0];
            }
            $mainData['data']['sheets'][$sheet['sheetid']]['section'] = $sectionName;
            echo '<li><p>';
              if (isset($mainData['data']['sheets'][$sheet['sheetid']]['lastupdate'])) {
                $name = $mainData['data']['sheets'][$sheet['sheetid']]['sheetname'];
                echo str_pad($name.':',30,' ',STR_PAD_RIGHT);
                echo '<a href="?sheet='.$sheet['sheetid'].'">Update content</a>';
              } else {
                if (!empty($sheet['sheetname'])) {
                  $name = $sheet['sheetname'];
                } else {
                  $name = 'Unnamed';
                }
                echo '<span class="warning">(New)</span> ';
                echo str_pad($name.':',24,' ',STR_PAD_RIGHT);
                echo '<a href="?sheet='.$sheet['sheetid'].'">Create content</a>';
              }
              echo ' | ';
              echo '<a href="https://docs.google.com/spreadsheets/d/'.$sheet['sheetid'].'" target="'.mt_rand().'">Edit spreadsheet</a>';
              if (isset($mainData['data']['sheets'][$sheet['sheetid']]['lastupdate'])) {
                echo ' | ';
                echo '<a href="http://'.$_SERVER['SERVER_NAME'].'/c/'.clean($sectionName).'/'.clean($name).'">Visit content</a>';
              }
            echo '</p></li>';
          }
        }
      echo '</ul>';
      
      echo '<h2>Other options</h2>';
      echo '<ul>';
        echo '<li><p><a href="?action=clean">Clean stored data</a> - try this if any content is appearing incorrectly on the website.</p></li>';
      echo '</ul>';
      
      if (isset($mainData['data']['tags'])) {
        ksort($mainData['data']['tags']);
        echo '<h2>Tags</h2>';
        echo '<p class="warning">In the future, tags will be used to make a new search facility for the website - you should start adding them to your articles in preparation for this.</p>';
        echo '<p>Pages should have a small number of tags. Tags should be broad in scope, so that more articles can be matchd up.</p>';
        echo '<p>Use \'Key Stage 3\', \'Key Stage 4\' and \'Sixth Form\' instead of referring to years or to GCSEs or A Levels.</p>';
        echo 'In the case of subjects that are part of a broader subject group (Languages with French, German and Spanish; Humanities with History, Geography and so on; Sports and each individual sport) tag both the individual subject and the subject group.</p>';
        echo '<p>The following tags have been recorded:</p>';
        echo '<ul>';
          foreach ($mainData['data']['tags'] as $tag => $content) {
            echo '<li>';
              $tagdetails = '<b>'.$tag.'</b> in '.count($content).' article';
              if (count($content) != 1) { $tagdetails .= 's'; }
              echo str_pad($tagdetails,54,' ',STR_PAD_RIGHT);
              echo '<a href="?action=droptag&tag='.$tag.'">Delete</a>';
            echo '</li>';
          }
        echo '</ul>';
      }
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

  /*

  echo "<hr />";
  echo "<h1>Formatting pages</h1>";
  echo "<h2>Basic text</h2>";
  echo "<p>This system works with Markdown, which is an intuitive way to format text in a plain text environment.</p>";
  echo "<p><a href=\"http://daringfireball.net/projects/markdown/syntax#block\">Full Markdown instructions</a></p>";
  echo "<p>If you want to go onto a new line in a spreadsheet cell, press <b>Alt+Enter</b> (as just enter will move you to the next cell). In this way, you have full access to Markdown's ability to format new paragraphs, lists and so on.</p>";
  echo "<h2>Adding images</h2>";
  echo "Specify the dataType as 'image' and then in the <b>url</b> column put a valid hyperlink to an image. This can be a Google Drive link - see below for more details on this. Anythign you put in <b>content</b> will be displayed in the image's description, and can be see when you click on the image (this brings up a full-screen version of the image).</p>";
  echo "<h3>Options for images</h3>";
  
  echo "<h3>Adding images from Google Drive</h3>";
  echo "<p>Once you have uploaded your image to Drive, right click on it and click <b>Share...</b>. Then click <b>Get shareable link</b> in the top right corner of the window that appears. This will give you the url you need - copy that into the <b>url</b> column of the page's worksheet.</p>";
  echo "<p>You should also check that the image is public, otherwise it won't display on the website. When you click <b>Get shareable link</b>, a box should appear in that window that says <b>Anyone with the link can view</b>. If the box specifies a different, more specific group (such as just anyone in your company with the link), then click on this box and select <b>Anyone with the link can view</b>. This option may be hidden behind <b>More...</b>.</p>";
  echo "<h2>Page titles</h2>";
  echo "<p>The main title displayed on the page is the same as the name of that page's worksheet. Page titles are limited to 50 characters in length.</p>";
  echo "<p>If you want a longer title on the page itself, or just a different title, write the title you want in the first row of the page's worksheet and give it the dataType 'title'.</p>";
  echo "<p>Note that this <u>must</u> be the first piece of data given, otherwise it will not display at all.</p>";
  
  */

?></pre>
    
  </body>
</html>