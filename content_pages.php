<?php 
include('header_declarations.php');

if (!isset($_GET['gallery'])) { $get_gallery = ""; } else { $get_gallery = $_GET['gallery']; }
if (!isset($_GET['folder'])) { $get_folder = ""; } else { $get_folder = $_GET['folder']; }
if (!isset($_GET['subfolder'])) { $get_subfolder = ""; } else { $get_subfolder = $_GET['subfolder']; }
if (!isset($_GET['page'])) { $get_page = ""; } else { $get_page = $_GET['page']; }

if ($get_gallery != "") {
	echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"screen and (min-device-width : 480px)\" href=\"/styles/gallery_lrg.css\"/>";
	echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"screen and (max-device-width : 480px)\" href=\"/styles/gallery_sml.css\"/>";
	}
include('header_navigation.php');

echo "<div class=\"ncol lft submenu lrg\">"; //Building the submenu

$dir = scandir("content_plain/".$get_folder, 1); //First, get all the subdirectories in the main directory being looked at
$dir = array_reverse($dir);

foreach ($dir as $subdir) { //List all the subdirectories
  $dirname = explode("~",$subdir);
  if (isset($dirname[1])) { // This is a cheap and cheerful way to confirm that the object being looked at is a folder, but it requires ALL subdirectories to be in the form 'X~NAME'
    echo "<h2><a href=\"javascript:openClose('".str_replace("'","",$dirname[1])."')\">".$dirname[1]."</a></h2>";
    
      $files = scandir("content_plain/".$get_folder."/".$subdir, 1); //Now get all the files in each subdirectory and turn them into appropriate links
      $files = array_reverse($files);
    
      echo "<ul id=\"".str_replace("'","",$dirname[1])."\">";
    
      foreach ($files as $page) {
        $detail = explode("~",$page);
        if (isset($detail[2])) { // If there's a third part to the array, then that means a particular instruction like an external LINK or a GALLERY or a SPECIAL content_rich page
          if ($detail[2] == "LINK.txt") { // This needs to be a link to an outside site - it opens in a new tab. The link infor is written inside the text file
            echo "<li><a href=\"".file_get_contents("content_plain/".$get_folder."/".$subdir."/".$page)."\" target=\"_BLANK\">".$detail[1]."</a></li>";
            }
          elseif ($detail[2] == "GALLERY") { // Point to the gallery function for the given folder
            echo "<li><a href=\"/gallery/".$get_folder."/".$dirname[1]."/".$detail[1]."\">".$detail[1]."</a></li>";
            }
          elseif ($detail[2] == "SPECIAL.txt") { // Point to the content_rich folder. Note that most of the navigation details given will be unnecessary for finding the file: they're there to display the submenu.
            echo "<li><a href=\"/rich/".$get_folder."/".$dirname[1]."/".$detail[1]."\">".$detail[1]."</a></li>";
            }
          }
        elseif (isset ($detail[1]) && substr($detail[1],-4) == ".txt") {
          $pagename = explode(".",$detail[1]);
          $pagename = $pagename[0];
          echo "<li><a href=\"/pages/".$get_folder."/".$dirname[1]."/".$pagename."\">".$pagename."</a></li>";
          }
        }
    
    echo "</ul>";
    
    }
  }
      
echo "</div>";

echo "<div class=\"mcol-rgt\">";

	if ($get_gallery != "") { include('php/gallery.php'); } //If the request is for a gallery page
  elseif (isset($_GET['special'])) { include('content_rich/'.$get_page.'.php'); } // If the request is for rich content
	else { //Otherwise, parse the appropriate content for the page
    
    if ($get_page != "") {
    
      foreach ($dir as $subdir) { // Above, the links are made into human-readable titles. This finds the actual names of the folders and files, in order to access the content.
        if (strpos($subdir,$get_subfolder) !== false) {
            $this_subdir = $subdir;
            $files = scandir("content_plain/".$get_folder."/".$subdir, 1);
            foreach ($files as $page) {
              if (strpos($page,$get_page) !== false) {
                $this_page = $page;
                }
              }
            }
          }
    
      if (isset($this_page) && file_exists("content_plain/".$get_folder."/".$this_subdir."/".$this_page)) {
      	$content = file_get_contents("content_plain/".$get_folder."/".$this_subdir."/".$this_page, true); //Open the appropriate text file for parsing
      	echo Parsedown::instance()->parse($content);
      	}

			  else { //Displays an error if the page can't be found
				  echo "<style> body { background-image: url('/main_imgs/error.png'); background-position: center bottom; background-repeat: no-repeat; background-attachment: fixed; background-size: 980px auto; } </style>";
				  echo "<h2>Oh dear!</h2>";
          echo "<p>This page seems to be lost. You could go back to the home page and try again, or check down the back of sofa. If you think there's an error, you could <a href=\"/pages/Information/General information/Contact us\">contact us</a> to report the problem.</p>";
			  	} 
			  }
      else { // We're not looking at a specific page, so display the welcome message
        echo "<h1>Welcome to this section</h1>";
			  echo "<p class=\"lrg\">Use the links on the left to navigate.</p>";
			  echo "<p class=\"sml\">Use the links below to navigate.</p>";
        }
	}
	
echo "</div>";

include('footer.php');

?>