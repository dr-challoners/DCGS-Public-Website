<?php
include ('header_declarations.php');
include ('header_navigation.php');

if (!isset($_GET['story'])) { $get_story = ""; } else { $get_story = $_GET['story']; }

if ($get_story != "index.php") {

?>

<!--googleoff: all--><div class="ncol lft submenu lrg">
  <h2 class="news">News</h2>

<?php
$newsfiles = scandir("content_plain/news/", 1); //Calls up all the files in the news folder
include_once ('php/make_news_arrays.php');
echo "<ul class=\"intranet\" id=\"n3\">"; // Although this is not the intranet, there's already a style that does exactly the same thing that's needed here.
foreach ($newsposts as $row) {
		$component = explode("~",$row);
		echo "<li>";
			echo "<a href=\"".$component[0]."~".$component[1];
			if ($component[2] != "") {
				echo "~".$component[2];
				}
			echo "\">";
			echo $component[1];
			echo "</a>";
		echo "</li>";
	}
echo "</ul>";
?>

</div>
<!--googleon: all--><div class="mcol-rgt">

<?php
$component = explode("~",$get_story);

echo "<h1>".$component[1]."</h1>";
echo "<h3>".date("jS F Y",mktime(0,0,0,substr($component[0],4,2),substr($component[0],6,2),substr($component[0],0,4)))."</h3>";

$image = array_search($component[1],$newsimages);
if ($image != "") {
	$image = addcslashes($newsfiles[array_search($component[1],$newsimages)],"'");
	echo "<div class=\"newsimg\" style=\"background-image: url('/content_plain/news/".$image."');\"></div>";
	}

$content = file_get_contents('content_plain/news/'.$get_story.".txt", true);
echo Parsedown::instance()->parse($content);

  if (isset($component[2])) { // Checks to see if an author has been given
echo "<p class=\"credit\">".$component[2];
$imagecredit = explode("~",$image);
    if (isset($imagecredit[1])) { // Tag on photography credit if necessary
	$imagecredit = explode(".",$imagecredit[1]);
	echo "<br />Photography by ".$imagecredit[0];
	}
echo "</p>";
  }
  elseif (isset($imagecredit[1])) { // If no author has been given, but there is a photography credit, display that
	$imagecredit = explode(".",$imagecredit[1]);
    echo "<p>Photography by ".$imagecredit[0]."</p>";
	}
?>

</div>

<?php
	}
else { //Displays an error if a story hasn't been set
	echo "<style> body { background-image: url('/main_imgs/error.png'); background-position: center bottom; background-repeat: no-repeat; background-attachment: fixed; background-size: 980px auto; } </style>";
	echo "<h2>Oh dear!</h2>";
	echo "<p>You appear to have navigated to nowhere. Try going back to the home page and having another go.</p>";
	} 

include ('footer.php'); ?>