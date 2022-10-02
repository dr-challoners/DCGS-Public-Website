  <ul class="nav nav-tabs" role="tablist">
    <?php
    if (isset($_GET['tab'])) {
      $tab = $_GET['tab'];
    } else {
      $tab = 'main';
    }
    $tabs =  '<li role="presentation"><a href="#main" aria-controls="main" role="tab" data-toggle="tab">Main Options</a></li>';
    $tabs .= '<li role="presentation"><a href="#content" aria-controls="content" role="tab" data-toggle="tab">Update DCGS</a></li>';
    $tabs .= '<li role="presentation"><a href="?tab=maths" aria-controls="maths">Mathematics</a></li>';
    $tabs = str_replace('><a href="#'.$tab,' class="active"><a href="#'.$tab,$tabs);
    echo $tabs;
    ?>
  </ul>
  <div class="tab-content">
    <?php
    if ($tab == 'main') {
      echo '<div role="tabpanel" class="tab-pane fade in active" id="main">';
    } else {
      echo '<div role="tabpanel" class="tab-pane fade" id="main">';
    }
    ?>
    <div class="row">
      <div class="col-xs-12">
        <p>Speak to Mr Burn if you require permission to access any of the following.</p>
      </div>
    </div>
    <div class="row buttonLine">
      <div class="col-xs-6 col-sm-4"><a class="btn btn-default btn-block" href="/" target="<?php echo mt_rand(); ?>"><i class="fas fa-shield"></i> Challoner's Website</a></div>
      <div class="col-xs-6 col-sm-4"><a class="btn btn-default btn-block" href="https://drive.google.com/drive/folders/0ByH41whuUvC_fnpLUVhXTGl6dUV4VWZyWWJCNlRQaGp5d0pDbE90QWlCSVJlVEg2ZURSZ0E?resourcekey=0-tFBggJxk8TJmvJQSXBrgmQ" target="<?php echo mt_rand(); ?>"><i class="fab fa-google-drive"></i> Website Content Folder</a></div>
      <div class="col-xs-6 col-sm-4"><a class="btn btn-default btn-block" href="https://drive.google.com/open?id=1n-oqN8rF98ZXqlH7A_eUx6K_5FgK2RUpiCx3aUMg3kM" target="<?php echo mt_rand(); ?>"><i class="fas fa-table"></i> Sections Master Spreadsheet</a></div>
      <div class="col-xs-6 col-sm-4"><a class="btn btn-default btn-block" href="https://sites.google.com/challoners.org/dcgs-publishing" target="<?php echo mt_rand(); ?>"><i class="fas fa-question-circle"></i> Publishing Guidance</a></div>
      <div class="col-xs-6 col-sm-4"><a class="btn btn-default btn-block" href="https://trello.com/b/rEUcWF5b/website" target="<?php echo mt_rand(); ?>"><i class="fab fa-trello"></i> Student Journalist Team</a></div>
      <div class="col-xs-6 col-sm-4"><a class="btn btn-default btn-block" href="https://analytics.google.com/analytics/web/?authuser=0#realtime/rt-overview/a92676121w137153019p141382362/" target="<?php echo mt_rand(); ?>"><i class="fas fa-chart-line"></i> Website Traffic Data</a></div>
    </div>
    
    
    <h3>Website Systems</h3>
    <div class="row options">
      <div class="col-xs-12 col-sm-3 col-md-2"><p>Front Page Notices:</p></div>
      <div class="col-xs-12 col-sm-9 col-md-10 btn-group" role="group" aria-label="...">
        <a class="btn btn-default" href="https://drive.google.com/open?id=1icLE9k67sw9gN9dcnZYsWt5QOnUxe7mTQGZk_2EFLZk" target="<?php echo mt_rand(); ?>">Edit</a>
        <a class="btn btn-default" href="https://drive.google.com/drive/folders/0ByH41whuUvC_fklEcU5ZakNocS1LVW9lTHNQaWNUYzV4Z29pbkFfWno3S0VYdnVaQXNZdlk?resourcekey=0-QHJySae_J-8D3pImIxR-Bw" target="<?php echo mt_rand(); ?>">Resources Folder</a>
        <div class="btn-group" role="group">
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Sync <span class="caret"></span></button>
          <ul class="dropdown-menu">
            <li><a href="/update" target="<?php echo mt_rand(); ?>">Live</a></li>
            <li><a href="/preview" target="<?php echo mt_rand(); ?>">Preview</a></li>
          </ul>
        </div>
        <a class="btn btn-default" href="https://docs.google.com/document/d/1FKmU4yO_jnwNrM3LM64afNebJPZ5KWFE_SiS5OYKt4I/" target="<?php echo mt_rand(); ?>">Help File</a>
      </div>
    </div>
    <div class="row options">
      <div class="col-xs-12 col-sm-3 col-md-2"><p>Highlight Adverts:</p></div>
      <div class="col-xs-12 col-sm-9 col-md-10 btn-group" role="group" aria-label="...">
        <a class="btn btn-default" href="https://docs.google.com/spreadsheets/d/1A2ZDoOM57fcVAADHgU9aUf1G9IAIGkz3wWXBiazYPoE/" target="<?php echo mt_rand(); ?>">Edit</a>
        <a class="btn btn-default" href="/update" target="<?php echo mt_rand(); ?>">Sync</a>
        <a class="btn btn-default" href="https://docs.google.com/document/d/1y-KKKgOD_q3Q9NaPusLM9hy1hne4oDpJNXyctdT8xDs/edit#" target="<?php echo mt_rand(); ?>">Help File</a>
      </div>
    </div>
    <div class="row options">
      <div class="col-xs-12 col-sm-3 col-md-2"><p>Sports Fixtures:</p></div>
      <div class="col-xs-12 col-sm-9 col-md-10 btn-group" role="group" aria-label="...">
        <a class="btn btn-default" href="https://docs.google.com/spreadsheets/d/1nDL3NXiwdO-wfbHcTLJmIvnZPL73BZeF7fYBj_heIyA/edit#gid=0" target="<?php echo mt_rand(); ?>">Edit</a>
        <a class="btn btn-default" href="/diary/update" target="<?php echo mt_rand(); ?>">Sync</a>
        <a class="btn btn-default" href="https://docs.google.com/document/d/1BWoJOevcLzb6papnBfiWx4UUvtHsLlxjyHqNv3J-gAQ/edit" target="<?php echo mt_rand(); ?>">Help File</a>
      </div>
    </div>
    <div class="row options">
      <div class="col-xs-12 col-sm-3 col-md-2"><p>House Competition:</p></div>
      <div class="col-xs-12 col-sm-9 col-md-10 btn-group" role="group" aria-label="...">
        <a class="btn btn-default" href="https://drive.google.com/drive/folders/0ByH41whuUvC_fkt2c0pLTGEyMWhOcHVEeVNtX1pmRjFsRjk2RVZBS2lZcU5DOFp5QlFVWmc?resourcekey=0-Cbetjb1UCTUSdJ_mTiW-nQ" target="<?php echo mt_rand(); ?>">Edit</a>
        <div class="btn-group" role="group">
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Sync <span class="caret"></span></button>
          <ul class="dropdown-menu">
            <li><a href="/c/enrichment/house-competition/current-positions/update" target="<?php echo mt_rand(); ?>">Current Positions</a></li>
            <li><a href="/c/enrichment/house-competition/about-the-houses/update" target="<?php echo mt_rand(); ?>">About the Houses</a></li>
            <li><a href="/c/enrichment/house-competition/roll-of-honour/update" target="<?php echo mt_rand(); ?>">Roll of Honour</a></li>
          </ul>
        </div>
        <a class="btn btn-default" href="https://docs.google.com/document/d/1IBPUFyvle3JRe62CfqOVhHKYYV2yxhM4as-P2GKmwpw/edit" target="<?php echo mt_rand(); ?>">Help File</a>
      </div>
    </div>
    <div class="row options">
      <div class="col-xs-12 col-sm-3 col-md-2"><p>Clubs and Societies:</p></div>
      <div class="col-xs-12 col-sm-9 col-md-10 btn-group" role="group" aria-label="...">
        <div class="btn-group" role="group">
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Edit Calendar <span class="caret"></span></button>
          <ul class="dropdown-menu">
            <li><a href="https://docs.google.com/spreadsheets/d/1ijdhkk8bvUYULZPZW8EuyN_kq34Q3AjleJjflY_EAZo/edit" target="<?php echo mt_rand(); ?>">Autumn</a></li>
            <li><a href="https://docs.google.com/spreadsheets/d/148yVcohmWBpNAhjpnSqsphqMIJ4MVo_GR_izk-jGdZg/edit" target="<?php echo mt_rand(); ?>">Spring</a></li>
            <li><a href="https://docs.google.com/spreadsheets/d/1jyOqFPjfAp9BmkGGu6i6YTn-NeHbmAjbwBk1uTDw6sw/edit" target="<?php echo mt_rand(); ?>">Summer</a></li>
          </ul>
        </div>
        <a class="btn btn-default" href="/sync?tab=content&section=Enrichment&sheet=1xMyo79efTDBV0GRx6fja4CcF-9JvW3L7pqMaEcPybNs&page=Clubs%20and%20Societies%20Calendar">Sync</a>
      </div>
    </div>
  </div> <!-- Ends the tab panel -->

