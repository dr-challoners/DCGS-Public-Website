<?php

// Javascript modules that only need to be loaded if a quiz is present
$quiz = '<script type='text/javascript' src="/modules/quiz/quiz.js"></script>
        <script type='text/javascript' src="/modules/js/md5.js"></script>
        <script type='text/javascript' src="/modules/js/jquery.base64.js"></script>
        <script src="/modules/js/jquery.pietimer.min.js"></script><script>';

// Div quiz is constructed in
$quiz .= '<div id="root"></div>';

// Script to initialise quiz
$quiz .= '<script>
        let q = new Quiz("root");
        q.generateQuiz('.$row['content'].');
    </script>';

$output['content'][] = $quiz;

?>
