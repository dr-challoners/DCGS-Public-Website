<?php 
	include('header_declarations.php');
	$intranet = 1; // Just to be able to highlight the button on the navigation menu
  include('header_navigation.php');
?>

<!--googleoff: all-->

<?php
if (isset($_GET['user'])) {
  
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
		  $directory = "content_system/intranet/staff/";
		$prefix = 'M';
	    include ('links_list.php');
	break;
    case "students":
	  	echo "<h1>Student links</h1>";
		  $directory = "content_system/intranet/students/";
		$prefix = 'O';
	    include ('links_list.php');
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
				
      $directory = "content_system/intranet/parents/";
      		$_REQUEST['prefix'] = 'Q';
			include ('links_list.php');
	break;
	}
  
      echo '<div class="clear lrg">';
        echo "<h2>Subject resources</h2>";
		    $directory = "content_system/intranet/subjects/";
		$prefix = 'N';
        include ('links_list.php');
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