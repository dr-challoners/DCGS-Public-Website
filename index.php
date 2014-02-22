<?php include('header_declarations.php'); ?>

<link rel="stylesheet" type="text/css" media="screen and (min-device-width : 480px)" href="/styles/homepage_lrg.css"/>
<link rel="stylesheet" type="text/css" media="screen and (max-device-width : 480px)" href="/styles/homepage_sml.css"/>

<?php include('header_navigation.php'); ?>

<div class="ncol rgt lrg">

	<div class="linkbox">
		<a href="/about/admissions/"><h3>Admissions</h3>
	</div>
	<div class="linkbox">
		<a href="/about/learning/?page=results"><h3>Results</h3>
	</div>
	<div class="linkbox">
		<a href="/about/vacancies/"><h3>Vacancies</h3>
	</div>
	
	<div class="twitter-header" id="news"><a href="https://twitter.com/ChallonersNews"><p>DCGS News <span>Follow</span></p></a></div>
	<a class="twitter-timeline"  href="https://twitter.com/ChallonersNews" data-chrome="noborders noheader nofooter" data-widget-id="430598364401373184">Tweets by @ChallonersNews</a>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

	<div class="twitter-header" id="sprt"><a href="https://twitter.com/ChallonersSport"><p>DCGS Sport <span>Follow</span></p></a></div>
	<a class="twitter-timeline"  href="https://twitter.com/ChallonersSport" data-chrome="noborders noheader nofooter" data-widget-id="430597618125664256">Tweets by @ChallonersSport</a>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
	
</div>
<div class="mcol">

<h1 class="dcgs sml">Welcome to Dr Challoner's Grammar School</h1>

<?
include ('override.php');
include ('magazine.php');
include ('highlight.php');
?>

</div>

<? include('footer.php'); ?>