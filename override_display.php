<style>
  div.override#travel { 
    padding-bottom: 4px; 
    border-color: #165d13; 
    background-image: none;
  }
  div.override#travel h1 { 
    background-image: url(../content_system/override/travel_icon.png); 
    color: #ffffff; 
    background-color: #165d13; 
  }
</style>

<?php include_once('parsing/Parsedown.php');

if (isset($_GET['override'])) { $status = array($_GET['override']); } else { $status = array(); }

$override = 0;
	
if (file_exists("content_system/override/00~STATUS.txt")) { // First check to see if there's any file to declare overrides - if there isn't, then there shan't be an override
  
  $status_file = file('content_system/override/00~STATUS.txt');
  // Extract the variables from the status file and tidy them up for easier processing
  $overrides = explode(':',$status_file[0]);  $bcolour = explode(':',$status_file[1]);  $tcolour = explode(':',$status_file[2]);
  $overrides = trim($overrides[1]);           $bcolour = trim($bcolour[1]);             $tcolour = trim($tcolour[1]);
  $overrides = strtolower($overrides); // Compensates for auto-capitalisation when editing the override_status file from a mobile device
  $overrides = explode(',',$overrides);
  
  foreach ($overrides as $entry) {
    $status[] = $entry;
  }

if (count($status) > 0) { //If any form of status has been set, display the override
  foreach ($status as $entry) {
    $entry = trim($entry);
    if (substr($entry,0,5) == 'snow_') { $type = 'snow'; } else { $type = $entry; } // This is to allow multiple different snow messages saved in the override folder (although all snow messages will be styled in the same way)
    if (file_exists('content_system/override/'.$entry.'.txt')) {
      
      // First sort out specific styles for the override. If there are none, default to the school crest and the blue
      unset($icon,$bkgd,$height,$bcol,$tcol);
      
      if (file_exists('content_system/override/'.$type.'_icon.png')) { // The icon goes next to the H1 header
        $icon = 'url(../content_system/override/'.$type.'_icon.png);';
      } else { $icon = 'url(../content_system/override/message_icon.png);'; }
      
      if (file_exists("content_system/override/".$type."_bkgd.jpg")) {
        $bkgd = 'url(../content_system/override/'.$type.'_bkgd.jpg);';
        list($width,$height) = getimagesize('content_system/override/'.$type.'_bkgd.jpg');
        $height = $height+4;
        $height = $height.'px;';
      } elseif (file_exists('content_system/override/'.$type.'_bkgd.png')) {
        $bkgd = 'url(../content_system/override/'.$type.'_bkgd.png);';
        list($width,$height) = getimagesize('content_system/override/'.$type.'_bkgd.png');
        $height = $height+4;
        $height = $height.'px;';
      } else {
        $bkgd = 'none;';
        $height = '4px;';
      }
      
      if ($type == 'alert') {
        $bcol = '#2b2b2b;';  $tcol = '#ffffff;';
      } elseif ($type == 'snow') {
        $bcol = '#bce4f3;';  $tcol = '#0d536d;';
      } elseif ($type == 'message') {
        $bcol = '#2F5397;';  $tcol = '#ffffff;';
      } elseif ($type == 'travel') {
        $bcol = '#165d13;';  $tcol = '#ffffff;';
      }
        
      if (!isset($bcol) && $bcolour != '') {
        $bcol = $bcolour.';';
      } elseif (!isset($bcol)) {
        $bcol = '#2F5397;';
      }
      
      if (!isset($tcol) && $tcolour != '') {
        $tcol = $tcolour.';';
      } elseif (!isset($tcol)) {
        $tcol = '#ffffff;';
      }
      
      echo '<style>';
      echo 'div.override#'.$type.' { padding-bottom: '.$height.' border-color: '.$bcol.' background-image: '.$bkgd.' }';
      echo 'div.override#'.$type.' h1 { background-image: '.$icon.' color: '.$tcol.' background-color: '.$bcol.' }';
      echo '</style>';
      
      $message = file_get_contents("content_system/override/".$entry.".txt", true);
      
      if (file_exists("content_system/override/".$type."_cover.png")) {
        echo '<img class="override lrg" alt="'.$message.'" src="content_system/override/'.$type.'_cover.png" />';
        $only = ' sml';
      } elseif (file_exists("content_system/override/".$type."_cover.jpg")) { // Need to write some better image extension recognition when we redo this properly
        echo '<img class="override lrg" alt="'.$message.'" src="content_system/override/'.$type.'_cover.jpg" />';
        $only = ' sml';
      } else { $only = ''; }
      echo '<div class="override'.$only.'" id="'.$type.'">';
			echo Parsedown::instance()->parse($message);
      echo '</div>';
	
	  $override = 1; // This lets the magazine know there's been an override, so it doesn't display a 'big' news story
    }
	}
}
else { // Easter egg time! ?>

<!--

88888888888888888888888888888888888888888888888888888888888                                                             
88::::::::::::::::::::::::::=8+::::::::::::::::::::::::::88                                                             
88~:::::::::::::::::::::::::=8+::::::::::::::::::::::::::88                                                             
88::::::::::::::::::::::::::=8+::::::::::::::::::::::::::88                                                             
88::::::::::::::::::::::::::=8+::::::::::::::::::::::::::88                                                             
88::::::::::::::::::::::::::=8+::::::::::::::::::::::::::88                                                             
88::::::::::::~:~~::::::::::=8+:::~~::::::~:~::::::~~::::88                                                             
88~8OZ~8:8~?88=:888:$~O~~O8Z=8+:I8888O~::88888+::I88888::88                                                             
88:888?I8I=888~~D888DZ~$D88O=8+:888888=~7888888::888888Z:88                                                             
O8:::$88~O88::::::~8~78+8:::=8+:888888:::888888::888888~:8Z                                                             
78::::::::::::::::::::::::::=8+:~O8D8:::::888Z::::~888:::8?                                                             
~8::::::::::::~:::::::::::::=8+::::::::::::::::::::::~::~8~                                                             
=8~::::::::::8888:::::::::::=8+::::::::::::::::::::::::::8:                                                             
:8I:::::::~:888888+~::::::::=8+:::::::::::::::::::::::::~O,                                                             
 88:::::::$888888888~:::::::=8+:::::I888::::::Z88Z~:::::7D                                                              
 8D:::::~88888888888D8::::::=8+:::~8D888D~::~888888~::::88                                                              
 78::::78888887~8D888888::::=8+::::888888O~::888888~:::~8+                                                              
 ~8:::D888888~:::~8888888~::=8+::::I8888O::::D88888:::::8~                                                              
  8O?8888888:::::::Z8888888~=8+::::::~~::::::~:~:::::::Z8                                                               
  Z888888D~:::::::::~D88888888+::::::::::::::::::::::::88                                                               
  ~888888::::::::::::::8888888+::::::::::???????????=::8~               MMMMMMN8NM         IMMMMMMMMM       :NMMMMM     
   D888+::::::::::::::::?88888+:::::::::::::MMMD~:~:MMMMD            NMNM      OMM       MMM8      NM      MM    8M     
   :88::::~8:::~::::~:8~::8888+:::::::::O888MMM~::::::8MMM          MMM          M     IMM:         M     MM      N     
    88::::~O888Z:O88888~::::88+::::::::I8888MMM::::::Z8 8MMM       MMN           8    7MM                MM       I     
    ~D~::::~888~8OZ888?:::::=8+::::::::=8888MMM::::::8~ ,MMM      MMM                 MM                 MM             
    ,$8:::::::~8Z8O7::::::::=8+:::::::::+888MMM:::::88    MMM    =MM                 MMM                 MMM            
      O8:::::::::::~::::::::=8+:::::::::::::OMM::::O8     MMM    MMM                 MM+                 8MMN           
      :88:::::::::::::::::::=8+:::::::::::::OMM:::?D,     IMM+   MMM                DMM                   MMMM          
       ~8?::::::::::::::::::=8+:::::::::::::OMM::?8~       MMM   MMM                MMM                    MMMMM        
        :8I:::::::::::::::::=8+:::::::::::::OMM:?8~        MMM  :MMN                MMM                     +MMMM       
         ~8O::::::::::::::::=8+:::::::::::::OMM+8~        ,MMM   MMM                MMM                       MMMM,     
          :OO:::::::::::::::=8+:::::::::::::OMM8,         8MM:   MMM                DMM+       8MMMMMMMM       $MMM     
            DD~:::::::::::::=8+:::::::::::::DMM,          MMM    MMM                 MMM           MMM          $MM     
             $8~::::::::::::=8+::::::::::::~MMM           MMM     MMM                MMM:          MMM           MM+    
              ~8Z:::::::::::=8+:::::::::::$8MMM          MMM      MMM+                MMM          MM8           MM     
                88::::::::::=8+::::::::::88 MMM         MMM,       MMM?          N    ,MMM~        MMI           MM     
                 ~8O::::::::=8+::::::::OD~  MMM        MMM          MMMM        M       MMMM       MM?   M      MM,     
                   88?::::::=8+::::::+8O    MMMMMMNNMMMM,             MMMMMMMMMMM         MMMMMMMMM      MMM  NMM,      
                    ~8O~::::=8+:::::OD~                                                                                 
                      ~88~::=8+::~88:                                                                                   
                        +88:=8+:88=                                                                                     
                          ~D888D=                                                                                       
                            ~8~                                                                                         

-->

<?php }} ?>

