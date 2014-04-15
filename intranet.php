<?php
	$intranet = "y";
	include('header_declarations.php');
	include('header_navigation.php');
?>

<div class="ncol lft submenu lrg">
	<ul class="intranet">
		<li><a href="/intranet/Staff and student intranet">Staff and student intranet</a></li>
		<li><a href="/intranet/Parent portal">Parent portal</a></li>
		<li><a href="/intranet/Subject resources">Subject resources</a></li>
	</ul>
</div>
<div class="mcol-rgt">

<?php
if (isset($_GET['user'])) {
	switch ($_GET['user']) {
	case "Subject resources":
		echo "<h1 class=\"intranet\">Subject resources</h1>";
			$directory = "content_plain/intranet/subjects/";
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
	case "Staff and student intranet":
		echo "<h1 class=\"intranet\">Staff and student intranet</h1>";
		$directory = "content_plain/intranet/staff-students/";
		echo "<div class=\"intranet\">";
			$col_count = 1;
			include ('php/links_list.php');
		echo "</div>";
	break;
	case "Parent portal":
		echo "<h1 class=\"intranet\">Parent portal</h1>";
		$directory = "content_plain/intranet/parents/";
		echo "<div class=\"intranet\">";
			echo "<div class=\"column lrg\">"; // This repeats the information in the Information content folder, to give parents another opportunity to find it all
				
				$dir = scandir("content_plain/Information", 1); //First, get all the subdirectories in the main directory being looked at
				$dir = array_reverse($dir);

				foreach ($dir as $subdir) { //List all the subdirectories
					$dirname = explode("~",$subdir);
					if (isset($dirname[1])) { // This is a cheap and cheerful way to confirm that the object being looked at is a folder, but it requires ALL subdirectories to be in the form 'X~NAME'
						echo "<div class=\"linksbox\"><h3>".$dirname[1]."</h3>";
    
						$files = scandir("content_plain/Information/".$subdir, 1); //Now get all the files in each subdirectory and turn them into appropriate links
						$files = array_reverse($files);
    
						echo "<ul>";
    
						foreach ($files as $page) {
							$detail = explode("~",$page);
							if (isset($detail[2])) { // If there's a third part to the array, then that means a particular instruction like an external LINK or a GALLERY or a SPECIAL content_rich page
								if ($detail[2] == "LINK.txt") { // This needs to be a link to an outside site - it opens in a new tab. The link infor is written inside the text file
									echo "<li><a href=\"".file_get_contents("content_plain/Information/".$subdir."/".$page)."\" target=\"_BLANK\">".$detail[1]."</a></li>";
									}
								elseif ($detail[2] == "GALLERY") { // Point to the gallery function for the given folder
									echo "<li><a href=\"/gallery/Information/".$dirname[1]."/".$detail[1]."\">".$detail[1]."</a></li>";
									}
								elseif ($detail[2] == "SPECIAL.txt") { // Point to the content_rich folder. Note that most of the navigation details given will be unnecessary for finding the file: they're there to display the submenu.
									echo "<li><a href=\"/rich/Information/".$dirname[1]."/".$detail[1]."\">".$detail[1]."</a></li>";
									}
								}
							elseif (isset ($detail[1]) && substr($detail[1],-4) == ".txt") {
								$pagename = explode(".",$detail[1]);
								$pagename = $pagename[0];
								echo "<li><a href=\"/pages/Information/".$dirname[1]."/".$pagename."\">".$pagename."</a></li>";
								}
							}
    
						echo "</ul></div>";
    
						}
					}
				
			echo "</div>";
			$col_count = 2;
			include ('php/links_list.php');
		echo "</div>";
	break;
	default:
		echo "<h1>DCGS intranet</h1>";
		echo "<p>You will need your username and password to access the links in this section. Click on a category on the left to begin.</p>";
	}
	}
	else {
		echo "<h1>DCGS intranet</h1>";
		echo "<p>You will need your username and password to access the links in this section. Click on a category on the left to begin.</p>";
		}
	
	echo "</div>";
	
	include ('footer.php');
?>
	