<?php
  include('sheetsCMSheader.php');
  if (!isset($_GET['sheet']) && !isset($_GET['action']) && !isset($_GET['learn'])) {
    include('sheetsCMSnavigation.php');
  } elseif (isset($_GET['sheet'])) {
    include('sheetsCMSprocess.php');
  } elseif (isset($_GET['learn'])) {
    include('sheetsCMSprocessLearn.php');
  }

?>
  </div> <!-- .container -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="/modules/js/bootstrap.min.js"></script>
</body>
</html>