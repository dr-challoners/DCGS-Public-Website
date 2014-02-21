<?php

function check_file_not_article($var) { //This sequence creates an array of everything that is NOT a news story file
	if (strpos($var,".txt") == "") { return true; }
	}
$notnews = array_filter($newsfiles,"check_file_not_article");

$newsposts = array_diff($newsfiles,$notnews); //Then remove all the files that are not news stories
$newsposts = array_values($newsposts); //Re-value the keys so that the news stories go sequentially
$newsposts = array_slice($newsposts,0,15); //Return the first 15 files (12 or 13 will display on the front page, then all of them on an individual news story)

for ($post = 0; $post < 15;) { //Cut .txt from the end of the filename (this just helps to disguise the file later, when passing it through the URL to an individual news story)
	$filename = explode(".",$newsposts[$post]);
	$newsposts[$post] = $filename[0];
	$post++;
	}

//Now use the array of items that are not news to create an array of image names

$tempkeys = array();
$tempvalues = array();

foreach ($notnews as $row) {
	if (strpos($row,".") != "" && $row != "." && $row != ".." && $row != "index.php") { //Removes directory elements
		array_push($tempkeys,array_search($row,$notnews));
		$row = substr($row,0,strpos($row,".")); //Cuts off the filename
		if (substr($row,0,strpos($row,"~")) != "") { //Cuts off the photographer info, if any
			$row = substr($row,0,strpos($row,"~"));
			}
		array_push($tempvalues,$row);
		}
	}
	
$newsimages = array_combine($tempkeys,$tempvalues);

?>
