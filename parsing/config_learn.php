<?php
  $rootpath = "learn/"; //This is the directory to the main website (will be "" unless the website is some sub-portion of the actual site)
  $codepath = "parsing/"; //This is the directory to the ParseBox files 
  $contentpath = "../content_learn/"; //This is the path from the main site to the main folder for storing content

  //When referring to ParseBox in the code to pull out files, the folder containing page files must be specified as $dir

  $pagewidth = 560; //Must be specified for various styles
  if (isset($ConfigColour)) {
    $colour = str_replace("#","hex",$ConfigColour); //The colour for active elements such as links, or set to "" for a default grey. To use hexcodes, write as 'hexNNNNNN'.
    } else { $colour = ""; }

  include('head_includes.php');
?>