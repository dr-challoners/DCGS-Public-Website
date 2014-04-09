<h1>House representatives and staff</h1>
<p>Student representatives are elected for their houses by both their peers and members of staff. The captains and vice captains in each house help organise teams for house competitions for each of the forms in their house, while the Sixth Form mentors work with students in their house in lower years, particularly Year 7. The form representatives help with the daily organisation of their form, as well as representing their form in Student Voice.</p>
<p>Members of staff take an active involvement within the Challoner's community and their house, and can often be seen wearing their house colour with pride - particularly on Sports Day!</p>

<?php

include ('house_styles.php');

$positions = array("C","VC","M","R11","R10","R9","R8","R7","S");
$houses = array("Foxell","Holman","Newman","Pearson","Rayner","Thorne");

$representatives = array();

$file = fopen($_SERVER['DOCUMENT_ROOT'].'/content_plain/Student life/House system/captains.csv',"r");

while(! feof($file)) {
	$line = fgetcsv($file);
	if ($line[2] != "") {
		array_push($representatives,$line);
		}
	}

fclose($file);

$file = fopen($_SERVER['DOCUMENT_ROOT'].'/content_plain/Student life/House system/staff.csv',"r", 1);

while(! feof($file)) {
	$line = fgetcsv($file);
	if ($line[1] != "" && $line[1] != "Name") {
		$staff = array();
		$staffname = $line[0]." ".$line[1];
		
		array_push($staff,$line[2]);
		array_push($staff,"S");
		array_push($staff,$staffname);
		
		array_push($representatives,$staff);
		}
	}

fclose($file);

foreach ($positions as $rank) {
	switch ($rank) {
		case "C":
			echo "<div class=\"line noclear\" id=\"header\">";
				echo "<div class=\"rank\"><h3>Captains</h3></div>";
				echo "<div class=\"house\" id=\"foxell\"><p>Foxell</p></div>";
				echo "<div class=\"house\" id=\"holman\"><p>Holman</p></div>";
				echo "<div class=\"house\" id=\"newman\"><p>Newman</p></div>";
				echo "<div class=\"house\" id=\"pearson\"><p>Pearson</p></div>";
				echo "<div class=\"house\" id=\"rayner\"><p>Rayner</p></div>";
				echo "<div class=\"house\" id=\"thorne\"><p>Thorne</p></div>";
			echo "</div>";
			echo "<div class=\"line noclear\">";
				echo "<div class=\"rank\"></div>";
			break;
		case "VC":
			echo "<hr />";
			echo "<div class=\"line\">";
				echo "<div class=\"rank\"><p>Vice Captains</p></div>";
			break;
		case "M":
			echo "<hr />";
			echo "<div class=\"line\">";
				echo "<div class=\"rank\"><h3>Mentors</h3></div>";
			break;
		case "R11":
			echo "<div class=\"line\" id=\"header\">";
				echo "<div class=\"rank\"><h3>Form Reps</h3></div>";
				echo "<div class=\"house\" id=\"foxell\"><p>Foxell</p></div>";
				echo "<div class=\"house\" id=\"holman\"><p>Holman</p></div>";
				echo "<div class=\"house\" id=\"newman\"><p>Newman</p></div>";
				echo "<div class=\"house\" id=\"pearson\"><p>Pearson</p></div>";
				echo "<div class=\"house\" id=\"rayner\"><p>Rayner</p></div>";
				echo "<div class=\"house\" id=\"thorne\"><p>Thorne</p></div>";
			echo "</div>";
			echo "<div class=\"line\">";
				echo "<div class=\"rank\"><p>Year 11</p></div>";
			break;
		case "R10":
			echo "<hr />";
			echo "<div class=\"line\">";
				echo "<div class=\"rank\"><p>Year 10</p></div>";
			break;
		case "R9":
			echo "<hr />";
			echo "<div class=\"line\">";
				echo "<div class=\"rank\"><p>Year 9</p></div>";
			break;
		case "R8":
			echo "<hr />";
			echo "<div class=\"line\">";
				echo "<div class=\"rank\"><p>Year 8</p></div>";
			break;
		case "R7":
			echo "<hr />";
			echo "<div class=\"line\">";
				echo "<div class=\"rank\"><p>Year 7</p></div>";
			break;
		case "S":
			echo "<div class=\"line\" id=\"header\">";
				echo "<div class=\"rank\"><h3>Staff</h3></div>";
				echo "<div class=\"house\" id=\"foxell\"><p>Foxell</p></div>";
				echo "<div class=\"house\" id=\"holman\"><p>Holman</p></div>";
				echo "<div class=\"house\" id=\"newman\"><p>Newman</p></div>";
				echo "<div class=\"house\" id=\"pearson\"><p>Pearson</p></div>";
				echo "<div class=\"house\" id=\"rayner\"><p>Rayner</p></div>";
				echo "<div class=\"house\" id=\"thorne\"><p>Thorne</p></div>";
			echo "</div>";
			echo "<div class=\"line\">";
				echo "<div class=\"rank\"></div>";
			break;
		}
	foreach ($houses as $house) {
		echo "<div class=\"house\">";
		foreach ($representatives as $person) {
			if ($person[1] == $rank && $person[0] == $house) {
				echo "<p>".$person[2]."</p>";
				}
			}
		echo "</div>";
		}
	echo "</div>";
	}

//echo "<pre>"; print_r($representatives); echo "</pre>";

?>