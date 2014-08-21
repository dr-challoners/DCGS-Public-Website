<?php
if (!isset($filename)) { $filename = $file[0]; } // In the unlikely event that an image hasn't been named, give its id as a title
echo '<a class="imagelink" href="/'.$rootpath.$filedir.'" data-lightbox="gallery" data-title="'.$filename.'">';
// Setting data-lightbox to gallery includes ALL images on the page as part of a Lightbox set
	echo '<img class="'.$filevalue.'" alt="'.$filename.'" src="/'.$rootpath.$filedir.'" \>';
echo "</a>";
?>