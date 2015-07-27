<?php 
	include('header_declarations.php');
	$intranet = 1; // Just to be able to highlight the button on the navigation menu
  include('header_navigation.php');
?>

<!--googleoff: all-->

<?php
if (isset($_GET['user'])) {
  
function makeIntranetLinks($sheetKey,$prefix) {
  
  // Use the stored spreadsheet array to generate the links list
  $lists = sheetToArray($sheetKey,'data_intranet',6);
  $headings = array_keys($lists);
  
  $c = 0;
  foreach ($headings as $list) {
    // On large screens, generate the right-hand section button along with the left-hand one: this means the links for both sections appear under both headings, rather than the right-hand heading jumping down the page when the left-hand list is opened.
    echo '<div class="intranet_head';
    if ($c%2 == 1) { echo ' sml'; }
    echo '">';
      echo '<h3><a href="javascript:boxOpen(\''.$prefix.$c.'\',\'boxlist\')">'.$list.'</a></h3>';
    echo "</div>";
    $cn = $c+1;
    if ($c%2 == 0 && isset($headings[$cn])) {
      echo '<div class="intranet_head lrg">';
        echo '<h3><a href="javascript:boxOpen(\''.$prefix.$cn.'\',\'boxlist\')">'.$headings[$cn].'</a></h3>';
      echo "</div>";
    }
    // Now for the links lists themselves
    echo '<div class="intranetbox"><div class="dropdown" name="boxlist" id="'.$prefix.$c.'">';
      $l = 0;
      foreach ($lists[$list] as $link) {
        if (strtolower($link['special']) != 'subheading') {
          if (strpos(str_replace(" ","",strtolower($link['special'])),'linebreak') !== false) {
            if ($l > 0) { echo '</ul>'; }
            echo '<hr />';
            $l = 0;
          }
          if ($l == 0) {
            echo '<ul>';
            $l++;
          }
          echo '<li>';
            echo '<a ';
              // Detect if the site is external (including Learn websites) and open in a new tab/add a class if they are
              if ((strpos($link['url'],'challoners.com') === false && strpos($link['url'],'://') !== false) || strpos($link['url'],'challoners.com/learn') !== false) { 
                echo 'target="page'.mt_rand().'" class="external" ';
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
        } else {
          if ($l > 0) {
            echo '</ul>';
            echo '<hr />';
          }
          echo '<h3>'.$link['title'].'</h3>';
          $l = 0;
        }
      }
      if ($l > 0) { echo '</ul>'; }
    echo '<hr id="end" /></div></div>';
  $c++;
  }
  
}
  
  echo '<div class="ncol lft intranetSidebar lrg">';
  
  switch ($_GET['user']) {
    case "parents":
    case "Parent_links":
      $feedURL  = 'DCGSParenting';
      $feedName = 'DCGS Parenting';
      $feedID   = '606699684757913600';
    break;
    case "students":
    case "Student_links":
      $feedURL  = 'Student_SLT';
      $feedName = 'DCGS Student SLT';
      $feedID   = '606700413740564480';
    break;
  }
  
  if (isset($feedID)) {
    echo '<div class="twitter-header" id="intranet"><a href="https://twitter.com/'.$feedURL.'" target="page'.mt_rand().'"><p>'.$feedName.' <span>Follow</span></p></a></div>';
    echo '<a class="twitter-timeline"  href="https://twitter.com/'.$feedURL.'" data-chrome="noborders noheader nofooter" data-widget-id="'.$feedID.'">Tweets by @'.$feedURL.'</a>';
    echo '<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';
  }
  
  echo '<!--googleon: all--></div>';
  
  echo '<div class="parsebox">';
    echo '<div class="intranet">';
  
	switch ($_GET['user']) {
	case "staff":
  case "Staff_links":
		  echo "<h1>Staff links</h1>";
		  makeIntranetLinks('1VSyWX6JwnA9qFF-uY6GCshpdyqHnqYI00P4--p-YvYk','M');
	break;
    case "students":
    case "Student_links":
	  	echo "<h1>Student links</h1>";
		  makeIntranetLinks('1tUKJxXeaWxf1vyGeI4YLysHPGE24f1uQzUNcGwcUmLw','O');
	break;
	case "parents":
  case "Parent_links":
		echo "<h1>Parent links and information</h1>";
		
    // First repeat the information in the Information content folder, to give parents another opportunity to find it all
				
				$dir = scandir("content_main/Information", 1); //First, get all the subdirectories in the main directory being looked at
        foreach ($dir as $subdir) { // List all the subdirectories
					$dirname = explode("~",$subdir);
					if (isset($dirname[1])) { // This is a cheap and cheerful way to confirm that the object being looked at is a folder, but it requires ALL subdirectories to be in the form 'X~NAME'
            $links[] = $subdir;
          }
        }
				$links = array_reverse($links);

        $c_end = count($links)-1;
				for ($c=0; $c<=$c_end; $c++) { // List all the subdirectories
					$cn = $c+1;
          $dirname = explode("~",$links[$c]);
          if ($cn <= $c_end) { $ndirname = explode("~",$links[$cn]); }
          if ($c%2 == 0 && $cn <= $c_end) { // The boxOpen function is in Parsebox
						echo '<div class="intranet_head lrg"><h3><a href="javascript:boxOpen(\'I'.$c.'\',\'boxlist\')">'.$dirname[1].'</a></h3></div>';
            echo '<div class="intranet_head lrg"><h3><a href="javascript:boxOpen(\'I'.$cn.'\',\'boxlist\')">'.$ndirname[1].'</a></h3></div>';
          }
						$files = scandir("content_main/Information/".$links[$c], 1); // Now get all the files in each subdirectory and turn them into appropriate links
						$files = array_reverse($files);
    
            echo '<div class="intranetbox lrg"><div class="dropdown" name="boxlist" id="I'.$c.'">';
						echo "<ul>";
            
						foreach ($files as $page) {
							$detail = explode("~",$page);
							if (isset($detail[2]) && $detail[2] == "LINK.txt") { // This needs to be a link to an outside site - it opens in a new tab. The link info is written inside the text file
								echo '<li><a href="'.file_get_contents('content_main/Information/'.$links[$c].'/'.$page).'" target="page'.mt_rand().'">'.str_replace('[plus]','+',$detail[1]).'</a></li>';
							}
							elseif (isset ($detail[1]) && substr($detail[1],-4) == ".txt") {
								$pagename = explode(".",$detail[1]);
								$pagename = $pagename[0];
								echo "<li><a href=\"/pages/Information/".$dirname[1]."/".$pagename."\">".str_replace('[plus]','+',$pagename)."</a></li>";
								}
							}
    
						echo '</ul><hr id="end" /></div></div>';
					}
				
      makeIntranetLinks('1LImIk6cenrhgsEBqmx-peV5EsHoFYBtDf4EYVNfC0dg','Q');
	break;
	}
  
      echo '<div class="clear lrg">';
        echo "<h2>Subject resources</h2>";
        makeIntranetLinks('1vTDVUq_zKKHTn7NvRt8r8akOeAVmWXh7CLC5UMW-IYs','N');
      echo '</div>';
    echo "</div>";
  echo "</div>";
  
	}
	else {
    $pages = array('Students','Staff','Parents');
    $d = 0; if (rand(1,999) == 1) { $d = rand(1,3); } // Duck time!
    $n = 1;
    foreach ($pages as $page) {
      if ($d != $n) {
        echo '<a class="intranetMainLink" href="/intranet/'.strtolower($page).'" style="background-position: '.rand(-50,226).'px '.rand(-40,50).'px, '.rand(-60,0).'px '.rand(-60,0).'px;"><h1>'.$page.'</h1></a>';
      } else {
        echo '<a class="intranetMainLink" id="duck" href="/intranet/'.strtolower($page).'" style="background-position: '.rand(-200,0).'px 0;"><h1>'.$page.'</h1></a>';
      }
    $n++;
    }
      echo '<p class="quickLinkNote">Quick links below - click above to see the full menus.</p>';
      function makeQuickLinks($sheetKey) {
        
        $caches = scandir('sync_logs/', 1);

        foreach ($caches as $file) {
          if (strpos($file,$sheetKey) !== false) {
            $links = json_decode(file_get_contents('sync_logs/'.$file), true);
          }
        }
        
        if (isset($links)) {
          foreach ($links as $row) {
            foreach ($row as $link) {
              if (strpos(str_replace(" ","",strtolower($link['special'])),'quicklink') !== false) {
                echo '<li>';
                  echo '<a ';
                    // Detect if the site is external (including Learn websites) and open in a new tab/add a class if they are
                    if ((strpos($link['url'],'challoners.com') === false && strpos($link['url'],'://') !== false) || strpos($link['url'],'challoners.com/learn') !== false) { 
                      echo 'target="page'.mt_rand().'" class="external" ';
                    }
                    echo 'href="'.$link['url'].'">';
                    echo '<p>'.$link['title'].'</p>';
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
      echo '<ul class="quickLinks">'; // Students - subject quick links go here as well
        makeQuickLinks('1tUKJxXeaWxf1vyGeI4YLysHPGE24f1uQzUNcGwcUmLw');
        makeQuickLinks('1vTDVUq_zKKHTn7NvRt8r8akOeAVmWXh7CLC5UMW-IYs');
      echo '</ul>';
      echo '<ul class="quickLinks">'; // Staff
        makeQuickLinks('1VSyWX6JwnA9qFF-uY6GCshpdyqHnqYI00P4--p-YvYk');
      echo '</ul>';
      echo '<ul class="quickLinks">'; // Parents
        makeQuickLinks('1LImIk6cenrhgsEBqmx-peV5EsHoFYBtDf4EYVNfC0dg');
      echo '</ul>';
	}
	

	
	include ('footer.php');
?>