<!DOCTYPE HTML>
<html>
  <head>
    <title><?php if(isset($_GET['processing']) && $_GET['processing'] == 10) { echo "Done: "; } else { echo "Working: "; } ?>DCGS Sync</title>
  </head>
  <body>

<pre><?php

  //Handling authorisation with Box API
	include('BoxAPI.class.php');
	include('client_config.php');
	
	$box = new Box_API($client_id, $client_secret, $redirect_uri);
	
	if(!$box->load_token()){
		if(isset($_GET['code'])){
			$token = $box->get_token($_GET['code'], true);
			if($box->write_token($token, 'file')){
				$box->load_token();
			}
		} else {
			$box->get_code();
		}
	}

  //General functions in use here

  function checkExists($haystack) {
    global $needle;
    if(strpos($haystack, $needle) === false) { return false; } else { return true; }
    }

  function recursiveRemoveDirectory($directory) {
    foreach(glob("{$directory}/*") as $file) {
      if(is_dir($file)) { 
        recursiveRemoveDirectory($file);
        } else {
        unlink($file);
        }
      }
      rmdir($directory);
    }

//Process: take all data from Box, check it against existing data and make changes as necessary

if (!isset($_GET['initial'])) { //We need this to know where to start - otherwise produce an error
  echo "<h2>Error</h2>";
  echo "<p>A starting directory to synchronise has not been specified. Please <a href=\"./\">return to the main console</a> and try again.</p>";
  }
else {
  $initial = $_GET['initial'];
if (!isset($_GET['processing'])) {
echo "<h2>Finding files</h2>";
//If we're not being told to do processing and there are no folders set to GET then we're at the start of the process. Set the initial folder to look in on Box and open the file to write Box data to.  
if (!isset($_GET['folders'])) {
  $folders = array($initial);
  $checkfile = fopen("../sync_logs/".$initial."_latest.txt", "w");
  }
else { //Otherwise we're looking through folders - carry on doing so
  $folders = explode(",",$_GET['folders']);
  $checkfile = fopen("../sync_logs/".$initial."_latest.txt", "a");
  }

//Take one folder at a time and look at the files in it
$folder = array_shift($folders);
$folder = $box->get_folder_details($folder);
  
 //print_r($folder);
 //break;
  
echo "<p>Current folder: <b>".$folder['name']."</b></p>";
echo "<p>Entries found in this folder:</p><ul>";

unset ($oldEntries);
if (file_exists('../sync_logs/'.$initial.'_current.txt')) { // This is to check to see if the folders we're about to inspect have actually been modified
  $oldEntries = file('../sync_logs/'.$initial.'_current.txt');
}
  
// Looks at each entry in a folder and turns it into a line of data giving ID, last modification date and relative path
foreach($folder['item_collection']['entries'] as $entry) {
  echo '<li>'.$entry['name'];
  if ($entry['type'] == "folder") {
    // First compile some details to use
    $subfolder = $box->get_folder_details($entry['id']);
    $path_dirs = $subfolder['path_collection']['entries'];
    $path = array();
    foreach ($path_dirs as $dir) {
      $path[] = $dir['name'];
      }
    $path[] = $subfolder['name'];
    $path = implode("/",$path);
    $path = substr($path,33); // Cuts out the 'All files/Public Website Content/' from Box at the beginning
    // Now check to see if the folder has actually been modified - this includes its contents
    // If it hasn't been modified, then we don't need to waste time searching through it
    if (isset($oldEntries) && strpos(implode('||',$oldEntries),$subfolder['id']."|".$subfolder['modified_at']) !== false) {
      // Even though it hasn't been modified, if its entry (and sub-entries) aren't in the file they'll be deleted, so copy them in
      foreach ($oldEntries as $row) {
        if (strpos($row,$path) !== false) { fwrite($checkfile,$row); }
      }
      echo " - NOT MODIFIED";
    } else {
      fwrite($checkfile,$subfolder['id']."|".$subfolder['modified_at']."|".$subfolder['type']."|".$path.PHP_EOL);
      $folders[] = $entry['id']; // It's a folder, so add it to the list to look through in a bit
    }
  } else {
    $file = $box->get_file_details($entry['id']);
    $path_dirs = $file['path_collection']['entries'];
    $path = array();
    foreach ($path_dirs as $dir) {
      $path[] = $dir['name'];
    }
    $path[] = $file['name'];
    $path = implode("/",$path);
    $path = substr($path,33); //Cuts out the 'All files' from Box at the beginning
    fwrite($checkfile,$file['id']."|".$file['modified_at']."|".$file['type']."|".$path.PHP_EOL);
  }
  echo '</li>';
}

echo "</ul>";

if (count($folders) > 1) { echo "<p><b>".count($folders)."</b> folders currently in the queue.</p>"; }
elseif (count($folders) == 1) { echo "<p><b>1</b> folder currently in the queue.</p>"; }
else { echo "<p>All folders and files found: now processing changes.</p>"; }

// If there's more folders still to look through, repeat the process - otherwise go to the processing stage
$folders = implode(",",$folders);
if ($folders != "") {
  // Add in the DEBUG lines when debugging, remove the LIVE lines
  echo '<p><a href="'; /* DEBUG */
  // echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0;URL= /* LIVE */
  echo './system_update.php?initial='.$initial.'&folders='.$folders;
  if ($_GET['reset'] != "") { echo "&reset=y"; } // Keep track of the reset command for later (we have the reset after the file structure has been logged, to reduce downtime after file deletion).
  // echo "\">"; /* LIVE */
  echo '">Click here</a> to proceed.</p>';
  }
elseif ($_GET['reset'] != "") { // If a reset instruction has been given, delete the folders and the current log so that all the files can just be re-uploaded. A mildly drastic measure, but it's something to fall back on in case of a glitch.
  $rootfolder = $box->get_folder_details($initial);
  $initpath = "../";
  foreach ($rootfolder[path_collection][entries] as $d) {
    if ($d[sequence_id] > 0) {
      $initpath .= $d[name]."/";
      }
    }
  $initpath .= $rootfolder[name]."/";
  recursiveRemoveDirectory($initpath);
  unlink("../sync_logs/".$initial."_current.txt");
  echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0;URL=./system_update.php?initial=".$initial."&processing=1\">";
  }
else {
  fclose($checkfile);
  echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0;URL=./system_update.php?initial=".$initial."&processing=1\">";
  }
  
} else {
  $p = $_GET['processing']; //Each of the processing stages needs to be cycled through in the correct order to avoid directory discrepancies
  if($p < 10) { echo "<h2>Processing files</h2>"; } else { echo "<h2>Sync complete</h2>"; }
  if($p == 5 || $p == 6) {
    if(isset($_GET['file'])) { $f = $_GET['file']; } else { $f = 0; } $f_end = $f+5; //Setting up a file count for steps 5 and 6
    }
  
  if(!file_exists("../sync_logs/".$initial."_current.txt")) { //Make the 'current' file if this is the first time working with this directory
    fopen("../sync_logs/".$initial."_current.txt", "x+");
    }
  $current = file("../sync_logs/".$initial."_current.txt");
  $latest = file("../sync_logs/".$initial."_latest.txt");
  
  $additions = array_diff($latest,$current); //Also returns all modified files 
  $deletions = array_diff($current,$latest); //Also returns all modified files, which will need to be ignored as they're dealt with in additions
  $deletions = array_values($deletions); //Resets the keys to allow counting through the array, to break up the number of files being processed at once
  $additions = array_reverse($additions); //Allows subdirectories to be modified before directories
  
  //print_r($additions);
  //print_r($deletions);
  
  if($p == 1) {
  foreach($deletions as $line) { //File deleted
    $line = explode("|",$line);
    if($line[2] == "file") { //Delete folders later
      $needle = $line[0];
      $match = array_filter($latest, 'checkExists'); //Checks to see if the ID is already present
      if(empty($match)) {
        $match = array_shift($match);
        $path = "../".rtrim($line[3]);
        unlink($path);
        echo "File deleted: ".$path."\n";
        }
      }
    }
    echo "<p>Continuing to process files.</p>";
  }
  
  foreach($additions as $key => $line) {
    $line = explode("|",$line);
    $path = rtrim($line[3]); //Takes out the line break from the processing.
    
    //First, deal with modifications of existing items
    $needle = $line[0];
    $match = array_filter($current, 'checkExists'); //Checks to see if the ID is already present
    if(!empty($match)) {
      $match = array_shift($match);
      $oldline = explode("|",$match);
      
      if(basename($line[3]) != basename($oldline[3]) && (($p == 2 && $line[2] == "file") || ($p == 3 && $line[2] == "folder"))) { //File or folder renamed
        $oldpath = "../".rtrim($oldline[3]);
        $newpath = "../".rtrim(dirname($oldline[3])."/".basename($line[3])); //Using the 'current' directory, in case there's also been directory changes to the file that we haven't implemented yet
        rename($oldpath,$newpath);
        $type = ucwords($line[2]);
        echo $type." renamed: ".basename($oldpath)." to ".basename($newpath)."\n";
        }
      elseif(dirname($line[3]) != dirname($oldline[3]) && (($p == 4 && $line[2] == "file") || ($p == 7 && $line[2] == "folder"))) { //File or folder moved
        $oldpath = "../".rtrim($oldline[3]);
        $newpath = "../".rtrim($line[3]);
        $oldpath_modname = "../".rtrim(dirname($oldline[3]))."/".rtrim(basename($line[3]));
        if($line[2] == "file") {
          if(file_exists($oldpath)) {
            copy($oldpath,$newpath);
            unlink($oldpath);
            }
          elseif(!file_exists($newpath)) {
            mkdir(dirname($newpath),0777,true);
            $file = fopen($newpath, "w");
            fwrite($file,$box->get_file_content($line[0]));
            fclose($file);
            /*if(file_exists($oldpath_modname)) { //In case the file has been renamed as well as moved, in which case it won't appear to exist at the previous location but will still be there to be removed.
              unlink($oldpath_modname);
              }*/
            }
          }
        else {
          mkdir($newpath,0777,true);
          rmdir($oldpath);
          }
          $type = ucwords($line[2]);
          echo $type." ".basename($newpath)." moved to ".dirname($newpath)."\n";
        }
      elseif($p == 5) { //File content changed
        if($line[2] == "file" && $line[1] != $oldline[1] && ($key >= $f && $key < $f_end)) { //Breaks up the number of files that are processed at once, to prevent a 504 error
          
          $path = pathinfo($line[3]);
          $filename = rtrim($path[basename]);
          $path = "../".$path[dirname]; //We're going to create the directories at this stage - they've probably already been made, but it's a failsafe in case they haven't.
          mkdir($path,0777,true);
          $file = fopen($path."/".$filename, "w");
          fwrite($file,$box->get_file_content($line[0]));
          fclose($file);
          }
          echo "<b>Changed file:</b> ".$path."/".$filename."\n";
        }
      }
    else { //Create new directories and files
    if($line[2] == "folder" && $p == 9) {
      $path = "../".$path;
      mkdir($path,0777,true);
      echo "New folder: ".$path."\n";
      }
    elseif($p == 6)  {
      if($line[2] == "file" && ($key >= $f && $key < $f_end)) { //Breaks up the number of files that are processed at once, to prevent a 504 error
        $path = pathinfo($line[3]);
        $filename = rtrim($path[basename]);
        $path = "../".$path[dirname]; //We're going to create the directories at this stage - they've probably already been made, but it's a failsafe in case they haven't.
        mkdir($path,0777,true);
        $file = fopen($path."/".$filename, "w");
        fwrite($file,$box->get_file_content($line[0]));
        fclose($file);
        echo "<b>New file:</b> ".$path."/".$filename."\n";
        }
      }
    }
    if(isset($f)) {
      if($key >= $f) { $f++; }
      if($f == $f_end && $f_end <= count($additions)) {
        echo "<p>Continuing to process files - please wait.</p>";
        echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0;URL=./system_update.php?initial=".$initial."&processing=".$p."&file=".$f."\">";
        break;
        }
      }
    }
  
   if($p == 8) {
  foreach($deletions as $line) { //Directory deleted
    $line = explode("|",$line);
    if($line[2] == "folder") {
      $needle = $line[0];
      $match = array_filter($latest, 'checkExists'); //Checks to see if the ID is already present
      if(empty($match)) {
        $match = array_shift($match);
        $path = "../".rtrim($line[3]);
        rmdir($path);
        echo "Folder deleted: ".$path."\n";
        }
      }
    }
    echo "<p>Continuing to process files.</p>";
  } 
  
  if($p == 10) { //Set up the new 'current' file and end the process
    copy("../sync_logs/".$initial."_latest.txt","../sync_logs/".$initial."_current.txt");
    unlink("../sync_logs/".$initial."_latest.txt");
    echo "<p>This directory is up to date.</p>";
    echo "<p>Options:</p>";
    echo "<ul>";
      echo "<p><li>Go to <a href=\"./\">the main console</a> to make further updates.</li></p>";
      echo '<p><li>View your changes on <a href="/" target="page'.mt_rand().'">the public website</a> (opens a new window).</li></p>';
    echo "</ul>";
  }
  
  if ($p < 10 && (!isset($f_end) || $f_end > count($additions))) {
    $p++;
    echo "<p>Continuing to process files.</p>";
    echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0;URL=./system_update.php?initial=".$initial."&processing=".$p."\">";
    }
  }
}

	
	if (isset($box->error)){
		echo $box->error . "\n";
	}

?></pre>