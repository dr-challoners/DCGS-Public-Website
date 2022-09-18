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
          <p><a href="<?php echo $hardLink_prospectus; ?>">Prospectus</a></p>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6">
          <p><a href="<?php echo $hardLink_alumni; ?>">Alumni</a></p>
        </div>
        <div class="col-sm-6">
          <p><a href="<?php echo $hardLink_sixthform; ?>">Sixth Form</a></p>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6">
          <p><a href="<?php echo $hardLink_vacancies; ?>">Vacancies</a></p>
        </div>
        <div class="col-sm-6">
          <p><a href="<?php echo $hardLink_supportingus; ?>">Support Us</a></p>
        </div>
      </div>
    </div>
    <a href="https://files.ofsted.gov.uk/v1/file/50147384" target="<?php echo 'page'.mt_rand(); ?>"><img class="img-responsive" id="ofstedLogo" src="/img/ofsted-outstanding.jpg" alt="Ofsted Outstanding Provider" /></a>
    <a href="https://www.astrahub.org/" target="<?php echo 'page'.mt_rand(); ?>"><img class="img-responsive" id="astraLogo" src="/img/astraTSHlogo.jpg" alt="Astra Teaching School Hub Buckinghamshire" /></a>
    <div class="twitterColumn" id="sports_team">
      <a href="https://sport.challoners.com/">
        <h3>DCGS Sport</h3>
        <p>sport.challoners.com</p>
      </a>
		  <a class="twitter-timeline" data-height="1560" data-chrome="noheader nofooter" data-link-color="#9B0B0B" href="https://twitter.com/DCGSSport?ref_src=twsrc%5Etfw">Tweets by DCGSSport</a>
    </div>
  </div>
</div>
<?php include('footer.php'); ?>