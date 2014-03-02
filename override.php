<?php

	$override = 0;

//Note that if multiple overrides are in the ACTIVE folder, closure will take priority, then snow, then adverts

if (file_exists("./override/ACTIVE/closure.txt")) {

	$message = file_get_contents("./override/ACTIVE/closure.txt", true);
	
	echo "<div class=\"override\" id=\"closure\">";
	echo Parsedown::instance()->parse($message);
	echo "</div>";
	
	$override = 1; //This lets the magazine know there's been an override, so it doesn't display a 'big' news story
	
	}
elseif (file_exists("./override/ACTIVE/snow_amber.txt")) {

	$message = file_get_contents("./override/ACTIVE/snow_amber.txt", true);
	
	echo "<div class=\"override\" id=\"snow_amber\">";
	echo Parsedown::instance()->parse($message);
	echo "</div>";
	
	$override = 1;
	
	}
//As long as there's either some text or a picture, the advert override will display:
elseif (file_exists("./override/ACTIVE/advert.txt") || file_exists("./override/ACTIVE/advert.jpg")) {

	if (file_exists("./override/ACTIVE/advert.txt")) { 
		$message = file_get_contents("./override/ACTIVE/advert.txt", true);
		}
	
	//Advert.jpg will sort itself out as a background image - if there isn't one, it just won't display
	
	if (file_exists("./override/ACTIVE/advert.css")) { //Option for a stylesheet, if you want to get technical!
		echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"screen and (min-device-width : 361px)\" href=\"/rebuild/override/ACTIVE/advert.css\"/>";
		}
	
	echo "<div class=\"override lrg\" id=\"advert\">"; //Note that adverts won't display on phones, as managing this just gets a bit too complicated...
	echo Parsedown::instance()->parse($message);
	echo "</div>";
	
	$override = 1;
	
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

