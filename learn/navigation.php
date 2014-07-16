<div class="navigation">

<?php if (isset($_GET['subject'])) { // If there's no subject set they shouldn't be here, so don't display any directory - an error message will display in the content

echo "<h1><a href=\"/".$rootpath.$_GET['subject']."\">Home</a></h1>";

$site = scandir("../content_learn/".$_GET['subject'], 1); //First, get all the main directories in the micro-site being looked at
$site = array_reverse($site);

foreach ($site as $dir) { // Now make a list of all the main directories
	$dirname = explode("~",$dir);
	if (isset($dirname[1])) { // As long as directories are in the form NUMBER~NAME, they will be listed: INDEX, config.php and background.jpg therefore won't be listed
		echo "<h1>".$dirname[1]."</h1>";
		
		// Now deal with the sub-directories in the main directory
		$directory = scandir("../content_learn/".$_GET['subject']."/".$dir, 1);
		$directory = array_reverse($directory);

		foreach ($directory as $subdir) {
			$subdirname = explode("~",$subdir);
			if (isset($subdirname[1])) { // Again, items won't be displayed if they're not in the form NUMBER~NAME
				
				// We need to immediately get the contents of each subdirectory folder, to see if it's actually a subdirectory or if it's a page
				$subdirectory = scandir("../content_learn/".$_GET['subject']."/".$dir."/".$subdir, 1);
				array_pop($subdirectory); // Drop the '.' and '..' items from the subdirectory, as we're going to be looking for '.' to see if there's files in the folder
				array_pop($subdirectory);
				$subdirectory = array_reverse($subdirectory);

				$checkdir = false;
				foreach ($subdirectory as $item) {
					if (strpos($item,".") !== false || strpos($item,"~GALLERY") !== false) { // Either files, or folders marked ~GALLERY, should indicate that we've reached a page
						$checkdir = true;
						break; // No need to finish the whole directory if we've found one
						}
					}

				if ($checkdir === true) { // It's a page, so just link straight to it
					echo "<h2><a href=\"/".$rootpath.$_GET['subject']."/".$dirname[1]."/".$subdirname[1]."\">";
						echo "<span>&nbsp;&nbsp;</span>";
						echo $subdirname[1];
					echo "</a></h2>";
					}
				else { // Otherwise, finally, create a list of the pages in the subdirectory
					
					echo "<div class=\"dropdown";
						if (isset($_GET['page'])) { // If we're on a page in this menu, keep the menu open
							foreach ($subdirectory as $item) {
								if (strpos($item,$_GET['page']) !== false) {
									echo " open";
									}
								}
							}
					echo "\" name=\"navmenu\" id=\"".str_replace("'","",$subdirname[1])."\">";
					
					echo "<h2><a href=\"javascript:openClose('".str_replace("'","",$subdirname[1])."','navmenu')\">";
						echo "<span class=\"open\">– </span>";
						echo "<span class=\"clsd\">+ </span>";
						echo $subdirname[1];
					echo "</a></h2>";
					
					echo "<ul>";
					foreach ($subdirectory as $page) {
						$pagename = explode("~",$page);
						if (isset($pagename[1]) && $pagename[1] != "GALLERY") { // The second condition is just in case a user has made a mistake and created some combined subdirectory/page folder
							echo "<li><a href=\"/".$rootpath.$_GET['subject']."/".$dirname[1]."/".$subdirname[1]."/".$pagename[1]."\">".$pagename[1]."</a></li>";
							}
						}
					echo "</ul>";
					
					echo "</div>";
					}
				
				}
			}
		
		}
	}

/*
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
  */
 } ?>
 
 </div>