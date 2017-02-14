<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
 
  <title>DCGS - Sync</title>
  <link rel="icon" type="image/png" href="img/faviconSync.png" />
  
  <link rel="stylesheet" href="/css/bootstrap.css" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="/css/dcgsCMS.css" />
  
  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  
  <?php
    date_default_timezone_set("Europe/London");
    include('modules/functions/parsedown.php');
		include('modules/functions/miscTools.php');
		include('modules/functions/fetchData.php');
		include('modules/functions/transformText.php');
    include('updateParsing.php');
  ?>
  
</head>
<body>
  <div class="container">
    <?php
      if (isset($_GET['page'])) { // Write a single page to the server
        $sheetData = sheetToArray($_GET['sheet'],'data/content',0);
        if ($_GET['section'] == 'News') {
          $share = 1;
        } else {
          $share = 0;
        }
        $check = parsePagesSheet($sheetData, $_GET['page'], $share);
        if (!isset($check) || $check != 'ERROR') {
          echo 'Page updated!';
        } else {
          echo 'Error!';
        }
      }
      if (isset($_GET['sheet'])) { // Display the pages in a single section to update
        if (!isset($sheetData)) {
          $sheetData = sheetToArray($_GET['sheet'],'data/content',0);
        }
        echo '<h1>'.$_GET['section'].': '.$sheetData['meta']['sheetname'].'</h1>';
        foreach ($sheetData['data'] as $page => $row) {
          echo '<ul>';
            echo '<li><a href="?section='.$_GET['section'].'&sheet='.$_GET['sheet'].'&page='.$page.'">'.$page.'</a></li>';
          echo '</ul>';
        }
      }
      else { // Display the main options
        $mainData = sheetToArray('1n-oqN8rF98ZXqlH7A_eUx6K_5FgK2RUpiCx3aUMg3kM','data/content',0);
        foreach ($mainData['data'] as $section => $row) {
          echo '<h1>'.$section.'</h1>';
          echo '<ul>';
          foreach ($row as $data) {
            if (strpos($data['sheetid'],'spreadsheets/d/') !== false) {
                  $cutoff = strpos($data['sheetid'],'spreadsheets/d/');
                  $cutoff = $cutoff+15;
                  $data['sheetid'] = substr($data['sheetid'],$cutoff);
                  $data['sheetid'] = explode('/',$data['sheetid'])[0];
                }
            echo '<li><a href="?section='.$section.'&sheet='.$data['sheetid'].'">'.$data['sheetname'].'</a></li>';
          }
          echo '</ul>';
        }
      }
    ?>