	</head>
	<?php // Generating the body tag with onload functions for different pages
		echo "<body";
			if (isset($_GET['date'])) { echo " onload=\"moveWindow()\""; } // Diary pages - jumps to current day
			if (isset($_GET['subfolder'])) { echo " onload=\"openClose('".str_replace("'","",$_GET['subfolder'])."')\""; } // Content pages - opens the section currently being browsed
		echo ">";
	?>
		
		<!-- These divs create the blue and white bars across the top and bottom of the page. On small screens, appropriate elements can handle this styling as the screen width will equal the page width. -->
		<div class="colourbar lrg" id="top"></div>
		<div class="colourbar lrg" id="btm"></div>
		
		<script type="text/javascript">
			function openClose(divId) {
				
//var inputs = document.getElementsByName("submenu");
//for(var i = 0; i < inputs.length; i++) {
    //inputs[i].style.display='none';
    //}

				
				if(document.getElementById(divId).style.display == 'block') { // Close the selected div if it's open
					document.getElementById(divId).style.display='none';
					}
				else {
					document.getElementById(divId).style.display = 'block'; // Otherwise open it
					}
				
				}
		</script>
		<script type="text/javascript">
			function openCloseAll(divId) {
			
				if(document.getElementById(divId).style.display == 'block') { var open = 1; }
				
				var inputs = document.getElementsByName("submenu");
				for(var i = 0; i < inputs.length; i++) {
					inputs[i].style.display='none';
					}
				
				if(open == 1) { // Close the selected div if it's open
					document.getElementById(divId).style.display='none';
					}
				else {
					document.getElementById(divId).style.display = 'block'; // Otherwise open it
					}
				
				}
		</script>
		<script type="text/javascript"> // Copyright 2006-2007 javascript-array.com
			var timeout	= 0;
			var closetimer	= 0;
			var ddmenuitem	= 0;

			function mopen(id) { // open hidden layer
				mcancelclosetime(); // cancel close timer
				if(ddmenuitem) ddmenuitem.style.visibility = 'hidden'; // close old layer
				ddmenuitem = document.getElementById(id);
				ddmenuitem.style.visibility = 'visible'; // get new layer and show it
				}

			function mclose() { // close displayed layer
				if(ddmenuitem) ddmenuitem.style.visibility = 'hidden';
				}

			function mclosetime() { // go close timer
				closetimer = window.setTimeout(mclose, timeout);
				}

			function mcancelclosetime() { // cancel close timer
				if(closetimer) {
					window.clearTimeout(closetimer);
					closetimer = null;
					}
				}

			document.onclick = mclose; // close layer when click-out
		</script>

		<div class="page"><!--On large screens, this constrains the elements to 980px wide and centres them in the browser. On small screens it does nothing. -->
			<!--googleoff: all--><div class="header"><!--Fixes all these items to the top of the browser-->
				
				<a class="bannerlink lrg" href="/"></a>
				<!-- 'sml' and 'lrg' classes allow different objects to be displayed in small/big screens: the CSS selects appropriately. -->
				<img class="banner_img lrg" src="/styles/imgs/logo_lrg.png" alt="Dr Challoner's Grammar School" />
				
				<div class="main_nav lrg">
				<a class="lbutton" href="/"><img src="/styles/imgs/home.png" alt="Home" /></a>
					<ul>
						<li><a href="/pages/Overview/" onmouseover="mopen('n2')" onmouseout="mclosetime()">Overview</a></li>
						<li><a href="/diary/<?php echo date('d')."/".date('m')."/".date('Y'); ?>">Diary</a></li>
						<li><a href="/intranet/">Intranet</a></li>
						<li><a href="/pages/Student life/" onmouseover="mopen('n4')" onmouseout="mclosetime()">Student life</a></li>
						<li><a href="/pages/Showcase/" onmouseover="mopen('n3')" onmouseout="mclosetime()">Showcase</a></li>
						<li><a href="/pages/Information/Alumni/Introduction">Alumni</a></li>
						<li><a href="/pages/Information/General information/Contact us">Contact us</a></li>
					</ul>
				<a class="rbutton" href="/search/"><img src="/styles/imgs/search.png" alt="Search" /></a>
				</div>
				
				<div class="main_nav sml">
					<a class="bannerlink" href="/"><img class="banner_img" src="/styles/imgs/logo_sml.png" alt="Dr Challoner's Grammar School" /></a>
					<a href="/search/"><img class="rbutton" src="/styles/imgs/sml_search.png" alt="Search" /></a>
					<p class="rbutton"><a href="javascript:openClose('main_nav')">Menu <span>&#9660;</span></a></p>
				</div>
				
				<div class="nav_menu" id="main_nav"> <!-- This serves no purpose on big screens, but on small screens provides a box to open and close to access the menu. -->
					<h1 class="sub_nav sml"><a href="javascript:openCloseAll('n8')">News</a></h1>
					<?php
						$newsposts = scandir("content_news/", 1); //Calls up all the files in the news folder
						$newsposts = array_slice($newsposts,0,15);
						echo "<div class=\"sub_nav sub_menu\" name=\"submenu\" id=\"n8\"><ul>";
						$n = 0; foreach ($newsposts as $row) {
							$component = explode("~",$row);
							echo "<li";
							if ($n == 0) { echo " id=\"first\""; }
							echo ">";
              echo "<a href=\"/news/".$component[0]."~".$component[1];
							if ($component[2] != "") {
								echo "~".$component[2];
								}
							echo "\">";
							echo "<em>".date("jS F",mktime(0,0,0,substr($component[0],4,2),substr($component[0],6,2),substr($component[0],0,4))).":</em> ".$component[1];
							echo "</a>";
							echo "</li>";
							}
						echo "</ul></div>";
					?>
          				
				<?php //This next section creates the drop-down menus for the main navigation
				
				$dropdowns = array("Information","Overview","Showcase","Student life");
				$div_id = 1;
				
				foreach ($dropdowns as $maindir) {
					
					echo "<h1 class=\"sub_nav sml\"><a href=\"javascript:openCloseAll('n".$div_id."')\">".$maindir."</a></h1>";
					echo "<div class=\"sub_nav sub_menu\" name=\"submenu\" id=\"n".$div_id."\" onmouseover=\"mcancelclosetime()\" onmouseout=\"mclosetime()\">";
						echo "<div class=\"centred\">";
				
					$dir = scandir("content_main/".$maindir, 1); //First, get all the subdirectories in the main directory being looked at
					$dir = array_reverse($dir);

					foreach ($dir as $subdir) { //List all the subdirectories
						$dirname = explode("~",$subdir);
						if (isset($dirname[1])) { // This is a cheap and cheerful way to confirm that the object being looked at is a folder, but it requires ALL subdirectories to be in the form 'X~NAME'
						
							echo "<div class=\"category\">";
							echo "<h2>".$dirname[1]."</h2>";
    
							$files = scandir("content_main/".$maindir."/".$subdir, 1); //Now get all the files in each subdirectory and turn them into appropriate links
							$files = array_reverse($files);
    
							echo "<ul>";
    
							foreach ($files as $page) {
								$detail = explode("~",$page);
								if (isset($detail[2]) && $detail[2] == "LINK.txt") { // This needs to be a link to an outside site - it opens in a new tab. The link info is written inside the text file
									echo '<li><a href="'.file_get_contents("content_main/".$maindir."/".$subdir."/".$page).'" target="_BLANK" class="external" >'.str_replace('[plus]','+',$detail[1]).'</a></li>';
								}
								elseif (isset ($detail[1])) {
									$pagename = explode(".",$detail[1]);
									$pagename = $pagename[0];
									echo "<li><a href=\"/pages/".$maindir."/".$dirname[1]."/".$pagename."\">".str_replace('[plus]','+',$pagename)."</a></li>";
									}
								}
    
							echo "</ul></div>";
    
							}	
						}
				
					echo "</div></div>";
					
					$div_id++; }

				?>
					
					<!-- Remainder of the links for the mobile menu. -->
					
          <h1 class="sub_nav sml"><a href="javascript:openCloseAll('nA')">Alumni</a></h1>
					<div class="sub_nav sub_menu" name="submenu" id="nA"><ul>
				  <?php    
							$files = scandir("content_main/Information/5~Alumni", 1); //Now get all the files in each subdirectory and turn them into appropriate links
							$files = array_reverse($files);
    
							echo "<ul>";
    
							foreach ($files as $page) {
								$detail = explode("~",$page);
								if (isset($detail[2]) && $detail[2] == "LINK.txt") { // This needs to be a link to an outside site - it opens in a new tab. The link info is written inside the text file
									echo '<li><a href="'.file_get_contents("content_main/Information/5~Alumni/".$page).'" target="_BLANK" class="external" >'.str_replace('[plus]','+',$detail[1]).'</a></li>';
								}
								elseif (isset ($detail[1])) {
									$pagename = explode(".",$detail[1]);
									$pagename = $pagename[0];
									echo "<li><a href=\"/pages/Information/Alumni/".$pagename."\">".str_replace('[plus]','+',$pagename)."</a></li>";
									}
								}
				    ?>
					  </ul></div>
          <h1 class="sub_nav sml"><a href="javascript:openCloseAll('n0')">Diary</a></h1>
						<div class="sub_nav sub_menu" name="submenu" id="n0"><ul>
							<li id="first"><a href="/diary/<?php echo date('d')."/".date('m')."/".date('Y')."/&device=mobile"; ?>">This week's events</a></li>
							<li><a href="/diary/<?php echo date('d')."/".date('m')."/".date('Y')."/&device=mobile&display=calendar"; ?>">Browse the calendar</a></li>
							<li><a href="/pages/Information/General information/Term dates">See term dates</a></li>
						</ul></div>
					<h1 class="sub_nav sml"><a href="javascript:openCloseAll('n9')">Intranet</a></h1>
						<div class="sub_nav sub_menu" name="submenu" id="n9"><ul>
							<li id="first"><a href="/intranet/Staff and student intranet">Staff and student intranet</a></li>
							<li><a href="/intranet/Parent portal">Parent portal</a></li>
							<li><a href="/intranet/Subject resources">Subject resources</a></li>
						</ul></div>
					<h1 class="sub_nav sml" id="last"><a href="/pages/Information/General information/Contact us">Contact us</a></h1>
					
					</div>

			<!--googleon: all--></div>