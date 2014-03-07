<?php 
include('header_declarations.php');

if (!isset($_GET['gallery'])) { $get_gallery = ""; } else { $get_gallery = $_GET['gallery']; }
if (!isset($_GET['folder'])) { $get_folder = ""; } else { $get_folder = $_GET['folder']; }
if (!isset($_GET['page'])) { $get_page = ""; } else { $get_page = $_GET['page']; }

if ($get_gallery != "") {
	echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"screen and (min-device-width : 480px)\" href=\"/styles/gallery_lrg.css\"/>";
	echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"screen and (max-device-width : 480px)\" href=\"/styles/gallery_sml.css\"/>";
	}
include('header_navigation.php');

$submenu = file_get_contents("content_plain/".$get_folder."/submenu.txt", true);


echo "<div class=\"ncol lft submenu\">";
	echo "<h3 class=\"sml\"><a href=\"javascript:openClose('n3','n1','n2')\">See more in this section</a></h3>";
	echo "<div id=\"n3\">";
		echo Parsedown::instance()->parse($submenu);
	echo "</div>";
echo "</div>";

echo "<div class=\"mcol-rgt\">";

	if ($get_gallery != "") { include('php/gallery.php'); } //If the request is for a gallery page
	else { //Otherwise, parse the appropriate content for the page

		//Selects the file specified, or gives the default file
		if ($get_page != "") { $content = $get_page; }
		else { $content = "default"; } 
		
		switch ($content) { //This allows particular pages to be highlights as having 'rich' content: for instance a contact form, or the House scores
		case "termdates":
			include ('content_rich/termdates.php');
			break;
		case "house_scores":
			include ('content_rich/house_scores.php');
			break;
		case "house_representatives":
			include ('content_rich/house_representatives.php');
			break;
		default: //If it's not a special case, go through the normal process
			if (file_exists("content_plain/".$get_folder."/".$content.".txt")) {
				$content = file_get_contents("content_plain/".$get_folder."/".$content.'.txt', true); //Open the appropriate text file for parsing
				echo Parsedown::instance()->parse($content);
				}
			elseif ($content == "default") { //If there's no page found and it's looking for the default page, then display some generic text
				echo "<h1>Welcome to this section</h1>";
				echo "<p class=\"lrg\">Use the links on the left to navigate.</p>";
				echo "<p class=\"sml\">Use the links below to navigate.</p>";
				}
			else { //Displays an error if the page can't be found
				echo "<style> body { background-image: url('/main_imgs/error.png'); background-position: center bottom; background-repeat: no-repeat; background-attachment: fixed; background-size: 980px auto; } </style>";
				echo "<h2>Oh dear!</h2>";
				echo "<p>This page seems to be lost. You could go back to the home page and try again, or check down the back of sofa. If you think there's an error, you could <a href=\"/about/contact/\">contact us</a> to report the problem.</p>";
				} 
			}
	}
	
echo "</div>";

include('footer.php');

?>