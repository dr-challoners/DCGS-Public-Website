<?php
	include('header_declarations.php');
	include('header_navigation.php');
?>

<div class="ncol lft submenu">
	<ul>
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
		echo "<h1>Subject resources</h1>";
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
		include ('php/links_list.php');
	break;
	case "Parent portal":
		echo "<h1 class=\"intranet\">Parent portal</h1>";
		$directory = "content_plain/intranet/parents/";
		include ('php/links_list.php');
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
	