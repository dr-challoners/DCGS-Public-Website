<?php

	$file = fopen($_SERVER['DOCUMENT_ROOT'].'/content_main/Student life/4~House system/results.csv',"r");
	$houses = array("Foxell","Holman","Newman","Pearson","Rayner","Thorne");
	$totals = array(0,0,0,0,0,0);
	while(! feof($file)) {
		$line = fgetcsv($file);
		if ($line[0] != "Event" && $line[0] != "New Term" && $line[0] != "") { //Skips lines that don't have scores in them (hopefully) {
			array_shift($line); // Chops out the event title, so that just the scores are left in each array
			$repeats = array_count_values($line); // Counts the number of houses that are tied for a position
			$a = 0; foreach ($line as $score) {
				if ($score != "X") { // Ignore non-participating Houses for an event
					$ties = $repeats[$score]; // Fetches the number of tied houses for this score
					$score = $score+0.5*($ties-1); // Calculates the rank average
					$score = 7-$score; // Calculates the corresponding score for that house
					$totals[$a] = $totals[$a]+$score; // Adds that score to the appropriate house in the totals array
					}
				$a++;
				}
			
			}
		}
		$totals = array_combine($houses,$totals); // Makes each key the house name, for more human-readable matching later
	fclose($file);
	
	$biggest = max($totals["Foxell"],$totals["Holman"],$totals["Newman"],$totals["Pearson"],$totals["Rayner"],$totals["Thorne"]);
	
	$foxellwidth = (700*$totals["Foxell"]/$biggest);
	$holmanwidth = (700*$totals["Holman"]/$biggest)-8;
	$newmanwidth = (700*$totals["Newman"]/$biggest)-8;
	$pearsonwidth = (700*$totals["Pearson"]/$biggest)-8;
	$raynerwidth = (700*$totals["Rayner"]/$biggest)-8;
	$thornewidth = (700*$totals["Thorne"]/$biggest)-8;	
			
?>
	<h1>Current scores</h1>
	<div class="house_scores">
		<p class="position" id="foxell" style="width: <?php echo $foxellwidth; ?>px;">Foxell <span><?php echo $totals["Foxell"] ?></span></p>
		<p class="position" id="holman" style="width: <?php echo $holmanwidth; ?>px;">Holman <span><?php echo $totals["Holman"] ?></span></p>
		<p class="position" id="newman" style="width: <?php echo $newmanwidth; ?>px;">Newman <span><?php echo $totals["Newman"] ?></span></p>
		<p class="position" id="pearson" style="width: <?php echo $pearsonwidth; ?>px;">Pearson <span><?php echo $totals["Pearson"] ?></span></p>
		<p class="position" id="rayner" style="width: <?php echo $raynerwidth; ?>px;">Rayner <span><?php echo $totals["Rayner"] ?></span></p>
		<p class="position" id="thorne" style="width: <?php echo $thornewidth; ?>px;">Thorne <span><?php echo $totals["Thorne"] ?></span></p>
	</div>
	
	<div class="event_breakdown">
	
	<h2>Breakdown by events</h2>

<?php

$file = fopen($_SERVER['DOCUMENT_ROOT'].'/content_main/Student life/4~House system/results.csv',"r");
$count = 0;
while(! feof($file)) {
	$line = fgetcsv($file);
	if ($count != 0 && $line[0] != "") { //To skip the first line (there's probably a better approach than this); also skips blank lines
		if ($line[0] == "New Term") { //It's starting a new term's set of results, so put in header lines
			echo "<div class=\"line\" id=\"header\">";
				echo "<h3>".$line[1]."</h3>";
				echo "<p class=\"score\" id=\"foxell\">F</p>";
				echo "<p class=\"score\" id=\"holman\">H</p>";
				echo "<p class=\"score\" id=\"newman\">N</p>";
				echo "<p class=\"score\" id=\"pearson\">P</p>";
				echo "<p class=\"score\" id=\"rayner\">R</p>";
				echo "<p class=\"score\" id=\"thorne\">T</p>";
			echo "</div>";
			}
		else { //Otherwise, display the scores
			echo "<div class=\"line\">";
				echo "<p class=\"event\">".$line[0]."</p>";
				for ($a=1; $a<7;) {
					echo "<p class=\"score\"";
						if ($count%10 == 0) { //Periodically display the house colours so the list is easier to follow
							echo " id=\"".strtolower($houses[$a-1])."\"";
							}
					echo ">".$line[$a]."</p>";
					$a++; }
			echo "</div>";
			}
		}
	$count++; }

fclose($file);

?> 

	</div>