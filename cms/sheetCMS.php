<?php // To use sheetCMS, this file must be included on the page

  // CONFIG - SET THIS FOR YOUR SITE ------------------ //

    $dataSrc    = 'data/content';
    $imgsSrc    = 'data/images';
    $sheetCMS   = 'cms';

    $mainSheet  = '1n-oqN8rF98ZXqlH7A_eUx6K_5FgK2RUpiCx3aUMg3kM';
    $contentURL = '/c/[SECTION]/[SHEET]/[PAGE]';
    $colour     = '#2358A3';
    $acronyms = array('dcgs','dchs','slt','sslt','sjt','pe','rs','pshe','uksa','ucas');

  // -------------------------------------------------- //

  include('functions_global.php');
  include('functions_CMS.php');
  echo '<link rel="stylesheet" type="text/css" media="screen" href="/'.$sheetCMS.'/sheetCMS.css" />';
  date_default_timezone_set("Europe/London");
  
  include('modules/parsedown/parsedown.php');

  if (file_exists($dataSrc.'/mainData.json')) {
    $mainData = file_get_contents($dataSrc.'/mainData.json');
    $mainData = json_decode($mainData, true);
  } elseif (file_exists('../'.$dataSrc.'/mainData.json')) {
    $mainData = file_get_contents('../'.$dataSrc.'/mainData.json');
    $mainData = json_decode($mainData, true);
  }

?>

  <!-- Pop-up images in galleries -->
  <script src="/<?php echo $sheetCMS; ?>/modules/lightbox/jquery-1.11.0.min.js"></script>
  <script src="/<?php echo $sheetCMS; ?>/modules/lightbox/lightbox.min.js"></script>
  <link rel="stylesheet" type="text/css" media="screen" href="/<?php echo $sheetCMS; ?>/modules/lightbox/lightbox.css"/>

  <!-- Open and close menus and widgets -->
  <script type="text/javascript">
    function simpleOpenClose(divID,divName) {
      if(document.getElementById(divID).className.match(/(?:^|\s)open(?!\S)/)) { var open = 1; } // Check to see if the specific item is currently open
      var inputs = document.getElementsByName(divName);
      for(var i = 0; i < inputs.length; i++) { // Close every box of the same type
        inputs[i].className = document.getElementById(divID).className.replace( /(?:^|\s)open(?!\S)/g , '' );
      }
      if(open != 1) { // Only open the selected item if it was originally closed
        document.getElementById(divID).className += " open";
      }
		}
  </script>