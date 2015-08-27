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

  if (!isset($_GET['sheet'])) {

    if (isset($mainData)) {
      $mainData = file_get_contents('../'.$dataSrc.'/mainData.json');
      $mainData = json_decode($mainData, true);
    } else {
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
          $mainData['data']['sheets'][$sheet['sheetid']]['section'] = $sectionName;
          echo '<li><p>';
            if (isset($mainData['data']['sheets'][$sheet['sheetid']]['lastupdate'])) {
              $name = $mainData['data']['sheets'][$sheet['sheetid']]['sheetname'];
              echo str_pad($name.':',30,' ',STR_PAD_RIGHT);
              echo '<a href="?sheet='.$sheet['sheetid'].'">Update content</a>';
            } else {
              $name = $sheet['sheetname'];
              echo '<span class="warning">(New)</span> ';
              echo str_pad($name.':',24,' ',STR_PAD_RIGHT);
              echo '<a href="?sheet='.$sheet['sheetid'].'">Create content</a>';
            }
            echo ' | ';
            echo '<a href="https://docs.google.com/spreadsheets/d/'.$sheet['sheetid'].'" target="'.mt_rand().'">Edit spreadsheet</a>';
          echo '</p></li>';
        }
      echo '</ul>';
    }

    if (!file_exists('../'.$dataSrc)) {
      mkdir('../'.$dataSrc,0777,true);
    }
    file_put_contents('../'.$dataSrc.'/mainData.json', json_encode($mainData));
    
  } else {
    if (!isset($_GET['page'])) {
   
      $_GET['sync'] = 1;
      $sheetData = sheetToArray($_GET['sheet'],'../'.$dataSrc,'manual');

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

      echo '<p><b>Updated: '.$mainData['data']['sheets'][$_GET['sheet']]['section'].'/'.$sheetData['meta']['sheetname'].'</b></p>';
      echo '<p>Now fetching images and tags - please wait...</p>';
      echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=./?sheet='.$_GET['sheet'].'&page=0">';
      
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
          $acronyms = array('dcgs','dchs','slt','sslt','sjt','pe','rs','pshe','uksa');
          $tags = explode(',',$row['content']);
          foreach ($tags as $tag) {
            $tag = trim($tag);
            $tag = strtolower($tag);
            if (in_array($tag,$acronyms)) {
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
      $gitm = $gitm%11;
      if ($gitm == 0) {
        echo '<img src="modules/gitm/gitm'.($_GET['page']%6).'.gif" />';
      }
      
    }
  }

   //view($mainData);  

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