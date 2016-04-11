<?php
  if (!isset($_GET['user'])) { // This allows the app to be displayed as an independent page - the modal isn't working on mobiles.
    include ('../../header.php');
    echo '<div class="row" id="weeklyPuzzle">';
  }
?>

<!-- Google sign in ----------- -->
<meta name="google-signin-scope" content="profile email">
<meta name="google-signin-client_id" content="562493904957-8l4k3ugj0rutqun2dp635iv3vfqp28hf.apps.googleusercontent.com">
<script src="https://apis.google.com/js/platform.js" async defer></script>
<!-- Socket.io -->
<script src="https://cdn.socket.io/socket.io-1.4.5.js"></script>
<!-- iCheck styles and function -->
<link rel="stylesheet" href="https://www.googledrive.com/host/0B9FoiZzm3CVVMy1rSVMxcnJENEU/icheck/yellow.css">
<script src="http://www.googledrive.com/host/0B9FoiZzm3CVVMy1rSVMxcnJENEU/icheck.js"></script>
<!-- Puzzle functions --------- -->
<script src="http://www.googledrive.com/host/0B9FoiZzm3CVVMy1rSVMxcnJENEU/script.js"></script>

<style>

  #weeklyPuzzle .modal-header, .modal-footer {
    background-color: #E95704;
    color: #fff;
    font-weight: bold;
    border: 0;
  }
  #weeklyPuzzle .modal-header {
    padding: 0 20px 8px;
  }
  #weeklyPuzzle .modal-header h3 {
    font-family: "Quattrocento Sans", Helvetica, sans-serif;
    font-weight: bold;
  }
  #weeklyPuzzle .modal-footer {
    padding: 8px 15px;
  }
  #weeklyPuzzle .modal-footer p {
    margin-bottom: 0;
    font-size: 14px;
  }
  #weeklyPuzzle a {
    color: #E95704;
    font-weight: bold;
  }
  #weeklyPuzzle a:hover {
    color: #FB9800;
  }

  #weeklyPuzzle .g-signin2 {
    width: 240px;
    margin: 15px auto 10px;
  }

  #question {
    font-size: 22px;
    font-weight: bold;
    line-height: 1.3;
    text-align: center;
  }

  #options {
    list-style-type: none;
    padding: 0;
    margin: 0 5px 20px;
    cursor: pointer;
  }
  #options li {
    padding: 4px 10px 0;
  }
  #options li:hover {
    background-color: #f6f6f6;
  }
  #options label {
    margin-left: 15px;
  }

  .puzzleData {
    margin-bottom: 15px;
    display:none;
  }
  .puzzleData h4 {
    font-family: "Quattrocento Sans", Helvetica, sans-serif;
    border-bottom: 1px solid #FB9800;
    padding-bottom: 6px;
    margin-top: 15px;
  }
  .puzzleData p {
    margin-bottom: 5px;
  }
  .puzzleData #revealAnswerButton a {
    cursor: pointer;
  }
  .puzzleData #prevQuestion {
    font-style: italic;
  }
  .puzzleData #prevAnswer {
    font-weight: bold;
  }

  #buttonContainer button {
    font-size: 16px;
    width: 110px;
  }
  #buttonContainer i {
    padding-right: 8px;
  }
  #buttonContainer .btn-signout {
    background-color: #eee;
    color: #111;
  }
  #buttonContainer .btn-signout:hover {
    background-color: #ddd;
  }
  #buttonContainer .btn-puzzleSubmit {
    background-color: #E95704;
    color: #fff;
  }
  #buttonContainer .btn-puzzleSubmit:hover {
    background-color: #FB9800;
  }

  #puzzle,
  #puzzleCont,
  #donePuzzle,
  #completedPuzzle,
  #puzzleCredit,
  #revealAnswer {
    display: none;
  }

  #weeklyPuzzle .text-danger {
    display: none;
    text-align: center;
  }

</style>

<div class="modal-header">
  <h3>Weekly puzzle <span class="pull-right"><i class="fa fa-puzzle-piece fa-lg"></i></span></h3>
</div>
<div class="modal-body" id="modal-body">
  <div class="row" id="signIn">
    <div class="col-xs-12" id="parentTitle">
      <p class="text-center">Please sign in with your Challoner's account to view the weekly puzzle.</p>
      <div class="g-signin2" data-onsuccess="onSignIn" data-width="240" data-theme="dark" data-longtitle="true"></div>
      <p class="text-danger" id="error3"><i class="fa fa-exclamation-triangle"></i> Sorry, you need to use a Challoner's account to sign in.</p>
    </div>
  </div>
    <div class="row" id="donePuzzle">
    <div class="col-xs-12 doneMessage" id="parentTitle">
      <p>You have already completed this puzzle. Come back next Monday for more!</p>
    </div>
  </div>
  <div class="row" id="completedPuzzle">
    <div class="col-xs-12" id="parentTitle">
      <p>Thank you for your submission.<br />Come back next Monday for the answer, and another puzzle!</p>
    </div>
  </div>
  <div class="row" id="puzzle">
    <section id="puzzleCont">
      <div class="col-xs-12" id="parentTitle">
        <p id="question"></p>
      </div>
      <form id="puzzle-form">
        <div class="col-md-12" id="parentTitle"></div>
        <div id="optionContainer" class="col-xs-12">
          <ul id="options">
          </ul>
        </div>
        <p class="text-danger" id="noAnsError"><i class="fa fa-exclamation-triangle"></i> Sorry, you need to submit an answer.</p>
        <div class='puzzleData col-xs-12' id='scoreDiv'>
            <p id="score"></p>
        </div>
        <div id="buttonContainer" class="col-xs-12">
          <button class="btn btn-signout" onClick="signOut()"><i class="fa fa-google"></i> Sign out</button>
          <button type="button" class="btn btn-puzzleSubmit pull-right" id="submit">Submit</button>
        </div>
      </form>
    </section>
        <div id='puzzleData' class="puzzleData col-xs-12">
    <h4>Last week's puzzle</h4>
    <p id='prevQuestion'></p>
    <p>Answer: <span id="revealAnswerButton"><a onClick="$('#revealAnswer').fadeToggle();$('#revealAnswerButton').hide();">click to reveal</a>.</span>
      <span id="revealAnswer">
        <span id='prevAnswer'></span>.
        <span id='explain'></span>
      </span>
    </p>
  </div>
  </div>



  <!-- DEVELOPMENT ONLY
  <button class="btn" onClick="removeKey()">Remove local storage</button>-->
</div>
<div class="modal-footer" id="puzzleCredit">
  <p id="creator"></p>
  <p>App developed by Mayank Sharma (Year 11) and Cameron Robey (Year 10).</p>
  <p>If you would like to contribute a puzzle, speak to Mr Burn.</p>
</div>
<script>
  function iCheck() {
    $('.puzzleInput').iCheck({
      checkboxClass: 'icheckbox_square-yellow',
      radioClass: 'iradio_square-yellow',
      increaseArea: '20%'
    });
  }
</script>

<?php
  if (!isset($_GET['user'])) {
    echo '</div>';
    include ('../../footer.php');
  }
?>