<?php

echo "<div class=\"photostub ".$thisbox."\" style=\"";
		
shuffle($hplace); shuffle($vplace);
echo "background-position: ".$hplace[0]." ".$vplace[0]."; ";
echo "background-image: url('/content_plain/".$_GET['folder']."/".$gallery."/".$photo."');";
		
echo "\"><a href=\"/image/".$_GET['folder']."/".$_GET['gallery']."/".$photo."\"></a></div>";

?>