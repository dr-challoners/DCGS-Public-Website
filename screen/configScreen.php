<?php
  $rootpath = ""; // This is the directory to the main website (will be "" unless the website is some sub-portion of the actual site)
  $codepath = "../parsing/"; // This is the directory to the ParseBox files 
  $contentpath = ""; // This is the path from the main site to the main folder for storing content
  $sharedpath = 'content_main/content_SHARED/'; // Necessary if you're planning on taking advantage of the shared content module

  //When referring to ParseBox in the code to pull out files, the folder containing page files must be specified as $dir

  $pagewidth = 891; //Must be specified for various styles
  $colour = "hex2358A3"; //The colour for active elements such as links, or set to "" for a default grey. To use hexcodes, write as 'hexNNNNNN'.

  include('../parsing/head_includes.php');
?>