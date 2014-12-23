<?php

$photo = array_pop($photos);

$pTitle = explode(".",$photo);
$pTitle = explode("=",$pTitle);
if (isset($pTitle[1])) { $pCredit = $pTitle[1]; }
$pTitle = implode(" - ",$pTitle);

shuffle($hplace); shuffle($vplace);

echo '<div class="photostub '.$box.'\' style="';
	echo 'background-position: '.$hplace[0].' '.$vplace[0].'; ';
	echo "background-image: url('/".$rootpath.$filedir."/".$photo."');";
echo "\">";

	echo '<a href="/'.$rootpath.$filedir.'/'.$photo.'"';
		echo ' data-lightbox="gallery"'; // All images in all galleries on a page can be flicked through as part of the same set
    if (strtolower(substr($pTitle,0,9)) != "nocaption") {	
      echo ' data-title="'.$pTitle.'"';
    } elseif (isset($pCredit)) {
      echo ' data-title="'.$pCredit.'"';
    }
    echo '>';
  echo '</a>';

  if (isset($pCredit)) { echo '<p>'.$pCredit.'</p>'; }

echo '</div>';
			
?>