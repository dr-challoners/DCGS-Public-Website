	</head>
	<body onload="moveWindow()"> <!-- The moveWindow function only has any effect in the diary display. -->
		
		<!-- These divs create the blue and white bars across the top and bottom of the page. On small screens, appropriate elements can handle this styling as the screen width will equal the page width. -->
		<div class="colourbar lrg" id="top"></div>
		<div class="colourbar lrg" id="btm"></div>
		
		<script type="text/javascript">
			function openClose(div1,div2,div3) {
				if(document.getElementById(div1).style.display == 'block') {
					document.getElementById(div1).style.display='none';
					}
				else {
					document.getElementById(div1).style.display = 'block';
					}
					
				document.getElementById(div2).style.display='none';
				document.getElementById(div3).style.display='none';
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
			<div class="header"><!--Fixes all these items to the top of the browser-->
				
				<a class="bannerlink lrg" href="/"></a>
				<!-- 'sml' and 'lrg' classes allow different objects to be displayed in small/big screens: the CSS selects appropriately. -->
				<img class="banner_img lrg" src="/main_imgs/logo_lrg.png" alt="Dr Challoner's Grammar School" />
				
				<div class="main_nav">
				<a class="lbutton" href="/"><img src="/main_imgs/home.png" alt="Home" /></a>
					<ul>
						<li><a href="/pages/Overview/" onmouseover="mopen('n1')" onmouseout="mclosetime()">Overview</a></li>
						<li><a href="/diary/<?php echo date('d')."/".date('m')."/".date('Y'); ?>">Diary</a></li>
						<li><a href="/intranet/">Intranet</a></li><!--This will need to do something different.-->
						<li><a href="/pages/Student life/" onmouseover="mopen('n2')" onmouseout="mclosetime()">Student life</a></li>
						<li><a href="/pages/Showcase/" onmouseover="mopen('n3')" onmouseout="mclosetime()">Showcase</a></li>
						<li><a href="/pages/Alumni/" onmouseover="mopen('n4')" onmouseout="mclosetime()">Alumni</a></li>
						<li><a href="/pages/Overview/Information/Contact us">Contact us</a></li>
					</ul>
				<a class="rbutton" href="/search/"><img src="/main_imgs/search.png" alt="Search" /></a>
				</div>
				
				<?php //This next section creates the drop-down menus for the main navigation
				
				$dropdowns = array("Overview","Student life","Showcase","Alumni");
				$div_id = 1;
				
				foreach ($dropdowns as $maindir) {
				
					echo "<div class=\"sub_nav\">";
						echo "<div class=\"centred\" id=\"n".$div_id."\" onmouseover=\"mcancelclosetime()\" onmouseout=\"mclosetime()\">";
				
					$dir = scandir("content_plain/".$maindir, 1); //First, get all the subdirectories in the main directory being looked at
					$dir = array_reverse($dir);

					foreach ($dir as $subdir) { //List all the subdirectories
						$dirname = explode("~",$subdir);
						if (isset($dirname[1])) { // This is a cheap and cheerful way to confirm that the object being looked at is a folder, but it requires ALL subdirectories to be in the form 'X~NAME'
						
							echo "<div class=\"category\">";
							echo "<h2>".$dirname[1]."</h2>";
    
							$files = scandir("content_plain/".$maindir."/".$subdir, 1); //Now get all the files in each subdirectory and turn them into appropriate links
							$files = array_reverse($files);
    
							echo "<ul>";
    
							foreach ($files as $page) {
								$detail = explode("~",$page);
								if (isset($detail[2])) { // If there's a third part to the array, then that means a particular instruction like an external LINK or a GALLERY or a SPECIAL content_rich page
								if ($detail[2] == "LINK.txt") { // This needs to be a link to an outside site - it opens in a new tab. The link info is written inside the text file
									echo "<li><a href=\"".file_get_contents("content_plain/".$maindir."/".$subdir."/".$page)."\" target=\"_BLANK\">".$detail[1]."</a></li>";
									}
								elseif ($detail[2] == "GALLERY") { // Point to the gallery function for the given folder
									echo "<li><a href=\"/gallery/".$maindir."/".$dirname[1]."/".$detail[1]."\">".$detail[1]."</a></li>";
									}
								elseif ($detail[2] == "SPECIAL.txt") { // Point to the content_rich folder. Note that most of the navigation details given will be unnecessary for finding the file: they're there to display the submenu.
									echo "<li><a href=\"/rich/".$maindir."/".$dirname[1]."/".$detail[1]."\">".$detail[1]."</a></li>";
									}
								}
								elseif (isset ($detail[1]) && substr($detail[1],-4) == ".txt") {
									$pagename = explode(".",$detail[1]);
									$pagename = $pagename[0];
									echo "<li><a href=\"/pages/".$maindir."/".$dirname[1]."/".$pagename."\">".$pagename."</a></li>";
									}
								}
    
							echo "</ul></div>";
    
							}	
						}
				
					echo "</div></div>";
					
					$div_id++; }
				
				?>
					
				
				<!--Let's break the mobile view entirely, shall we?
				<!-- On small screens very little will be displayed in the footer: it will all go in the drop-down menus here. --
				<div class="nav_dropdown">
				<div class="sml">
				<a class="bannerlink" href="/"><img class="banner_img" src="/main_imgs/logo_sml.png" alt="Dr Challoner's Grammar School" /></a>
				<p><span><a href="javascript:openClose('n1','n2','n3')">Links</a></span> <span class="spacer">&middot;</span> <span><a onclick="openClose('n2','n1','n3')">About us</a></span></p>
				</div>
				
				<div class="nav_about" id="n2"><ul>
					<li class="sml"><a href="/pages/overview/">Overview</a></li>
					<li><a href="/pages/learning/">Learning</a></li>
					<li><a href="/pages/the-arts/">The Arts</a></li>
					<li><a href="/pages/sports/">Sports</a></li>
					<li><a href="/pages/enrichment/">Enrichment</a></li>
					<li><a href="/pages/houses/">Houses</a></li>
					<li><a href="/pages/history/">History</a></li>
					<li class="sml"><a href="/pages/admissions/">Prospectus</a></li>
					<li class="sml"><a href="/">Academy info</a></li>
				</ul><hr class="sml clear" /></div>
				
				<div class="nav_links" id="n1"><ul>
					<li class="lrg"><a href="/">Home</a></li>
					<!-- This goes to the diary with the current day set--
					<li class="lrg"><a href="/diary/<?php echo date('d')."/".date('m')."/".date('Y'); ?>">Diary</a></li>
					<li class="sml"><a href="/diary/<?php echo date('d')."/".date('m')."/".date('Y')."/&device=mobile"; ?>">Diary</a></li>
					<li class="sml"><a href="/pages/information/termdates">Term dates</a></li>
					<li><a href="/portal/parents/">Parents</a></li>
					<li><a href="/portal/intranet/">Intranet</a></li>
					<li class="sml"><a href="/pages/admissions/">Admissions</a></li>
					<li><a href="/">Alumni</a></li>
					<li><a href="/">Support us</a></li>
					<li class="sml"><a href="/pages/information/vacancies">Vacancies</a></li>
					<li><a href="/pages/information/contactus">Contact us</a></li>
				</ul><hr class="sml clear" /></div>
				
				</div> -->

			</div>