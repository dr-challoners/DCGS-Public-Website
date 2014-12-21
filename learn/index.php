<?php include('header.php'); ?>

<div id="banner"> <!-- The big colour bar across the top -->
  <!--googleoff: all-->
  <div id="titles">
    <img src="/learn/styles/imgs/learnlogo.png" />
    <?php
      if (isset($ConfigTitle)) {
        echo '<h1><a href="/'.$rootpath.$_GET['subject'].'">'.$ConfigTitle.'</a></h1>';
      }
    ?>
  </div>
</div>
<div class="page"> <!-- For centre-aligning the page -->
  <div class="lftcol">
    <div class="navigation">
      <?php
        if (isset($ConfigTitle)) {
          echo '<h2><a href="/'.$rootpath.$_GET['subject'].'">'.$ConfigTitle.' home page</a></h2>';
        }
      ?>
      <h2><a href="http://www.challoners.com">Dr Challoner's Grammar School</a></h2>
    </div>
    <div class="navigation">
      <?php include('navigation.php'); // Drop-down menu navigation ?>
    </div>
  </div>
  <!--googleon: all-->
  <div class="parsebox">
    <?php
      $parsediv = 1;
      include('content_location.php'); // The actual content for the page, generated from the folders
   ?>
  </div>
  <hr class="clear"> <!-- Cleaning up floating elements -->
</div>

<?php include('footer.php'); ?>