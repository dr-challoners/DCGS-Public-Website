<?php

$p = "L";
if (isset($prefix)) {
  $p = $prefix;
}

$links = scandir($directory);
$c_end = count($links)-1;
for ($c=0; $c<=$c_end; $c++) {
	if (strpos($directory.$links[$c],".txt") !== false) { //It's a text file (thereby containing links in markdown)
		$linklist = file_get_contents($directory.$links[$c], true);
    $listname = substr(explode("~",$links[$c])[1],0,-4);
    echo '<div class="intranet_head';
    if ($c%2 == 1) { echo ' sml'; }
    echo '">';
      echo '<h3><a href="javascript:boxOpen(\''.$p.$c.'\',\'boxlist\')">'.$listname.'</a></h3>';
    echo "</div>";
    $cn = $c+1;
    if ($c%2 == 0 && $cn <= $c_end) {
      $listname = substr(explode("~",$links[$cn])[1],0,-4);
      echo '<div class="intranet_head lrg">';
      echo '<h3><a href="javascript:boxOpen(\''.$p.$cn.'\',\'boxlist\')">'.$listname.'</a></h3>';
    echo "</div>";
    }
    echo '<div class="intranetbox"><div class="dropdown" name="boxlist" id="'.$p.$c.'">';
			echo Parsedown::instance()->parse($linklist);
    echo '<hr id="end" /></div></div>';
  }
}

?>