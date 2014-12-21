<?php if (isset($_GET['subject'])) { // If there's no subject set they shouldn't be here, so don't display any directory - an error message will display in the content

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
						//echo "<span>&nbsp;&nbsp;</span>";
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
 } ?>