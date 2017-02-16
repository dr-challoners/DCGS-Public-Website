  <ul class="nav nav-tabs" role="tablist">
    <?php
    if (isset($_GET['tab'])) {
      $tab = $_GET['tab'];
    } else {
      $tab = 'main';
    }
    $tabs =  '<li role="presentation"><a href="#main" aria-controls="main" role="tab" data-toggle="tab">Main options</a></li>';
    $tabs .= '<li role="presentation"><a href="#sjt" aria-controls="sjt" role="tab" data-toggle="tab">SJT</a></li>';
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
    <div class="row buttonLine">
      <div class="col-xs-12 col-sm-5 col-md-4"><a class="btn btn-default btn-block" href="https://drive.google.com/drive/folders/0ByH41whuUvC_fnpLUVhXTGl6dUV4VWZyWWJCNlRQaGp5d0pDbE90QWlCSVJlVEg2ZURSZ0E" target="<?php echo mt_rand(); ?>"><i class="fa fa-folder-open fa-fw"></i> Main content folder on Drive</a></div>
      <div class="col-xs-12 col-sm-7 col-md-8"><p>Contact SBU if you need access.</p></div>
    </div>
    <div class="row buttonLine">
      <div class="col-xs-12 col-sm-5 col-md-4"><a class="btn btn-default btn-block" href="https://drive.google.com/open?id=1n-oqN8rF98ZXqlH7A_eUx6K_5FgK2RUpiCx3aUMg3kM" target="<?php echo mt_rand(); ?>"><i class="fa fa-table fa-fw"></i> Modify the master spreadsheet</a></div>
    </div>
    <div class="row buttonLine">
      <div class="col-xs-12 col-sm-5 col-md-4"><a class="btn btn-default btn-block" href="https://sites.google.com/a/challoners.org/dcgs-publishing/" target="<?php echo mt_rand(); ?>"><i class="fa fa-question-circle fa-fw"></i> Open the support wiki</a></div>
    </div>
    <div class="row buttonLine">
      <div class="col-xs-12 col-sm-5 col-md-4"><a class="btn btn-default btn-block" href="/" target="<?php echo mt_rand(); ?>"><i class="fa fa-shield fa-fw"></i> Go to the school website</a></div>
    </div>
    <h3>Website systems</h3>
    <div class="row options">
      <div class="col-xs-12 col-sm-3 col-md-2"><p>Intranet links:</p></div>
      <div class="col-xs-12 col-sm-9 col-md-10 btn-group" role="group" aria-label="...">
        <a class="btn btn-default" href="https://drive.google.com/drive/u/0/folders/0ByH41whuUvC_fi1QWkgyMloxM0w1eFdPVWhIa29NcEZ1Sk91UU85X0JGV2tkUzNYRXljWUE" target="<?php echo mt_rand(); ?>">Edit spreadsheets</a>
        <div class="btn-group" role="group">
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Force re-sync <span class="caret"></span></button>
          <ul class="dropdown-menu">
            <li><a href="/intranet/students/update" target="<?php echo mt_rand(); ?>">Students</a></li>
            <li><a href="/intranet/staff/update" target="<?php echo mt_rand(); ?>">Staff</a></li>
            <li><a href="/intranet/parents/update" target="<?php echo mt_rand(); ?>">Parents</a></li>
          </ul>
        </div>
        <a class="btn btn-default" href="https://sites.google.com/a/challoners.org/dcgs-publishing/managing-the-website/intranet-links" target="<?php echo mt_rand(); ?>">View help file</a>
      </div>
    </div>
    <div class="row options">
      <div class="col-xs-12 col-sm-3 col-md-2"><p>Front page override:</p></div>
      <div class="col-xs-12 col-sm-9 col-md-10 btn-group" role="group" aria-label="...">
        <a class="btn btn-default" href="https://drive.google.com/drive/folders/0ByH41whuUvC_fklEcU5ZakNocS1LVW9lTHNQaWNUYzV4Z29pbkFfWno3S0VYdnVaQXNZdlk" target="<?php echo mt_rand(); ?>">Open content folder</a>
        <a class="btn btn-default" href="/update" target="<?php echo mt_rand(); ?>">Force re-sync</a>
        <a class="btn btn-default" href="/preview" target="<?php echo mt_rand(); ?>">Preview overrides</a>
        <a class="btn btn-default" href="https://sites.google.com/a/challoners.org/dcgs-publishing/managing-the-website/override-messages" target="<?php echo mt_rand(); ?>">View help file</a>
      </div>
    </div>
    <div class="row options">
      <div class="col-xs-12 col-sm-3 col-md-2"><p>Sports fixtures:</p></div>
      <div class="col-xs-12 col-sm-9 col-md-10 btn-group" role="group" aria-label="...">
        <a class="btn btn-default" href="https://docs.google.com/spreadsheets/d/1nDL3NXiwdO-wfbHcTLJmIvnZPL73BZeF7fYBj_heIyA/edit#gid=0" target="<?php echo mt_rand(); ?>">Edit spreadsheet</a>
        <a class="btn btn-default" href="/diary/update" target="<?php echo mt_rand(); ?>">Force re-sync</a>
        <a class="btn btn-default" href="https://docs.google.com/document/d/1BWoJOevcLzb6papnBfiWx4UUvtHsLlxjyHqNv3J-gAQ/edit" target="<?php echo mt_rand(); ?>">View help file</a>
      </div>
    </div>
    <div class="row options">
      <div class="col-xs-12 col-sm-3 col-md-2"><p>House Competition:</p></div>
      <div class="col-xs-12 col-sm-9 col-md-10 btn-group" role="group" aria-label="...">
        <a class="btn btn-default" href="/c/community/house-competition/current-positions/update" target="<?php echo mt_rand(); ?>">Update website</a>
        <a class="btn btn-default" href="https://drive.google.com/drive/folders/0ByH41whuUvC_fkt2c0pLTGEyMWhOcHVEeVNtX1pmRjFsRjk2RVZBS2lZcU5DOFp5QlFVWmc" target="<?php echo mt_rand(); ?>">Edit content</a>
      </div>
    </div>
    <div class="row options">
      <div class="col-xs-12 col-sm-3 col-md-2"><p>Clubs and societies:</p></div>
      <div class="col-xs-12 col-sm-9 col-md-10 btn-group" role="group" aria-label="...">
        <a class="btn btn-default" href="/c/community/clubs-and-societies/what~1s-on-this-term/update" target="<?php echo mt_rand(); ?>">Update website</a>
        <div class="btn-group" role="group">
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Edit calendar <span class="caret"></span></button>
          <ul class="dropdown-menu">
            <li><a href="https://docs.google.com/spreadsheets/d/1cRJPvzWoKjVBeoyzgUrt1gq0qYqXFfRgl7STRBkW8KQ/edit#gid=0" target="<?php echo mt_rand(); ?>">Autumn</a></li>
            <li><a href="https://docs.google.com/spreadsheets/d/1mVNNX_V_3veJC6pAzQeZ6uC48xhO5zJukNMsZhEkEz4/edit#gid=0" target="<?php echo mt_rand(); ?>">Spring</a></li>
            <li><a href="https://docs.google.com/spreadsheets/d/1CGSyQHppyse_T2xXj3K9-8aKyoR6lzCRXAsDMM7mG9c/edit#gid=0" target="<?php echo mt_rand(); ?>">Summer</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <?php
  if ($tab == 'sjt') {
    echo '<div role="tabpanel" class="tab-pane fade in active" id="sjt">';
  } else {
    echo '<div role="tabpanel" class="tab-pane fade" id="sjt">';
  }
  ?>
  <div class="row buttonLine">
    <div class="col-xs-12 col-sm-5 col-md-4"><a class="btn btn-default btn-block" href="https://sites.google.com/a/challoners.org/dcgstv/bookings" target="<?php echo mt_rand(); ?>"><i class="fa fa-camera fa-fw"></i> Book camera and TV Studio time</a></div>
  </div>
  <hr />
  <div class="row buttonLine">
    <div class="col-xs-12 col-sm-5 col-md-4"><a class="btn btn-default btn-block" href="https://sites.google.com/a/challoners.org/dcgs-publishing/dcgs-style-guide" target="<?php echo mt_rand(); ?>"><i class="fa fa-pencil-square-o fa-fw"></i> Read the style guide</a></div>
    <div class="col-xs-12 col-sm-7 col-md-8"><p>You must know and follow this for all articles.</p></div>
  </div>
  <div class="row buttonLine">
    <div class="col-xs-12 col-sm-5 col-md-4"><a class="btn btn-default btn-block" href="https://sites.google.com/a/challoners.org/dcgs-publishing/managing-the-website/datatypes" target="<?php echo mt_rand(); ?>"><i class="fa fa-pencil-square-o fa-fw"></i> Read article formatting instructions</a></div>
  </div>
  <div class="row buttonLine">
    <div class="col-xs-12 col-sm-5 col-md-4"><a class="btn btn-default btn-block" href="https://docs.google.com/spreadsheets/d/1oDZmEOsifN8iQpEE_XI1QBNGiiIMNk-Cont-UsLyFwU/edit#gid=0" target="<?php echo mt_rand(); ?>"><i class="fa fa-table fa-fw"></i> View SJT contributor records</a></div>
    <div class="col-xs-12 col-sm-7 col-md-8"><p>Use the filter icon to change which students you can see.</p></div>
  </div>
  <!--<div class="row buttonLine">
    <div class="col-xs-12 col-sm-5 col-md-4"><a class="btn btn-default btn-block" href="https://statcounter.com/" target="<?php echo mt_rand(); ?>"><i class="fa fa-line-chart fa-fw"></i> Review website statistics</a></div>        <div class="col-xs-12 col-sm-7 col-md-8"><p>Speak to Mr Burn if you want access to this.</p></div>
  </div>-->
  <hr />
  <div class="row buttonLine">
    <div class="col-xs-12 col-sm-5 col-md-4"><a class="btn btn-default btn-block" href="https://trello.com/b/rEUcWF5b/website" target="<?php echo mt_rand(); ?>"><i class="fa fa-trello fa-fw"></i> Trello: Main SJT board</a></div>
  </div>
  <div class="row buttonLine">
    <div class="col-xs-12 col-sm-5 col-md-4"><a class="btn btn-default btn-block" href="https://trello.com/b/GHzwVAhu/editorial" target="<?php echo mt_rand(); ?>"><i class="fa fa-trello fa-fw"></i> Trello: SJT editors' board</a></div>
  </div>
</div>