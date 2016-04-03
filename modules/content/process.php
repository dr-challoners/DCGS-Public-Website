<?php

function parsePagesSheet($sheetKey, $pageName, $share = 0, $tools = 1, $pageArray = false) {
  
  // This function goes through the collected array and converts each row into HTML according to the content found there
  // It is stored as a single string, to which some content like author credits is then added before the content is returned
  // sheetKey need just be the sheet key for the data (as the function must be used with the config file that gives the rest)
  // pageName is presumed to have spaces and none-url friendly characters stripped from it (it does a clean anyway).
  // If share is set to 1 then a Twitter link is added to the page
  // If tools is set to 0, then the page won't display print and QR code icons
  // If an appropriate $pageArray is given, then finding the pageArray is bypassed and it uses what you give it.
  
  // This system creates Bootstrap standard HTML markup, and does not include any additional styles
  
  global $mainData, $userID, $pageURL; // $pageURL is just for getting the correct QR code
  
  if ($pageArray == false) {
    $sheetArray = file_get_contents('data/content/'.$sheetKey.'.json');
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
  }
  if ($pageArray == false) {
    $error = 1;
    return $error;
  } else { // This is where the magic happens
    
    $output = array();
    
    foreach ($pageArray as $key => $row) {
      unset($imageName,$dataType,$file,$skipRow,$content,$set);
      $row['format'] = strtolower($row['format']); 
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
       
      // Process news article dataTypes
      // Unless $skipRow is set on these functions, the ordinary functions below will still process afterwards
      include ('newsDataTypes.php');

      if (!isset($skipRow) && $row['format'] != 'hidden') {
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

          case 'text':
          default:
          case 'maths':
          case 'math':
            unset($button,$textID);
            if (!empty($row['format'])) {
              $format = explode(' ',$row['format']);
            } else {
              $format = array();
            }
            unset ($block);
            if (in_array('left-block',$format) || in_array('right-block',$format)) {
              if (in_array('left-block',$format)) {
                $place = 'pull-left';
              }
              if (in_array('right-block',$format)) {
                $place = 'pull-right';
              }
              $block = '<div class="col-sm-5 '.$place.'">';
            }
            if (in_array('dropdown',$format)) {
              $textID = 'text'.mt_rand();
              $buttonText = explode(PHP_EOL,$row['content']);
              $button = '<a class="barLink btn btn-default btn-block hidden-print" role="button" data-toggle="collapse" href="#'.$textID.'" aria-expanded="false" aria-controls="collapseExample">';
              if (in_array('quote',$format)) {
                $button .= '<i class="fa fa-comment"></i>';
              } else {
                $button .= '<i class="fa fa-align-left"></i>';
              }
              $button .= array_shift($buttonText);
              $button .= '</a>';
              $content = array();
              foreach ($buttonText as $row) {
                if (!empty($row)) {
                  $content[] = $row;
                }
              }
              $content = implode("\n\n",$content);
            } else {
              $content = $row['content'];
            }
            $content = formatText($content);
            if (!in_array('quote',$format)) {              
              $class = array();
              if (in_array('right',$format)) {
                $class[] = 'text-right';
              } elseif (in_array('centred',$format) || in_array('centre',$format) || in_array('center',$format) || in_array('centered',$format)) {
                $class[] = 'text-center';
              } elseif (in_array('justify',$format) || in_array('justified',$format)) {
                $class[] = 'text-justify';
              }
              if (in_array('lead',$format)) {
                $class[] = 'lead';
              }
              if (!empty($class)) {
                $class = implode(' ',$class);
                // preg_replace would be better here, if ever you can be bothered
                $oldTags = array(
                  '<p>',
                  '<h1>',
                  '<h2>',
                  '<h3>',
                  '<h4>',
                  '<h5>',
                  '<h6>',
                );
                $newTags = array(
                  '<p class="'.$class.'">',
                  '<h1 class="'.$class.'">',
                  '<h2 class="'.$class.'">',
                  '<h3 class="'.$class.'">',
                  '<h4 class="'.$class.'">',
                  '<h5 class="'.$class.'">',
                  '<h6 class="'.$class.'">',
                );
                $content = str_replace($oldTags,$newTags,$content);
              }
              if (in_array('highlight',$format) && !in_array('dropdown',$format)) {
                $content = '<div class="row highlightText"><div class="col-xs-12">'.$content.'</div></div>';
              }
            } else {
              $content = '<blockquote>'.$content.'</blockquote>';
              $content = str_replace(array('[',']'),array('<footer>','</footer>'),$content);
              if (in_array('right',$format)) {
                $content = str_replace('<blockquote>','<blockquote class="blockquote-reverse">',$content);
              }
            }
            if (isset($button)) {
              $content = $button.'<div class="collapse" id="'.$textID.'">'.$content.'</div>';
            }
            if (isset($block)) {
              $content = $block.$content.'</div>';
            }
            if (isset($set) && !isset($block)) {
              $output['content'][$set]['set'][] = $content;
            } else {
              $output['content'][] = $content;
            }
          break;
            
          case 'title':
            $output['title'] = formatText(ltrim($row['content'],'#'),0);
          break;

          case 'link':
          case 'file':
          case 'email':
            // Redefining url and dataType as separate variables allows us to make modifications without losing the original info
            // Some fallbacks in case users put details in the wrong place
            if (!empty($row['url'])) {
              $url = $row['url'];
            } else {
              $url = $row['content'];
            }
            if (!empty($url)) {
              switch ($dataType) {
                case 'link':
                  // Gives brand icons to some websites
                  if (strpos($url,"twitter.com") !== false) {
                    $linkIcon = 'twitter';
                  } else {
                    $linkIcon = 'link';
                  }
                break;
                case 'file':
                  $linkIcon = 'file';
                break;
                case 'email':
                  $linkIcon = 'envelope-o';
                  // Add the 'mailto:' component and some simple robot baffling
                  $address = ""; $i = 0;
                  for ($i = 0; $i < strlen($url); $i++) { $address .= '&#'.ord($url[$i]).';'; }
                  $url = "mailto:".$address; 
                break;
              }
              $content = '<a target="'.mt_rand().'" class="barLink btn btn-default btn-block" href="'.$url.'" role="button">';
                $content .= '<i class="fa fa-'.$linkIcon.'"></i>';
                if (!empty($row['content'])) {
                  $content .= formatText($row['content'],0);
                } else {
                  $content .= $row['url'];
                }
              $content .= '</a>';
              $output['content'][] = $content;
            }
          break;

          case 'image':
            include ('modules/content/imageDisplay.php');
          break;
          
          case 'video':
          case 'youtube':
            unset ($vID,$pID,$time);
            if (strpos($row['url'],'youtube.com') !== false || strpos($row['url'],'youtu.be') !== false) { // YouTube
              if (strpos($row['url'],'v=') !== false) { // There's a video ID (this will be most of them, though you can get playlists without
                $vID = substr($row['url'],strpos($row['url'],'v=')+2,11); // All video IDs seem to be 11 characters long
              } elseif (strpos($row['url'],'youtu.be/') !== false) { // Short URLs have the video ID just past the short domain
                $vID = substr($row['url'],strpos($row['url'],'youtu.be/')+9,11);
              }
              if (strpos($row['url'],'list=') !== false) { // We're looking at a playlist of videos
                $pID = substr($row['url'],strpos($row['url'],'list=')+5); // Playlist IDs don't have a fixed length
                $pID = explode('&',$pID)[0]; // In case there's additional parameters
              }
              /*
              $src = $row['url'];
              $src = str_replace('youtu.be','youtube.com',$src);
              $src = str_replace('youtube.com/','youtube.com/embed/',$src); */
              if (strpos($row['url'],'?t=') !== false) {
                $time = explode('?t=',$row['url'])[1];
                //$time = $time[1];
                //$src  = $src[0];
                $time = rtrim($time,'s');
                if (strpos($time,'m') !== false) {
                  $time = explode('m',$time);
                  $time = $time[0]*60+$time[1];
                }
                //echo $time.'<br>';
                //$src = $src.'?start='.$time;
              }
              $src = 'https://www.youtube.com/embed/';
              if (isset($vID)) {
                $src .= $vID;
                if (isset($time)) {
                  $src .= '?start='.$time;
                } elseif (isset($pID)) {
                  $src .= '?list='.$pID;
                }
              } elseif (isset($pID)) {
                $src .= 'playlist?list='.$pID;
              }
            } elseif (strpos($row['url'],'drive.google.com') !== false) { // gDrive
              $id = explode('?id=',$row['url'])[1];
              $id = explode('&',$id)[0]; // Just to tidy up
              $src = 'https://drive.google.com/a/challoners.org/file/d/'.$id.'/preview';
              // It doesn't matter that challoners.org is specified here:
              // if the file is from somewhere else it will figure it out, albeit marginally slower
            } elseif (strpos($row['url'],'vimeo.com') !== false) { // Vimeo
              $id = explode('/',$row['url']);
              $id = array_pop($id);
              $src = 'https://player.vimeo.com/video/'.$id.'?color=649DE8&title=0&byline=0&badge=0&portrait=0';
            } else {
              $src = ''; // This will just break the iFrame, but it means there won't be php errors on the page
            }
            $content = makeiFrame($src,'video',$row['content'],$row['format']);
            if (!isset($set)) {
              $output['content'][] = $content;
            } else {
              $output['content'][$set]['set'][] = $content;
            }
          break;
          
          case 'audio':
          // Following extra cases are insurance should people forget the correct datatype
          case 'soundcloud':
          case 'audioboom':
            if (strpos($row['url'],'soundcloud') !== false) {
              $sc = file_get_contents('http://api.soundcloud.com/resolve.json?url='.$row['url'].'&client_id=59f4a725f3d9f62a3057e87a9a19b3c6');
              $sc = json_decode($sc);
              $id = $sc->id;
              if ($sc->kind == 'playlist') {
                $outputType = 'playlists';
                $boxType    = 'audioPlaylist';
              } else {
                $outputType = 'tracks';
                $boxType    = 'audio';
              }
              $src  = 'https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/'.$outputType.'/'.$id.'&amp;color=2358A3&amp;auto_play=false&amp;hide_related=false&amp;show_artwork=false';
              $output['content'][] = makeiFrame($src,$boxType,$row['content'],$row['format']);
            }
          elseif (strpos($row['url'],'audioboom') !== false) {
            if (strpos($row['url'],'playlists') === false) {
              $src = explode('boos/',$row['url'])[1];
              $src = explode('?',$src)[0];
              $src = str_replace('.embed','',$src);
              $src = '//embeds.audioboom.com/boos/'.$src.'/embed/v3?link_color=%23173F7A&amp;image_option=none';
              $output['content'][] = makeiFrame($src,'audio',$row['content']);
            } else {
              $src = explode('playlists/',$row['url'])[1];
              $src = str_replace('.embed','',$src);
              $src = '//embeds.audioboom.com/publishing/playlist/v4?bg_fill_col=%23f5f5f5&amp;boo_content_type=playlist&amp;data_for_content_type='.$src.'&amp;image_option=none&amp;link_color=%23173F7A&amp;src=https%3A%2F%2Fapi.audioboom.com%2Fplaylists%2F'.$src;
              $output['content'][] = makeiFrame($src,'audioPlaylist',$row['content']);
            }
          }
          break;
          
          case 'form':
            // Google Forms (possibly other forms later, but for now...)
            if (strpos($row['url'],"docs.google") !== false && strpos($row['url'],"forms") !== false) {
              $id = strpos($row['url'],'d/');
              $id = substr($row['url'],$id+2);
              $id = explode('/',$id)[0];
              $output['content'][] = makeiFrame('https://docs.google.com/forms/d/'.$id.'/viewform?embedded=true','form',$row['content']);
            }
          break;
            
            // https://docs.google.com/document/d/1usHGVrjYkKvAIvoQ48gw7Hd96Q_Bk8mC-Neu-YggpmY/pub?embedded=true
            
          case 'gdocs': case 'docs': case 'gdoc': case 'doc':
          case 'gsheets': case 'sheets': case 'gsheet': case 'sheet':
          case 'gslides': case 'slides': case 'gslide': case 'slide':
            if (strpos($row['url'],"docs.google") !== false) {
              unset($docType);
              if (strpos($row['url'],"document") !== false) {
                $docType = 'document';
              } elseif (strpos($row['url'],"presentation") !== false) {
                $docType = 'presentation';
              } elseif (strpos($row['url'],"spreadsheets") !== false) {
                $docType = 'spreadsheets';
              }
              if (isset($docType)) {
                $id = strpos($row['url'],'d/');
                $id = substr($row['url'],$id+2);
                $id = explode('/',$id)[0];
                $url = 'https://docs.google.com/'.$docType.'/d/'.$id.'/preview';
                $output['content'][] = makeiFrame($url,$docType,$row['content']);
              }
            }
          break;
          
          case 'tags':
          case 'tag':
            // Tags are deprecated, so should be ignored.
          break;
          
          case 'table':
            if (strpos($row['url'],'google.com/spreadsheets') !== false) {
              $cutoff = strpos($row['url'],'spreadsheets/d/');
              $cutoff = $cutoff+15;
              $sheetID = substr($row['url'],$cutoff);
              $sheetID = explode('/',$sheetID)[0];
              $tableArray = sheetToArray($sheetID,'data/content',1);
              $processedTable = array();
              foreach ($tableArray['data'] as $table => $rows) {
                $processedTable[$table] = array();
                foreach ($rows as $line) {
                  if (!isset($processedTable[$table]['th'])) {
                    // Define the table headings
                    // First check to see if all of the keys are blank (begin with _): if they are, the headings will be the content, otherwise the headings will be the keys.
                    $blanks = ''; $tableKeys = array();
                    foreach ($line as $key => $cell) {
                      $blanks .= $key[0];
                      $tableKeys[] = $key;
                    }
                    $blanks = str_replace('_','',$blanks);
                    if ($blanks == '') {
                      foreach ($line as $key => $cell) {
                        if ($cell[0] != '_') {
                          $processedTable[$table]['th'][$key] = $cell;
                        } else {
                          $processedTable[$table]['th'][$key] = '';
                        }
                      }
                      $r = 1;
                    } else {
                      foreach ($line as $key => $cell) {
                        if ($key[0] != '_') {
                          $processedTable[$table]['th'][$key] = ucwords($key);
                        } else {
                          $processedTable[$table]['th'][$key] = '';
                        }
                      }
                      foreach ($line as $key => $cell) {
                        $processedTable[$table][1][$key] = $cell;
                      }
                      $r = 2;
                    }
                    
                  } else {
                    // Now get on with populating the rows
                    foreach ($tableKeys as $key) {
                      if (isset($line[$key])) {
                        $processedTable[$table][$r][$key] = $line[$key];
                      } else {
                        $processedTable[$table][$r][$key] = '';
                      }
                    }
                    $r++;
                  }
                }
              }
              // Now display the table
              $content = '';
              if (strpos($row['format'],'tab') !== false) {
                $tabList = '';
              }
              unset($active);
              foreach ($processedTable as $table => $rows) {
                unset($top);
                if (strpos($row['format'],'tab') !== false) {
                  $tabList .= '<li role="presentation"';
                    if (!isset($active)) {
                      $tabList .= ' class="active"';
                    }
                  $tabList .= '><a href="#'.clean($table).'" aria-controls="'.clean($table).'" role="tab" data-toggle="tab"><h3>'.$table.'</h3></a></li>';
                  $content .= '<div role="tabpanel" class="tab-pane';
                    if (!isset($active)) {
                      $content .= ' active';
                      $active = 1;
                    }
                  $content .= '" id="'.clean($table).'">';
                }
                if (strpos($row['format'],'title') !== false && strpos($row['format'],'tab') === false && $table[0] != '_') {
                  $content .= '<h3>'.$table.'</h3>';
                }
                $content .= '<table class="table table-hover table-condensed">';
                foreach ($rows as $line) {
                  if (!isset($top)) {
                    $content .= '<thead><tr>';
                    foreach ($line as $cell) {
                      $content .= '<th><h4>'.$cell.'</h4></th>';
                    }
                    $content .= '</tr></thead>';
                    $top = 1;
                  } else {
                    $content .= '<tr>';
                      foreach ($line as $cell) {
                        $content .= '<td>'.$cell.'</td>';
                      }
                    $content .= '</tr>';
                  }
                }
                $content .= '</table>';
                if (strpos($row['format'],'tab') !== false) {
                  $content .= '</div>';
                }
              }
              if (strpos($row['format'],'tab') !== false) {
                $tabList = '<ul class="nav nav-tabs tableTabs" role="tablist">'.$tabList.'</ul>';
                $content = $tabList.'<div class="tab-content">'.$content.'</div>';
              }
              $output['content'][] = $content;
            }
          break;
          
          case 'code':
            if (!file_exists('data/code')) {
              mkdir('data/code',0777,true);
            }
            file_put_contents('data/code/'.makeID($row['content']).'-'.clean($pageName),$row['content']);
            $output['content'][]['code'] = 'data/code/'.makeID($row['content']).'-'.clean($pageName);
          break;
          
          case 'geogebra':
            if (strpos($row['url'],'geogebra.org') !== false) {
              $id = explode('id/',$row['url'])[1];
              // http://tube.geogebra.org/material/simple/id/88993
              // rc  - right click, zooming, keyboard editing
              // ai  - input bar
              // sdz - pan and zoom
              // smb - show menu
              // stb - show toolbar (menu must be true)
              // ld  - label dragging
              // sri - show reset icon
              $src = 'https://www.geogebra.org/material/iframe/id/'.$id.'/width/640/height/480/rc/false/ai/false/sdz/true/smb/false/stb/false/stbh/true/ld/false/sri/false/at/auto';
              $output['content'][] = makeiFrame($src,'geogebra');
              }
          break;
            
          case 'wolframalpha':
          case 'wolfram-alpha':
            if (strpos($row['url'],'wolframalpha.com/widget') !== false) {
              $id = explode('id=',$row['url'])[1];
              $output['content'][] = '<script type="text/javascript" id="WolframAlphaScript'.$id.'" src="//www.wolframalpha.com/widget/widget.jsp?id='.$id.'&theme=orange&output=popup"></script>';
            }
          break;

        }
      }
      if (!isset($output['title'])) {
        if (isset($page)) {
          $title = str_ireplace(array('[hidden]','[link]'),'',$page);
          $title = trim($title);
          $output['title'] = formatText($title,0);
        } else {
          $output['title'] = $pageName;
        }
      }
    }
    // Title and article info
    echo '<div class="row articleInfo">';
      echo '<div class="col-xs-10">';
        echo '<h1>'.$output['title'].'</h1>';
        if (isset($output['info']['date'])) {
          echo '<p><strong>'.$output['info']['date'].'</strong></p>';
        }
        function rollCredits($info, $display) {
          echo '<p>'.$display.': ';
          $c = count($info);
          for ($i = 0; $i < $c; $i++) {
            echo $info[$i];
            if ($i < $c-2) {
              echo ', ';
            } elseif ($i < $c-1) {
              echo ' and ';
            }
          }
          echo '</p>';
        }
        foreach (array('writing','photos','recording','editing') as $creditType) {
          if (isset($output['info'][$creditType])) {
            if ($creditType == 'photos') {
              $displayCredit = 'Photography';
            } else {
              $displayCredit = ucfirst($creditType);
            }
            rollCredits($output['info'][$creditType],$displayCredit);
          }
        }
      echo '</div>';
      echo '<div class="col-xs-2 hidden-print">';
        if ($share == 1) {
          echo '<a role="button" class="twitterLink" href="https://twitter.com/intent/tweet?url=http://www.challoners.com&amp;text='.urlencode(strip_tags($output['title'])).'&amp;via=ChallonersNews">';
            echo '<i class="fa fa-twitter fa-fw"></i>';
          echo '</a>';
        }
        if ($tools == 1) {
          echo '<a role="button" href="javascript:window.print()">';
            echo '<i class="fa fa-print fa-fw hidden-xs"></i>';
          echo '</a>';
          echo '<a role="button" data-toggle="modal" data-target="#qrCode">'; 
            echo '<i class="fa fa-qrcode fa-fw hidden-xs"></i>';
          echo '</a>';
        }
      echo '</div>';
    echo '</div>';
    // QR code pop-up and suggestions for use
    echo '<div class="modal fade" id="qrCode" tabindex="-1" role="dialog" aria-labelledby="QR code for page">';
      echo '<div class="modal-dialog" role="document">';
        echo '<div class="modal-content">';
          echo '<div class="modal-body qrCode_display">';
            echo '<img class="img-responsive" src="https://chart.googleapis.com/chart?cht=qr&chs=540x540&chl='.$pageURL.'&choe=UTF-8" />';
            echo '<p>QR code for this page. Right click on the image and select \'Copy image\' or \'Save image as...\' to take a copy of this QR code. Add it to a worksheet for students to scan and jump immediately to this page. Or just display this pop-up box on your classroom projector.</p>';
          echo '</div>';
        echo '</div>';
      echo '</div>';
    echo '</div>';
    // Article content
    foreach ($output['content'] as $block) {
      if (isset($block['code'])) {
        include($block['code']);
      } elseif (isset($block['set'])) {
        $setHold = 0;
        $setBox  = '<div class="col-sm-X">Y</div>';
        echo '<div class="row setDisplay">';
          while (count($block['set']) > 0) {
            if ($setHold <= 0) {
              switch (count($block['set'])) {
                case 1:
                case 2:
                case 3:
                case 4:
                case 6:
                  $setSize = 12/count($block['set']);
                  $setHold = count($block['set']);
                break;
                case 5:
                case 9:
                case 10:
                case 11:
                  $setSize = 'fifths';
                  $setHold = 5;
                break;
                case 7:
                case 8:
                  $setSize = 3;
                  $setHold = 4;
                break;
                default : // 12 or more
                  $setSize = 2;
                  $setHold = 6;
                break;
              }
            }
            echo str_replace(array('X','Y'),array($setSize,array_shift($block['set'])),$setBox);
            $setHold--;
          }
        echo '</div>';
      } else {
        echo $block;
      }
    }
    // Also keep track of the number of times each page has been viewed
    if (file_exists($_SERVER['DOCUMENT_ROOT'].'/data/stats/hits'.date("Ym").'.json')) {
      $pageHits = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/data/stats/hits'.date("Ym").'.json');
      $pageHits = json_decode($pageHits, true);
    } else {
      $pageHits = array();
    }
    $pageHits['data'][$pageURL][] = array('timestamp' => time(), 'userID' => $userID);
    if (!file_exists('data/stats')) {
      mkdir('data/stats',0777,true);
    }
    file_put_contents('data/stats/hits'.date("Ym").'.json', json_encode($pageHits));
  } 
}


                
?>