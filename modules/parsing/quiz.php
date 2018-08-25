<?php
$quizDetails = explode(",",$row['content']);

// There can only be two items here, if not return nothing
if (count($quizDetails) === 2) {
    $author = $quizDetails[0];
    $name = $quizDetails[1];

    if (file_exists($_SERVER["DOCUMENT_ROOT"].'/data/quiz/'.$author.'/'.$name.'.json')) {
        // Javascript modules that only need to be loaded if a quiz is present
        $quiz = '<script type="text/javascript" src="/modules/quiz/quiz.js"></script>
                <script type="text/javascript" src="/modules/js/md5.js"></script>
                <script type="text/javascript" src="/modules/js/jquery.base64.js"></script>
                <script src="/modules/js/jquery.pietimer.min.js"></script>';

        // Div quiz is constructed in
        $quiz .= '<div id="root"></div>';

        // Script to initialise quiz
        $quiz .= '<script>
                let q = new Quiz("root");
                q.generateQuiz("'.$author.'","'.$name. '");
            </script>';
        $output['content'][] = $quiz;
    }

}

?>
