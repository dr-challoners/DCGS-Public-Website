<?php

include('../../header_declarations.php');
include('../../header_navigation.php');

echo "<h1 class=\"intranet\">Subject resources</h1>";
echo "<div class=\"intranet\">";
	$links = scandir("./");
	include('../subjects/subject_list.php');
echo "</div>";

include('../../footer.php'); ?>