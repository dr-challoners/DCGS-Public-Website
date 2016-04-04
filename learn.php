<?php
  $userID = time().'-'.mt_rand();
  if (!isset($_COOKIE['user'])) {
    setcookie('user', $userID, time() + (86400 * 7), "/");
  } else {
    $userID = $_COOKIE['user'];
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <?php
    date_default_timezone_set("Europe/London");
    include('modules/commonFunctions.php');
    include('modules/functionsCMS.php');
    include('modules/content/process.php');
    include('modules/parsedown.php');

    // All pages rely on the mainData, even if just for navigation
    if (file_exists('data/content/learnData.json') && isset($_GET['subject'])) {
      $learnData = file_get_contents('data/content/learnData.json');
      $learnData = json_decode($learnData, true);
      if (isset($learnData['data'][$_GET['subject']])) {
        $siteData = $learnData['data'][$_GET['subject']];
        $subject  = $siteData['config']['title'];
        echo '<title>Learn '.$subject.' with DCGS</title>';
      } else {
        echo '<title>Learn with DCGS</title>';
        $error = 1;
      }
    } else {
      $error = 1;
    }
  ?>
  
  
  <link rel="icon" type="image/png" href="img/favicon.png" />
  <meta name="description" content="Revision materials from Dr Challoner's Grammar School." />
  
  <link href='https://fonts.googleapis.com/css?family=Quattrocento+Sans:400,400italic,700,700italic' rel='stylesheet' type='text/css' />
  
  <link rel="stylesheet" href="/css/bootstrap.css" />
  <link rel="stylesheet" href="/css/navbar-fixed-side.css" />
  <link rel="stylesheet" href="/css/dcgsContent.css" />
  <link rel="stylesheet" href="/css/dcgsLearn.css" />
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
  
  <!-- Major JavaScript libraries: at the top for general usage -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <script type='text/javascript' src='/modules/js/moment.js'></script>
  <script src="/modules/js/bootstrap.min.js"></script>
  
</head>
<body>
  <?php if (!isset($error)) { ?>
  <style>
    <?php 
      if (!empty($siteData['config']['backgroundimage'])) {
        $bkgd = fetchImageFromURL('/data/images',$siteData['config']['backgroundimage']);
        echo '@media (min-width: 768px) { body { background-image: url('.$bkgd.'); } }';
      }
      if (!empty($siteData['config']['colour'])) {
        $colour = $siteData['config']['colour'];
        echo 'a, .navbar-learnMenu .navbar-collapse a { color: '.$colour.'; }';
        echo '.navbar-learnBanner, .navbar-learnMenu .navbar-header, .barLink:hover { background-color: '.$colour.'; }';
      } 
    ?>
  </style>
  <nav class="navbar navbar-learnBanner navbar-fixed-top hidden-xs">
    <a href="/learn/<?php echo clean($_GET['subject']); ?>">
      <div class="container">
        <img src="/img/learnLogo.png" alt="DCGS Learn" />
        <?php echo '<h1 class="pull-right">'.$subject.'</h1>'; ?>
      </div>
    </a>
  </nav>
  <div class="container">
    <div class="row">
      <div class="col-sm-4 col-md-3">
        <nav class="navbar navbar-learnMenu navbar-fixed-side">
          <div class="container">
              <div class="navbar-header">
                <button class="navbar-toggle" data-target=".navbar-collapse" data-toggle="collapse">
                  Menu <i class="fa fa-chevron-down"></i>
                </button>
                <a class="navbar-brand visible-xs-block" href="/learn/<?php echo clean($_GET['subject']); ?>">Learn <?php echo $subject; ?></a>
              </div>
              <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                  <li><a href="/learn/<?php echo clean($_GET['subject']); ?>"><i class="fa fa-home fa-fw"></i> Home</a></li>
                  <li><a href="/"><i class="fa fa-shield fa-fw"></i> DCGS</a></li>
                  <?php
                    foreach($siteData['sheets'] as $sheet) {
                      if (!isset($section) || $section != $sheet['section']) {
                        if (isset($section)) { echo '</div>'; } // Finishes up the previous panel group
                        $section = $sheet['section'];
                        echo '<h3>'.$section.'</h3>';
                        echo '<div class="panel-group" id="learnMenu" role="tablist" aria-multiselectable="true">';
                      }
                      
                        echo '<div class="panel">';
                          echo '<div class="panel-heading" role="tab" id="heading-'.clean($section).'-'.clean($sheet['sheetname']).'">';
                            echo '<h4 class="panel-title">';
                              echo '<a role="button" data-toggle="collapse" data-parent="#learnMenu" href="#collapse-'.clean($section).'-'.clean($sheet['sheetname']).'" aria-expanded="true" aria-controls="collapse-'.clean($section).'-'.clean($sheet['sheetname']).'">';
                                echo $sheet['sheetname'];
                              echo '</a>';
                            echo '</h4>';
                          echo '</div>';
                          echo '<div id="collapse-'.clean($section).'-'.clean($sheet['sheetname']).'" class="panel-collapse collapse';
                            if (isset($_GET['sheet']) && clean($_GET['sheet']) == clean($sheet['sheetname']) && clean($_GET['section']) == clean($section)) {
                              echo ' in';
                            }
                          echo '" role="tabpanel" aria-labelledby="heading-'.clean($section).'-'.clean($sheet['sheetname']).'">';
                            echo '<div class="panel-body"><ul>';
                              foreach ($sheet['pages'] as $page) {
                                if (stripos($page,'[hidden]') === false) {
                                  if (stripos($page,'[link:') === false) {
                                    echo '<li><a href="/learn/'.clean($_GET['subject']).'/'.clean($section).'/'.clean($sheet['sheetname']).'/'.clean($page).'">'.$page.'</a></li>';
                                  } else {
                                    $link = explode('[link:',$page);
                                    $linkName = trim($link[0]);
                                    $linkURL  = explode(']',$link[1])[0];
                                    echo '<li><a href="'.$linkURL.'">'.formatText($linkName,0).'</a></li>';
                                  }
                                }
                              }
                            echo '</ul></div>';
                          echo '</div>';
                        echo '</div>';
                    }
                  ?>
                </ul>
              </div>
            </div>
        </nav>
      </div>
      <div class="col-sm-8 col-md-9 learnPage">
        <?php
          if (isset($_GET['page'])) {
            $pageName = $_GET['page'];
          } elseif (isset($_GET['sheet'])) {
            foreach ($siteData['sheets'] as $sheet) {
              if (clean($_GET['section']) == clean($sheet['section']) && clean($_GET['sheet']) == clean($sheet['sheetname'])) {
                $pageName = clean($sheet['pages'][0]);
                break;
              }
            }
          }
          if (!isset($pageName)) {
            $pageURL = 'http://www.challoners.com/learn/'.$_GET['subject'];
            parsePagesSheet(false,'Welcome',0,0,$siteData['index']);
          } else {
            foreach ($siteData['sheets'] as $sheetID => $sheet) {
              if (clean($_GET['section']) == clean($sheet['section']) && clean($_GET['sheet']) == clean($sheet['sheetname'])) {
                $pageURL = 'http://www.challoners.com/learn/'.clean($_GET['subject']).'/'.clean($section).'/'.clean($sheet['sheetname']).'/'.clean($pageName);
                parsePagesSheet($sheetID,$pageName);
                break;
              }
            }
          }
        ?>
      </div>
    </div>
  </div> <!-- .container -->
  <script type="text/javascript" src="/modules/js/fadeSlideShow.js"></script>
  <script type="text/javascript">
    jQuery(document).ready(function(){
      jQuery('#slideshow').fadeSlideShow();
    });
  </script>
  <script type="text/javascript" async
    src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-MML-AM_CHTML">
  </script>
  <script type="text/javascript" src="/modules/fancyBox/jquery.fancybox.js?v=2.1.5"></script>
  <script type="text/javascript" src="/modules/fancyBox/jquery.mousewheel-3.0.6.pack.js"></script>
  <script>
    $(document).ready(function() {
      $(".fancyBox").fancybox({
        type : 'image',
        helpers		: {
          title	: { type : 'over' }
        }
      });
    });
  </script>
  <?php
    } else {
      include ('globalError.php');
    }
  ?>
</body>
</html>