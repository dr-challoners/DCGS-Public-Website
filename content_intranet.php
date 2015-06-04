<?php 
	include('header_declarations.php');
	$intranet = 1; // Just to be able to highlight the button on the navigation menu
  include('header_navigation.php');
?>

<!--googleoff: all-->

<?php
if (isset($_GET['user'])) {
  
function makeIntranetLinks($sheetKey,$prefix) {
  
  // Create an array of all the worksheets within the specified sheet
  
  $worksheetList = file_get_contents('https://spreadsheets.google.com/feeds/worksheets/'.$sheetKey.'/public/basic?alt=json');
  $worksheetList = json_decode($worksheetList);
  $worksheetList = $worksheetList->feed->entry;
  
  $sections = array();
  
  foreach ($worksheetList as $row) {
    $row = $row->title;
    $row = get_object_vars($row);
    $sections[] = $row['$t'];
  }
  
  // Now work through the spreadsheet one worksheet at a time, creating a multi-dimensional array of the link list data for the whole spreadsheet
  
  $lists = array();
  
  $sectionKey = 1;
  foreach ($sections as $section) {
    $list = file_get_contents('https://spreadsheets.google.com/feeds/list/'.$sheetKey.'/'.$sectionKey.'/public/values?alt=json');
    $list = json_decode($list);
    if (isset($list->feed->entry)) { // Various debugging throughout in case there are half-setup worksheets in the spreadsheet when it updates
      $list = $list->feed->entry;
      
      $links = array();
      
      foreach ($list as $row) {
        $row = get_object_vars($row);
        if (array_key_exists('gsx$title',$row) && array_key_exists('gsx$url',$row) && array_key_exists('gsx$notes',$row) && array_key_exists('gsx$special',$row)) {
          $title   = get_object_vars($row['gsx$title']);
          $url     = get_object_vars($row['gsx$url']);
          $notes   = get_object_vars($row['gsx$notes']);
          $special = get_object_vars($row['gsx$special']);
          $row = array('title'=>$title['$t'], 'url'=>$url['$t'], 'notes'=>$notes['$t'], 'special'=>$special['$t']);
          $links[] = $row;
        }
      }
      $lists[$section] = $links;
    }
    $sectionKey++;
  }
    
  // Use the spreadsheet array to generate the links list
  
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
  
  echo '<div class="ncol lft submenu lrg">';
	  echo '<ul class="intranet">';
      echo '<li><a href="/intranet/students">Student links</a></li>';
		  echo '<li><a href="/intranet/staff">Staff links</a></li>';
		  echo '<li><a href="/intranet/parents">Parent links and information</a></li>';
	  echo '</ul>';
  echo '<!--googleon: all--></div>';
  
  echo '<div class="parsebox">';
    echo '<div class="intranet">';
  
	switch ($_GET['user']) {
	case "staff":
		  echo "<h1>Staff links</h1>";
		  makeIntranetLinks('1VSyWX6JwnA9qFF-uY6GCshpdyqHnqYI00P4--p-YvYk','M');
	break;
    case "students":
	  	echo "<h1>Student links</h1>";
		  makeIntranetLinks('1tUKJxXeaWxf1vyGeI4YLysHPGE24f1uQzUNcGwcUmLw','O');
	break;
	case "parents":
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
    
    $buttons = array(1,2,3,4,5,6);
    shuffle($buttons);
    
		echo '<a class="intranetMainLink" href="/intranet/students"';
      echo ' style="background-position: '.rand(-50,226).'px '.rand(-40,50).'px;"';
      echo '><h1>Students</h1></a>';
    echo '<a class="intranetMainLink" href="/intranet/staff"';
      echo ' style="background-position: '.rand(-50,226).'px '.rand(-40,50).'px;"';
      echo '><h1>Staff</h1></a>';
    echo '<a class="intranetMainLink" href="/intranet/parents"';
      echo ' style="background-position: '.rand(-50,226).'px '.rand(-40,50).'px;"';
      echo '><h1>Parents</h1></a>';
		}
	

	
	include ('footer.php');
?>