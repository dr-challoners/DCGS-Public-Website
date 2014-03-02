	</head>
	<body>
		
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
		
		<!-- These divs create the blue and white bars across the top and bottom of the page. On small screens, appropriate elements can handle this styling as the screen width will equal the page width. -->
		<div class="colourbar lrg" id="top"></div>
		<div class="colourbar lrg" id="btm"></div>

		<div class="page"><!--On large screens, this constrains the elements to 980px wide and centres them in the browser. On small screens it does nothing. -->
			<div class="header"><!--Fixes all these items to the top of the browser-->
				
				<a class="bannerlink lrg" href="/"></a>
				<!-- 'sml' and 'lrg' classes allow different objects to be displayed in small/big screens: the CSS selects appropriately. -->
				<img class="banner_img lrg" src="/main_imgs/logo_lrg.png" alt="Dr Challoner's Grammar School" />
				
				<!-- On small screens very little will be displayed in the footer: it will all go in the drop-down menus here. -->
				<div class="nav_dropdown">
				<div class="sml">
				<a class="bannerlink" href="/"><img class="banner_img" src="/main_imgs/logo_sml.png" alt="Dr Challoner's Grammar School" /></a>
				<p><span onmouseover="mopen('n1')">Links</span> <span class="spacer">&middot;</span> <span onmouseover="mopen('n2')">About us</span></p>
				</div>
				
				<div class="nav_about" id="n2" onmouseover="mcancelclosetime()"><ul>
					<li class="sml"><a href="/pages/overview/">Overview</a></li>
					<li><a href="/pages/learning/">Learning</a></li>
					<li class="sml"><a href="/">Results</a></li>
					<li><a href="/pages/the-arts/">The Arts</a></li>
					<li><a href="/">Sports</a></li>
					<li><a href="/pages/enrichment/">Enrichment</a></li>
					<li><a href="/pages/houses/">Houses</a></li>
					<li><a href="/pages/history/">History</a></li>
					<li class="sml"><a href="/">Prospectus</a></li>
					<li class="sml"><a href="/">Academy info</a></li>
				</ul><hr class="sml clear" /></div>
				
				<div class="nav_links" id="n1" onmouseover="mcancelclosetime()"><ul>
					<li class="lrg"><a href="/">Home</a></li>
					<!-- This goes to the diary with the current day set-->
					<li class="lrg"><a href="/diary/<? echo date(Y)."/".date(m)."/".date(Ymd)."#".date(Ymd); ?>">Diary</a></li>
					<li class="sml"><a href="/diary/m/<? echo date(Ymd)."#".date(Ymd); ?>">Diary</a></li>
					<li class="sml"><a href="/">Term dates</a></li>
					<li><a href="/portal/parents/">Parents</a></li>
					<li><a href="/portal/intranet/">Intranet</a></li>
					<li class="sml"><a href="/pages/admissions/">Admissions</a></li>
					<li><a href="/">Alumni</a></li>
					<li><a href="/">Support us</a></li>
					<li class="sml"><a href="/">Vacancies</a></li>
					<li><a href="/">Contact us</a></li>
				</ul><hr class="sml clear" /></div>
				
				</div>

			</div>