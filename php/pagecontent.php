<?php 

include('../../header_declarations.php');
if ($_GET['gallery'] != "") {
	echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"screen and (min-device-width : 480px)\" href=\"/styles/gallery_lrg.css\"/>";
	echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"screen and (max-device-width : 480px)\" href=\"/styles/gallery_sml.css\"/>";
	}
include('../../header_navigation.php');

$submenu = file_get_contents('submenu.txt', true);

echo "<div class=\"ncol lft submenu\">";
	echo "<h3 class=\"sml\">See more in this section</h3>";
	echo Parsedown::instance()->parse($submenu);
echo "</div>";

echo "<div class=\"mcol-rgt\">";

	if ($_GET['gallery'] != "") { include('gallery.php'); } //If the request is for a gallery page
	else { //Otherwise, parse the appropriate content for the page

		if ($_GET['page'] != "") { $content = $_GET['page']; }
		else { $content = $default; } //Selects the file specified, or gives the default file

		$content = file_get_contents($content.'.txt', true); //Open the appropriate text file for parsing
		echo Parsedown::instance()->parse($content);
	
	}
	
echo "</div>";

include('../../footer.php');

?>