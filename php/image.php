<?php

echo "<div class=\"photostub ".$thisbox."\" style=\"";
		
shuffle($hplace); shuffle($vplace);
echo "background-position: ".$hplace[0]." ".$vplace[0]."; ";
echo "background-image: url('gallery-".$_GET['gallery']."/".$photo."');";
		
echo "\"><a href=\"?gallery=".$_GET['gallery']."&image=".$photo."\"></a></div>";

?>