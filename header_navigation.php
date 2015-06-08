	</head>
	<?php // Generating the body tag with onload functions for different pages
		echo "<body";
			if (isset($_GET['date'])) { echo ' onload="moveWindow()"'; } // Diary pages - jumps to current day
		echo ">";
	?>
		
		<!-- These divs create the blue and white bars across the top and bottom of the page. On small screens, appropriate elements can handle this styling as the screen width will equal the page width. -->
		<div class="colourbar lrg" id="top"></div>
		<div class="colourbar lrg" id="btm"></div>
		
		<script type="text/javascript">
			function openClose(divId) {
				
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
    <script> // Google Analytics
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-53669990-1', 'auto');
      ga('send', 'pageview');
    </script>

		<div class="page"><!--On large screens, this constrains the elements to 980px wide and centres them in the browser. On small screens it does nothing. -->
			<!--googleoff: all-->
      <div class="header"><!--Fixes all these items to the top of the browser on large screens-->
				
				<a class="bannerlink lrg" href="/"></a>
				<!-- 'sml' and 'lrg' classes allow different objects to be displayed in small/big screens: the CSS selects appropriately. -->
        <?php
if (isset($blood)) {
  echo '<img class="banner_img lrg" src="/styles/imgs/logo_lrg_BLOOD.png" alt="Dr Challoner\'s Grammar School" />';
} else {
  echo '<img class="banner_img lrg" src="/styles/imgs/logo_lrg.png" alt="Dr Challoner\'s Grammar School" />';
  } ?>
				
				<div class="main_nav lrg">
				<a class="lbutton" href="/"><img src="/styles/imgs/home.png" alt="Home" /></a>
					<ul>
            <?php
            echo '<li><a href="/pages/Overview/" ';
              if (isset($_GET['folder']) && strtolower($_GET['folder']) == "overview") {
                echo 'id="selected"';
              } else {
                echo 'onmouseover="mopen(\'n2\')" onmouseout="mclosetime()"';
              }
            echo '><span class="hideW">O</span>verview</a></li>';
            echo '<li><a href="/diary/'.date('d').'/'.date('m').'/'.date('Y').'"';
              if (isset($_GET['date'])) { echo ' id="selected"'; }
            echo '>Di<span class="hideW">a</span>ry</a></li>';
            echo '<li><a href="/intranet/"';
              if (isset($intranet)) { echo ' id="selected"'; }
            echo '>Intr<span class="hideW">a</span>net</a></li>';
            echo '<li><a href="/pages/Student_life/" ';
              if (isset($_GET['folder']) && strtolower($_GET['folder']) == "student_life") {
                echo 'id="selected"';
              } else {
                echo 'onmouseover="mopen(\'n3\')" onmouseout="mclosetime()"';
              }
            echo '>Student life</a></li>';
            echo '<li><a href="/pages/Showcase/" ';
              if (isset($_GET['folder']) && strtolower($_GET['folder']) == "showcase") {
                echo 'id="selected"';
              } else {
                echo 'onmouseover="mopen(\'n4\')" onmouseout="mclosetime()"';
              }
            echo '>Sh<span class="hideW">o</span>wc<span class="hideW">a</span>se</a></li>';
            echo '<li><a href="/pages/Information/Alumni/"';
               if (isset($_GET['subfolder']) && strtolower($_GET['subfolder']) == "alumni") { echo ' id="selected"'; }
            echo '><span class="hideW">A</span>lumni</a></li>';
            echo '<li><a href="/pages/Information/General_information/Contact_us"';
               if (isset($_GET['page']) && strtolower($_GET['page']) == "contact_us") { echo ' id="selected"'; }
            echo '>C<span class="hideW">o</span>nt<span class="hideW">a</span>ct us</a></li>';
            ?>
					</ul>
				<a class="rbutton" href="/search/"><img src="/styles/imgs/search.png" alt="Search" /></a>
				</div>
				
				<div class="main_nav sml">
					<a class="bannerlink" href="/"><img class="banner_img" src="/styles/imgs/logo_sml.png" alt="Dr Challoner's Grammar School" /></a>
					<a href="/search/"><img class="rbutton" src="/styles/imgs/sml_search.png" alt="Search" /></a>
					<p class="rbutton"><a href="javascript:openClose('main_nav')">Menu <span>&#9660;</span></a></p>
				</div>
				
				<div class="nav_menu" id="main_nav"> <!-- This serves no purpose on big screens, but on small screens provides a box to open and close to access the menu. -->
          				
				<?php // This next section creates the drop-down menus for the main navigation
				
				$dropdowns = array("Information","Overview","Student life","Showcase");
				$div_id = 1;
				
				foreach ($dropdowns as $maindir) {
					
					echo "<h1 class=\"sub_nav sml\"><a href=\"javascript:openCloseAll('n".$div_id."')\">".$maindir."</a></h1>";
					echo "<div class=\"sub_nav sub_menu\" name=\"submenu\" id=\"n".$div_id."\" onmouseover=\"mcancelclosetime()\" onmouseout=\"mclosetime()\">";
				
					$dir = scandir("content_main/".$maindir, 1); // First, get all the subdirectories in the main directory being looked at
					$dir = array_reverse($dir);

					foreach ($dir as $subdir) { // List all the subdirectories
						$dirname = explode("~",$subdir);
						if (isset($dirname[1])) { // This is a cheap and cheerful way to confirm that the object being looked at is a folder, but it requires ALL subdirectories to be in the form 'X~NAME'
						
							echo "<div class=\"category\">";
							echo "<h2>".$dirname[1]."</h2>";
    
							$files = scandir("content_main/".$maindir."/".$subdir, 1); // Now get all the files in each subdirectory and turn them into appropriate links
							$files = array_reverse($files);
    
							echo "<ul>";
    
							foreach ($files as $page) {
								$detail = explode("~",$page);
								if (isset($detail[2]) && $detail[2] == "LINK.txt") { // This needs to be a link to an outside site - it opens in a new tab. The link info is written inside the text file
									echo '<li><a href="'.file_get_contents("content_main/".$maindir."/".$subdir."/".$page).'" target="page'.mt_rand().'" class="external" >'.str_replace('[plus]','+',$detail[1]).'</a></li>';
								}
								elseif (isset ($detail[1])) {
									$pagename = explode(".",$detail[1]);
									$pagename = $pagename[0];
									echo '<li><a href="/pages/'.str_replace($linkChars,$linkRplce,$maindir).'/'.str_replace($linkChars,$linkRplce,$dirname[1]).'/'.str_replace($linkChars,$linkRplce,$pagename).'">';
                  echo str_replace('[plus]','+',$pagename).'</a></li>';
									}
								}
    
							echo '</ul>';
              echo '</div>';
    
							}	
						}
				
					echo "</div>";
					
					$div_id++; }

          // Intranet quick links to display as a dropdown - not on the mobile site
          if (file_exists('content_system/intranet/00~QuickLinks.txt')) {
            echo '<div class="sub_nav sub_menu lrg" name="submenu" id="nQL" onmouseover="mcancelclosetime()" onmouseout="mclosetime()">';
              echo '<div class="category">';
                echo '<h1>Quick links</h1>';
                echo '<p id="intranetLink"><a href="/intranet/">See the full intranet</a></p>';
              echo '</div>';
              $quickLinks = file_get_contents('content_system/intranet/00~QuickLinks.txt');
              $quickLinks = Parsedown::instance()->parse($quickLinks);
              $quickLinks = str_replace('<h2>','<div class="category"><h2>',$quickLinks);
              $quickLinks = str_replace('</ul>','</ul></div>',$quickLinks);
              echo $quickLinks;
					  echo '</div>';
          }

				?>
					
					<!-- Remainder of the links for the mobile menu. -->
					
          <h1 class="sub_nav sml"><a href="javascript:openCloseAll('nA')">Alumni</a></h1>
					<div class="sub_nav sub_menu" name="submenu" id="nA"><ul>
				  <?php    
							$files = scandir("content_main/Information/5~Alumni", 1); // Now get all the files in each subdirectory and turn them into appropriate links
							$files = array_reverse($files);
    
							echo "<ul>";
    
							foreach ($files as $page) {
								$detail = explode("~",$page);
								if (isset($detail[2]) && $detail[2] == "LINK.txt") { // This needs to be a link to an outside site - it opens in a new tab. The link info is written inside the text file
									echo '<li><a href="'.file_get_contents("content_main/Information/5~Alumni/".$page).'" target="page'.mt_rand().'" class="external" >'.str_replace('[plus]','+',$detail[1]).'</a></li>';
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
							<li id="first"><a href="/intranet/Staff_links">Staff links</a></li>
              <li id="first"><a href="/intranet/Student_links">Student links</a></li>
							<li><a href="/intranet/Parent_links">Parent links and information</a></li>
							<li><a href="/intranet/Subject_resources">Subject resources</a></li>
						</ul></div>
					<h1 class="sub_nav sml" id="last"><a href="/pages/Information/General_information/Contact_us">Contact us</a></h1>
					
					</div>

			<!--googleon: all--></div>