<?php date_default_timezone_set("Europe/London"); // Repeated for use in debugging

// Changing to just one day's worth of events: the remainder is commented out in case the plan is changed. New stuff is denoted.

$date = date("Ymd"); // NEW
if (file_exists("content_plain/diary/".$date.".xml")) { // NEW
$file = simplexml_load_file("content_plain/diary/".$date.".xml"); // NEW
// $file = simplexml_load_file("content_plain/diary/Highlight.xml"); // Put this back in if you change your mind

//$highlights = array();
$event = array(); // NEW

	foreach($file->events->event as $line) { //Work through each event one at a time
		
    //$event = array();
		
    //$startdate = $line -> date;
    //$startdate = date("Ymd",mktime(0,0,0,substr($startdate,3,2),substr($startdate,0,2),substr($startdate,6,4)));
		
		$title = (string)$line -> title;
		
    //$key = "";
    //$count = 0;
		
    //foreach($highlights as $row) {
      //if($title == $row[2] && $title != "Lunchtime Recital - Amersham Free Church") { //The lunchtime recitals are recurring events on different days - not a sequence of events
      //$key = $count;
      //	break;
      //	}
    //		$count++;
    //	}
    //if ($key !== "") { //This doesn't seem to work when the key is 0 - may need further testing
    //	$highlights[$key][1] = $startdate;
    //	}
    //else {
    //	$enddate = $startdate;
			
    //	array_push($event,$startdate);
    //	array_push($event,$enddate);
    	array_push($event,$title);
				
    //	array_push($highlights,$event);
    //	}
    //}
		
//$currentdate = date("Ymd");
//$twomonths = date("Ymd",mktime(0,0,0,date("m"),date("d")+70,date("Y")));

    //$ticker = 0;
	
    //foreach ($highlights as $highlight) { //Counts the number of items that will appear in the display, so that the final one isn't appended with a comma
    //	if ($highlight[1] >= $currentdate && $highlight[0] <= $twomonths) {
    //		$ticker++;
    //		}
    	}


$ticker = count($event); // NEW
if ($ticker > 0) { // NEW
?>

<script language="javascript"><!--
	// This code creates the changing highlights box.
	// The last highlight must not have a comma after it, or the whole thing will break.

					textLines=new Array(
					
<?php
		
	$count = 1;
    
		
    foreach ($event as $post) { // NEW
    //foreach ($highlights as $highlight) {
  //if ($highlight[1] >= $currentdate && $highlight[0] <= $twomonths) { //Only gives highlights for now (including currently happening) until two and a half months away
  //$start = date("l jS F",mktime(0,0,0,substr($highlight[0],4,2),substr($highlight[0],6,2),substr($highlight[0],0,4)));
  //	echo "\"<a href=\\\"diary/".substr($highlight[0],0,4)."/".substr($highlight[0],4,2)."/".$highlight[0]."#".$highlight[0]."\\\">";
  //		echo "<p><strong>".$start;
      echo "\"<a href=\\\"diary/".date("d")."/".date("m")."/".date("Y")."\\\">"; // NEW
      echo "<p><strong>Today's events:</strong> ".$post."</p></a>\""; // NEW
      //if ($highlight[0] != $highlight[1]) { //If there's a duration to the highlight, give start and end dates
      //$end = date("l jS F",mktime(0,0,0,substr($highlight[1],4,2),substr($highlight[1],6,2),substr($highlight[1],0,4)));
      //echo " - ".$end;
      //}
      //echo ":</strong> ".$highlight[2]."</p></a>\"";
			if ($count != $ticker) { echo ","; }
			echo "\n";
			$count++;
			}
//}
		
?>
					
			
						);

					numOn=Math.floor(Math.random()*(textLines.length+1)); // Randomly picks a quote to start on, then works through in order.
					delay=8; // Set the delay time inbetween each change (in seconds, decimal values can be used).
					stopOK=1; // Set this variable to 0 to stop mouse overs from stopping the text from changing.
					change=1;
					window.onload=start;

					function start(){
						setTimeout("Change()",1);
						}
					function Change(){
						if(change==1){ // Check to see if the user has their mouse over the text.
							if(numOn>=textLines.length||numOn<0){numOn=0} // Make sure we are on a valid Line Number and if not, set it to the starting line.
							textChanger.innerHTML=textLines[numOn]; // Set the text inside the <div> to the specified line number.
							numOn++; // Increase the line number by one to get the next string.
							}
						setTimeout("Change()",delay*1000); // Call this function again to write the string to the <div>.
						}
					// --></script>

<div class="highlight lrg">
	<div id="textChanger" onmouseover="if(stopOK==1){change=0}" onmouseout="change=1"></div>
</div>

<?php }} // NEW ?>