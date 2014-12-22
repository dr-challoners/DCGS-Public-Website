<?php
if (isset($filename)) { // Get photographer credit, if there is any
  if (strpos($filename,"=") !== false) { $filecredit = explode("=",$filename)[1]; }
  $filename = str_replace("="," - ",$filename); // Keep the credit in the caption when viewing the image closely
}

echo '<div class="imgDiv';
  if (isset($filevalue)) { echo ' '.$filevalue; }
echo '">';
  echo '<a href="/'.$rootpath.$filedir.'" data-lightbox="gallery"'; // Setting data-lightbox to gallery includes ALL images on the page as part of a Lightbox set
    if (isset($filename)) { echo 'data-title="'.$filename.'"'; }
  echo '>';
    echo '<img';
      if (isset($filename)) { echo ' alt="'.$filename.'"'; } else { echo ' alt="Image '.$file[0].'"'; } // If there's no caption, then the alt text will just be the image id
    echo ' src="/'.$rootpath.$filedir.'" />';
  echo '</a>';
  if (isset($filecredit)) { echo '<p>'.$filecredit.'</p>'; }
echo '</div>';
?>