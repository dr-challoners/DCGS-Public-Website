<?php 
include('header_declarations.php');

if (!isset($_GET['folder'])) { $get_folder = ""; } else { $get_folder = str_replace($linkRplce,$linkChars,$_GET['folder']); }
if (!isset($_GET['subfolder'])) { $get_subfolder = ""; } else { $get_subfolder = str_replace($linkRplce,$linkChars,$_GET['subfolder']); }
if (!isset($_GET['page'])) { $get_page = ""; } else { $get_page = str_replace($linkRplce,$linkChars,$_GET['page']); }
	
include('header_navigation.php');

echo '<!--googleoff: all-->';

if ($get_subfolder == "") { // We're on the front page for a section, so we're going to display some pretty pictures
  echo '<div class="ncol frontmenuImgs lrg">';
  
  $photos = scandir("content_system/sidebarImgs/", 1);
  array_pop($photos);
  array_pop($photos); // Removes . and .. from the array in order to get a proper count
  shuffle($photos);
  
  $r = rand(1,2);
    
  if ($r == 1) { echo '<div class="photostub med" style="background-image:url(\'/content_system/sidebarImgs/'.array_pop($photos).'\');"></div>'; } 
    echo '<div class="tny-box">';
      echo '<div class="photostub tny" style="background-image:url(\'/content_system/sidebarImgs/'.array_pop($photos).'\');"></div>';
      echo '<div class="photostub tny" style="background-image:url(\'/content_system/sidebarImgs/'.array_pop($photos).'\');"></div>';
      echo '<div class="photostub tny" style="background-image:url(\'/content_system/sidebarImgs/'.array_pop($photos).'\');"></div>';
      echo '<div class="photostub tny" style="background-image:url(\'/content_system/sidebarImgs/'.array_pop($photos).'\');"></div>';
    echo '</div>';
  if ($r == 2) { echo '<div class="photostub med" style="background-image:url(\'/content_system/sidebarImgs/'.array_pop($photos).'\');"></div>'; } 
  
  echo '</div>';
}

if ($get_subfolder != "") { // Building the submenu. If it's a front page for a section, then the submenu is displayed in a wide box.
  echo '<div class="ncol lft submenu lrg">'; 
} else {
  echo '<div class="mcol frontmenu lrg">';
  echo '<h1>'.$get_folder.'</h1>';
}              

$dir = scandir("content_main/".$get_folder, 1); // First, get all the subdirectories in the main directory being looked at
$dir = array_reverse($dir);

foreach ($dir as $subdir) { // List all the subdirectories
  $dirname = explode("~",$subdir);
  if (isset($dirname[1])) { // This is a cheap and cheerful way to confirm that the object being looked at is a folder, but it requires ALL subdirectories to be in the form 'X~NAME'
    echo '<h2>';
      if ($get_subfolder != "") { echo "<a href=\"javascript:openClose('".str_replace("'","",$dirname[1])."')\">"; }
        echo $dirname[1];
      if ($get_subfolder != "") { echo '</a>'; }
    echo '</h2>';
    
      $files = scandir("content_main/".$get_folder."/".$subdir, 1); //Now get all the files in each subdirectory and turn them into appropriate links
      $files = array_reverse($files);
    
      echo '<ul id="'.str_replace("'","",$dirname[1]).'"';
        if ($dirname[1] == $get_subfolder) { echo 'style="display:block;"'; }  // Keep the menu open if it's for the subfolder that contains the current page
      echo '>';
    
      foreach ($files as $page) {
        $detail = explode("~",$page);
        if (isset($detail[2]) && $detail[2] == "LINK.txt") { // This needs to be a link to an outside site - it opens in a new tab. The link info is written inside the text file
            echo '<li><a href="'.file_get_contents("content_main/".$get_folder."/".$subdir."/".$page).'" target="page'.mt_rand().'" class="external">'.str_replace('[plus]','+',$detail[1]).'</a></li>';
            }
        elseif (isset ($detail[1])) {
          $pagename = explode(".",$detail[1]);
          $pagename = $pagename[0];
          echo '<li><a href="/pages/'.str_replace($linkChars,$linkRplce,$get_folder).'/'.str_replace($linkChars,$linkRplce,$dirname[1]).'/'.str_replace($linkChars,$linkRplce,$pagename).'">';
          echo str_replace('[plus]','+',$pagename).'</a></li>'; 
          }
        }
    
    echo "</ul>";
    
    }
  }

echo '</div>';



echo '<!--googleon: all-->';

    if ($get_subfolder != "") { // Parse the appropriate content for the page
      echo '<div class="parsebox">'; $parsediv = 1;
      foreach ($dir as $subdir) { // Above, the links are made into human-readable titles. This finds the actual names of the folders and files, in order to access the content.
        if (strpos($subdir,$get_subfolder) !== false) {
            $this_subdir = $subdir;
            $files = scandir("content_main/".$get_folder."/".$subdir, 1);
            if ($get_page != "") { // Get the page in question if one's been specified
              foreach ($files as $page) {
                if (strpos($page,$get_page) !== false) {
                  $this_page = $page;
                  }
                }
              } else { // Otherwise pull out the first page in the folder to display
              $files = array_reverse($files);
              $this_page = $files[2]; // The '2' means we skip . and .. in the directory array
              }
            }
          }
    
      if (isset($this_page) && file_exists("content_main/".$get_folder."/".$this_subdir."/".$this_page)) {
        $dir = "content_main/".$get_folder."/".$this_subdir."/".$this_page;
        if(strpos($this_page, ".txt") !== false) { // This page is a single plain text file
          $dir = "content_main/".$get_folder."/".$this_subdir;
          $parts = array($this_page);
          }
          include('parsing/parsebox.php');
      	}

			  else { // Displays an error if the page can't be found
				  echo "<style> body { background-image: url('/styles/imgs/error.png'); background-position: right bottom; background-repeat: no-repeat; background-attachment: fixed; background-size: 980px auto; } </style>";
				  echo '<div class="parsebox">';
          echo "<h1>Oh dear!</h1>";
          echo "<p>This page seems to be lost. You could go back to the home page and try again, or check down the back of sofa. If you think there's an error, you could <a href=\"/pages/Information/General information/Contact us\">contact us</a> to report the problem.</p>";
          echo "</div>";
			  	}
      echo "</div>";
			}

include('footer.php');

?>