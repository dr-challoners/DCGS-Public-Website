<?php date_default_timezone_set("Europe/London"); ?>

<?php
  echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\"";
  echo ' href="/'.$codepath.'parsebox.css.php?pagewidth='.$pagewidth;
    if($colour != "") { echo "&colour=".$colour; }
  echo "\"/>\n";
?>

<?php // KaTeX maths render library

echo '<link rel="stylesheet" href="/'.$codepath.'katex/katex.min.css">';
echo '<script src="/'.$codepath.'katex/katex.min.js"></script>';

function str_replace_first($search, $replace, $subject) {
    $pos = strpos($subject, $search);
    if ($pos !== false) {
        $subject = substr_replace($subject, $replace, $pos, strlen($search));
    }
    return $subject;
}
  
?>

<!-- General Parsebox functions -->
<script src="/<?php echo $codepath; ?>functions.js"></script>

<!-- Sheetsee general functions -->
<script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js'></script>
<script type='text/javascript' src='/js/tabletop.js'></script>
<script type='text/javascript' src='/js/sheetsee.js'></script>
<script type='text/javascript' src='/js/moment.js'></script>
<script type='text/javascript' src='/js/diary.js'></script>

<!-- This parses mathematical code. See http://www.mathjax.org/ for documentation.
<script type="text/javascript"
	src="https://c328740.ssl.cf1.rackcdn.com/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML">
</script> -->

<!-- Pop-up images in galleries -->
<script src="/<?php echo $codepath; ?>lightbox/jquery-1.11.0.min.js"></script>
<script src="/<?php echo $codepath; ?>lightbox/lightbox.min.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="/<?php echo $codepath; ?>lightbox/lightbox.css"/>