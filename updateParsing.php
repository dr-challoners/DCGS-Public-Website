<?php function parsePagesSheet($sheetData, $pageName, $share = 0, $tools = 1) {

  // This function goes through the collected array and converts each row into HTML according to the content found there
  // It is stored as a single string, to which some content like author credits is then added before the content is returned
  // sheetKey need just be the sheet key for the data (as the function must be used with the config file that gives the rest)
  // pageName is presumed to have spaces and none-url friendly characters stripped from it (it does a clean anyway).
  // If share is set to 1 then a Twitter link is added to the page
  // If tools is set to 0, then the page won't display print and QR code icons
  
  // This system creates Bootstrap standard HTML markup, and does not include any additional styles
  
  $output = array();
  $fileName = str_ireplace('[hidden]','',$pageName,$hidden);
  $fileName = str_ireplace('[link]','',$fileName,$link);
  $fileName = clean($fileName);
  $section  = clean($_GET['section']);
  $sheet    = clean($sheetData['meta']['sheetname']);
  $directory = $section.'/'.$sheet;
  $output['pageURL']  = 'http://www.challoners.com/';
  if ($hidden > 0) {
    $output['pageURL'] .= 'h';
  } else {
    $output['pageURL'] .= 'c';
  }
  $output['pageURL'] .= '/'.$directory.'/'.$fileName;
  if (isset($sheetData['data'][$pageName])) {
    $pageData = $sheetData['data'][$pageName];
  } else {
    // Dealing with titles that accidentally have whitespace at either end
    foreach ($sheetData['data'] as $pageMatch => $row) {
      if (trim($pageMatch) == $pageName) {
        $pageData = $sheetData['data'][$pageMatch];
        break;
      }
    }
  }
  if (isset($pageData) && !empty($pageData)) {
    foreach ($pageData as $row) {
      unset($imageName,$dataType,$file,$content,$set);
      if (!empty($row['datatype'])) {
        $dataType = clean($row['datatype']);
      } else {
        // If the data type has been specified, then the program can format accordingly.
        // If it hasn't been specified, the program makes a basic guess as to what it might be.
        if (!empty($row['url'])) {
          $dataType = 'link';
        } else {
          $dataType = 'text';
        }
      }
      if ($row['format'] != 'hidden') {
        if (!isset($output['content'])) {
          $c = 0;
        } else {
          $c = count($output['content']);
        }
        if (strpos($row['format'],'set') !== false) {
          if (isset($output['content'][$c-1]['set']) && strpos($row['format'],'new') === false) {
            $set = $c-1;
          } else {
            $set = $c;
          }
        }
        switch ($dataType) {
          // First convert deprecated/synonymous dataTypes into their correct equivalents
          case 'newsdate': $dataType = 'infodate'; break;
          case 'newscredit': case 'newsauthor': $dataType = 'infowriting'; break;
          case 'newseditor': case 'infoeditor': $dataType = 'infoediting'; break;
        }
        switch ($dataType) {
          case 'title':
            $output['title'] = ucwords(formatText(ltrim($row['content'],'#'),0));
            break;
          case 'infodate':
            $output['info']['date'] = $row['content'];
            break;
          case 'infowriting': case 'infoediting': case 'infophotos': case 'inforecording':
            include('modules/parsing/contributors.php');
            break;
          default: case 'text': case 'maths': case 'math':
            include('modules/parsing/textMaths.php');
            break;
          case 'tags': case 'tag': break; // Tags are deprecated, so should be ignored.
          case 'link': case 'file': case 'email':
            include('modules/parsing/links.php');
            break;
          case 'image': case 'newsimage':
            include ('modules/parsing/images.php');
            break;
          case 'video': case 'newsvideo': case 'youtube':
            include ('modules/parsing/video.php');
            break;
          case 'audio': case 'soundcloud': case 'audioboom':
            include ('modules/parsing/audio.php');
            break;
          case 'form':
            include ('modules/parsing/forms.php');
            break;
          case 'gdocs': case 'docs': case 'gdoc': case 'doc':
          case 'gsheets': case 'sheets': case 'gsheet': case 'sheet':
          case 'gslides': case 'slides': case 'gslide': case 'slide':
            include ('modules/parsing/gFiles.php');
            break;
          case 'code':
            include ('modules/parsing/code.php');
            break;
          case 'geogebra':
            include ('modules/parsing/geogebra.php');
            break;
          case 'wolframalpha': case 'wolfram-alpha':
            include ('modules/parsing/wolframAlpha.php');
            break;
        }
      }
    }
    if (!isset($output['title'])) {
      $output['title'] = str_ireplace(array('[hidden]','[link]'),'',$pageName);
      $output['title'] = trim($output['title']);
      $output['title'] = ucwords(formatText($output['title'],0));
    }
    // Creating the code for the actual page
    $output['page']  = '<?php include($_SERVER[\'DOCUMENT_ROOT\'].\'/header.php\'); $section = \''.$section.'\'; $sheet = \''.$sheet.'\'; ?>';
    $output['page'] .= '<div class="row">';
    $output['page'] .= '<?php include($_SERVER[\'DOCUMENT_ROOT\'].\'/contentNavigation.php\'); ?>';
    $output['page'] .= '<div class="col-sm-8">';
    $output['page'] .= '<div class="row articleInfo">';
    $output['page'] .= '<div class="col-xs-10">';
    $output['page'] .= '<h1>'.$output['title'].'</h1>';
    if (isset($output['info']['date'])) {
      $output['page'] .= '<p><strong>'.$output['info']['date'].'</strong></p>';
    }
    function rollCredits($info, $display) {
      $credits = '<p>'.$display.': ';
      $c = count($info);
      for ($i = 0; $i < $c; $i++) {
        $credits .= $info[$i];
        if ($i < $c-2) {
          $credits .= ', ';
        } elseif ($i < $c-1) {
          $credits .= ' and ';
        }
      }
      $credits .= '</p>';
      return $credits;
    } 
    foreach (array('writing','photos','recording','editing') as $creditType) {
      if (isset($output['info'][$creditType])) {
        if ($creditType == 'photos') {
          $displayCredit = 'Photography';
        } else {
          $displayCredit = ucfirst($creditType);
        }
        $output['page'] .= rollCredits($output['info'][$creditType],$displayCredit);
      }
    }
    $output['page'] .= '</div>';
    $output['page'] .= '<div class="col-xs-2 hidden-print">';
    if ($share == 1) {
      $output['page'] .= '<a role="button" class="twitterLink" href="https://twitter.com/intent/tweet?url='.$output['pageURL'].'&amp;text='.urlencode(strip_tags($output['title'])).'&amp;via=ChallonersGS">';
      $output['page'] .= '<i class="fa fa-twitter fa-fw"></i>';
      $output['page'] .= '</a>';
    }
    if ($tools == 1) {
      $output['page'] .= '<a role="button" href="javascript:window.print()">';
      $output['page'] .= '<i class="fa fa-print fa-fw hidden-xs"></i>';
      $output['page'] .= '</a>';
      $output['page'] .= '<a role="button" data-toggle="modal" data-target="#qrCode">'; 
      $output['page'] .= '<i class="fa fa-qrcode fa-fw hidden-xs"></i>';
      $output['page'] .= '</a>';
    }
    $output['page'] .= '</div>';
    $output['page'] .= '</div>';
    // QR code pop-up and suggestions for use
    $output['page'] .= '<div class="modal fade" id="qrCode" tabindex="-1" role="dialog" aria-labelledby="QR code for page">';
    $output['page'] .= '<div class="modal-dialog" role="document">';
    $output['page'] .= '<div class="modal-content">';
    $output['page'] .= '<div class="modal-body qrCode_display">';
    $output['page'] .= '<img class="img-responsive" src="https://chart.googleapis.com/chart?cht=qr&chs=540x540&chl='.$output['pageURL'].'&choe=UTF-8" />';
    $output['page'] .= '<p>QR code for this page. Right click on the image and select \'Copy image\' or \'Save image as...\' to take a copy of this QR code. Add it to a worksheet for students to scan and jump immediately to this page. Or just display this pop-up box on your classroom projector.</p>';
    $output['page'] .= '</div>';
    $output['page'] .= '</div>';
    $output['page'] .= '</div>';
    $output['page'] .= '</div>';
    // Article content
    foreach ($output['content'] as $block) {
      if (isset($block['set'])) {
        $setHold = 0;
        $setBox  = '<div class="col-sm-X">Y</div>';
        $output['page'] .= '<div class="row setDisplay">';
        while (count($block['set']) > 0) {
          if ($setHold <= 0) {
            switch (count($block['set'])) {
              case 1: case 2: case 3: case 4: case 6:
                $setSize = 12/count($block['set']);
                $setHold = count($block['set']);
                break;
              case 5: case 9: case 10: case 11:
                $setSize = 'fifths';
                $setHold = 5;
                break;
              case 7: case 8:
                $setSize = 3;
                $setHold = 4;
                break;
              default: // 12 or more
                $setSize = 2;
                $setHold = 6;
                break;
            }
          }
          $output['page'] .= str_replace(array('X','Y'),array($setSize,array_shift($block['set'])),$setBox);
          $setHold--;
        }
        $output['page'] .= '</div>';
      } else {
        $block = str_replace('&lt;?php ','<?php ',$block); // Makes sure php in code blocks is recognised
        $output['page'] .= $block;
      }
    }
    $output['page'] .= '</div>';
    $output['page'] .= '</div>';
    $output['page'] .= '<?php include($_SERVER[\'DOCUMENT_ROOT\'].\'/footer.php\'); ?>';
    if ($hidden > 0) {
      $directory = 'pages/hidden/'.$directory;
    } else {
      $directory = 'pages/visible/'.$directory;
    }
    if (!file_exists($directory)) {
      mkdir($directory,0777,true);
    }

    file_put_contents($directory.'/'.$fileName.'.php', $output['page']);
  } else {
    return 'ERROR';
  }
} ?>