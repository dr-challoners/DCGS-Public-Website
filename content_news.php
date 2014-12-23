<?php
include ('header_declarations.php');

if (!isset($_GET['story'])) { $get_story = ""; } else { $get_story = str_replace('_',' ',$_GET['story']); }

// Meta tags for Facebook sharing, provided there's a story to display
if ($get_story != "index.php") {
  echo '<meta property="og:type" content ="article" />';
  echo '<meta property="og:title" content ="'.explode("~",$get_story)[1].' - News from Challoner\'s" />';
  echo '<meta property="og:site_name" content ="Dr Challoner\'s Grammar School" />';
  echo '<meta property="og:image" content ="http://'.$_SERVER['SERVER_NAME'].'/styles/imgs/fb-shared-post.png" />';
}

include ('header_navigation.php');

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
			echo "<a href=\"".$component[0]."~".str_replace(' ','_',$component[1]);
			if (isset($component[2])) {
				echo "~".str_replace(' ','_',$component[2]);
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
echo '<h3>'.date("jS F Y",mktime(0,0,0,substr($component[0],4,2),substr($component[0],6,2),substr($component[0],0,4))).'</h3>';

$parsediv = 1;
$dir = 'content_news/'.$get_story;
include('parsing/parsebox.php');
  
echo '<div class="sharing lrg">';  

  // Share on Facebook
  echo '<iframe src="//www.facebook.com/plugins/share_button.php?href=';
  $shareurl = 'http://www.challoners.com/news/'.$get_story;
  echo urlencode($shareurl);
  echo '&amp;layout=button" scrolling="no" frameborder="0" style="border:none; overflow:hidden;" allowTransparency="true"></iframe>';
  
  // Share on Twitter
  echo '<a href="https://twitter.com/share" class="twitter-share-button" data-text="';
  echo 'News from DCGS: '.$component[1];
  echo '" data-via="ChallonersNews" data-count="none">Tweet</a>';
  echo "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>";
  
echo '</div>';
  
// If an author (and an editor) has been given, display their name(s)
if (isset($component[2])) {
  echo '<p class="credit">'.$component[2];
  if (isset($component[3])) { echo '<br /><span>Edited by '.$component[3].'</span>'; }
  echo '</p>'; }


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