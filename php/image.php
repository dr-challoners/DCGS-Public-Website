<?php

echo "<div class=\"photostub ".$thisbox."\" style=\"";
		
shuffle($hplace); shuffle($vplace);
echo "background-position: ".$hplace[0]." ".$vplace[0]."; ";
echo "background-image: url('/content_plain/".$get_folder."/".$gallery."/".$photo."');";
		
echo "\"><a href=\"/image/".$get_folder."/".$get_gallery."/".$photo."\"></a></div>";

?>