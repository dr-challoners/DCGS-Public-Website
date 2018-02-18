<?php function parsePagesSheet($sheetData, $pageName, $mainID, $siteLoc, $pageLoc, $share = 0) {

  // This function goes through the collected array and converts each row into HTML according to the content found there
  // It is stored as a single string, to which some content like author credits is then added before the content is returned
  // sheetKey need just be the sheet key for the data (as the function must be used with the config file that gives the rest)
  // pageName is presumed to have spaces and none-url friendly characters stripped from it (it does a clean anyway).
  // If share is set to 1 then a Twitter link is added to the page
  // If tools is set to 0, then the page won't display print and QR code icons
  
  // This system creates Bootstrap standard HTML markup, and does not include any additional styles
  
  $navData = array();
  $output = array();
  $images = array();
  $fileName = str_ireplace('[hidden]','',$pageName,$hidden);
  $fileName = str_ireplace('[link]','',$fileName,$link);
  $fileName = clean($fileName);
  $section  = clean($_GET['section']);
  $sheet    = clean($sheetData['meta']['sheetname']);
  $directory = $section.'/'.$sheet;
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
    if ($link > 0) {
      $navData['link'] = $pageData[2]['url'];
    } else {
      $navData['link'] = '/'.$siteLoc.'/'.$directory.'/'.$fileName;
  $output['pageURL']  = 'http://www.challoners.com/'.$siteLoc.'/'.$directory.'/'.$fileName;
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
        if (!isset($output['content'])) {
          $c = 0;
        } else {
          $c = count($output['content']);
        }
        if (strpos($row['format'],'gallery') !== false) {
          if (isset($output['content'][$c-1]['gallery']) && strpos($row['format'],'new') === false) {
            $gallery = $c-1;
          } else {
            $gallery = $c;
          }
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
            $output['title'] = formatText(trim($row['content'],'#'),0);
            break;
          case 'infodate':
            $output['info']['date'] = $row['content'];
            $navData['preview']['date'] = $row['content'];
            break;
          case 'infowriting': case 'infoediting': case 'infophotos': case 'inforecording':
            include('modules/parsing/contributors.php');
            break;
          default: case 'text': case 'maths': case 'math':
            $previewText = formatText($row['content'],0);
            $previewText = preg_replace("/<h[0-9]>[^<]+<\/h[0-9]>/",'',$previewText);
            $previewText = strip_tags($previewText);
            if (isset($navData['preview']['text'])) {
              $navData['preview']['text'] .= $previewText.' ';
            } else {
              $navData['preview']['text'] = $previewText.' ';
            }
            if ($row['format'] != 'hidden') {
              include('modules/parsing/textMaths.php');
            }
            break;
          case 'tags': case 'tag': break; // Tags are deprecated, so should be ignored.
          case 'link': case 'file': case 'email':
            include('modules/parsing/links.php');
            break;
          case 'image': case 'newsimage':
            $imageID = makeID($row['url'], 1);
            $images[] = array('id' => $imageID, 'url' => $row['url'], 'content' => $row['content'], 'format' => $row['format']);
            if ($row['format'] != 'hidden') {
              if (isset($set)) {
                $output['content'][$set]['set'][] = '[IMAGE:'.$imageID.']';
              } elseif (isset($gallery)) {
                $output['content'][$gallery]['gallery'][] = array('name' => '[IMAGE:'.$imageID.']', 'format' => $row['format']);
              } else {
                $output['content'][] = '[IMAGE:'.$imageID.']';
              }
            }
            if (!empty($row['content'])) {
              $image = makeID($row['url'],1).'-'.clean($row['content']);                      
            } else {
              $image = makeID($row['url']);
            }
            $navData['preview']['images'][] = array('file' => $image, 'type' => $dataType);
            break;
          case 'video': case 'newsvideo': case 'youtube':
            if ($row['format'] != 'hidden') {
              include ('modules/parsing/video.php');
            }
            if ($dataType == 'newsvideo') {
              $navData['preview']['videos'][] = array('type' => $vType, 'id' => $id, 'src' => $src);
            }
            break;
          case 'audio': case 'soundcloud': case 'audioboom':
            include ('modules/parsing/audio.php');
            break;
          case 'form':
            include ('modules/parsing/forms.php');
            break;
          case 'table':
            include ('modules/parsing/tables.php');
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
    if (!isset($output['title'])) {
      $output['title'] = str_ireplace(array('[hidden]','[link]'),'',$pageName);
      $output['title'] = formatText(trim($output['title']),0);
    }
    // Creating the code for the actual page
    $output['page']  = '<?php $section = \''.$section.'\'; $sheet = \''.$sheet.'\'; ?>';
     if($_GET['tab'] == 'maths') {
       $output['page'] .= '<?php include($_SERVER[\'DOCUMENT_ROOT\'].\'/maths/header.php\'); ?>';
     } else {
    $output['page'] .= '<?php include($_SERVER[\'DOCUMENT_ROOT\'].\'/header.php\'); ?>';
    $output['page'] .= '<div class="row">';
    $output['page'] .= '<?php include($_SERVER[\'DOCUMENT_ROOT\'].\'/navigationSide.php\'); ?>';
    $output['page'] .= '<div class="col-sm-8">';
     }
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
      $output['page'] .= '<a role="button" href="javascript:window.print()">';
      $output['page'] .= '<i class="fa fa-print fa-fw hidden-xs"></i>';
      $output['page'] .= '</a>';
      $output['page'] .= '<a role="button" data-toggle="modal" data-target="#qrCode">'; 
      $output['page'] .= '<i class="fa fa-qrcode fa-fw hidden-xs"></i>';
      $output['page'] .= '</a>';
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
      } elseif (isset($block['gallery'])) {
        $galleryCount = 0;
        $setBox  = '<div class="col-sm-X">Y</div>';
        $output['page'] .= '<div class="row gallery">';
        
        foreach ($block['gallery'] as $nibble) {
          $output['page'] .= '<div class="galleryItem';
          if (strpos($nibble['format'],'medium') !== false) {
            $output['page'] .= ' galleryItem-medium';
          } elseif (strpos($nibble['format'],'large') !== false) {
            $output['page'] .= ' galleryItem-large';
          } else {
            $output['page'] .= ' galleryItem-small';
          }
          if ($galleryCount == 0 && strpos($nibble['format'],'large') == false && strpos($nibble['format'],'medium') == false) {
            $output['page'] .= ' gallerySizer';
            $galleryCount++;
          }
          $output['page'] .= '">' . $nibble['name'] .'</div>';
          
        }

        $output['page'] .= '</div>';

      } else {
        $block = str_replace('&lt;?php ','<?php ',$block); // Makes sure php in code blocks is recognised
        $output['page'] .= $block;
      }
    }
      if($_GET['tab'] == 'maths') {
       $output['page'] .= '<?php include($_SERVER[\'DOCUMENT_ROOT\'].\'/maths/footer.php\'); ?>';
     } else {
    $output['page'] .= '</div>';
    $output['page'] .= '</div>';
    $output['page'] .= '<?php include($_SERVER[\'DOCUMENT_ROOT\'].\'/footer.php\'); ?>';
      }
    }
    $directory = $pageLoc.$directory;
    if (!file_exists($directory)) {
      mkdir($directory,0777,true);
    }
    if ($hidden == 0) { // Update the directory navigation file, but only if this page is not a hidden page
      foreach ($navData['preview']['images'] as $row) {
        if ($row['type'] == 'newsimage') {
          $news = 1;
        }
      }
      if (isset($news)) {
        foreach ($navData['preview']['images'] as $key => $row) {
          if ($row['type'] == 'image') {
            unset($navData['preview']['images'][$key]);
          }
        }
        $navData['preview']['images'] = array_values($navData['preview']['images']);
      }
      $dir = scandir($pageLoc.$section);
      $dir = array_reverse($dir);
      foreach ($dir as $row) {
        if (strpos($row,'navDir-') !== false && strpos($row,'.json') !== false) {
          $fallback = $row; // This is to save the most recent one before the one you're creating, just in case anything goes wrong and the new one drops out
          $curDir = file_get_contents($pageLoc.$section.'/'.$row);
          $curDir = json_decode($curDir, true);
          break;
        }
      }
      $mainData = sheetToArray($mainID,'data/content');
      foreach ($mainData['data'][$_GET['section']] as $row) {
        if ($row['sheetname'] == $sheetData['meta']['sheetname']) {
          $newDir[$row['sheetname']] = array();
        } elseif (isset($curDir[$row['sheetname']])) {
          $newDir[$row['sheetname']] = $curDir[$row['sheetname']];
        }
      }
      $pageName = str_ireplace('[link]','',$pageName);
      $pageName = trim($pageName);
      foreach ($sheetData['data'] as $key => $row) {
        $key = str_ireplace('[link]','',$key);
        $key = trim($key);
        if ($key == $pageName) {
          $newDir[$sheetData['meta']['sheetname']][$key] = $navData;
        } elseif (isset($curDir[$sheetData['meta']['sheetname']][$key])) {
          $newDir[$sheetData['meta']['sheetname']][$key] = $curDir[$sheetData['meta']['sheetname']][$key];
        }
      }
      $timestamp = mktime();
      file_put_contents($pageLoc.$section.'/navDir-'.$timestamp.'.json', json_encode($newDir));
      foreach ($dir as $row) {
        if (strpos($row,'.json') !== false && $row !== $fallback) {
          unlink($pageLoc.$section.'/'.$row);
        }
      }
    }
    file_put_contents($directory.'/'.$fileName.'.php', $output['page']);
    if (count($images) > 0) {
      file_put_contents($directory.'/'.$fileName.'.json', json_encode($images));
      return count($images);
    }
  }
  } 
 ?>
