<?php include('header.php'); ?>

<div class="page"> <!-- For centre-aligning the page -->

<!--googleoff: all-->	
<div class="lftcol">
  <a class="logolink" href="/<?php echo $rootpath.$_GET['subject']; ?>"><div class="header">
		<h1>Learn</h1>
		<?php if (isset($ConfigTitle)) { echo "<h2>".$ConfigTitle."</h2>"; } ?>
    </div></a>

<?php
	
// The code for the logo can go directly into this file
	
include('navigation.php'); // Drop-down menu navigation
	
?>
</div>
<!--googleon: all-->
<div class="parsebox">
<?php

$parsediv = 1;
include('content_location.php'); // The actual content for the page, generated from the folders
	
?>
</div>

<hr class="clear"> <!-- Cleaning up floating elements -->
</div>

<?php include('footer.php'); ?>