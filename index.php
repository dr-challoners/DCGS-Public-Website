<?php include('header_declarations.php'); ?>
<?php if (!preg_match('/(?i)msie [4-8]/',$_SERVER['HTTP_USER_AGENT'])) { // IE 8 or earlier can't handle media queries
  echo '<link rel="stylesheet" type="text/css" media="screen and (min-device-width : 480px)" href="/styles/homepage_lrg.css"/>';
  echo '<link rel="stylesheet" type="text/css" media="screen and (max-device-width : 480px)" href="/styles/homepage_sml.css"/>';
} else {
  echo '<link rel="stylesheet" type="text/css" href="/styles/homepage_lrg.css"/>';
} ?>

<?php include('header_navigation.php'); ?>

<div class="ncol rgt lrg">
	<div class="linkbox">
    <a href="/pages/Information/Admissions/General information"><h3>Admissions</h3></a>
	</div>
	<div class="linkbox">
    <a href="/pages/Information/General information/Staff vacancies"><h3>Vacancies</h3></a>
	</div>
	<div class="linkbox">
    <a href="/pages/Information/Supporting us/Annual Giving Programme"><h3>Supporting us</h3></a>
	</div>
  <a href="http://www.astra-alliance.com/" target="_BLANK"><img class="linkbutton" src="./styles/imgs/astraLAbutton.png" alt="Astra Learning Alliance" /></a>
	
	<div class="twitter-header" id="news"><a href="https://twitter.com/ChallonersNews"><p>DCGS News <span>Follow</span></p></a></div>
	<a class="twitter-timeline"  href="https://twitter.com/ChallonersNews" data-chrome="noborders noheader nofooter" data-widget-id="430598364401373184">Tweets by @ChallonersNews</a>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

	<div class="twitter-header" id="sprt"><a href="https://twitter.com/ChallonersSport"><p>DCGS Sport <span>Follow</span></p></a></div>
	<a class="twitter-timeline"  href="https://twitter.com/ChallonersSport" data-chrome="noborders noheader nofooter" data-widget-id="430597618125664256">Tweets by @ChallonersSport</a>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
	
</div>
<div class="mcol">

<h1 class="dcgs sml">Welcome to Dr Challoner's Grammar School</h1>

<?php
include ('override_display.php');
include ('magazine.php');
?>

</div>

<?php include('footer.php'); ?>