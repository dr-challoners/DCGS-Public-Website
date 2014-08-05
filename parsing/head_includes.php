<?php date_default_timezone_set("Europe/London"); ?>

<script src="/<?php echo $codepath; ?>functions.js"></script>

<?php
  echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\"";
  echo ' href="/'.$codepath.'parsebox.css.php?pagewidth='.$pagewidth;
    if($colour != "") { echo "&colour=".$colour; }
  echo "\"/>\n";
?>

<!-- This parses mathematical code. See http://www.mathjax.org/ for documentation. -->
<script type="text/javascript"
	src="https://c328740.ssl.cf1.rackcdn.com/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML">
</script>

<!-- Pop-up images in galleries -->
<script src="/<?php echo $codepath; ?>lightbox/jquery-1.11.0.min.js"></script>
<script src="/<?php echo $codepath; ?>lightbox/lightbox.min.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="/<?php echo $codepath; ?>lightbox/lightbox.css"/>
