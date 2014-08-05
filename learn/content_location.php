<?php

// Content links should be in the form challoners.com/learn/SUBJECT/FOLDER/SUBFOLDER/PAGE

if ((isset($_GET['folder']) && !isset($_GET['page']))) {
	// Error check: if the folder has been set then it should lead to a page, but if something is missing later in the URL then an error is returned
	echo "<h1>Erm...</h1>";
	echo "<p>It looks like there's something missing from the web address you're using: the folder, or the subfolder, or the page reference itself. Please check the address, or perhaps try reaching the page from the navigation menu on the left.</p>";
	}
elseif (isset($_GET['page'])) { // If this is false, then the INDEX page content will be returned instead (below)

	// all of the folders, subfolders and page folders should be given in the form NUMBER~NAME for easy ordering. However, we don't want to have to include NUMBER in the URL. This finds the appropriate folders etc without the NUMBER being there.
	
	$folders = scandir($contentpath.$_GET['subject'], 1);
	foreach ($folders as $folder) {
		$foldername = explode("~",$folder);
		if (isset($foldername[1])) { $foldername = $foldername[1]; } else { $foldername = $foldername[0]; } // This is so that hidden directories and pages, which don't begin NUMBER~ are still possible to navigate to.
		if (strtolower($foldername) == strtolower($_GET['folder'])) {
			$subfolders = scandir($contentpath.$_GET['subject']."/".$folder, 1);
			break;
			}
		}
	if (isset($_GET['subfolder'])) {
		foreach ($subfolders as $subfolder) {
			$subfoldername = explode("~",$subfolder);
			if (isset($subfoldername[1])) { $subfoldername = $subfoldername[1]; } else { $subfoldername = $subfoldername[0]; }
			if (strtolower($subfoldername) == strtolower($_GET['subfolder'])) {
				$pages = scandir($contentpath.$_GET['subject']."/".$folder."/".$subfolder, 1);
				break;
				}
			}
		foreach ($pages as $page) {
			$pagename = explode("~",$page);
			if (isset($pagename[1])) { $pagename = $pagename[1]; } else { $pagename = $pagename[0]; }
			if (strtolower($pagename) == strtolower($_GET['page'])) {
				$parts = scandir($contentpath.$_GET['subject']."/".$folder."/".$subfolder."/".$page, 1);
				$page_check = 1;
				break;
				}
			}
		}
	else {
		foreach ($subfolders as $page) {
			$pagename = explode("~",$page);
			if (isset($pagename[1])) { $pagename = $pagename[1]; } else { $pagename = $pagename[0]; }
			if (strtolower($pagename) == strtolower($_GET['page'])) {
				$parts = scandir($contentpath.$_GET['subject']."/".$folder."/".$page, 1);
				$page_check = 1;
				break;
				}
			}
		}
	
	if (!isset($page_check)) {
		echo "<h1>Erm...</h1>";
		echo "<p>It looks like there's something wrong with the web address you're using: the page that has been specified cannot be found. Please check the address, or perhaps try reaching the page from the navigation menu on the left.</p>";
		}
	else { // Now let's get some content!
		
		$pagetitle = explode("~",$page);
		if (!isset($pagetitle[1])) { // If there was no ~ then it's a hidden page, and should be marked up as such
			$hidden = true; $pagetitle = $pagetitle[0];
			}
		else {
			$hidden = false; $pagetitle = $pagetitle[1];
			}
		
		echo "<h1>".$pagetitle."</h1>";
		
		if (isset($_GET['subfolder'])) {
			$dir = $contentpath.$_GET['subject']."/".$folder."/".$subfolder."/".$page; // Declare this variable so the whole directory structure doesn't have to be repeated below
			}
		else {
			$dir = $contentpath.$_GET['subject']."/".$folder."/".$page;
			}
    unset($parts);
		include('../'.$codepath.'parsebox.php'); //Need to change the dir depending on the way the system is set up
		
		}
	}
elseif (isset($_GET['subject'])) { // There needs to be a subject set. If there is at least this, but nothing else, then pull up the index page
	
	if (isset($ConfigWelcomeTitle)) { echo "<h1>".$ConfigWelcomeTitle."</h1>"; }
	else { echo "<h1>Welcome</h1>"; }

	$dir = $contentpath.$_GET['subject']."/INDEX";
  unset($parts);
	include('../'.$codepath.'parsebox.php');

	}
else {
	// Error check: If nothing's been set, there's nowhere to go, so display an error
	echo "<h1>Welcome to nowhere</h1>";
	echo "<p>There's nothing here. It's a bit quiet, isn't it? You need to type a subject into the address bar at the top of your browser. Alternately hit the 'back' button, take a deep breath, and try something else.</p>";
	}