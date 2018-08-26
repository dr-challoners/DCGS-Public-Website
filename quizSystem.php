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
	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-TXfwrfuHVznxCssTxWoPZjhcss/hp38gEOH8UPZG/JcXonvBQ6SlsIF49wUzsGno" crossorigin="anonymous">
  <link rel="stylesheet" href="/css/dcgsQuiz.css" />

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
		include('modules/functions/parsedown.php');
		include('modules/functions/miscTools.php');
		include('modules/functions/fetchData.php');
		include('modules/functions/transformText.php');
  ?>

  <!-- Major JavaScript libraries: at the top for general usage -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <script type='text/javascript' src='/modules/js/moment.js'></script>
  <script type='text/javascript' src="/modules/js/bootstrap.min.js"></script>
  <script type='text/javascript' src="/modules/js/md5.js"></script>
  <script type='text/javascript' src="/modules/js/jquery.base64.js"></script>

</head>
<body>
    <?php
    // Fetch appropriate data for the quizzes
    // Note the use of 'manual' throughout, to prevent updates while students are loading quizzes
    $authorList = sheetToArray('1O6JfZ1dVshPmRL8RIK6_b63V_OoQ30hOR0aXPRUltes', 'data/quiz', 'manual'); // Master sheet
    if (isset($_GET['author']) && isset($_GET['quiz'])) {
        // Build the quiz page if we're looking at a specific quiz
        // Check author exists
        $pageExists = false;
        $syncPage = false;
        $quizList = null;
        $rawFileName = null;
        foreach ($authorList['data']['quiz'] as $authorData) {
            if (clean($authorData['authorname']) === clean($_GET['author'])) {
                // Check quiz exists
                if (!file_exists('data/quiz/' . clean($authorData['authorname']))) break;
                $localQuizList = array_diff(scandir('data/quiz/' . clean($authorData['authorname'])), array('.', '..'));
                $rawFileName = $authorData['sheetid'];
                if (file_exists('data/quiz/'.$_GET['author'].'/'.$_GET['quiz'].'.json')) {
                    // File exists: check if data in file
                    $rawQuizData = json_decode(file_get_contents('data/quiz/'.$_GET['author'].'/'.$_GET['quiz'].'.json'), true);
                    $pageExists = true;
                    if (array_key_exists('update', $rawQuizData)) $syncPage = true; // Placeholder, needs syncing
                } else {
                    $quizList = sheetToArray($authorData['sheetid'], 'data/quiz', 'manual');
                    // File doesn't exist: check if quiz exists: we already know that the author exists
                    $quizFileName = null;
                    //print_r($quizList['data']);
                    foreach (array_keys($quizList['data']) as $quizName) {
                        if (clean(explode('#',$quizName)[0]) === $_GET['quiz']) {
                            $quizFileName = clean($_GET['quiz']);
                        }
                    }
                    if ($quizFileName !== null) {
                        // Quiz exists: we want to sync the page as this is the first time visiting
                        $pageExists = true;
                        $syncPage = true;
                    }
                }
                break;
            }
        }

        if ($pageExists && ((isset($_GET['sync']) || $syncPage))) {
            if (!$quizList) $quizList = sheetToArray($authorData['sheetid'], 'data/quiz', 'manual');
            $quizDataToSync = array();
            foreach ($quizList['data'] as $quizName => $quizData) {
                // Select the correct data to build quiz from
                $quizURLName = $quizName;
                if (strpos($quizURLName,'#') !== false) $quizURLName = explode('#',$quizName)[0];
                $quizURLName = clean($quizURLName);
                if ($quizURLName === $_GET['quiz']) {
                    $quizDataToSync = $quizData;
                    $quizNameToSync = $quizName;
                }
            }

            echo $quizNameToSync;
            if (strpos($quizNameToSync,'#') !== false) {
                $quizNameToSync = explode('#',$quizNameToSync);
                $quizDisplayName   = trim($quizNameToSync[0]);
                $quizFileName      = clean($quizNameToSync[0]);
                $winnerCode        = trim($quizNameToSync[1]);
            } else {
                $quizDisplayName = trim($quizNameToSync);
                $quizFileName    = clean($quizNameToSync);
            }

            $quizArray = array();

            foreach ($quizDataToSync as $question) {
                if ((!empty($question['questiontext']) || !empty($question['imagevideourl'])) && !empty($question['answer'])) { // Must have a question and answer
                    // Parse the text and image/video link as a single entry in the array.
                    $questionContent = "";
                    if (!empty($question['questiontext'])) {
                        $questionContent .= Parsedown::instance()->parse($question['questiontext']);
                    }
                    if (!empty($question['imagevideourl'])) {
                        // Simple check to see if it's a YouTube video; if it isn't, assume an image instead.
                        if (!strpos($question['imagevideourl'],'youtube') && !strpos($question['imagevideourl'],'youtu.be')) {
                            $questionContent .= '<img src="'.fetchImageFromURL('/data/quiz',$question['imagevideourl']).'" class="img-responsive" />';
                        } else { // YouTube videos
                            if (strpos($question['imagevideourl'],'v=') !== false) {
                                $videoID = substr($question['imagevideourl'],strpos($question['imagevideourl'],'v=')+2,11);
                            } elseif (strpos($question['imagevideourl'],'youtu.be/') !== false) {
                                $videoID = substr($question['imagevideourl'],strpos($question['imagevideourl'],'youtu.be/')+9,11);
                            }
                            $questionContent .= '<div class="embed-responsive embed-responsive-16by9"><iframe src="https://www.youtube.com/embed/'.$videoID.'" allowfullscreen="true" class="embed-responsive-item"></iframe></div>';
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
                        $quizArray['author'] = clean($authorData['authorname']);
                        $quizArray['name'] = $quizDisplayName;
                        $quizArray['questions'][] = array('content' => $questionContent, 'answer' => $questionAnswers, 'format' => $questionFormat);
                        unset($questionContent,$questionAnswers,$questionFormat);
                    }
                }
                file_put_contents('data/quiz/'.$_GET['author'].'/'.$quizFileName.'.json', json_encode($quizArray));

        }
        // Delete file if exists
        if ($rawFileName && file_exists('data/quiz/'.$rawFileName.'.json')) {
            unlink('data/quiz/'.$rawFileName.'.json');
        }

        // Empty html ready to be filled by the js
        if ($pageExists) {
            echo '
                <div class="container">
                  <div id="root"></div>
                </div>
                <script type="text/javascript" src="/modules/js/quiz.js"></script>
            	<script>
                    let q = new Quiz("root");
                    let author = "'. $_GET['author'] .'";
                    let fileName = "'. $_GET['quiz'] .'";
                    q.generateQuiz(author,fileName);
            	</script>';
        } else {
            echo '
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <h1 id="quizTitle">Error 404: Quiz Not Found</h1>
                    </div>
                </div>
            </div>
            ';
        }
    } else {
        if (array_key_exists('sync', $_GET)) {
            foreach ($authorList['data']['quiz'] as $authorData) {
                if ($authorData['sheetid'] === $_GET['sync']) {
                    $data = sheetToArray($authorData['sheetid'], 'data/quiz', 'manual');

                    // Check if directory exists and create if not
                    if (!file_exists('data/quiz/' . clean($data['meta']['sheetname']))) mkdir('data/quiz/' . clean($data['meta']['sheetname']), 0777, true);
                    $localQuizList = array_diff(scandir('data/quiz/' . clean($data['meta']['sheetname'])), array('.', '..'));
                    foreach ($data['data'] as $quiz => $quizTitle) {
                        $file = clean(explode('#',$quiz)[0]).'.json';
                        $key = array_search($file, $localQuizList);
                        if($key) {
                            // The quiz already exists, remove from local array
                            unset($localQuizList[$key]);
                        } else {
                            // The quiz does not exist, create temporary file
                            file_put_contents('data/quiz/'.clean($data['meta']['sheetname']).'/'.$file, json_encode(array("update"=>true,"name"=>explode('#',$quiz)[0])));
                        }
                    }
                    // Now delete all files left as these are deleted or renamed quizzes
                    foreach ($localQuizList as $file) {
                        unlink('data/quiz/'.clean($data['meta']['sheetname']).'/'.$file);
                    }
                    unlink('data/quiz/'.$authorData['sheetid'].'.json');
                }
            }
        }

        // Display only the names of one quiz author
        if (isset($_GET['author'])) {
            foreach ($authorList['data']['quiz'] as $key => $authorData) {
                if (clean($authorData['authorname']) !== clean($_GET['author'])) { unset($authorList['data']['quiz'][$key]); }
            }
        }
        echo '<div class="container">';
        echo '<div class="row">';
        echo '<div class="col-xs-12">';
        echo '<h1>Quiz Admin</h1>';
        echo '</div>';
        echo '</div>';
        echo '<div class="row">';
        echo '<div class="col-xs-12 col-sm-6 col-sm-offset-3" id="helpLink">';
        echo '<a target="'.mt_rand().'" href="https://docs.google.com/document/d/1qQdBAcW8AcPEuLv38I17PkMPfk7me_kFV8Vy6hiYOL8">How to make a quiz</a>';
        echo '</div>';
        echo '</div>';

        // Check if any authors left, if none then invalid author
        if (empty($authorList['data']['quiz'])) echo '<h3>Error 404: Author not found</h3>';
        foreach ($authorList['data']['quiz'] as $authorData) {
            echo '<div class="row authorRow">';
            echo '<div class="col-sm-4 col-xs-12"><h2>'.$authorData['authorname'].'</h2></div>';
            echo '<div class="col-sm-8 col-xs-12">';
            echo '<a href="/quiz/?sync='.$authorData['sheetid'].'">Refresh Quiz List</a>';
            echo '<a target="'.mt_rand().'" href="https://docs.google.com/spreadsheets/d/'.$authorData['sheetid'].'">Edit My Quizzes</a>';
            echo '</div>';
            echo '</div>';

            if (file_exists('data/quiz/' . clean($authorData['authorname']))) {
                $authorQuizzes = array_diff(scandir('data/quiz/' . clean($authorData['authorname'])), array('.', '..'));
                foreach($authorQuizzes as $quiz) {
                    $quizData = json_decode(file_get_contents('data/quiz/' . clean($authorData['authorname']) .'/'.$quiz), true);
                    $quizName = $quizData['name'];
                    echo '<div class="row quizRow">';
                    echo '<div class="col-sm-4 col-xs-12"><h3>';
                    echo $quizName;
                    echo '</h3></div>';
                    $targetLoadID = mt_rand();
                    echo '<div class="col-sm-8 col-xs-12">';
                    echo ' <a target="'.clean($authorData['authorname']).'-'.clean($quizName).'-'.$targetLoadID.'" href="/quiz/'.clean($authorData['authorname']).'/'.clean($quizName).'">Go to Quiz</a>';
                    echo ' <a target="'.clean($authorData['authorname']).'-'.clean($quizName).'-'.$targetLoadID.'" href="/quiz/'.clean($authorData['authorname']).'/'.clean($quizName).'/update">Update</a>';
                    echo ' <a data-toggle="modal" data-target="#QR-'.clean($authorData['authorname']).'-'.clean($quizName).'">QR Code</a>';
                    echo ' <a data-toggle="modal" data-target="#Link-'.clean($authorData['authorname']).'-'.clean($quizName).'">Display Link</a>';
                    echo '</div>';
                    echo '</div>';
                    echo '<div class="modal fade" id="QR-'.clean($authorData['authorname']).'-'.clean($quizName).'" tabindex="-1">';
                    echo '<div class="modal-dialog" role="document">';
                    echo '<div class="modal-content">';
                    echo '<div class="modal-body">';
                    echo '<img class="img-responsive" src="https://chart.googleapis.com/chart?cht=qr&chs=540x540&chl=http://www.challoners.com/quiz/'.clean($authorData['authorname']).'/'.clean($quizName).'&choe=UTF-8" />';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '<div class="modal fade" id="Link-'.clean($authorData['authorname']).'-'.clean($quizName).'" tabindex="-1">';
                    echo '<div class="modal-dialog textURL">';
                    echo '<div class="modal-content">';
                    echo '<div class="modal-body">';
                    echo '<p>challoners.com/quiz/'.clean($authorData['authorname']).'/'.clean($quizName).'</p>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            }
        }

        echo '</div>';
        echo '</div>';
    }
    ?>
	<script type="text/javascript" async src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-MML-AM_CHTML"></script>
  <script src="/modules/js/jquery.pietimer.min.js"></script>
</body>
</html>
