<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <title>
    <?php
      date_default_timezone_set("Europe/London");
		  include('modules/functions/parsedown.php');
		  include('modules/functions/miscTools.php');
		  include('modules/functions/fetchData.php');
		  include('modules/functions/transformText.php');
    
      if (isset($displayTitle)) {
        echo $displayTitle.' - ';
      } elseif (isset($sheet)) {
        if ($section == 'news') {
          echo 'News - ';
        }
        else {
          echo revert($sheet).' - ';
        }
      }
    ?>
    Dr Challoner's Grammar School
  </title>
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
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-TXfwrfuHVznxCssTxWoPZjhcss/hp38gEOH8UPZG/JcXonvBQ6SlsIF49wUzsGno" crossorigin="anonymous">
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
    // Common links
    $hardLink_termdates         = '/c/information/general-information/term-and-holiday-dates';
    $hardLink_admissions        = '/c/information/admissions/admissions-policy';
    $hardLink_prospectus        = '/c/information/admissions/prospectus';
    $hardLink_vacancies         = '/c/information/general-information/staff-vacancies';
    $hardLink_supportingus      = '/c/community/supporting-the-school/astra-fund';
    $hardLink_information       = '/c/information/';
    $hardLink_privacy           = 'https://drive.google.com/file/d/1quXxCLdklK9QLZ6kJPwUXU4okuEJOcLq/view';
    $hardLink_contactus         = '/c/information/general-information/contacting-us';
    $hardLink_reportconcern     = '/c/information/general-information/report-a-concern';
    $hardLink_alumni            = '/c/community/alumni/old-challoners';
		$hardLink_sixthform         = '/c/our-school/sixth-form/welcome-to-the-sixth-form';
  ?>
  
  <!-- Major JavaScript libraries: at the top for general usage -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js"></script>
  <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
  <script type='text/javascript' src='/modules/js/moment.js'></script>
  <script src="/modules/js/bootstrap.min.js"></script>
  <script type='text/javascript' src='/modules/js/gallery.js'></script>
  
</head>
<body>
  <!-- Load Google Analytics --> 
  <?php include_once("analyticstracking.php") ?>
  <div class="container dcgsBanner hidden-xs">
    <a href="/"></a>
    <img class="img-responsive" src="/img/dcgsBanner.png" alt="Dr Challoner's Grammar School" />
  </div>
	<div class="container-fluid hidden-xs menuFix">
		<div class="container" id="DCGSMainNav-Links">
			<p>
				<a href="/">Home</a>
				<a id="our-school" role="button" data-toggle="collapse" data-parent="#DCGSMainNav-Menu" href="#collapse-our-school" aria-expanded="false" aria-controls="collapse-our-school">Our&nbsp;School</a>
        <a id="community" role="button" data-toggle="collapse" data-parent="#DCGSMainNav-Menu" href="#collapse-community" aria-expanded="false" aria-controls="collapse-community">Community</a>
				<a id="information" role="button" data-toggle="collapse" data-parent="#DCGSMainNav-Menu" href="#collapse-information" aria-expanded="false" aria-controls="collapse-information">Information</a>
				<a id="enrichment" role="button" data-toggle="collapse" data-parent="#DCGSMainNav-Menu" href="#collapse-enrichment" aria-expanded="false" aria-controls="collapse-enrichment">Enrichment</a>
        <a id="resources" role="button" data-toggle="collapse" data-parent="#DCGSMainNav-Menu" href="#collapse-resources" aria-expanded="false" aria-controls="collapse-resources">Resources</a>
				<a href="/diary">Diary</a>
				<a href="<?php echo $hardLink_contactus; ?>">Contact&nbsp;Us</a>
			</p>
		</div>
		<div class="container-fluid DCGSMainNav-MenuBkgd">
		<div class="container" id="DCGSMainNav-Menu" role="tablist" aria-multiselectable="true">
		<?php
			function makeScreenMenu($menu) {
				$navMenu  = '<div class="panel">';
				$navMenu .= '<div id="collapse-'.clean($menu).'" class="collapse collapseMain" role="tabpanel" aria-labelledby="'.clean($menu).'">';
				$navMenu .= '<div class="row panelMain">';
				$dir = scandir($_SERVER['DOCUMENT_ROOT'].'/pages/'.clean($menu));
				$dir = array_reverse($dir);
				foreach ($dir as $row) {
					if (strpos($row,'navDir-') !== false && strpos($row,'.json') !== false) {
						$dir = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/pages/'.clean($menu).'/'.$row);
						$dir = json_decode($dir, true);
						break;
					}
				}
				$colCount = 0;
				foreach ($dir as $sheetName => $pages) {
					if ($colCount % 2 == 0) {
					if ($colCount > 0) {
						$navMenu .= '</div>';
					}
					$navMenu .= '<div class="col-xs-4 panelMain">';
					}
					$navMenu .= '<h3 class="panelMain">'.$sheetName.'</h3>';
					$navMenu .= '<ul>';
					foreach ($pages as $pageName => $data) {
            if (!isset($data['show']) || $data['show'] < mktime()) {
						  $navMenu .= '<li><a href="'.$data['link'].'">'.formatText($pageName,0).'</a></li>';
            }
					}
					$navMenu .= '</ul>';
					$colCount++;
				}
				$navMenu .= '</div>';
				$navMenu .= '</div>';
				$navMenu .= '</div>';
				$navMenu .= '</div>';
				return $navMenu;
			}
			echo makeScreenMenu('Our School');
      echo makeScreenMenu('Community');
			echo makeScreenMenu('Information');
			echo makeScreenMenu('Enrichment');
		?>
      <div class="panel">
        <div id="collapse-resources" class="collapse collapseMain" role="tabpanel" aria-labelledby="resources">
          <div class="row panelMain">
            <div class="col-xs-4 panelMain">
              <h3 class="panelMain">Students</h3>
                <ul>
                  <li><a href="https://sites.google.com/challoners.org/student-resources" target="<?php echo 'page'.mt_rand(); ?>">Student Resources</a></li>
                  <li><a href="https://www.google.com/url?q=https%3A%2F%2Fchalloners.students.isams.cloud%2F&sa=D&sntz=1&usg=AFQjCNEF9Rz-SU_ljeMGy4BrZPekMbWv6w" target="<?php echo 'page'.mt_rand(); ?>">Student Portal</a></li>
                  <li><a href="https://sites.google.com/challoners.org/student-resources/subject-resources" target="<?php echo 'page'.mt_rand(); ?>">Subject Resources</a></li>
                  <li><a href="https://sites.google.com/challoners.org/sixth-form" target="<?php echo 'page'.mt_rand(); ?>">Sixth Form</a></li>
                </ul>
            </div>
            <div class="col-xs-4 panelMain">
              <h3 class="panelMain">Staff</h3>
                <ul>
                  <li><a href="https://sites.google.com/challoners.org/staff-resources" target="<?php echo 'page'.mt_rand(); ?>">Staff Resources</a></li>
                  <li><a href="https://challoners.isams.cloud/Main/Framework" target="<?php echo 'page'.mt_rand(); ?>">iSAMS</a></li>
                </ul>
            </div>
            <div class="col-xs-4 panelMain">
              <h3 class="panelMain">Parents</h3>
                <ul>
                  <li><a href="https://sites.google.com/challoners.org/parent-handbook" target="<?php echo 'page'.mt_rand(); ?>">Parent Handbook</a></li>
                  <li><a href="https://www.google.com/url?q=https%3A%2F%2Fchalloners.parents.isams.cloud%2F&sa=D&sntz=1&usg=AFQjCNHrk4gTCFQkEoFM0XOnrOjtjkQw8g" target="<?php echo 'page'.mt_rand(); ?>">Parent Portal</a></li>
                  <li><a href="https://sites.google.com/challoners.org/sixth-form/handbook" target="<?php echo 'page'.mt_rand(); ?>">Sixth Form Handbook</a></li>
                </ul>
            </div>
          </div>
        </div>
      </div>
		</div>
	</div>
	

	</div>
  <!-- 400 Years banner -->
  <div class="container hidden-xs banner400">
    <div class="row">
      <img src="/img/dcgsBanner_400YearsBanner.png" alt="400 Years of DCGS" />
      <p>
        <a href="https://www.challoners.com/c/our-school/400-years/calendar-of-events">Events Calendar</a>
        <a href="https://www.challoners.com/c/our-school/400-years/a-brief-history-of-dcgs">History</a>
        <a href="https://www.challoners.com/c/our-school/400-years/anniversary-book">Anniversary Book</a>
        <a href="https://www.challoners.com/c/our-school/400-years/dcgs-in-pictures">DCGS in Pictures</a>
        <a href="https://www.challoners.com/c/our-school/400-years/fondest-memories">Memories</a>
        <a href="https://www.challoners.com/c/our-school/400-years/400-facts-for-400-years">400 Facts</a>
    </div>
  </div>
  <!-- End of 400 Years banner -->
  <nav class="navbar dcgsNavbar visible-xs-block menuFix">
  <div class="container">
    <div class="navbar-header">
      <a class="navbar-brand" href="/">
        <img src="/img/dcgsBanner_mobile.png" alt="Dr Challoner's Grammar School" />
      </a>
      <a class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menuContent" aria-expanded="false">
        Menu
      </a>  
    </div>
    <div class="collapse navbar-collapse" id="menuContent">
      <ul class="nav nav-justified navbar-nav">
        <?php
				function makeMobileMenu($menu) {
					$navMenu = '<li class="dropdown">';
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
          $sheetCount = 1;
					foreach ($dir as $sheetName => $pages) {
						$navMenu .= '<h3>'.$sheetName.'</h3>';
						$navMenu .= '<ul>';
						foreach ($pages as $pageName => $data) {
              if (!isset($data['show']) || $data['show'] < mktime()) {
							  $navMenu .= '<li><a href="'.$data['link'].'">'.formatText($pageName,0).'</a></li>';
              }
						}
						$navMenu .= '</ul>';
            $sheetCount++;
            if ($menu === 'News' && $sheetCount > 12) {
              break;
            }
					}
					$navMenu .= '</div>';
					$navMenu .= '</li>';
					return $navMenu;
				}
				echo makeMobileMenu('Our School');
        echo makeMobileMenu('Community');
				echo makeMobileMenu('Information');
				echo makeMobileMenu('Enrichment');
				echo makeMobileMenu('News');
				echo '<li class="dropdown">';
				echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Resources</a>';
				echo '<div class="dropdown-menu">';
				echo '<ul>';
				echo '<li><a href="https://sites.google.com/challoners.org/student-resources">Students</a></li>';
				echo '<li><a href="https://sites.google.com/challoners.org/staff-resources">Staff</a></li>';
				echo '<li><a href="https://sites.google.com/challoners.org/parent-handbook">Parents</a></li>';
				echo '</ul>';
				echo '</div>';
				echo '</li>';
				echo '<li class="dropdown">';
				echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Diary</a>';
				echo '<div class="dropdown-menu">';
				echo '<ul>';
				echo '<li><a href="/diary">This Week\'s Events</a></li>';
				echo '<li><a href="/diary/calendar/">Navigate the Calendar</a></li>';
				echo '</ul>';
				echo '</div>';
				echo '</li>';
        ?>
        <li><a href="<?php echo $hardLink_alumni; ?>">Alumni</a></li>
        <li><a href="<?php echo $hardLink_contactus; ?>">Contact&nbsp;Us</a></li>
        
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
  <div class="container">
