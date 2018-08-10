<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
 
  <title>DCGS - Sync
		<?php
		if (isset($_GET['page'])) {
			echo '(working)';
		} elseif (isset($_GET['stage'])) {
			echo '(done)';
		}
		?>
	</title>
  <link rel="icon" type="image/png" href="img/faviconSync.png" />
  
  <link rel="stylesheet" href="/css/bootstrap.css" />
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-TXfwrfuHVznxCssTxWoPZjhcss/hp38gEOH8UPZG/JcXonvBQ6SlsIF49wUzsGno" crossorigin="anonymous">
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
		<div class="row">
			<div class="col-xs-12">
        <h1><a href="./sync" style="color:black";><i class="fas fa-cog"></i></a> DCGS Content Management System</h1>
      </div>
		</div>
		<div class="row">
		<?php 
			if ($_GET['tab'] == 'maths') {
				include('updateMaths.php');
				$mainID = '1m31LpUcjWpJdvWJl-CVhbeHGlOHzVBARBhHWYfv5tPc';
				$siteLoc = 'maths';
				$pageLoc = 'maths/pages/';
			} else {
				include('updateOptions.php');
				$mainID = '1n-oqN8rF98ZXqlH7A_eUx6K_5FgK2RUpiCx3aUMg3kM';
				$siteLoc = 'c';
				$pageLoc = 'pages/';
			}
			?>
		<?php
  if ($tab == 'content' || $tab == 'maths') {
    echo '<div role="tabpanel" class="tab-pane fade in active" id="content">';
  } else {
    echo '<div role="tabpanel" class="tab-pane fade" id="content">';
  }
  ?>
			<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
			<?php
				if ($_GET['tab'] == 'maths') {
					$area = 'maths';
				} else {
					$area = 'content';
				}
				if (isset($_GET['page'])) { // Write a single page to the server
					if (!isset($_GET['stage'])) {
						//$sheetData = sheetToArray($_GET['sheet'],'data/content'); //- I don't think this is needed.
						// To begin, just add a message to indicate that the process has started.
						$percent = 0;
						$message = 'checking for images';
						echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=/sync?tab='.$area.'&section='.$_GET['section'].'&sheet='.$_GET['sheet'].'&page='.$_GET['page'].'&stage=img">';
					} elseif ($_GET['stage'] == 'img') {
						// First stage - check to see if there are images.
						$sheetData = sheetToArray($_GET['sheet'],'data/content',0);
						$c = searchPageForImages($sheetData, $_GET['page'], $pageLoc);
						if ($c > 0) {
							$percent = round(100/($c+1));
							$message = 'images found, processing';
							echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=/sync?tab='.$area.'&section='.$_GET['section'].'&sheet='.$_GET['sheet'].'&page='.$_GET['page'].'&stage=0&end='.$c.'">';
						} else {
							$percent = 50;
							$message = 'no images found, building page';
							echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=/sync?tab='.$area.'&section='.$_GET['section'].'&sheet='.$_GET['sheet'].'&page='.$_GET['page'].'&stage=pge">';
						}
					} elseif ($_GET['stage'] == 'pge') {
							// This is the final stage, after images have processed, but the code comes in first so the if function here works.
							$sheetData = sheetToArray($_GET['sheet'],'data/content',0);
							parsePagesSheet($sheetData, $_GET['page'], $mainID, $siteLoc, $pageLoc);
							$percent = 100;
							$message = 'page updated, finishing processing';
							echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=/sync?tab='.$area.'&section='.$_GET['section'].'&sheet='.$_GET['sheet'].'&stage='.$_GET['page'].'">';
						} else {
							// Processing images. Each page reload takes one image from the json,
							// saves the image file, and updates the json with the code to go on the page.
							$sheetData = sheetToArray($_GET['sheet'],'data/content');
							$directory = $pageLoc.clean($_GET['section']).'/'.clean($sheetData['meta']['sheetname']);
							$fileName = str_ireplace('[hidden]','',$_GET['page']);
							$fileName = clean($fileName);
							$imagesArray = file_get_contents($directory.'/'.$fileName.'.json');
							$imagesArray = json_decode($imagesArray, true);
							$currentImage = $imagesArray[$_GET['stage']];
							include ('modules/parsing/images.php');
							$imagesArray[$_GET['stage']] = array('id' => $currentImage['id'], 'output' => $content);
							file_put_contents($directory.'/'.$fileName.'.json', json_encode($imagesArray));
							$next = $_GET['stage']+1;
							if ($next < $_GET['end']) {
								$percent = round((100*($next))/($_GET['end']+1));
								$message = 'processing images';
								echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=/sync?tab='.$area.'&section='.$_GET['section'].'&sheet='.$_GET['sheet'].'&page='.$_GET['page'].'&stage='.$next.'&end='.$_GET['end'].'">';
							} else {
								$percent = round((100*($next))/($_GET['end']+1));
								$message = 'images processed, building page';
								//unlink($directory.'/'.$fileName.'.json'); - needs to go in after the page has been created
								echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=/sync?tab='.$area.'&section='.$_GET['section'].'&sheet='.$_GET['sheet'].'&page='.$_GET['page'].'&stage=pge">';
							}
						}
				} 
				if (isset($_GET['sheet'])) { // Display the pages in a single section to update
					if (!isset($sheetData)) {
						$sheetData = sheetToArray($_GET['sheet'],'data/content',0);
					}
					if (isset($_GET['delete'])) {
						unlink($pageLoc.clean($_GET['section']).'/'.clean($sheetData['meta']['sheetname']).'/'.$_GET['delete']);
					}
					$exists = directoryToArray($pageLoc.clean($_GET['section']).'/'.clean($sheetData['meta']['sheetname']));
					foreach ($exists as $key => $file) {
						if (stripos($file,'.php') == false) {
							unset($exists[$key]);
						}
					}
					echo '<a href="/sync?tab='.$area.'">Back to main options</a>';
					echo '<a class="pull-right" href="/sync?tab='.$area.'&section='.$_GET['section'].'&sheet='.$_GET['sheet'].'">Refresh this section (if you have added or renamed pages)</a>';
					echo '<div class="panel panel-default quickLinks">';
					echo '<div class="panel-heading">';
					echo '<h4 class="panel-title">'.$_GET['section'].': '.$sheetData['meta']['sheetname'].'</h4>';
					echo '</div>';
					echo '<div class="panel-body">';
					foreach ($sheetData['data'] as $page => $row) {
						$eCheck = str_ireplace(array('[hidden]','[link]'),'',$page);
						$eCheck = clean($eCheck);
						$e = array_search($eCheck.'.php',$exists);
						if ($e !== false) {
							unset($exists[$e]);
						}
						echo '<div class="row buttonLine">';
						if (!isset($_GET['page'])) {
							$link = '/sync?tab='.$area.'&section='.$_GET['section'].'&sheet='.$_GET['sheet'].'&page='.$page;
							if ($e === false) {
								echo '<div class="col-xs-2"><a class="btn btn-success btn-block btn-center" href="'.$link.'">Create</a></div>';
							} else {
								echo '<div class="col-xs-2"><a class="btn btn-default btn-block btn-center" href="'.$link.'">Update</a></div>';
							}
							echo '<div class="col-xs-10"><p>';
							if (isset($_GET['stage']) && clean($_GET['stage']) == clean($page)) {
								echo '<span class="text-success"><b>'.$page.'</b> - page updated '.date('H:i:s').'</span>';
							} else {
								echo $page;
							}
							echo '</p></div>';
						} else {
							if (clean($_GET['page']) == clean($page)) {
								echo '<div class="col-xs-2"><a class="btn btn-default btn-block btn-center disabled" aria-disabled="true">'.$percent.'%</a></div>';
								echo '<div class="col-xs-10"><p><span class="text-primary"><b>'.$page.'</b> - '.$message.'</span></p></div>';
							} else {
								echo '<div class="col-xs-2"><a class="btn btn-default btn-block btn-center disabled" aria-disabled="true">...</a></div>';
								echo '<div class="col-xs-10"><p>'.$page.'</p></div>';
							}
						}
						echo '</div>';
						unset ($e);
					}
					if (!isset($_GET['page'])) {
						foreach ($exists as $row) {
							echo '<div class="row buttonLine">';
							echo '<div class="col-xs-2"><a class="btn btn-danger btn-block btn-center" href="/sync?tab='.$area.'&section='.$_GET['section'].'&sheet='.$_GET['sheet'].'&delete='.$row.'">Delete</a></div>';
							echo '<div class="col-xs-10"><p>'.$row.'</p></div>';
							echo '</div>';
						}
					}
					echo '</div>';
					echo '</div>';
				}	else { // Display the main options
					$mainData = sheetToArray($mainID,'data/content',0);
					foreach ($mainData['data'] as $section => $row) {
						echo '<div class="panel panel-default content">';
						echo '<div class="panel-heading" role="tab" id="heading-'.clean($section).'">';
						echo '<h4 class="panel-title">';
						echo '<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-'.clean($section).'" aria-expanded="false" aria-controls="collapse-'.clean($section).'">'.$section.'</a>';
						echo '</h4>';
						echo '</div>';
						echo '<div id="collapse-'.clean($section).'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-'.clean($section).'">';
						echo '<div class="panel-body">';
						foreach ($row as $data) {
							if (strpos($data['sheetid'],'spreadsheets/d/') !== false) {
										$cutoff = strpos($data['sheetid'],'spreadsheets/d/');
										$cutoff = $cutoff+15;
										$data['sheetid'] = substr($data['sheetid'],$cutoff);
										$data['sheetid'] = explode('/',$data['sheetid'])[0];
									}
							echo '<div class="row options">';
							echo '<div class="col-xs-12 col-sm-3"><p>'.$data['sheetname'].':</p></div>';
							echo '<div class="col-xs-12 col-sm-9 btn-group" role="group" aria-label="...">';
							echo '<a class="btn btn-default" href="/sync?tab='.$area.'&section='.$section.'&sheet='.$data['sheetid'].'">Update content</a>';
							echo '<a class="btn btn-default" href="https://docs.google.com/spreadsheets/d/'.$data['sheetid'].'" target="'.mt_rand().'">Edit spreadsheet</a>';
							echo '<a class="btn btn-default" href="/'.$siteLoc.'/'.clean($section).'/'.clean($data['sheetname']).'" target="'.mt_rand().'">Visit content</a>';
							echo '</div>';
							echo '</div>';
						}
						echo '</div>';
						echo '</div>';
						echo '</div>';
					}
				}
			?>
				</div>
			</div>
		</div>
	</div> <!-- .container -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="/modules/js/bootstrap.min.js"></script>
</body>
</html>