<?php include('header.php'); ?>
<div class="row">
  <div class="col-sm-frontLarge">
    <?php
      include('indexVTU.php');
      include('indexOverride.php');
      include('highlights.php');
      include('indexNews.php');
    ?>
  </div>
  <div class="col-sm-frontSmall hidden-xs">
    <div class="row intranetButtons">
      <div class="col-sm-5">
        <p>Resources</p>
      </div>
      <div class="col-sm-7">
        <!-- Other navigation icons could go in here -->
      </div>
    </div>
    <div class="row intranetAreas">
      <div class="col-sm-4">
        <p><a href="https://sites.google.com/challoners.org/student-resources" target="<?php echo 'page'.mt_rand(); ?>">Students</a></p>
      </div>
      <div class="col-sm-4">
        <p><a href="https://sites.google.com/challoners.org/staff-resources" target="<?php echo 'page'.mt_rand(); ?>">Staff</a></p>
      </div>
      <div class="col-sm-4">
        <p><a href="https://sites.google.com/challoners.org/parent-handbook" target="<?php echo 'page'.mt_rand(); ?>">Parents</a></p>
      </div>
    </div>
    <div class="shortcutLinks">
      <div class="row">
        <div class="col-sm-6">
          <p><a href="<?php echo $hardLink_admissions; ?>">Admissions</a></p>
        </div>
        <div class="col-sm-6">
          <p><a href="<?php echo $hardLink_sixthform; ?>">Sixth Form</a></p>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6">
          <p><a href="<?php echo $hardLink_prospectus; ?>">Prospectus</a></p>
        </div>
        <div class="col-sm-6">
          <p><a href="<?php echo $hardLink_alumni; ?>">Alumni</a></p>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6">
          <p><a href="https://files.ofsted.gov.uk/v1/file/50147384" target="<?php echo 'page'.mt_rand(); ?>">Ofsted</a></p>
        </div>
        <div class="col-sm-6">
          <p><a href="<?php echo $hardLink_supportingus; ?>">Support Us</a></p>
        </div>
      </div>
    </div>
    <div class="sport"><p><a href="https://sport.challoners.com/">Sports Website & Fixtures</a></p></div>
    <div class="vacancies"><p><a href="<?php echo $hardLink_vacancies; ?>">Work with us</a></p></div>
    <a href="https://www.astrahub.org/" target="<?php echo 'page'.mt_rand(); ?>"><img class="img-responsive" id="astraLogo" src="/img/astraTSHlogo.jpg" alt="Astra Teaching School Hub Buckinghamshire" /></a>
    
    <div class="socialMedia">
      <p>Social Media</p>
      <a href="https://twitter.com/ChallonersGS" target="<?php echo 'page'.mt_rand(); ?>"><img src="/img/smIcon_twitter.png" alt="Twitter" /></a>
      <a href="https://www.facebook.com/drchallonersgrammarschool" target="<?php echo 'page'.mt_rand(); ?>"><img src="/img/smIcon_facebook.png" alt="Facebook" /></a>
      <a href="https://www.instagram.com/challonersgs/" target="<?php echo 'page'.mt_rand(); ?>"><img src="/img/smIcon_instagram.png" alt="Instagram" /></a>
      <a href="https://www.linkedin.com/school/dr-challoner's-grammar-school/" target="<?php echo 'page'.mt_rand(); ?>"><img src="/img/smIcon_linkedin.png" alt="LinkedIn" /></a>
      <?php
        $tC = rand(0,7);
        if ($tC == 0) {
          $twitterFeed = 'astra_hub';
        } elseif ($tC == 1) {
          $twitterFeed = 'ChallonersHead';
        } elseif ($tC <= 3) {
          $twitterFeed = 'ChallonersGS';
        } else {
          $twitterFeed = 'DCGSSport';
        }
        echo '<a class="twitter-timeline" data-height="1200" data-chrome="noheader nofooter" href="https://twitter.com/'.$twitterFeed.'?ref_src=twsrc%5Etfw"></a>';
      ?>
    </div>
    
    <div class="twitterColumn">
		  
    </div>
  </div>
</div>
<?php include('footer.php'); ?>