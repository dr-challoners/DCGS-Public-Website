	</head>
  <body>
		
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
        <img class="banner_img lrg" src="/styles/imgs/logo_lrg.png" alt="Dr Challoner's Grammar School" />
				
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
            echo '>Overview</a></li>';
            echo '<li><a href="/diary"';
              if (isset($curTimestamp)) { echo ' id="selected"'; }
            echo '>Diary</a></li>';
            echo '<li><a href="/intranet/"';
              if (isset($intranet)) { echo ' id="selected"'; }
            echo '>Intranet</a></li>';
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
            echo '>Showcase</a></li>';
            echo '<li><a href="/pages/Information/Alumni/"';
               if (isset($_GET['subfolder']) && strtolower($_GET['subfolder']) == "alumni") { echo ' id="selected"'; }
            echo '>Alumni</a></li>';
            echo '<li><a href="/pages/Information/General_information/Contact_us"';
               if (isset($_GET['page']) && strtolower($_GET['page']) == "contact_us") { echo ' id="selected"'; }
            echo '>Contact us</a></li>';
            ?>
					</ul>
				<a class="rbutton" href="/search/"><img src="/styles/imgs/search.png" alt="Search" /></a>
				</div>
				
				<div class="main_nav sml">
					<a href="/"><img src="/styles/imgs/logo_sml.png" alt="Dr Challoner's Grammar School" /></a>
					<p><a href="javascript:openClose('main_nav')">Menu</a></p>
				</div>
				
				<div class="nav_menu" id="main_nav"> <!-- This serves no purpose on big screens, but on small screens provides a box to open and close to access the menu. -->
          				
				<?php // This next section creates the drop-down menus for the main navigation
				
				$dropdowns = array("Information","Overview","Student life","Showcase");
				$div_id = 1;
				
				foreach ($dropdowns as $maindir) {
          
					echo '<div class="sub_menu" name="submenu" id="n'.$div_id.'" onmouseover="mcancelclosetime()" onmouseout="mclosetime()">';
				
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
					
					$div_id++;
        }

        ?>

        <!-- These are mobile-specific navigation menus -->
          
          <!-- Alumni -->
          <div class="sub_menu" name="submenu" id="nA">
            <h2>Alumni</h2>
            <ul>
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
          
					<!-- Diary -->	
          <div class="sub_menu" name="submenu" id="n0">
            <h2>Diary</h2>
            <ul>
							<li><a href="/diary">This week's events</a></li>
							<li><a href="/diary/calendar/">Browse the calendar</a></li>
						</ul>
          </div>
					
          <!-- Intranet -->
						<div class="sub_menu" name="submenu" id="n9">
              <h2>Intranet</h2>
              <ul>
                <li><a href="/intranet/students">Students</a></li>
                <li><a href="/intranet/staff">Staff</a></li>
                <li><a href="/intranet/parents">Parents</a></li>
						  </ul>
              <ul>
                <h2>Quick links</h2>
                <li><a href="https://dcgs.okta.com/">Okta portal</a></li>
                <li><a href="https://drive.google.com">Google Drive</a></li>
                <li><a href="https://mail.google.com">Gmail</a></li>
                <li><a href="https://classroom.google.com">Google Classroom</a></li>
              </ul>
          </div>
          
        <?php  
        
        // Corresponding mobile menu navigation links

				$div_id = 1;
				foreach ($dropdowns as $maindir) {
					echo '<h1 class="sml"><a href="javascript:openCloseAll(\'n'.$div_id.'\')">'.$maindir.'</a></h1>';
          $div_id++;
        }

				?>
					
					<!-- Remainder of the links for the mobile menu. -->
					
          <h1 class="sub_nav sml"><a href="javascript:openCloseAll('nA')">Alumni</a></h1>
          <h1 class="sml"><a href="javascript:openCloseAll('n0')">Diary</a></h1>
          <h1 class="sml"><a href="javascript:openCloseAll('n9')">Intranet</a></h1>
          <h1 class="sml" id="last"><a href="/pages/Information/General_information/Contact_us">Contact us</a></h1>					
					
					<hr class="clear" /></div>

			<!--googleon: all--></div>