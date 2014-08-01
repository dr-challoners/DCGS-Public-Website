<?php 
include('header_declarations.php');

if (!isset($_GET['folder'])) { $get_folder = ""; } else { $get_folder = $_GET['folder']; }
if (!isset($_GET['subfolder'])) { $get_subfolder = ""; } else { $get_subfolder = $_GET['subfolder']; }
if (!isset($_GET['page'])) { $get_page = ""; } else { $get_page = $_GET['page']; }
	
include('header_navigation.php');

echo "<!--googleoff: all--><div class=\"ncol lft submenu lrg\">"; //Building the submenu

$dir = scandir("content_main/".$get_folder, 1); //First, get all the subdirectories in the main directory being looked at
$dir = array_reverse($dir);

foreach ($dir as $subdir) { //List all the subdirectories
  $dirname = explode("~",$subdir);
  if (isset($dirname[1])) { // This is a cheap and cheerful way to confirm that the object being looked at is a folder, but it requires ALL subdirectories to be in the form 'X~NAME'
    echo "<h2><a href=\"javascript:openClose('".str_replace("'","",$dirname[1])."')\">".$dirname[1]."</a></h2>";
    
      $files = scandir("content_main/".$get_folder."/".$subdir, 1); //Now get all the files in each subdirectory and turn them into appropriate links
      $files = array_reverse($files);
    
      echo "<ul id=\"".str_replace("'","",$dirname[1])."\">";
    
      foreach ($files as $page) {
        $detail = explode("~",$page);
        if (isset($detail[2]) && $detail[2] == "LINK.txt") { // This needs to be a link to an outside site - it opens in a new tab. The link infor is written inside the text file
            echo "<li><a href=\"".file_get_contents("content_main/".$get_folder."/".$subdir."/".$page)."\" target=\"_BLANK\">".$detail[1]."</a></li>";
            }
        elseif (isset ($detail[1])) {
          $pagename = explode(".",$detail[1]);
          $pagename = $pagename[0];
          echo "<li><a href=\"/pages/".$get_folder."/".$dirname[1]."/".$pagename."\">".$pagename."</a></li>";
          }
        }
    
    echo "</ul>";
    
    }
  }
      
echo "<!--googleon: all--></div>";

echo "<div class=\"mcol-rgt\">";

    if ($get_page != "") { //Parse the appropriate content for the page
      foreach ($dir as $subdir) { // Above, the links are made into human-readable titles. This finds the actual names of the folders and files, in order to access the content.
        if (strpos($subdir,$get_subfolder) !== false) {
            $this_subdir = $subdir;
            $files = scandir("content_main/".$get_folder."/".$subdir, 1);
            foreach ($files as $page) {
              if (strpos($page,$get_page) !== false) {
                $this_page = $page;
                }
              }
            }
          }
    
      if (isset($this_page) && file_exists("content_main/".$get_folder."/".$this_subdir."/".$this_page)) {
        $dir = "content_main/".$get_folder."/".$this_subdir."/".$this_page;
        if(strpos($this_page, ".txt") !== false) { //This page is just a plain text file, so parse it as pure markdown
      	  $content = file_get_contents($dir, true); //Open the appropriate text file for parsing
          include('parsing/parsedown.php'); //Converts markdown text to HTML - see parsedown.org
      	  echo Parsedown::instance()->parse($content);
          } else { //Otherwise we're talking about a folder, indicating a rich page to be parsed with ParseBox
          include('parsing/parsebox.php');
          }
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
	
echo "</div>";

include('footer.php');

?>