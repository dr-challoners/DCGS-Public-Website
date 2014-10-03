<?php 
	include('header_declarations.php');
	include('header_navigation.php');
  include('parsing/Parsedown.php'); //Converts markdown text to HTML - see parsedown.org
?>

<!--googleoff: all--><div class="ncol lft submenu lrg">
	<ul class="intranet">
		<li><a href="/intranet/Staff_links">Staff links</a></li>
    <li><a href="/intranet/Student_links">Student links</a></li>
		<li><a href="/intranet/Parent_links">Parent links and information</a></li>
		<li><a href="/intranet/Subject_resources">Subject resources</a></li>
	</ul>
<!--googleon: all--></div>
<div class="parsebox">

<?php
if (isset($_GET['user'])) {
	switch ($_GET['user']) {
	case "Subject_resources":
    echo '<div class="intranet">';
		echo "<h1>Subject resources</h1>";
		$directory = "content_system/intranet/subjects/";
	$prefix = 'L';
    include ('links_list.php');
    echo '</div>';
	break;
	case "Staff_links":
    echo '<div class="intranet">';
		  echo "<h1>Staff links</h1>";
		  $directory = "content_system/intranet/staff/";
		$prefix = 'M';
	    include ('links_list.php');
      echo '<div class="clear lrg">';
        echo "<h2>Subject resources</h2>";
		    $directory = "content_system/intranet/subjects/";
		$prefix = 'N';
        include ('links_list.php');
      echo '</div>';
    echo '</div>';
	break;
    case "Student_links":
    echo '<div class="intranet">';
	  	echo "<h1>Student links</h1>";
		  $directory = "content_system/intranet/students/";
		$prefix = 'O';
	    include ('links_list.php');
      echo '<div class="clear lrg">';
        echo "<h2>Subject resources</h2>";
		    $directory = "content_system/intranet/subjects/";
		$prefix = 'P';
        include ('links_list.php');
      echo '</div>';
    echo '</div>';
	break;
	case "Parent_links":
    echo '<div class="intranet">';
		echo "<h1>Parent links and information</h1>";
		
    // First repeat the information in the Information content folder, to give parents another opportunity to find it all
				
				$dir = scandir("content_main/Information", 1); //First, get all the subdirectories in the main directory being looked at
				$dir = array_reverse($dir);

        $c = 1;
				foreach ($dir as $subdir) { //List all the subdirectories
					$dirname = explode("~",$subdir);
					if (isset($dirname[1])) { // This is a cheap and cheerful way to confirm that the object being looked at is a folder, but it requires ALL subdirectories to be in the form 'X~NAME'
						echo '<div class="intranetbox lrg"><h3><a href="javascript:boxOpen(\'I'.$c.'\',\'boxlist\')">'.$dirname[1].'</a></h3>';
    
						$files = scandir("content_main/Information/".$subdir, 1); //Now get all the files in each subdirectory and turn them into appropriate links
						$files = array_reverse($files);
    
            echo '<div class="dropdown" name="boxlist" id="I'.$c.'">';
						echo "<ul>";
    
						foreach ($files as $page) {
							$detail = explode("~",$page);
							if (isset($detail[2]) && $detail[2] == "LINK.txt") { // This needs to be a link to an outside site - it opens in a new tab. The link info is written inside the text file
								echo "<li><a href=\"".file_get_contents("content_main/Information/".$subdir."/".$page)."\" target=\"_BLANK\">".str_replace('[plus]','+',$detail[1])."</a></li>";
							}
							elseif (isset ($detail[1]) && substr($detail[1],-4) == ".txt") {
								$pagename = explode(".",$detail[1]);
								$pagename = $pagename[0];
								echo "<li><a href=\"/pages/Information/".$dirname[1]."/".$pagename."\">".str_replace('[plus]','+',$pagename)."</a></li>";
								}
							}
    
						echo "</ul></div></div>";
    
						}
					$c++; }
				
      $directory = "content_system/intranet/parents/";
      		$_REQUEST['prefix'] = 'Q';
			include ('links_list.php');
		echo "</div>";
	break;
	}
	}
	else {
		echo "<h1>DCGS intranet</h1>";
		echo "<p>Staff and students require a username and password to access the links in this section. Parents can view documents without a login, but will need a username and password to access reports and catering statements.<br />Click on a category on the left to begin.</p>";
    if (file_exists('content_system/intranet/00~QuickLinks.txt')) {
      echo '<h2 style="margin: 16px 0 12px 0;">Quick links</h2>';
      $dir = 'content_system/intranet';
      $parts = array('00~QuickLinks.txt');
      include('parsing/parsebox.php');
    }
		}
	
	echo "</div>";
	
	include ('footer.php');
?>