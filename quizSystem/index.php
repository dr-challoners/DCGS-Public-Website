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
  
  <link rel="stylesheet" href="/css/bootstrap.css" />
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
  <script type='text/javascript' src='/modules/js/moment.js'></script>
  <script src="/modules/js/bootstrap.min.js"></script>
  <script src="/modules/js/md5.js"></script>
  <script src="/modules/js/jquery.base64.js"></script>
  
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
												$questionContent .= '<img src="'.fetchImageFromURL('/quizSystem/data',$question['imagevideourl']).'" />';
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
										foreach ($questionAnswers as $answerKey => $answerData) {
											$answerData = trim($answerData);
											if ($questionFormat == 'loose') {
												$answerData = clean($answerData);
											}
											if ($questionFormat != 'choice') {
												$answerData = md5($answerData);
											} elseif ($answerKey == $choiceAnswer) {
                        $choiceAnswer = md5($answerData);
                      }
											$questionAnswers[$answerKey] = $answerData;
										}
                    if ($questionFormat == 'choice') {
                      $questionAnswers['c'] = $choiceAnswer;
                    }
                    if (isset($winnerCode)) {
                      $quizArray['id'] = base64_encode($winnerCode);
                    }
										$quizArray['questions'][] = array('content' => $questionContent, 'answer' => $questionAnswers, 'format' => $questionFormat);
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
    var buildQuestion = function() {
      var quizPosition = (countCurrent - 1) * 100 / countTotal;
      $('#quizProgress').css('width', quizPosition + '%');
      $('#quizContent').html('<h2>Question ' + countCurrent + ' of ' + countTotal + '</h2>');
      $('#quizContent').append(quizData['questions'][countCurrent-1].content);
      if (quizData['questions'][countCurrent-1].format != 'choice') {
        $('#answerInput').html(
          '<label for="answerBox">' +
          'Answer:' +
          '<input type="text" id="answerBox" />' +
          '</label>' +
          '<button type="submit">Submit</button>'
        ); 
      } else {
        $('#answerInput').empty();
        $.each(quizData['questions'][countCurrent-1].answer, function(key, answer) {
          if (key != 'c') {
            $('#answerInput').append(
              '<input type="radio" id="answer-' + key + '" name="choice" value="' + answer + '"/>' +
              '<label for="answer-' + key + '">' + answer + '</label>');
          }
        });
        $('#answerInput').append('<button type="submit">Submit</button>');
      }
      MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
    };
    var correctAnswer = function () {
      if (countCurrent < countTotal) {
        countCurrent++;
        buildQuestion();
      } else {
        $('#quizProgress').css('width', '100%');
        $('#answerInput').remove();
        var winnerMessage = ['Congratulations!', 'Well done!', 'Great job!', 'Nice one!', 'Good work!', 'Awesome!', 'Hooray!'];
        var messageNumber = Math.floor(Math.random() * (winnerMessage.length - 1))
        $('#quizContent').html(
          '<h2>' + winnerMessage[messageNumber] + '</h2>' +
          '<p>You have completed this quiz!</p>'
        );
        if (quizData['id']) {
          $('#quizContent').append('<p>The winning code is ' + $.base64.decode(quizData['id']) + '.<p>');
        }
      }
    };
    var wrongAnswer = function () {
      $(':input[type="submit"]').prop('disabled', true);
      $('#answerInput').append('<p id="wrongAnswer">That\'s not correct. Check your working and try again.</p>');
      setTimeout(function() {
        $(':input[type="submit"]').prop('disabled', false);
        $('#wrongAnswer').remove();
      }, 5000);
    };
		$(document).ready(function() {
			$('#quizTitle').html('<?php echo $quizDisplayName; ?>');
			$.getJSON('/quizSystem/data/<?php echo $quizFileName; ?>.json', {_: new Date().getTime()}, function(json) {
        console.log(json);
        quizData = json;
        countCurrent = 1;
        countTotal   = quizData['questions'].length;
        buildQuestion();
      });
		});
    $('#answerInput').submit(function(event) {
      event.preventDefault();
      if (quizData['questions'][countCurrent-1].format != 'choice') {
        if (quizData['questions'][countCurrent-1].format === 'loose') {
          var answerToCheck = $('input#answerBox').val().toLowerCase().trim();
              answerToCheck = answerToCheck.replace(/ /g,'-');
              answerToCheck = answerToCheck.replace(/[^a-z0-9\-]/g,'');
              answerToCheck = answerToCheck.replace(/-+/g,'-');
              answerToCheck = md5(answerToCheck);
        } else {
          var answerToCheck = md5($('input#answerBox').val().trim());
        }
        if ($.inArray(answerToCheck,quizData['questions'][countCurrent-1].answer) != -1) {
          correctAnswer();
        } else {
          wrongAnswer();
        }
      } else {
        var answerToCheck = $('input[name="choice"]:checked').val();
            answerToCheck = md5(answerToCheck);
        if (answerToCheck == quizData['questions'][countCurrent-1].answer['c']) {
          correctAnswer();
        } else {
          wrongAnswer();
        }
      }
    });
	</script>
	<?php
    } else {
      echo '<div class="container">';
        echo '<div class="row">';
          echo '<h1>Quiz Admin</h1>';
          echo '<a target="'.mt_rand().'" href="https://docs.google.com/document/d/1qQdBAcW8AcPEuLv38I17PkMPfk7me_kFV8Vy6hiYOL8">How to make a quiz</a>';
        echo '</div>';
        foreach ($authorList['data']['quiz'] as $authorData) {
          echo '<h2>'.$authorData['authorname'].'</h2>';
          echo '<a href="/quiz/?sync='.$authorData['sheetid'].'">Refresh Quiz List</a>';
          echo '<a target="'.mt_rand().'" href="https://docs.google.com/spreadsheets/d/'.$authorData['sheetid'].'">Edit My Quizzes</a>';
          if (isset($sync) && $sync == $authorData['sheetid']) {
            $syncStatus = 0;
          } else {
            $syncStatus = manual;
          }
          $quizList = sheetToArray($authorData['sheetid'], 'data', $syncStatus);
          foreach ($quizList['data'] as $quizName => $quizData) {
            $quizName = explode('#',$quizName)[0];
            $quizName = trim($quizName);
            echo '<p>';
              echo $quizName;
              $targetLoadID = mt_rand();
              echo ' <a target="'.clean($authorData['authorname']).'-'.clean($quizName).'-'.$targetLoadID.'" href="/quiz/'.clean($authorData['authorname']).'/'.clean($quizName).'">Go to Quiz</a>';
              echo ' <a target="'.clean($authorData['authorname']).'-'.clean($quizName).'-'.$targetLoadID.'" href="/quiz/'.clean($authorData['authorname']).'/'.clean($quizName).'/update">Update</a>';
              echo ' <a data-toggle="modal" data-target="#QR-'.clean($authorData['authorname']).'-'.clean($quizName).'">Link for Students</a>';
            echo '</p>';
            echo '<div class="modal fade" id="QR-'.clean($authorData['authorname']).'-'.clean($quizName).'" tabindex="-1" role="dialog" aria-labelledby="QR code for this quiz">';
              echo '<div class="modal-dialog" role="document">';
                echo '<div class="modal-content">';
                  echo '<div class="modal-body">';
                    echo '<img class="img-responsive" src="https://chart.googleapis.com/chart?cht=qr&chs=540x540&chl=http://www.challoners.com/quiz/'.clean($authorData['authorname']).'/'.clean($quizName).'&choe=UTF-8" />';
                    echo '<p>QR code for this quiz. You can display this screen via your projector for students to scan the code on their iPads. Or right click on the image and select \'Copy image\' or \'Save image as...\' to take a copy of this QR code, to add to your presentation.</p>';
                    echo '<p>You can also point students to the quiz by telling them the address: <b>challoners.com/quiz/'.clean($authorData['authorname']).'/'.clean($quizName).'</b></p>';
                  echo '</div>';
                echo '</div>';
              echo '</div>';  
            echo '</div>';
          }
        }
      echo '</div>';
     }
  ?>
	<script type="text/javascript" async src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-MML-AM_CHTML"></script>
</body>
</html>