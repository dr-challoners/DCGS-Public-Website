<?php 
	$intranet = "y";
	include('header_declarations.php');
	include('header_navigation.php');
  include('parsing/Parsedown.php'); //Converts markdown text to HTML - see parsedown.org
?>

<!--googleoff: all--><div class="ncol lft submenu lrg">
	<ul class="intranet">
		<li><a href="/intranet/Staff_and_student_intranet">Staff and student intranet</a></li>
		<li><a href="/intranet/Parent_portal">Parent portal</a></li>
		<li><a href="/intranet/Subject_resources">Subject resources</a></li>
	</ul>
<!--googleon: all--></div>
<div class="mcol-rgt">

<?php
if (isset($_GET['user'])) {
	switch ($_GET['user']) {
	case "Subject_resources":
		echo "<h1 class=\"intranet\">Subject resources</h1>";
			$directory = "content_system/intranet/subjects/";
			$links = scandir($directory);
	
			$count = 0;

			foreach ($links as $row) {
				if (strpos($directory.$row,".txt") !== false) { //It's a text file (thereby containing links in markdown)
		
					$subject = explode(".",$row);
					$subject = $subject[0];
		
					$id = str_replace(" ","",$subject);
					$id = strtolower($id); //For the CSS
		
					if ($count%3 == 0) { echo "<div class=\"subjectbar\">"; $open = 1; }
		
					echo "<div class=\"subjectbox\" id=\"".$id."\">";
						echo "<h2>".$subject."</h2>";
						$linkslist = file_get_contents($directory.$row, true);
						echo Parsedown::instance()->parse($linkslist);
					echo "</div>";
		
					if ($count%3 == 2) { echo "</div>"; $open = 0; }
		
					$count++; }
				}
	
			if ($open == 1) { echo "</div>"; }
	break;
	case "Staff_and_student_intranet":
		echo "<h1 class=\"intranet\">Staff and student intranet</h1>";
		$directory = "content_system/intranet/staff-students/";
		echo "<div class=\"intranet\">";
			$col_count = 1;
			include ('links_list.php');
		echo "</div>";
	break;
	case "Parent_portal":
		echo "<h1 class=\"intranet\">Parent portal</h1>";
		$directory = "content_system/intranet/parents/";
		echo "<div class=\"intranet\">";
			echo "<div class=\"column lrg\">"; // This repeats the information in the Information content folder, to give parents another opportunity to find it all
				
				$dir = scandir("content_main/Information", 1); //First, get all the subdirectories in the main directory being looked at
				$dir = array_reverse($dir);

				foreach ($dir as $subdir) { //List all the subdirectories
					$dirname = explode("~",$subdir);
					if (isset($dirname[1])) { // This is a cheap and cheerful way to confirm that the object being looked at is a folder, but it requires ALL subdirectories to be in the form 'X~NAME'
						echo "<div class=\"linksbox\"><h3>".$dirname[1]."</h3>";
    
						$files = scandir("content_main/Information/".$subdir, 1); //Now get all the files in each subdirectory and turn them into appropriate links
						$files = array_reverse($files);
    
						echo "<ul>";
    
						foreach ($files as $page) {
							$detail = explode("~",$page);
							if (isset($detail[2])) { // If there's a third part to the array, then that means a particular instruction like an external LINK or a GALLERY or a SPECIAL content_rich page
								if ($detail[2] == "LINK.txt") { // This needs to be a link to an outside site - it opens in a new tab. The link infor is written inside the text file
									echo "<li><a href=\"".file_get_contents("content_main/Information/".$subdir."/".$page)."\" target=\"_BLANK\">".str_replace('[plus]','+',$detail[1])."</a></li>";
									}
								}
							elseif (isset ($detail[1]) && substr($detail[1],-4) == ".txt") {
								$pagename = explode(".",$detail[1]);
								$pagename = $pagename[0];
								echo "<li><a href=\"/pages/Information/".$dirname[1]."/".$pagename."\">".str_replace('[plus]','+',$pagename)."</a></li>";
								}
							}
    
						echo "</ul></div>";
    
						}
					}
				
			echo "</div>";
			$col_count = 2;
			include ('links_list.php');
		echo "</div>";
	break;
	}
	}
	else {
		echo "<h1>DCGS intranet</h1>";
		echo "<p>You will need your username and password to access the links in this section.<br />Click on a category on the left to begin.</p>";
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
	