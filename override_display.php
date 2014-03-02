<?php

$override = 0;
	
$status = file_get_contents("controls/override_status.txt", true);

if ($status == "none" && $_GET['override'] == "") { $status = ""; } //If there's no status being given, stop the override from happening
elseif ($status == "none" && $_GET['override'] != "") { $status = $_GET['override']; } //If there's no formal override, check for a test override

if ($status != "") { //If any form of status has been set, display the override

	if (file_exists("content_plain/override/".$status.".css")) { //Option for a stylesheet, if you want to get technical!
		echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"screen and (min-device-width : 361px)\" href=\"content_plain/override/".$status.".css\"/>";
		}

	echo "<div class=\"override\" id=\"".$status."\""; //There are built-in styles for some overrides, identified by the corresponding status. Built-in statuses are: closure, snow_amber
	
		if (file_exists("content_plain/override/".$status.".jpg")) { //Checks for pngs or jpgs matching the status: if one exists, it sets it as a background picture
			echo " style=\"background-image: url(content_plain/override/".$status.".jpg);\"";
			}
		elseif (file_exists("content_plain/override/".$status.".jpg")) { //Checks for pngs or jpgs matching the status: if one exists, it sets it as a background picture
			echo " style=\"background-image: url(content_plain/override/".$status.".jpg);\"";
			}
	
		echo ">";
		
		if (file_exists("content_plain/override/".$status.".txt")) { //If you want a message (which generally you will unless a picture contains the message), make sure the filename matches the status
			$message = file_get_contents("content_plain/override/".$status.".txt", true);
			echo Parsedown::instance()->parse($message);
			}
	
	echo "</div>";
	
	$override = 1; //This lets the magazine know there's been an override, so it doesn't display a 'big' news story
	
	}
	
else { //Easter egg time! ?>

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

<? } ?>

