<h1>Override controls</h1>

<?php

if ($_GET['override'] != "") { //A change to the override status has been requested, so make the change
	file_put_contents("override_status.txt",$_GET['override']);
	echo "<p>The override status has been changed to <strong>".$_GET['override']."</strong></p>";
	}
	
?>

<p>
<a href="?override=none">Turn off the override</a><br />
<a href="?override=closure">School closure</a><br />
<a href="?override=snow_amber">Snow: amber status</a><br />
<a href="?override=advert">Advert</a>
</p>