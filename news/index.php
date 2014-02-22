<?php
include ('../header_declarations.php');
include ('../header_navigation.php');
?>

<div class="ncol lft submenu">

<?
$newsfiles = scandir("./", 1); //Calls up all the files in the news folder
include ('../php/make_news_arrays.php');
echo "<h3 class=\"sml\">See more in this section</h3>";
echo "<ul>";
foreach ($newsposts as $row) {
		$component = explode("~",$row);
		echo "<li>";
			echo "<a href=\"?story=".$component[0]."~".$component[1]."~".$component[2]."\">";
			echo $component[1];
			echo "</a>";
		echo "</li>";
	}
echo "</ul>";
?>

</div>
<div class="mcol-rgt">

<?
$component = explode("~",$_GET['story']);

echo "<h1>".$component[1]."</h1>";
echo "<h3>".date("jS F Y",mktime(0,0,0,substr($component[0],4,2),substr($component[0],6,2),substr($component[0],0,4),0))."</h3>";

$image = array_search($component[1],$newsimages);
if ($image != "") {
	$image = addcslashes($newsfiles[array_search($component[1],$newsimages)],"'");
	echo "<div class=\"newsimg\" style=\"background-image: url('".$image."');\"></div>";
	}

$content = file_get_contents('./'.$_GET['story'].".txt", true);
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

<? include ('../footer.php'); ?>