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
    <a href="/pages/Information/Admissions/"><h3>Admissions</h3></a>
	</div>
	<div class="linkbox">
    <a href="/pages/Information/General_information/Staff_vacancies"><h3>Vacancies</h3></a>
	</div>
	<div class="linkbox">
    <a href="/pages/Information/Supporting_us/"><h3>Supporting us</h3></a>
	</div>
  <a href="http://www.astra-alliance.com/" target="_BLANK"><img class="linkbutton" src="./styles/imgs/astraLAbutton.png" alt="Astra Learning Alliance" /></a>
	
	<div class="twitter-header" id="news"><a href="https://twitter.com/ChallonersNews" target="_BLANK"><p>DCGS News <span>Follow</span></p></a></div>
	<a class="twitter-timeline"  href="https://twitter.com/ChallonersNews" data-chrome="noborders noheader nofooter" data-widget-id="430598364401373184">Tweets by @ChallonersNews</a>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

	<div class="twitter-header" id="sprt"><a href="https://twitter.com/ChallonersSport" target="_BLANK"><p>DCGS Sport <span>Follow</span></p></a></div>
	<a class="twitter-timeline"  href="https://twitter.com/ChallonersSport" data-chrome="noborders noheader nofooter" data-widget-id="430597618125664256">Tweets by @ChallonersSport</a>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
	
</div>
<div class="mcol">

<h1 class="dcgs sml">Welcome to Dr Challoner's Grammar School</h1>

<?php
if (preg_match('/(?i)msie [4-7]/',$_SERVER['HTTP_USER_AGENT']) || isset($_GET['updatebrowser'])) { // Notification to upgrade for older versions of IE
  echo '<div class="old_browser_notice lrg">'; // Put in the 'lrg' because the notice *shouldn't* appear on mobiles, but that'll catch it if it does
  echo '<h1>IMPORTANT: Your browser is out of date</h1>';
  echo '<p>While efforts have been made to ensure compatibility with some older internet browsers, it is costly and impractical to support them all. Your browser is unfortunately too old, and this website will not run correctly on it.</p>';
  echo '<p>You can use the links below to update or replace your browser <i>for free.</i> As well as being able to use this site, you will enjoy a faster, fuller and more secure internet experience overall.</p>';
  echo '<p><img src="./styles/imgs/upgradeto_chrome.png" /><a href="http://www.google.com/chrome/" target="_BLANK">Switch to Chrome - a cutting-edge, straightforward browser</a></p>';
  echo '<p><img src="./styles/imgs/upgradeto_firefox.png" /><a href="http://www.getfirefox.com/" target="_BLANK">Switch to Firefox - a friendly browser that\'s easily customised</a></p>';
  echo '<p><img src="./styles/imgs/upgradeto_ie.png" /><a href="http://www.getie.com/" target="_BLANK">Upgrade Internet Explorer</a></p>';
  echo '</div>';
}
include ('override_display.php');
include ('magazine.php');
?>

</div>

<?php include('footer.php'); ?>