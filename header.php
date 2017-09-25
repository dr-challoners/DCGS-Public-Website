<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <title>Dr Challoner's Grammar School</title>
  <link rel="icon" type="image/png" href="img/favicon.png" />
  <meta name="description" content="Well established boys' secondary school with co-educational Sixth Form. News, prospectus, ethos, history and academic achievements." />

  <link href='https://fonts.googleapis.com/css?family=Crimson+Text:400,400italic' rel='stylesheet' type='text/css'>
  <link href='https://fonts.googleapis.com/css?family=Quattrocento+Sans:400,400italic,700,700italic' rel='stylesheet' type='text/css' />
  
  <link rel="stylesheet" href="/css/bootstrap.css" />
  <link rel="stylesheet" href="/css/dcgsAll.css" />
  <link rel="stylesheet" href="/css/dcgsNavigation.css" />
  <link rel="stylesheet" href="/css/dcgsHomepage.css" />
  <link rel="stylesheet" href="/css/dcgsContent.css" />
  <link rel="stylesheet" href="/css/dcgsIntranet.css" />
  <link rel="stylesheet" href="/css/dcgsDiary.css" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/modules/fancyBox/jquery.fancybox.css?v=2.1.5" media="screen" />

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  
  <!-- Favicons, baby! -->
  <link rel="apple-touch-icon" sizes="57x57" href="/img/icons/apple-touch-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="/img/icons/apple-touch-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="/img/icons/apple-touch-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="/img/icons/apple-touch-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="/img/icons/apple-touch-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="/img/icons/apple-touch-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="/img/icons/apple-touch-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="/img/icons/apple-touch-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="/img/icons/apple-touch-icon-180x180.png">
  <link rel="icon" type="image/png" href="/img/icons/favicon-32x32.png" sizes="32x32">
  <link rel="icon" type="image/png" href="/img/icons/android-chrome-192x192.png" sizes="192x192">
  <link rel="icon" type="image/png" href="/img/icons/favicon-96x96.png" sizes="96x96">
  <link rel="icon" type="image/png" href="/img/icons/favicon-16x16.png" sizes="16x16">
  <link rel="manifest" href="/img/icons/manifest.json">
  <link rel="mask-icon" href="/img/icons/safari-pinned-tab.svg" color="#1b4b87">
  <link rel="shortcut icon" href="/img/icons/favicon.ico">
  <meta name="apple-mobile-web-app-title" content="Challoner's">
  <meta name="application-name" content="Challoner's">
  <meta name="msapplication-TileColor" content="#2b5797">
  <meta name="msapplication-TileImage" content="/img/icons/mstile-144x144.png">
  <meta name="msapplication-config" content="/img/icons/browserconfig.xml">
  <meta name="theme-color" content="#1b4b87">

  <?php

    date_default_timezone_set("Europe/London");
		include('modules/functions/parsedown.php');
		include('modules/functions/miscTools.php');
		include('modules/functions/fetchData.php');
		include('modules/functions/transformText.php');

    // Common links
    $hardLink_termdates         = '/c/information/general-information/term-and-holiday-dates';
    $hardLink_admissions        = '/c/information/admissions/general-information';
    $hardLink_prospectus        = '/c/overview/introduction/prospectus';
    $hardLink_vacancies         = '/c/information/general-information/staff-vacancies';
    $hardLink_supportingus      = '/c/information/supporting-us/annual-giving-programme';
    $hardLink_information       = '/c/information/';
    $hardLink_schoolshop        = '/c/information/general-information/school-shop-uniform-and-stationery-information';
    $hardLink_privacy           = 'https://dcgs.box.com/s/naaj3kzym9wfbolj3a7tgath4gl5ihpt';
    $hardLink_contactus         = '/c/information/general-information/contact-us';
    $hardLink_alumni            = '/c/information/alumni/overview';
  ?>
  
  <!-- Major JavaScript libraries: at the top for general usage -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <script type='text/javascript' src='/modules/js/moment.js'></script>
  <script src="/modules/js/bootstrap.min.js"></script>
  
</head>
<body>
  <!-- Load Google Analytics --> 
  <?php include_once("analyticstracking.php") ?>
  <div class="container dcgsBanner hidden-xs">
    <a href="/"></a>
    <img class="img-responsive" src="/img/dcgsBanner.png" alt="Dr Challoner's Grammar School" />
  </div>
  <nav class="navbar dcgsNavbar" id="menuFix">
  <div class="container">
    <div class="navbar-header">
      <a class="navbar-brand visible-xs-block" href="/">
        <img src="/img/dcgsBanner_mobile.png" alt="Dr Challoner's Grammar School" />
      </a>
      <a class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menuContent" aria-expanded="false">
        Menu
      </a>  
    </div>
    <div class="collapse navbar-collapse" id="menuContent">
      <ul class="nav nav-justified navbar-nav">
        <li class="hidden-xs"><a href="/">Home</a></li>
        <?php
				function makeNavMenu($menu, $mobile = 0) {
					global $section;
					$navMenu = '<li class="dropdown';
					if (isset($section) && $section == clean($menu)) {
						$navMenu .= ' active';
					}
					if (!empty($mobile)) {
						$navMenu .= ' visible-xs-block';
					}
					$navMenu .= '">';
					$navMenu .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'.str_replace(' ','&nbsp;',$menu).'</a>';
					$navMenu .= '<div class="dropdown-menu">';
					
					$dir = scandir($_SERVER['DOCUMENT_ROOT'].'/pages/'.clean($menu));
					$dir = array_reverse($dir);
					foreach ($dir as $row) {
						if (strpos($row,'navDir-') !== false && strpos($row,'.json') !== false) {
							$dir = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/pages/'.clean($menu).'/'.$row);
							$dir = json_decode($dir, true);
							break;
						}
					}
					foreach ($dir as $sheetName => $pages) {
						$navMenu .= '<h3>'.$sheetName.'</h3>';
						$navMenu .= '<ul>';
						foreach ($pages as $pageName => $data) {
							$navMenu .= '<li><a href="'.$data['link'].'">'.formatText($pageName,0).'</a></li>';
						}
						$navMenu .= '</ul>';
					}
					$navMenu .= '</div>';
					$navMenu .= '</li>';
					return $navMenu;
				}

				echo makeNavMenu('Overview');
				echo makeNavMenu('Information',1);
				echo makeNavMenu('Student Life');
				echo makeNavMenu('Community');
				echo makeNavMenu('News',1);
          
          echo '<li class="hidden-xs';
          if (isset($section) && $section == 'intranet') {
            echo ' active';
          }
          echo '"><a href="/intranet">Intranet</a></li>';
          echo '<li class="visible-xs-block dropdown">';
            echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Intranet</a>';
            echo '<div class="dropdown-menu">';
              echo '<ul>';
                echo '<li><a href="/intranet/students">Students</a></li>';
                echo '<li><a href="/intranet/staff">Staff</a></li>';
                echo '<li><a href="/intranet/parents">Parents</a></li>';
              echo '</ul>';
            echo '</div>';
          echo '</li>';
          echo '<li class="hidden-xs';
          if (isset($section) && $section == 'diary') {
            echo ' active';
          }
          echo '"><a href="/diary">Diary</a></li>';
          echo '<li class="visible-xs-block dropdown">';
            echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Diary</a>';
            echo '<div class="dropdown-menu">';
              echo '<ul>';
                echo '<li><a href="/diary">This week\'s events</a></li>';
                echo '<li><a href="/diary/calendar/">Navigate the calendar</a></li>';
              echo '</ul>';
            echo '</div>';
          echo '</li>';
        ?>
        <li><a href="<?php echo $hardLink_alumni; ?>">Alumni</a></li>
        <li><a href="<?php echo $hardLink_contactus; ?>">Contact&nbsp;us</a></li>
        
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
  <div class="container">
