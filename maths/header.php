<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Learn Maths with DCGS</title>
    <link rel="icon" type="image/png" href="img/favicon.png" />
    <meta name="description" content="Revision materials for Maths from Dr Challoner's Grammar School." />
    
    <link href='https://fonts.googleapis.com/css?family=Crimson+Text:400,400italic' rel='stylesheet' type='text/css'>
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
    
    <?php
    date_default_timezone_set("Europe/London");
    include($_SERVER['DOCUMENT_ROOT'].'modules/functions/parsedown.php');
		include($_SERVER['DOCUMENT_ROOT'].'modules/functions/miscTools.php');
		include($_SERVER['DOCUMENT_ROOT'].'modules/functions/fetchData.php');
		include($_SERVER['DOCUMENT_ROOT'].'modules/functions/transformText.php');    
    ?>
  
  <!-- Major JavaScript libraries: at the top for general usage -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <script type='text/javascript' src='/modules/js/moment.js'></script>
  <script src="/modules/js/bootstrap.min.js"></script>
  
  </head>
  <body>
  <nav class="navbar navbar-learnBanner navbar-fixed-top hidden-xs">
    <a href="/maths">
      <div class="container">
        <img src="/img/learnLogo.png" alt="DCGS Learn" />
        <h1 class="pull-right">Mathematics</h1>
      </div>
    </a>
  </nav>
  <div class="container">
    <div class="row">
      <?php include('navigation.php'); ?>
      <div class="col-sm-8 col-md-9 learnPage">