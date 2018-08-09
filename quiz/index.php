<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <title>Dr Challoner's Grammar School</title>
  <meta name="description" content="Well established boys' secondary school with co-educational Sixth Form. News, prospectus, ethos, history and academic achievements." />

  <link href='https://fonts.googleapis.com/css?family=Crimson+Text:400,400italic' rel='stylesheet' type='text/css'>
  <link href='https://fonts.googleapis.com/css?family=Quattrocento+Sans:400,400italic,700,700italic' rel='stylesheet' type='text/css' />
  
  <link rel="stylesheet" href="../css/bootstrap.css" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
  
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

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  
  <?php
    date_default_timezone_set("Europe/London");
		include('../modules/functions/parsedown.php');
		include('../modules/functions/miscTools.php');
		include('../modules/functions/fetchData.php');
		include('../modules/functions/transformText.php');
  ?>
  
  <!-- Major JavaScript libraries: at the top for general usage -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <script type='text/javascript' src='../modules/js/moment.js'></script>
  <script src="../modules/js/bootstrap.min.js"></script>
  
</head>
<body>
  <?php
    // Fetch appropriate data for the quizzes
    // Note the use of 'manual' throughout, to prevent updates while students are loading quizzes
    $authorList = sheetToArray('1O6JfZ1dVshPmRL8RIK6_b63V_OoQ30hOR0aXPRUltes', 'data', manual); // Master sheet
    if (isset($_GET['author']) && isset($_GET['quiz'])) {
      // Build the quiz page if we're looking at a specific quiz
      foreach ($authorList['data']['quiz'] as $authorData) {
        if (clean($authorData['authorname']) == clean($_GET['author'])) {
          $quizList = sheetToArray($authorData['sheetid'], 'data', manual);
          foreach ($quizList['data'] as $quizName => $quizData) {
            if (strpos($quizName,'#') !== false) {
            $quizName = explode('#',$quizName);
            $quizDisplayName   = trim($quizName[0]);
            $quizFileName      = clean($quizName[0]);
            $winnerCode        = trim($quizName[1]);
            } else {
              $quizDisplayName = trim($quizName);
              $quizFileName    = clean($quizName);
            }
            if ($quizFileName == clean($_GET['quiz'])) {
              // Found the correct quiz. Stop here with the quiz name data.
              // If syncing, now make/update the relevant json file.
              if (isset($_GET['sync'])) {
                $quizArray = array();
                foreach ($quizData as $question) {
									if ((!empty($question['questiontext']) || !empty($question['imagevideourl'])) && !empty($question['answer'])) { // Must have a question and answer
										// Parse the text and image/video link as a single entry in the array.
										$questionContent;
										if (!empty($question['questiontext'])) {
											$questionContent .= Parsedown::instance()->parse($question['questiontext']);
										}
										if (!empty($question['imagevideourl'])) {
											// Simple check to see if it's a YouTube video; if it isn't, assume an image instead.
											if (!strpos($question['imagevideourl'],'youtube') && !strpos($question['imagevideourl'],'youtu.be')) {
												$questionContent .= '<img src="'.fetchImageFromURL('/quiz/data',$question['imagevideourl']).'" />';
											} else { // YouTube videos
												if (strpos($question['imagevideourl'],'v=') !== false) {
													$videoID = substr($question['imagevideourl'],strpos($question['imagevideourl'],'v=')+2,11);
												} elseif (strpos($question['imagevideourl'],'youtu.be/') !== false) {
													$videoID = substr($question['imagevideourl'],strpos($question['imagevideourl'],'youtu.be/')+9,11);   
												}
												$questionContent .= '<iframe src="https://www.youtube.com/embed/'.$videoID.'" allowfullscreen="true"></iframe>';
											}
										}
										// Validate the format type
										if (strpos(clean($question['questionformat']),'choice') !== false) {
											$questionFormat = explode('#',$question['questionformat']);
											$choiceAnswer = clean($questionFormat[1])-1;
											$questionFormat = clean($questionFormat[0]);
										} else {
											$questionFormat = clean($question['questionformat']);
											if ($questionFormat != 'loose') {
												$questionFormat = 'strict';
											}
										}
										// Parse the answers: strip whitespace from either side and check the formatting
										$questionAnswers = explode('#',$question['answer']);
										if (isset($choiceAnswer)) {
											if(array_key_exists($choiceAnswer,$questionAnswers)) {
												$keys = array_keys($questionAnswers);
												$keys[array_search($choiceAnswer,$keys)] = 'c';
												$questionAnswers = array_combine($keys,$questionAnswers);
											}
										}
										foreach ($questionAnswers as $answerKey => $answerData) {
											$answerData = trim($answerData);
											if ($questionFormat == 'loose') {
												$answerData = clean($answerData);
											}
											if ($questionFormat != 'choice') {
												$answerData = md5($answerData);
											}
											$questionAnswers[$answerKey] = $answerData;
										}
										$quizArray[] = array('content' => $questionContent, 'answer' => $questionAnswers, 'format' => $questionFormat);
										unset($questionContent,$questionAnswers,$questionFormat);
									}
                }
                file_put_contents('data/'.$quizFileName.'.json', json_encode($quizArray));
              }
              break;
            }
          }
        }
      }
			// Empty html ready to be filled by the js
	?>
	<div class="container">
		<div class="row">
			<h1 id="quizTitle"></h1>
		</div>
		<div class="row">
			<div class="progress">
  			<div class="progress-bar" role="progressbar" id="quizProgress"></div>
			</div>
		</div>
		<div class="row" id="quizContent"></div>
		<div class="row">
			<form id="answerInput">
			</form>
		</div>
	</div>
	<script>
		$(document).ready(function() {
			$('#quizTitle').html('<?php echo $quizDisplayName; ?>');
			$.getJSON('./data/<?php echo $quizFileName; ?>.json', function(data) {
				var countCurrent = 1;
				var countTotal   = data.length;
				var quizPosition = (countCurrent - 1) * 100 / countTotal;
				$('#quizProgress').css('width', quizPosition + '%');
				$('#quizContent').html('<h2>Question ' + countCurrent + ' of ' + countTotal + '</h2>');
      	$('#quizContent').append(data[countCurrent-1].content);
				if (data[countCurrent-1].format != 'choice') {
					$('#answerInput').html(
						'<label for="answerBox">' +
							'Answer:' +
							'<input type="text" id="answerBox" />' +
						'</label>' +
						'<button type="submit" id="checkAnswer">Submit</button>'
					);
				} else {
					// Multiple choice form building
				}
			});
		});
	</script>
	<?php
    } else {
      // The admin menu for teachers will go here.
    }
  ?>
	<script type="text/javascript" async src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-MML-AM_CHTML"></script>
</body>
</html>