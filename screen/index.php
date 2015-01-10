<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:svg="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"  xmlns:xml="http://www.w3.org/XML/1998/namespace">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-Style-Type" content="text/css" />
    <title>DCGS TV News</title>
    <link rel="icon" href="/styles/imgs/favicon.png" />
		<link rel="shortcut icon" href="/styles/imgs/favicon.png" />
    <link rel="stylesheet" type="text/css" media="screen" href="/styles/general.css"/>
    
    <?php
      include_once('../parsing/Parsedown.php'); // Converts markdown text to HTML - see parsedown.org
      include('configScreen.php');
		?>
    <!-- Put the small styles after the ParseBox styles to make them easier to override. This is all very messy, but it can be tidied up later... for now, let's get the job done! -->
    <?php if (!preg_match('/(?i)msie [4-8]/',$_SERVER['HTTP_USER_AGENT'])) {
      echo '<link rel="stylesheet" type="text/css" media="screen and (max-device-width : 480px)" href="/styles/screen_sml.css"/>';
      } ?>
    
    <link rel="stylesheet" type="text/css" media="screen" href="screenStyles.css"/>
    
  </head>
  <body>

    <div class="screen lrg">
    
<?php

$newsposts = scandir("../content_news/", 1); // Calls up all the files in the news folder
array_pop($newsposts);
array_pop($newsposts); // Removes . and .. from the array

if (!isset($_GET['display'])) { $d = 1; } else { $d = $_GET['display']; }
$d = $d-1;

$post = $newsposts[$d];

$component = explode("~",$post);

echo "<h1>".$component[1]."</h1>";
echo '<h3>'.date("jS F Y",mktime(0,0,0,substr($component[0],4,2),substr($component[0],6,2),substr($component[0],0,4))).'</h3>';

$dir = '../content_news/'.$post;
include('../parsing/parsebox.php');

?>
      
    </div>
    <style> .parsebox { font-size: 150%; } </style>
  </body>
</html>