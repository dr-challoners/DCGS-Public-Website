<?php $section = 'intranet'; include('header.php'); ?>
<div class="row">
<?php
if (isset($_GET['user'])) {
  
function makeIntranetLinks($sheetKey,$section = 'block') {
  
  // Use the stored spreadsheet array to generate the links list
  $lists = sheetToArray($sheetKey,'data/intranet',6);
  $headings = array_keys($lists['data']);
  
  $c = 0;
  foreach ($headings as $list) {
    // On large screens, generate the right-hand section button along with the left-hand one: this means the links for both sections appear under both headings, rather than the right-hand heading jumping down the page when the left-hand list is opened.
    echo '<div class="panel">';
    echo '<div class="panel-heading';
      if ($c%2 == 1) { echo ' visible-xs-block visible-sm-block'; }
    echo '" role="tab" id="heading-'.$section.'-'.makeID($list,1).'">';
        echo '<a class="collapsed" role="button" data-toggle="collapse" data-parent="#intranetMenu" href="#collapse-'.$section.'-'.makeID($list,1).'" aria-expanded="true" aria-controls="collapse-'.$section.'-'.makeID($list,1).'">';
          echo $list;
        echo '</a>';
    echo '</div>';
    $cn = $c+1;
    if ($c%2 == 0 && isset($headings[$cn])) {
      echo '<div class="panel-heading hidden-xs hidden-sm" role="tab" id="heading-'.$section.'-'.makeID($headings[$cn],1).'">';
          echo '<a class="collapsed" role="button" data-toggle="collapse" data-parent="#intranetMenu" href="#collapse-'.$section.'-'.makeID($headings[$cn],1).'" aria-expanded="false" aria-controls="collapse-'.$section.'-'.makeID($headings[$cn],1).'">';
            echo $headings[$cn];
          echo '</a>';
      echo '</div>';
    
    }
      // Now for the links lists themselves
      echo '<div id="collapse-'.$section.'-'.makeID($list,1).'" class="links collapse" role="tabpanel" aria-labelledby="heading-'.$section.'-'.makeID($list,1).'">';
        echo '<div class="row">';
          foreach ($lists['data'][$list] as $link) {
            if (!empty($link['title']) || !empty($link['url'])) {
              $special = strtolower($link['special']);
              $special = str_replace(' ','',$special);
              $special = explode(',',$special);
              if (!empty($link['title'])) {
                $title = $link['title'];
              } else {
                $title = str_replace(array('http://','https://'),'',$link['url']);
              }
              unset($url,$notes);
              if (!empty($link['url'])) {
                $url = $link['url'];
                $url = '<a href="'.$url.'">'.$title.'</a>';
                if ((strpos($url,'challoners.com') === false && strpos($url,'://') !== false) || strpos($url,'challoners.com/learn') !== false) {
                  $url = str_replace('<a ','<a target="'.mt_rand().'" ',$url);
                }
              }
              if (!empty($link['notes'])) {
                $notes = formatText($link['notes']);
              }
              if (in_array('linebreak',$special)) {
                echo '</div><div class="row">';
              }
              if (!isset($url) && !isset($notes)) {
                echo '<h3 class="col-xs-12">'.$title.'</h3>';
              } else {
                if (isset($notes) && strlen($notes) > 100) {
                  echo '<div class="col-xs-12">';
                } else {
                  echo '<div class="col-sm-6">';
                }
                if (!isset($url)) {
                  echo '<p>'.$title.'</p>';
                } else {
                  if (in_array('qrcode',$special)) {
                    echo '<p>';
                      echo $url;
                      echo '<span class="pull-right hidden-xs"><a role="button" data-toggle="modal" data-target="#'.makeID($link['url']).'"><i class="fa fa-qrcode"></i></a></span>';
                    echo '</p>';
                    // QR code pop-up and suggestions for use
                    echo '<div class="modal fade" id="'.makeID($link['url']).'" tabindex="-1" role="dialog" aria-labelledby="QR code for page">';
                      echo '<div class="modal-dialog" role="document">';
                        echo '<div class="modal-content">';
                          echo '<div class="modal-body qrCode_display">';
                            echo '<img class="img-responsive" src="https://chart.googleapis.com/chart?cht=qr&chs=540x540&chl='.$link['url'].'&choe=UTF-8" />';
                            echo '<p>QR code for this link. Right click on the image and select \'Copy image\' or \'Save image as...\' to take a copy of this QR code. Add it to a worksheet for students to scan and jump immediately to this link. Or just display this pop-up box on your classroom projector.</p>';
                          echo '</div>';
                        echo '</div>';
                      echo '</div>';
                    echo '</div>';
                  } else {
                    echo '<p>'.$url.'</p>';
                  }
                }
                if (isset($notes)) {
                  echo $notes;
                }
                echo '</div>';
              }
            }
          }
        echo '</div>';
      echo '</div>'; // .links
    echo '</div>';   // .panel
  $c++;
  }
  
} ?>
  
  <div class="col-sm-4 hidden-xs">
    <?php
      echo '<div class="row smallUserNav">';
      $users = array('Students','Staff','Parents');
      foreach ($users as $user) {
        if (strtolower($user) == strtolower($_GET['user'])) {
          echo '<div class="col-sm-4"><a role="button" class="active">'.$user.'</a></div>';
        } else {
          echo '<div class="col-sm-4"><a role="button" href="/intranet/'.strtolower($user).'">'.$user.'</a></div>';
        }
      }
      echo '</div>';
      switch (strtolower($_GET['user'])) {
        case 'parents':
          echo '<div class="twitterColumn">';
            echo '<a href="https://twitter.com/DCGSParenting" target="'.mt_rand().'">DCGS Parenting <span class="pull-right"><i class="fa fa-twitter fa-lg"></i></span></a>';
            echo '<a class="twitter-timeline" data-height="530" data-chrome="noheader nofooter" data-link-color="#2358A3" href="https://twitter.com/DCGSParenting?ref_src=twsrc%5Etfw">Tweets by DCGSParenting</a>';
          echo '</div>';
        break;
        case "students":
				case "staff":
					if (file_exists('data/intranet/edVideos.json') && !isset($_GET['sync'])) {
						$edVideos = file_get_contents('data/intranet/edVideos.json');
      			$edVideos = json_decode($edVideos, true);
					} else {
						$edVideos = array();
						$pageToken = '';
						while (isset($pageToken)) {
							$playlistData = file_get_contents('https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults=50'.$pageToken.'&playlistId=PL95IE7ddeVd5R77NTS_cdoBB1xW3l3GR4&key=AIzaSyCgnnpTk5H9pCr41VyXEicVtxoORxccvfo');
							$playlistData = json_decode($playlistData, true);
							if (isset($playlistData['nextPageToken'])) {
								$pageToken = '&pageToken='.$playlistData['nextPageToken'];
							} else {
								unset($pageToken);
							}
							foreach ($playlistData['items'] as $item) {
								$videoData = array();
								$videoData['id']     = $item['snippet']['resourceId']['videoId'];
								$videoData['title']  = $item['snippet']['title'];
								$edVideos[] = $videoData;
							}
						}
						file_put_contents('data/intranet/edVideos.json', json_encode($edVideos));
					}
					shuffle ($edVideos);
					for ($v = 0; $v < 3; $v++) {
						$video = array_shift($edVideos);
						echo '<div class="row edVideo">';
							echo '<div class="embedFeature col-sm-12">';
								echo '<div class="embed-responsive embed-responsive-video hidden-print">';
									echo '<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/'.$video['id'].'?showinfo=0" allowfullscreen="true"></iframe>';
								echo '</div>';
								echo '<p>'.$video['title'].'</p>';
							echo '</div>';
						echo '</div>';
					}
        break;
      }
    ?>
  </div>
  <div class="col-sm-8">
    <h1><?php echo ucwords($_GET['user']); ?> intranet</h1>
    <div id="intranetMenu" role="tablist" aria-multiselectable="true">
      <?php
      switch ($_GET['user']) {
        case "staff":
            makeIntranetLinks('1VSyWX6JwnA9qFF-uY6GCshpdyqHnqYI00P4--p-YvYk');
        break;
          case "students":
            makeIntranetLinks('1tUKJxXeaWxf1vyGeI4YLysHPGE24f1uQzUNcGwcUmLw');
        break;
        case "parents":		
            makeIntranetLinks('1LImIk6cenrhgsEBqmx-peV5EsHoFYBtDf4EYVNfC0dg');
        break;
        }
        echo "<h2>Subject resources</h2>";
        makeIntranetLinks('1vTDVUq_zKKHTn7NvRt8r8akOeAVmWXh7CLC5UMW-IYs','subjects');
      ?>
    </div>
  </div>
      
  <?php
  
	}
	else {
    $pages = array('Students','Staff','Parents');
    
    function makeQuickLinks($sheetKey) {
      $caches = scandir('data/intranet/', 1);
      foreach ($caches as $file) {
        if (strpos($file,$sheetKey) !== false) {
          $links = json_decode(file_get_contents('data/intranet/'.$file), true);
        }
      }
      if (isset($links)) {
        foreach ($links['data'] as $row) {
          foreach ($row as $link) {
            if (strpos(str_replace(" ","",strtolower($link['special'])),'quicklink') !== false && !empty($link['url'])) {
              echo '<li>';
              echo '<a ';
              if ((strpos($link['url'],'challoners.com') === false && strpos($link['url'],'://') !== false) || strpos($link['url'],'challoners.com/learn') !== false) { 
                echo 'target="page'.mt_rand().'" ';
              }
              echo 'href="'.$link['url'].'">';
              echo $link['title'];
              echo '</a>';
              if (!empty($link['notes'])) {
                echo '<p>';
                echo $link['notes'];
                echo '</p>';
              }
              echo '</li>';
            }
          }
        }
        unset($links);
      }
    }
    
    foreach ($pages as $page) {
      echo '<div class="col-sm-4">';
      echo '<a role="button" class="btn btn-intranetMain btn-block" href="/intranet/'.strtolower($page).'" style="background-position: '.rand(-50,226).'px '.rand(-40,50).'px, '.rand(-60,0).'px '.rand(-60,0).'px;"><h1>'.$page.'</h1></a>';
      echo '<div class="panel panel-default quickLinks hidden-xs">';
        echo '<div class="panel-heading"><h3 class="panel-title">Quick links for '.strtolower($page).':</h3></div>';
        echo '<div class="panel-body"><ul>';
          switch (strtolower($page)) {
            case 'students':
              makeQuickLinks('1tUKJxXeaWxf1vyGeI4YLysHPGE24f1uQzUNcGwcUmLw');
              makeQuickLinks('1vTDVUq_zKKHTn7NvRt8r8akOeAVmWXh7CLC5UMW-IYs');
            break;
            case 'staff':
              makeQuickLinks('1VSyWX6JwnA9qFF-uY6GCshpdyqHnqYI00P4--p-YvYk');
            break;
            case 'parents':
              makeQuickLinks('1LImIk6cenrhgsEBqmx-peV5EsHoFYBtDf4EYVNfC0dg');
            break;
          }
        echo '</ul></div>';
      echo '</div>';
      echo '</div>';
    }
	}

	?>
</div>
<?php include('footer.php'); ?>