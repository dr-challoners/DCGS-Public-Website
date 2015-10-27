<?php

  // These functions will only work if the config parameters have been correctly set up in sheetCMS.
  // The config parameters must specify:
  //   - the path to the directory that the data is stored in
  //   - the path to the directory that any images are stored in
  //   - the sheet ID for the main content sheet, which specifies the structure for all other sheets

function parsePagesSheet($sheetKey, $pageName, $CMSdiv = 0, $titleDisplay = 1, $customModule = 'none', $showRelated = 0) {
  
  // This is the big one. This function goes through the collected array and converts each row into HTML according to the content found there.
  // sheetKey need just be the sheet key for the data (as the function must be used with the config file that gives the rest)
  // pageName is presumed to have spaces and none-url friendly characters stripped from it (it does a clean anyway).
  // Setting CMSdiv to 1 tells the function that the div with class 'sheetCMS' has already been included, and so won't be made here
  // Make sure that this div exists if you wish to use the built-in sheetCMS styles
  // If titleDisplay is set to 0 then the first line won't be an h1 header of the page's name
  // If showRelated is set to 1 and the page has tags, related pages will be shown at the bottom of the page
  // Where possible, this system creates HTML only and leaves styling to the website
  
  global $mainData, $dataSrc, $imgsSrc, $codeSrc, $sheetCMS, $colour, $pageTitle;
  
  $sheetArray = file_get_contents($dataSrc.'/'.$sheetKey.'.json');
  $sheetArray = json_decode($sheetArray, true);
  
  foreach ($sheetArray['data'] as $page => $data) {
    $pageSearch = array($pageName,$page);
    foreach ($pageSearch as $key => $term) {
      $pageSearch[$key] = strtolower($pageSearch[$key]);
      $pageSearch[$key] = str_replace('[hidden]','',$pageSearch[$key]);
      $pageSearch[$key] = trim($pageSearch[$key]);
      $pageSearch[$key] = clean($pageSearch[$key]);
    }
    if ($pageSearch[0] == $pageSearch[1]) {
      $pageArray = $data;
      break;
    }
  }
  if (!isset($pageArray)) {
    $error = 1;
    return $error;
  } else {
    $pageTitle = str_replace('[PAGE]',trim(str_ireplace(array('[hidden]','[link]'),'',$page)),$pageTitle);
    $pageTitle = formatText($pageTitle,0);
    $pageTitle = strip_tags($pageTitle);
    echo '<script type="text/javascript">document.title = "'.$pageTitle.'"</script>';
    if ($CMSdiv == 0) {
      echo '<div class="sheetCMS">'."\n\n";
    }
    
    if ($titleDisplay == 1) {
      $title = str_ireplace(array('[hidden]','[link]'),'',$page);
      $title = trim($title);
      echo '<h1>'.formatText($title,0).'</h1>'."\n\n";
    }
    
    foreach ($pageArray as $key => $row) {
      unset($imageName,$dataType,$file,$skipRow);
      
      if (!empty($row['datatype'])) {
        $dataType = strtolower(clean($row['datatype']));
      } else {
        // If the data type has been specified, then the program can format accordingly.
        // If it hasn't been specified, the program makes a basic guess as to what it might be.
        if (!empty($row['url'])) {
          $dataType = 'link';
        } else {
          $dataType = 'text';
        }
      }
      
      if ($customModule != 'none' && file_exists($sheetCMS.'/modules/custom/'.$customModule)) {
        include ($sheetCMS.'/modules/custom/'.$customModule);
        // This file should be just a switch ($dataType) function
        // The ordinary functions below will still process unless you set $skipRow
      }
      if (!isset($skipRow) && $row['format'] != 'hidden') {
        switch ($dataType) {

          case 'text':
          default:
            $content = formatText($row['content']);
            if (strpos($row['format'],'style[') !== false || strpos($row['format'],'div[') !== false) {
              $styles = explode(',',$row['format']);
              $styles = array_reverse($styles);
              foreach ($styles as $style) {
                $style = trim($style);
                if (strpos($style,'style[') !== false) {
                  $style = str_replace(array('style[',']'),'',$style);
                  $content = str_replace('<p>','<p style="'.$style.'">',$content);
                }
                if (strpos($style,'div[') !== false) {
                  $style = str_replace(array('div[',']'),'',$style);
                  $content = '<div style="'.$style.'">'.$content.'</div>';
                }
              }
            }
            echo $content;
          break;

          case 'link':
          case 'file':
          case 'email':
            // Redefining url and dataType as separate variables allows us to make modifications without losing the original info
            $url = $row['url'];
            $type = $dataType;
            if ($type == 'email') {
              // Add the 'mailto:' component and some simple robot baffling
              $address = ""; $i = 0;
              for ($i = 0; $i < strlen($url); $i++) { $address .= '&#'.ord($url[$i]).';'; }
              $url = "mailto:".$address;              
            }
            echo '<p class="link';
              if ($type != 'link') {
                echo ' '.$type;
              } elseif (strpos($url,"twitter.com") !== false) {
                echo ' twitter';
              }
            echo '">';
              echo '<a href="'.$url.'">';
                if (!empty($row['content'])) {
                  echo formatText($row['content'],0);
                } else {
                  echo $row['url'];
                }
              echo '</a>';
            echo '</p>';
          break;

          case 'image':
            include ($sheetCMS.'/modules/imageDisplay.php');
          break;
          
          case 'video':
          case 'youtube':
            // YouTube videos
            if (strpos($row['url'],"youtu.be") !== false) {
              $id = strpos($row['url'],"e/");
              $id = substr($row['url'],$id+2);
            } elseif (strpos($row['url'],"youtube") !== false && strpos($row['url'],"watch")) {
              $id = strpos($row['url'],"v=");
              $id = substr($row['url'],$id+2);
            } elseif (strpos($row['url'],"youtube") !== false && strpos($row['url'],"edit")) {
              $id = strpos($row['url'],"d=");
              $id = substr($row['url'],$id);
            }
            makeiFrame("//www.youtube-nocookie.com/embed/$id?rel=0",'video',$row['content']);
          break;
          
          case 'audio':
          case 'soundcloud':
            // Currently only SoundCloud audio embedding is supported (and this is unlikely to change without good reason)
            if (strpos($row['url'],'soundcloud') !== false && substr_count($row['url'],"/") == 4) {
              $sc = file_get_contents('http://api.soundcloud.com/resolve.json?url='.$row['url'].'&client_id=59f4a725f3d9f62a3057e87a9a19b3c6');
              $sc = json_decode($sc);
              $id = $sc->id;
              if (isset($colour)) { $colour = strtolower(str_replace("hex","",$colour)); } else { $colour = "666666"; }
              $iFrameContent  = "https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/$id&amp;color=$colour&amp;auto_play=false&amp;hide_related=false&amp;show_artwork=false";
              makeiFrame($iFrameContent,'soundcloud',$row['content']);
            }
          break;
          
          case 'form':
            // Google Forms (possibly other forms later, but for now...)
            if (strpos($row['url'],"docs.google") !== false && strpos($row['url'],"forms") !== false) {
              $id = strpos($row['url'],"d/");
              $id = substr($row['url'],$id+2);
              $id = explode("/",$id)[0];
              makeiFrame("https://docs.google.com/forms/d/$id/viewform?embedded=true",'form',$row['content']);
            }
          break;
          
          case 'tags':
          case 'tag':  // The instruction is to write 'tags' for this datatype, but this is a failsafe
          // This doesn't actually output yet - that's the next step!
            if (strpos($row['format'],'related') !== false || $showRelated == 1) {
              $matched = array();
              $related = array();
              $highRank = 0;
              $tags = explode(',',$row['content']);
              foreach ($mainData['data']['tags'] as $key => $data) {
                foreach ($tags as $tag) {
                  if (strtolower(trim($tag)) == strtolower($key)) {
                    foreach ($mainData['data']['tags'][$key] as $id => $info) {
                      if (clean($info[2]) != clean($pageName)) {
                        if (isset($matched[$id])) {
                          $rank = $matched[$id]['rank'];
                          $rank++;
                        } else {
                          $rank = 1;
                        }

                        $matched[$id] = $info;
                        $matched[$id]['rank'] = $rank;
                        if ($highRank < $rank) {
                          $highRank = $rank;
                        }
                      }
                    }
                  }
                }
              }
              $matched = array_reverse($matched);
              for ($r = $highRank; $r > 0; $r--) {
                foreach ($matched as $match) {
                  if ($match['rank'] == $r) {
                    $returned[] = array('section' => $match[0],'sheet' => $match[1],'page' => $match[2]);
                  }
                }
              }
              if (count($returned) < 4) {
                unset ($returned);
              }
              //if (isset($returned)){view ($returned);}
            }
          break;
          
          case 'table':
            if (strpos($row['url'],'google.com/spreadsheets') !== false) {
              $cutoff = strpos($row['url'],'spreadsheets/d/');
              $cutoff = $cutoff+15;
              $sheetID = substr($row['url'],$cutoff);
              $sheetID = explode('/',$sheetID)[0];
              $tableArray = sheetToArray($sheetID,$dataSrc,1);
              foreach ($tableArray['data'] as $table => $rows) {
                $top = 1;
                if (strpos($row['format'],'title') !== false && strtolower($table) != '[untitled]') {
                  echo '<h2>'.$table.'</h2>';
                }
                echo '<table>';
                foreach ($rows as $line) {
                  if ($top == 1) {
                    echo '<tr>';
                      foreach ($line as $heading => $cell) {
                        echo '<th><h3>';
                          if ($heading[0] != '_') {
                            echo ucwords($heading);
                            $top = 0;
                          } else {
                            echo trim(str_ireplace('[empty]','',$cell));
                            $top = 2;
                          }
                        echo '</h3></th>';
                      }
                    echo '</tr>';
                  }
                  if ($top == 0) {
                    echo '<tr>';
                      foreach ($line as $cell) {
                        echo '<td><p>'.trim(str_ireplace('[empty]','',$cell)).'</p></td>';
                      }
                    echo '</tr>';
                  } else {
                    $top = 0;
                  }
                }
                echo '</table>';
              }
            }
          break;
          
          case 'code':
            if (!file_exists($codeSrc)) {
              mkdir($codeSrc,0777,true);
            }
            file_put_contents($codeSrc.'/'.makeID($row['content']),$row['content']);
            include($codeSrc.'/'.makeID($row['content']));
          break;

        }
      }

      echo "\n\n"; 
    }
        
    if ($CMSdiv == 0) {
      echo '</div>'."\n\n";
    }
    
  }
  
}

function makeiFrame($iFrameContent, $iFrameClass = '', $iFrameTitle = '') {
  $line = '';
  $iFrameID = makeID($iFrameContent);
  if (!empty($iFrameClass)) {
    $iFrameName = $iFrameClass;
  } else {
    $iFrameName = $iFrameID;
  }
  if (!empty($iFrameTitle)) {
    $line .= '<div class="simpleOpenClose" name="'.$iFrameName.'" id="'.$iFrameID.'">'."\n";
    $line .= '<p class="link '.$iFrameName.'"><a href="javascript:simpleOpenClose(\''.$iFrameID.'\',\''.$iFrameName.'\')">';
    $line .= formatText($iFrameTitle,0).'</a></p>'."\n";
  }
  $line .= '<iframe ';
  if (!empty($iFrameClass)) {
    $line .= 'class="'.$iFrameClass.'" ';
  }
  $line .= 'src="'.$iFrameContent.'" ';
  $line .= 'frameborder="no" allowfullscreen></iframe>';
  if (!empty($iFrameTitle)) {
    $line .= "\n".'<p class="close"><a href="javascript:simpleOpenClose(\''.$iFrameID.'\',\''.$iFrameName.'\')">&#x2715; Close</a></p>'."\n".'</div>';
  }
  echo $line;
}

function fetchImage($imageURL,$imageName) {
  
  global $imgsSrc;
  
  if (!file_exists($imgsSrc.'/'.$imageName)) {
    if (strpos($imageURL,'drive.google.com') !== false) {
      if (strpos($imageURL,'/file/d/') !== false) {
        $file = strpos($imageURL,'/file/d/');
      } elseif (strpos($imageURL,'open?id=') !== false) {
        $file = strpos($imageURL,'open?id=');
      }
      if (isset($file)) {
        $file = $file+8;
        $file = substr($imageURL,$file);
        $file = explode('/',$file)[0];
        $file = 'http://drive.google.com/uc?export=view&id='.$file;
        if (@file_get_contents($file) === false) {
          // This means if the image doesn't fetch, it just drops out (hopefully)
          unset ($file);
        }
      }
    } elseif (isImage($imageURL)) {
      $file = $imageURL;
    }
    if (isset($file)) {
      $file = file_get_contents($file);
      if (!file_exists($imgsSrc)) {
        mkdir($imgsSrc,0777,true);
      }
      file_put_contents($imgsSrc.'/'.$imageName,$file);
    } else { return 'ERROR'; }
  }
}

function navigatePagesSheet($sheetsToNavigate, $variablesAs = '?section=[SECTION]&sheet=[SHEET]&page=[PAGE]', $dropdownMenus = '') {
  
  global $dataSrc;
  
  // variablesAs gives control over the url given by each link in the navigation menu, particularly useful for URL re-writing
  // It can take any format as long as [SECTION], [SHEET] and [PAGE] are present
  
  foreach ($sheetsToNavigate as $id => $sheet) {
    if (isset($sheet['sheetname'])) {
    if (!empty($dropdownMenus)) {
      echo '<div class="simpleOpenClose';
      if (isset($_GET['sheet']) && clean($sheet['sheetname']) == clean($_GET['sheet'])) {
        echo ' open';
      }
      echo '" id="'.$id.'" name="'.$dropdownMenus.'">'."\n";
      echo '<h2>';
        echo '<a href="javascript:simpleOpenClose(\''.$id.'\',\''.$dropdownMenus.'\')">';
          echo formatText($sheet['sheetname'],0);
        echo '</a>';
      echo '</h2>'."\n";
    } else {
      echo '<h2>'.formatText($sheet['sheetname'],0).'</h2>'."\n";
    }
    echo '<ul>'."\n";
    foreach ($sheet['pages'] as $page) {
      if (strpos(strtolower($page),'[hidden]') === false) {
        if (strpos(strtolower($page),'[link]') === false) {
          $pageURL = str_replace('[PAGE]',clean($page),$variablesAs);
          $pageURL = str_replace('[SHEET]',clean($sheet['sheetname']),$pageURL);
          $pageURL = str_replace('[SECTION]',clean($sheet['section']),$pageURL);
        } else {
          $linkSheet = file_get_contents($dataSrc.'/'.$id.'.json');
          $linkSheet = json_decode($linkSheet, true);
          if (isset($linkSheet['data'][$page][2]['url'])) {
            $pageURL = $linkSheet['data'][$page][2]['url'];
          } else {
            $pageURL = str_replace('[PAGE]',clean($page),$variablesAs);
            $pageURL = str_replace('[SHEET]',clean($sheet['sheetname']),$pageURL);
            $pageURL = str_replace('[SECTION]',clean($sheet['section']),$pageURL);
          }
          $page    = trim(str_ireplace('[link]','',$page));
        }
        echo '<li>';
          echo '<a href="'.$pageURL.'">'.formatText($page,0).'</a>';
        echo '</li>'."\n";
      }
    }
    echo '</ul>'."\n";
    if (!empty($dropdownMenus)) {
      echo '</div>'."\n";
    }
    echo "\n";
    }
  }
  
}

?>