<?php

$p = "L";
if (isset($prefix)) {
  $p = $prefix;
}

$links = scandir($directory);
$c = 1;
foreach ($links as $row) {
	if (strpos($directory.$row,".txt") !== false) { //It's a text file (thereby containing links in markdown)
		$linklist = file_get_contents($directory.$row, true);
    $listname = substr(explode("~",$row)[1],0,-4);
    echo '<div class="intranetbox';
    //if (isset($subjects)) { echo ' subject'; }
    echo '"';
    //if (isset($subjects)) { echo ' id="'.strtolower(str_replace(" ","",$listname)).'"'; }
    echo '>';
      echo '<h3><a href="javascript:boxOpen(\''.$p.$c.'\',\'boxlist\')">'.$listname.'</a></h3>';
      echo '<div class="dropdown" name="boxlist" id="'.$p.$c.'">';
			  echo Parsedown::instance()->parse($linklist);
      echo '</div>';
		echo "</div>";
  }
$c++; }

?>