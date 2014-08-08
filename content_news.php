<?php
include ('header_declarations.php');
include ('header_navigation.php');

if (!isset($_GET['story'])) { $get_story = ""; } else { $get_story = $_GET['story']; }

if ($get_story != "index.php") {

?>

<!--googleoff: all--><div class="ncol lft submenu lrg">
  <h2 class="news">News</h2>

<?php
$newsposts = scandir("content_news/", 1); //Calls up all the files in the news folder
$newsposts = array_slice($newsposts,0,15);
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
<!--googleon: all--><div class="parsebox">

<?php
$component = explode("~",$get_story);

echo "<h1>".$component[1]."</h1>";
echo "<h3 class=\"newsdate\">".date("jS F Y",mktime(0,0,0,substr($component[0],4,2),substr($component[0],6,2),substr($component[0],0,4)))."</h3>";

$parsediv = 1;
$dir = 'content_news/'.$get_story;
include('parsing/parsebox.php');

if (isset($component[2])) { // Checks to see if an author has been given
  echo "<p class=\"credit\">".$component[2]."</p>";
  }

?>

</div>

<?php
	}
else { //Displays an error if a story hasn't been set
	echo "<style> body { background-image: url('/styles/imgs/error.png'); background-position: right bottom; background-repeat: no-repeat; background-attachment: fixed; background-size: 980px auto; } </style>";
  echo '<div class="parsebox">';
  echo "<h1>Oh dear!</h1>";
	echo '<p>You appear to have navigated to nowhere. Try going <a href="/">back to the home page</a> and having another go.</p>';
  echo '</div>';
	} 

include ('footer.php'); ?>