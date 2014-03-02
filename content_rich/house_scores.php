<?php

	include ('house_styles.php');

	require ('db-funcs.php');	
	
	open_db($_SERVER['DOCUMENT_ROOT'].'/content_plain/houses/results.csv');
	$houselist=array("Foxell","Holman","Newman","Pearson","Rayner","Thorne");

	reset($houselist);
	while ($c=each($houselist))	{
		$totalPoints[$c[1]]=0;
		}
		
	while (getLine_db()) {
		if (getItem_db("event") == "New Term")
		continue;
		
		reset($houselist);
		while ($c=each($houselist)) {
			addHouseScore($c[1]);
			}
		}
	
		$biggest = max($totalPoints[$houselist[0]],$totalPoints[$houselist[1]],$totalPoints[$houselist[2]],$totalPoints[$houselist[3]],$totalPoints[$houselist[4]],$totalPoints[$houselist[5]]);
			$foxellwidth = (630*$totalPoints[$houselist[0]]/$biggest)-18;
			$holmanwidth = (630*$totalPoints[$houselist[1]]/$biggest)-18;
			$newmanwidth = (630*$totalPoints[$houselist[2]]/$biggest)-18;
			$pearsonwidth = (630*$totalPoints[$houselist[3]]/$biggest)-18;
			$raynerwidth = (630*$totalPoints[$houselist[4]]/$biggest)-18;
			$thornewidth = (630*$totalPoints[$houselist[5]]/$biggest)-18;	
			
?>
	<h1>Current scores</h1>
	<div class="house_scores">
		<p class="position" id="foxell" style="width: <? echo $foxellwidth; ?>px;">Foxell <span><? echo $totalPoints[$houselist[0]]; ?></span></p>
		<p class="position" id="holman" style="width: <? echo $holmanwidth; ?>px;">Holman <span><? echo $totalPoints[$houselist[1]]; ?></span></p>
		<p class="position" id="newman" style="width: <? echo $newmanwidth; ?>px;">Newman <span><? echo $totalPoints[$houselist[2]]; ?></span></p>
		<p class="position" id="pearson" style="width: <? echo $pearsonwidth; ?>px;">Pearson <span><? echo $totalPoints[$houselist[3]]; ?></span></p>
		<p class="position" id="rayner" style="width: <? echo $raynerwidth; ?>px;">Rayner <span><? echo $totalPoints[$houselist[4]]; ?></span></p>
		<p class="position" id="thorne" style="width: <? echo $thornewidth; ?>px;">Thorne <span><? echo $totalPoints[$houselist[5]]; ?></span></p>
	</div>
	
	<h2>Breakdown by events</h2>

<?php

$file = fopen($_SERVER['DOCUMENT_ROOT'].'/content_plain/houses/results.csv',"r");

while(! feof($file)) {
	$line = fgetcsv($file);
	if ($count != "" && $line[0] != "") { //To skip the first line (there's probably a better approach than this); also skips blank lines
		if ($line[0] == "New Term") { //It's starting a new term's set of results, so put in header lines
			echo "<div class=\"line\" id=\"header\">";
				echo "<h3>".$line[1]."</h3>";
				echo "<p class=\"score\" id=\"foxell\">Foxell</p>";
				echo "<p class=\"score\" id=\"holman\">Holman</p>";
				echo "<p class=\"score\" id=\"newman\">Newman</p>";
				echo "<p class=\"score\" id=\"pearson\">Pearson</p>";
				echo "<p class=\"score\" id=\"rayner\">Rayner</p>";
				echo "<p class=\"score\" id=\"thorne\">Thorne</p>";
			echo "</div>";
			}
		else { //Otherwise, display the scores
			echo "<div class=\"line\">";
				echo "<p class=\"event\">".$line[0]."</p>";
				for ($a=1; $a<7;) {
					echo "<p class=\"score\"";
						if ($count%10 == 0) { //Periodically display the house colours so the list is easier to follow
							echo " id=\"".strtolower($houselist[$a-1])."\"";
							}
					echo ">".$line[$a]."</p>";
					$a++; }
			echo "</div>";
			}
		}
	$count++; }

fclose($file);

?> 
