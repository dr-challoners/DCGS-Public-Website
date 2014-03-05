<?php
include ('header_declarations.php');
include ('header_navigation.php');

if ($_GET['story'] != "index.php") {

?>

<div class="ncol lft submenu">
<h3 class="sml"><a href="javascript:openClose('n3','n1','n2')">See more in this section</a></h3>";

<?php
$newsfiles = scandir("content_plain/news/", 1); //Calls up all the files in the news folder
include ('php/make_news_arrays.php');
echo "<ul id=\"n3\">";
foreach ($newsposts as $row) {
		$component = explode("~",$row);
		echo "<li>";
			echo "<a href=\"".$component[0]."~".$component[1]."~".$component[2]."\">";
			echo $component[1];
			echo "</a>";
		echo "</li>";
	}
echo "</ul>";
?>

</div>
<div class="mcol-rgt">

<?php
$component = explode("~",$_GET['story']);

echo "<h1>".$component[1]."</h1>";
echo "<h3>".date("jS F Y",mktime(0,0,0,substr($component[0],4,2),substr($component[0],6,2),substr($component[0],0,4),0))."</h3>";

$image = array_search($component[1],$newsimages);
if ($image != "") {
	$image = addcslashes($newsfiles[array_search($component[1],$newsimages)],"'");
	echo "<div class=\"newsimg\" style=\"background-image: url('/content_plain/news/".$image."');\"></div>";
	}

$content = file_get_contents('content_plain/news/'.$_GET['story'].".txt", true);
echo Parsedown::instance()->parse($content);

echo "<p class=\"credit\">".$component[2];
$imagecredit = explode("~",$image);
if ($imagecredit[1] != "") {
	$imagecredit = explode(".",$imagecredit[1]);
	echo "<br />Photograph by ".$imagecredit[0];
	}
echo "</p>";
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