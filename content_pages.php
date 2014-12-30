<?php 
include('header_declarations.php');

if (!isset($_GET['folder'])) { $get_folder = ""; } else { $get_folder = str_replace($linkRplce,$linkChars,$_GET['folder']); }
if (!isset($_GET['subfolder'])) { $get_subfolder = ""; } else { $get_subfolder = str_replace($linkRplce,$linkChars,$_GET['subfolder']); }
if (!isset($_GET['page'])) { $get_page = ""; } else { $get_page = str_replace($linkRplce,$linkChars,$_GET['page']); }
	
include('header_navigation.php');

echo '<!--googleoff: all--><div class="ncol lft submenu lrg">'; // Building the submenu

$dir = scandir("content_main/".$get_folder, 1); // First, get all the subdirectories in the main directory being looked at
$dir = array_reverse($dir);

foreach ($dir as $subdir) { // List all the subdirectories
  $dirname = explode("~",$subdir);
  if (isset($dirname[1])) { // This is a cheap and cheerful way to confirm that the object being looked at is a folder, but it requires ALL subdirectories to be in the form 'X~NAME'
    echo "<h2><a href=\"javascript:openClose('".str_replace("'","",$dirname[1])."')\">".$dirname[1]."</a></h2>";
    
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
      
echo "<!--googleon: all--></div>";

echo '<div class="parsebox">'; $parsediv = 1;

    if ($get_subfolder != "") { // Parse the appropriate content for the page
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
			  }
      else { // We're not looking at a specific page, so display the welcome message
        echo '<h1>'.$get_folder.'</h1>';
        echo '<p>';
        switch ($get_folder) {
          case 'Information':
            echo 'In this section you will find answers to your various administrative queries.<br />If you can\'t see what you are looking for, please <a href="/pages/Information/General information/Contact us">contact us</a>.';
          break;
          case 'Student life':
            echo 'Find out more in this section about the wide range of experiences and development opportunities available to all our students.';
          break;
          case 'Showcase':
            echo 'We are immensely proud of our students. This section highlights some of the many activities our students have been involved in, as well as some of their accomplishments.';
          break;
          case 'Overview':
            echo 'Welcome to Challoner\'s!<br />In this section, we hope to give you a broad idea of our aims and ethos.';
          break;
          }
        echo '</p>';
        $bkgds = scandir('./styles/bkgds/',1);
        $n = count($bkgds);
        $bkgd = 'bkgd'.rand(1,$n-2).'.jpg';
        echo '<style>';
          echo 'body { ';
            echo 'background-image: url(/styles/bkgds/'.$bkgd.');';
            echo 'background-repeat: no-repeat;';
            echo 'background-attachment: fixed;';
            echo 'background-position: right bottom;';
            echo 'background-position: right calc(100% - 33px);';
          echo ' }';
          echo 'div.parsebox { background-color: rgba(256,256,256,0.7); }';
        echo '</style>';
        }
	
echo "</div>";

include('footer.php');

?>