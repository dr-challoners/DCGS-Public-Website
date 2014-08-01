<?php
$youtube_height = round($pagewidth*0.6); //Allows YouTube videos to be resized sensibly
include('parsedown.php'); //Converts markdown text to HTML - see parsedown.org

$parts = scandir($dir, 1); //$dir is the folder that contains all the parts of the page - this must be found and passed on in the page that ParseBox is being used within
$parts = array_reverse($parts); // Puts the array in ascending order first
	
	foreach ($parts as $part) {
		$content = file_get_contents($dir."/".$part, true);
		$file = explode("~",$part);
		if (isset($file[2])) { // This indicates that there's a special instruction, like YOUTUBE or ANDROID
			$type = explode(".",$file[2]);
			switch (strtoupper($type[0])) { // There is no default: if it's not a recognised type, ignore it
				case "YOUTUBE": // Users just put the video URL into the text file, then this will find the video ID and write embed code
					$id_place = strpos($content,"v=");
					$id_place = $id_place+2;
					$id = substr($content,$id_place);
					
					echo "<div class=\"dropdown\" name=\"youtube\" id=\"$id\">";
						echo "<p class=\"linkout\"><img src=\"/".$codepath."icons/YouTube video.png\" alt=\"YouTube video: \" class=\"icon\" />";
						echo "<a href=\"javascript:boxOpen('".$id."','youtube')\">$file[1]</a></p>";
						echo "<iframe class=\"youtube\"";
						echo " src=\"//www.youtube-nocookie.com/embed/$id?rel=0\" allowfullscreen></iframe>";
					echo "</div>";
				break;
        case "FORM": // Users just put the form URL into the text file, then this will find the form ID and write embed code
          $id_place = strpos($content,"d/");
					$id_place = $id_place+2;
					$id = substr($content,$id_place);
          $id = explode("/",$id);
          $id = $id[0];
        
          echo "<div class=\"dropdown\" name=\"gform\" id=\"$id\">";
						echo "<p class=\"linkout\"><img src=\"/".$codepath."icons/Google form.png\" alt=\"Google form: \" class=\"icon\" />";
						echo "<a href=\"javascript:boxOpen('".$id."','gform')\">$file[1]</a></p>";
            echo "<iframe class=\"gform\"";
					  echo " src=\"https://docs.google.com/forms/d/$id/viewform?embedded=true\">Loading...</iframe>";
          echo "</div>";
        break;
				case "SOUNDCLOUD":
					// Users should go to 'Share' on the SoundCloud file, then 'Embed' and copy the embed code as it is into the text file - this will do the rest
					$id_place = strpos($content,"tracks/");
					$id_place = $id_place+7;
					$id = substr($content,$id_place);
					$id = explode("&",$id);
					$id = $id[0];
        
          echo "<div class=\"dropdown\" name=\"soundcloud\" id=\"$id\">";
						echo "<p class=\"linkout\"><img src=\"/".$codepath."icons/SoundCloud track.png\" alt=\"SoundCloud track: \" class=\"icon\" />";
						echo "<a href=\"javascript:boxOpen('".$id."','soundcloud')\">$file[1]</a></p>";
					  echo "<iframe ";
					  echo "width=\"100%\" height=\"166\" ";
					  echo "scrolling=\"no\" frameborder=\"no\" ";
					  if (isset($ConfigColour)) { $colour = strtolower(str_replace("#","",$ConfigColour)); } else { $colour = "666666"; }					
					  echo "src=\"https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/$id&amp;color=$colour&amp;auto_play=false&amp;hide_related=false&amp;show_artwork=false\"></iframe>";
          echo "</div>";
				break;
				case "LINK": // Creates prominent external link (inline links are still possible, of course)
					// First has an attempt at detecting links to certain places, to give them specific icons
					if (strpos($content,"apple.com") !== false && strpos($content,"app") !== false) { // Apple store
						$icon = "iTunes app";
						}
					elseif (strpos($content,"play.google") !== false && strpos($content,"app") !== false) { // Android apps on the Google Play store
						$icon = "Android app";
						}
					elseif (strpos($content,"wolframalpha.com") !== false) { // Any form of Wolfram|Alpha link, but include specific instructions in the tutorials for using Clip 'n Share
						$icon = "Wolfram|Alpha";
						}
          elseif (strpos($content,"docs.google") !== false && strpos($content,"presentation") !== false) {
						$icon = "Google slides";
						}
          elseif (strpos($content,"docs.google") !== false && strpos($content,"spreadsheets") !== false) {
						$icon = "Google sheets";
						}
          elseif (strpos($content,"docs.google") !== false && strpos($content,"document") !== false) {
						$icon = "Google docs";
						}
					else { $icon = "External link"; }
					echo "<p class=\"linkout\"><img src=\"/".$codepath."icons/".str_replace("|","",$icon).".png\" alt=\"$icon: \" class=\"icon\" />";
					echo "<a target=\"_BLANK\" href=\"$content\">$file[1]</a></p>";
				break;
				case "MAIL": // Parse e-mail addresses, including some basic baffling for robots. These files need to be saved with ANSI encoding.
				case "E-MAIL":
				case "EMAIL":
					$address = ""; $i = 0;
					for ($i = 0; $i < strlen($content); $i++) { $address .= '&#'.ord($content[$i]).';'; }
					echo "<p class=\"linkout\"><img src=\"/".$codepath."icons/E-mail.png\" alt=\"E-mail: \" class=\"icon\" />";
					echo "<a href=\"mailto:$address\">$file[1]</a></p>";
				break;
				case "LEFT": // Images to left-align
					echo "<a class=\"imagelink\" href=\"/".$rootpath.$dir."/$part\" data-lightbox=\"gallery\" data-title=\"$file[1]\">"; // This is to include ALL images on the page as part of a Lightbox set
						echo "<img class=\"lft\" alt=\"$file[1]\" src=\"/".$rootpath.$dir."/$part\" \>";
					echo "</a>";
				break;
				case "RIGHT": // Images to right-align
					echo "<a class=\"imagelink\" href=\"/".$rootpath.$dir."/$part\" data-lightbox=\"gallery\" data-title=\"$file[1]\">";
						echo "<img class=\"rgt\" alt=\"$file[1]\" src=\"/".$rootpath.$dir."/$part\" \>";
					echo "</a>";
				break;
				case "WIDE": // Images that fit across the full width of the content column
					echo "<a class=\"imagelink\" href=\"/".$rootpath.$dir."/$part\" data-lightbox=\"gallery\" data-title=\"$file[1]\">";
						echo "<img class=\"wde\" alt=\"$file[1]\" src=\"/".$rootpath.$dir."/$part\" \>";
					echo "</a>";
				break;
				case "MATHS":
				case "MATH":
					echo "<p>";
					echo $content;
					echo "</p>";
				break;
				case "GALLERY": // So that galleries can be given names
          echo "<h2>$file[1]</h2>";
					include ('gallery.php');
				break;
				}
        if (file_exists($codepath."custom_named.php")) {
          include("custom_named.php"); // Additional custom modules for specific websites - for files that have a name as well as an instruction. These modules must begin 'switch (strtoupper($type[0]))'.
          }
			}
		elseif (isset($file[1])) { // All correctly named files should start with NUMBER~ so if there's no ~ at all, just ignore that file (it's broken, not needed or it's hidden)
      $checkfile = strtoupper(basename($file[1],".txt"));
			if ($checkfile == "GALLERY") {
				include ('gallery.php');
				}
			elseif ($checkfile == "MATHS" || $checkfile == "MATH") { // So that blocks of maths don't have to be given titles (although they'll still need an ordering number, of course)
				echo "<p>";
				echo $content;
				echo "</p>";
				}
			elseif ($checkfile == "SOUNDCLOUD") { // So that SoundCloud files don't need titles
				$id_place = strpos($content,"tracks/");
				$id_place = $id_place+7;
				$id = substr($content,$id_place);
				$id = explode("&",$id);
				$id = $id[0];
				echo "<iframe ";
				echo "width=\"100%\" height=\"166\" ";
				echo "scrolling=\"no\" frameborder=\"no\" ";
				if (isset($ConfigColour)) { $colour = strtolower(str_replace("#","",$ConfigColour)); } else { $colour = "666666"; }					
				echo "src=\"https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/$id&amp;color=$colour&amp;auto_play=false&amp;hide_related=false&amp;show_artwork=false\"></iframe>";
				}
      elseif ($checkfile == "YOUTUBE") { // YouTube files without names don't drop down: they're placed directly into the page
				$id_place = strpos($content,"v=");
				$id_place = $id_place+2;
				$id = substr($content,$id_place);
					
			  echo "<iframe class=\"youtube\"";
				echo " src=\"//www.youtube-nocookie.com/embed/$id?rel=0\" allowfullscreen></iframe>";
        }
      elseif ($checkfile == "FORM") {
				$id_place = strpos($content,"d/");
				$id_place = $id_place+2;
				$id = substr($content,$id_place);
        $id = explode("/",$id);
        $id = $id[0];
        
        echo "<iframe class=\"gform\"";
			  echo " src=\"https://docs.google.com/forms/d/$id/viewform?embedded=true\">Loading...</iframe>";
        }
      elseif (file_exists($codepath."custom_unnamed.php") && strpos($file[1],".") == false) { 
        include("custom_unnamed.php"); // Additional custom modules for specific websites - for files that have a name as well as an instruction. These modules must begin as an 'if($checkfile ==' statement.
        }
			else {
				$type = explode(".",$file[1]);
				$filetype = strtolower($type[1]);
				switch ($filetype) {
					case "jpg":
					case "jpeg":
					case "png":
					case "gif":
						echo "<a class=\"imagelink\" href=\"/".$rootpath.$dir."/$part\" data-lightbox=\"gallery\" data-title=\"$type[0]\">";
							echo "<img class=\"mid\" alt=\"$type[0]\" src=\"/".$rootpath.$dir."/$part\" \>";
						echo "</a>";
					break;
					case "txt":
						echo Parsedown::instance()->parse($content);
					break;
					case "xls":
					case "xlsx":
					case "ods": // Excel or OpenOffice spreadsheets
						echo "<p class=\"linkout\"><img src=\"/".$codepath."icons/Spreadsheet.png\" alt=\"Spreadsheet: \" class=\"icon\" />";
						echo "<a href=\"/".$rootpath.$dir."/$part\">$type[0]</a></p>";
					break;
					case "doc":
					case "docx":
					case "odt": // Word or OpenOffice document
						echo "<p class=\"linkout\"><img src=\"/".$codepath."icons/Document.png\" alt=\"Document: \" class=\"icon\" />";
						echo "<a href=\"/".$rootpath.$dir."/$part\">$type[0]</a></p>";
					break;
					case "ppt":
					case "pptx":
					case "odp": // PowerPoint or OpenOffice presentation
						echo "<p class=\"linkout\"><img src=\"/".$codepath."icons/Presentation.png\" alt=\"Presentation: \" class=\"icon\" />";
						echo "<a href=\"/".$rootpath.$dir."/$part\">$type[0]</a></p>";
					break;
					case "pdf":
						echo "<p class=\"linkout\"><img src=\"/".$codepath."icons/PDF.png\" alt=\"PDF document: \" class=\"icon\" />";
						echo "<a href=\"/".$rootpath.$dir."/$part\" target=\"_BLANK\">$type[0]</a></p>"; // This will open in a new tab
					break;
					case "php":
					case "html":
					case "htm":
					case "js":
						include($dir."/".$part);
					break;
					case "css":
						echo "<style>";
						include($dir."/".$part);
						echo "</style>";
					break;
					}
				}
			}
		}
		
?>