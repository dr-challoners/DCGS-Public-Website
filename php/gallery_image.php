<?php

			echo "<div class=\"photostub ".$thisbox."\" style=\"";
				shuffle($hplace); shuffle($vplace);
				echo "background-position: ".$hplace[0]." ".$vplace[0]."; ";
				echo "background-image: url('/".$rootpath.$dir."/".$part."/".$photo."');";
			echo "\">";
			echo "<a href=\"/".$rootpath.$dir."/".$part."/".$photo."\" ";
			echo "data-lightbox=\"gallery\" "; // All images in all galleries on a page can be flicked through as part of the same set
			$phototitle = explode(".",$photo);
			echo "data-title=\"$phototitle[0]\"></a>"; // Provides a caption
			echo "</div>";
			
?>