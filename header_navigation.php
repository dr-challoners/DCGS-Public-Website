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
            echo '<li><a href="/c/Overview/" ';
              if (isset($_GET['section']) && strtolower($_GET['section']) == "overview") {
                echo 'id="selected"';
              } else {
                echo 'onmouseover="mopen(\'n2\')" onmouseout="mclosetime()"';
              }
            echo '>Overview</a></li>';
            echo '<li><a href="/c/Student-life/" ';
              if (isset($_GET['section']) && strtolower($_GET['section']) == "student-life") {
                echo 'id="selected"';
              } else {
                echo 'onmouseover="mopen(\'n3\')" onmouseout="mclosetime()"';
              }
            echo '>Student life</a></li>';
            echo '<li><a href="/c/Community/" ';
              if (isset($_GET['section']) && strtolower($_GET['section']) == "community") {
                echo 'id="selected"';
              } else {
                echo 'onmouseover="mopen(\'n4\')" onmouseout="mclosetime()"';
              }
            echo '>Community</a></li>';
            echo '<li><a href="/intranet/"';
              if (isset($intranet)) { echo ' id="selected"'; }
            echo '>Intranet</a></li>';
            echo '<li><a href="/diary"';
              if (isset($curTimestamp)) { echo ' id="selected"'; }
            echo '>Diary</a></li>';
            echo '<li><a href="/c/Information/Alumni/"';
               if (isset($_GET['sheet']) && strtolower($_GET['sheet']) == "alumni") { echo ' id="selected"'; }
            echo '>Alumni</a></li>';
            echo '<li><a href="/c/Information/General-information/Contact-us"';
               if (isset($_GET['page']) && strtolower($_GET['page']) == "contact-us") { echo ' id="selected"'; }
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
				
				$dropdowns = array("Information","Overview","Student life","Community");
				$div_id = 1;
				foreach ($dropdowns as $maindir) {
					echo '<div class="sub_menu" name="submenu" id="n'.$div_id.'" onmouseover="mcancelclosetime()" onmouseout="mclosetime()">';
          $sheets = array();
				  foreach ($mainData['data']['sheets'] as $id => $sheet) {
            if (strtolower(clean($sheet['section'])) == strtolower(clean($maindir))) {
              $sectionName = $sheet['section'];
              $sheets[$id] = $sheet;
            }
          }
          navigatePagesSheet($sheets,$contentURL);
					echo "</div>";
					$div_id++;
        }
        ?>

        <!-- These are mobile-specific navigation menus -->
          
          <!-- Alumni -->
          <div class="sub_menu" name="submenu" id="nA">
				  <?php
            $id = '1omig90iSURs1yZXtPo5ce6S-xBkESFFL23ukSKHgANw';
            $sheets = array();
            $sheets[$id] = $mainData['data']['sheets'][$id];
						navigatePagesSheet($sheets,$contentURL);
				    ?>
					</div>
          
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
          <h1 class="sml" id="last"><a href="/c/Information/General-information/Contact-us">Contact us</a></h1>					
					
					<hr class="clear" /></div>

			<!--googleon: all--></div>