<?php

$newsfiles = scandir("./news/", 1); //Calls up all the files in the news folder
include ('./php/make_news_arrays.php');
	
for ($non = 0; $non <= 12;) { //Mark posts that don't have images
	$components = explode("~",$newsposts[$non]);
	if (!in_array($components[1],$newsimages)) { //First identify the current post as not having an image
		$newsposts[$non] = "NON~".$newsposts[$non]; //Then mark it as being so in the array
		}
	$non++;
	}
for ($non = 0; $non <= 12;) { //Then mark appropriate neighbours to each post
	if (substr($newsposts[$non],0,4) == "NON~") { //Only perform a check if the current post has no image
		if ($non == 0 && (substr($newsposts[$non+1],0,4) != "NON~")) { //If the first story doesn't have an image and the second one does, match up the second one
			$newsposts[$non+1] = "NON~".$newsposts[$non+1];
			}
		elseif ($non == 1 && (substr($newsposts[$non-1],0,4) != "NON~") && (substr($newsposts[$non+1],0,4) != "NON~")) { //If the second story doesn't have an image and the first and third do, match up the third
			$newsposts[$non+1] = "NON~".$newsposts[$non+1];	
			}
		elseif ($non > 0 && (substr($newsposts[$non-1],0,4) == "NON~") && (substr($newsposts[$non+1],0,4) == "NON~")) { //We're in the middle of a run, so keep switching odd/even tracker
			if ($odd == 0) { $odd = 1; } else { $odd = 0; }
			}
		elseif ($non > 0 && (substr($newsposts[$non-1],0,4) == "NON~") && (substr($newsposts[$non+1],0,4) != "NON~") && $odd == 1) { //We've reached the end of a run, and there's been an odd number of stories with no images so far, so add one more
			$newsposts[$non+1] = "NON~".$newsposts[$non+1];
			$odd = 0;
			}
		elseif ($non > 0 && (substr($newsposts[$non-1],0,4) != "NON~") && (substr($newsposts[$non+1],0,4) != "NON~")) { //It's a news post with no image all on its own
			$plusminus = rand(0,1); //This randomly picks a story either side to match
			if ($plusminus == 0) { $plusminus = -1; }
			$newsposts[$non+$plusminus] = "NON~".$newsposts[$non+$plusminus];
			}
		}
	$non++;
	}
	
if ($override != 1) { //As long as there's not an override happening
$a = 0; while ($big == "") { //Find the first post with an image and mark it as the leading 'big' story
	if (substr($newsposts[$a],0,4) != "NON~") {
		$newsposts[$a] = "BIG~".$newsposts[$a];
		$big++;
		}
	$a++;
	}
	}
	
$runs = array();
for ($bar = 0; $bar <= 8;) { //Find every run of four stories all with images by first finding the key of the first image in each run
	if (substr($newsposts[$bar],3,1) != "~" && substr($newsposts[$bar+1],3,1) != "~" && substr($newsposts[$bar+2],3,1) != "~" && substr($newsposts[$bar+3],3,1) != "~") { //They have neither a NON~ nor BIG~ marker
		array_push($runs,$bar);
		}
	$bar++;
	}
	
$barstart = array_rand($runs); //Pick one possible starting point at random
$barstart = $runs[$barstart];

if ($barstart != "") { //Provided there's room for a run (and there should be, otherwise you should be taking more photos!), this marks out the run
	for ($plus = 0; $plus <= 3;) {
		$barpoint = $barstart+$plus;
		$newsposts[$barpoint] = "BAR~".$newsposts[$barpoint];
		$plus++;
		}
	}

for ($std = 0; $std <= 12;) { //Finally, mark the remaining posts as being normal (this will make reading them for output easier)
	if (substr($newsposts[$std],3,1) != "~") {
		$newsposts[$std] = "STD~".$newsposts[$std];
		}
	$std++;
	}
	
//AFTER ALL THAT, LET'S DISPLAY SOME CONTENT!

function word_cutoff($text, $length) {
    if(strlen($text) > $length) {
        $text = substr($text, 0, strpos($text, ' ', $length));
		}
    return $text;
	}

echo "<div class=\"magazine\">";	
	
$count = 1; foreach ($newsposts as $post) {
	
	if ($count < 13 || ($count == 13 && $npic%2 == 1)) { //This determines a sensible place to end (12 ideally, or one more if a row with no pictures needs to be finished)
	
	//Pull out necessary components of the story
	$component = explode("~",$post);
	if ($component[0] != "NON") { $image = addcslashes($newsfiles[array_search($component[2],$newsimages)],"'"); }
	$date = date("jS F Y",mktime(0,0,0,substr($component[1],4,2),substr($component[1],6,2),substr($component[1],0,4),0));
	$file = substr($post,4);
	$story = file_get_contents('./news/'.substr($post,4).".txt", true);
	
	//Format according to type
	if ($component[0] == "BIG") {
		echo "<a href=\"./news/?story=".$file."\">";
		echo "<div class=\"big\">";
			echo "<div class=\"newsimg\" style=\"background-image: url('/rebuild/news/".$image."');\"></div>";
			echo "<h2>".$component[2]."</h2>";
			echo "<p><em>".$date."</em><span class=\"lrg\"><em>:</em> ";
			echo word_cutoff($story,120)." ...";
			echo "</span></p>";
		echo "</div>";
		echo "</a>";
		$npic = 0; $count++;
		}
	if ($component[0] == "STD") {
		echo "<a href=\"./news/?story=".$file."\">";
		echo "<div class=\"std\">";
			echo "<div class=\"newsimg\" style=\"background-image: url('/rebuild/news/".$image."');\"></div>";
			echo "<h3>".$component[2]."</h3>";
			echo "<p><em>".$date."</em><span class=\"lrg\"><em>:</em> ";
			echo word_cutoff($story,140)." ...";
			echo "</span></p>";
		echo "</div>";
		echo "</a>";
		$npic = 0; $count++;
		}
	if ($component[0] == "NON") {
		echo "<a href=\"./news/?story=".$file."\">";
		echo "<div class=\"non\">";
			echo "<h3>".$component[2]."</h3>";
			echo "<p><em>".$date."</em></p>";
		echo "</div>";
		echo "</a>";
		$npic++; $count++;
		}
	if ($component[0] == "BAR") {
		echo "<a href=\"./news/?story=".$file."\">";
		echo "<div class=\"bar\"";
			if ($bcount == 3) { echo " id=\"end\""; }
		echo ">";
			echo "<div class=\"newsimg\" style=\"background-image: url('/rebuild/news/".$image."');\"></div>";
			echo "<h3>".$component[2]."</h3>";
			echo "<p class=\"sml\"><em>".$date."</em></p>"; //Going for just the date on these story stubs
		echo "</div>";
		echo "</a>";
		$bcount++; $npic = 0; $count++;
		}
	}
	}

echo "</div>";
	
?>