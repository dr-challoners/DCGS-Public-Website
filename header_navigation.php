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

		<div class="page"><!--On large screens, this constrains the elements to 980px wide and centres them in the browser. On small screens it does nothing. -->
			<div class="header"><!--Fixes all these items to the top of the browser-->
				
				<a class="bannerlink lrg" href="/"></a>
				<!-- 'sml' and 'lrg' classes allow different objects to be displayed in small/big screens: the CSS selects appropriately. -->
				<img class="banner_img lrg" src="/main_imgs/logo_lrg.png" alt="Dr Challoner's Grammar School" />
				
				<div class="main_nav">
				<a class="lbutton" href="/"><img src="/main_imgs/home.png" alt="Home" /></a>
					<ul>
						<li><a href="/pages/Overview/">Overview</a></li>
						<li><a href="/diary/<?php echo date('d')."/".date('m')."/".date('Y'); ?>">Diary</a></li>
						<li><a href="/portal/intranet/">Intranet</a></li><!--This will need to do something different.-->
						<li><a href="/pages/Student life/">Student life</a></li>
						<li><a href="/pages/Showcase/">Showcase</a></li>
						<li><a href="/pages/Alumni/">Alumni</a></li>
						<li><a href="/pages/Overview/Information/Contact us">Contact us</a></li>
					</ul>
				<a class="rbutton" href="/search/"><img src="/main_imgs/search.png" alt="Search" /></a>
				</div>
					
				
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