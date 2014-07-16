<?php include('header.php'); ?>

<div class="page"> <!-- For centre-aligning the page -->

<!--googleoff: all-->	
<div class="lftcol">
	<div class="header">
		<h1>Learn</h1>
		<?php if (isset($ConfigTitle)) { echo "<h2>".$ConfigTitle."</h2>"; } ?>
	</div>

<?php
	
// The code for the logo can go directly into this file
	
include('navigation.php'); // Drop-down menu navigation
	
?>
</div>
<!--googleon: all-->
<div class="rgtcol">
<?php
	
include('content_location.php'); // The actual content for the page, generated from the folders
	
?>
</div>

<hr class="clear"> <!-- Cleaning up floating elements -->
</div>

<?php include('footer.php'); ?>