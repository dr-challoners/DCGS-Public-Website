<?php include('header.php'); ?>
<div class="row">
  <div class="col-sm-frontLarge">
    <?php
      include('indexVTU.php');
      include('indexOverride.php');
      include('indexNews.php');
    ?>
  </div>
  <div class="col-sm-frontSmall hidden-xs">
    <div class="row intranetButtons">
      <div class="col-sm-5">
        <p>Intranet</p>
      </div>
      <div class="col-sm-7">
        <a href="http://docs.challoners.org/"   id="Drive"    ></a>
        <a href="http://mail.challoners.org/"   id="Gmail"    ></a>
        <a href="https://classroom.google.com/" id="Classroom"></a>
      </div>
    </div>
    <div class="row intranetAreas">
      <div class="col-sm-4">
        <p><a href="/intranet/students">Students</a></p>
      </div>
      <div class="col-sm-4">
        <p><a href="/intranet/staff">Staff</a></p>
      </div>
      <div class="col-sm-4">
        <p><a href="/intranet/parents">Parents</a></p>
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
          <p><a href="<?php echo $hardLink_schoolshop; ?>">School shop</a></p>
        </div>
        <div class="col-sm-6">
          <p><a href="<?php echo $hardLink_information; ?>">Information</a></p>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6">
          <p><a href="<?php echo $hardLink_vacancies; ?>">Vacancies</a></p>
        </div>
        <div class="col-sm-6">
          <p><a href="<?php echo $hardLink_supportingus; ?>">Support us</a></p>
        </div>
      </div>
    </div>
    <a href="#astraLA_tab" aria-controls="astraLA_tab" role="button" class="btn btn-Astra btn-block" data-toggle="tab">
      <img src="/img/astraLA_logoR.png" />
      <img src="/img/astraLA_logoL.png" alt="Astra Learning Alliance" />
    </a>
    <div class="tab-content">
      <div role="tabpanel" class="tab-pane active">
        <div class="twitterColumn" id="sports_team">
          <a href="https://twitter.com/DCGSSport" target="<?php echo 'page'.mt_rand(); ?>">DCGS Sport <span class="pull-right"><i class="fa fa-twitter fa-lg"></i></span></a>
          <a class="twitter-timeline" href="https://twitter.com/DCGSSport" data-chrome="noborders noheader nofooter" data-widget-id="430597618125664256">Tweets by @DCGSSport</a>
        </div>
      </div>
      <div role="tabpanel" class="tab-pane fade" id="astraLA_tab">
        <p>DCGS is the lead school of the Astra Learning Alliance, providing outstanding opportunities for all staff through training, support and action research across a range of secondary and primary schools in Buckinghamshire.</p>
        <p class="astraURL"><a href="http://www.astra-alliance.com/">www.astra-alliance.com</a></p>
        <div class="twitterColumn" id="astraLA">
          <!-- <a href="https://twitter.com/AstraAlliance" target="<?php echo 'page'.mt_rand(); ?>">Astra Learning Alliance <span class="pull-right"><i class="fa fa-twitter fa-lg"></i></span></a> -->
	        <a class="twitter-timeline"  href="https://twitter.com/AstraAlliance" data-chrome="noborders noheader nofooter" data-widget-id="533279910580604930">Tweets by @AstraAlliance</a>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include('footer.php'); ?>