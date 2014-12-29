<?php

// $dir is the folder that contains all the parts of the page - this must be passed on by the page that ParseBox is being used in
if (isset($dir) && !isset($parts)) { 
  $parts = scandir($dir, 1);
  $parts = array_reverse($parts); // Puts the array in ascending order first
  }
// You'll note from the above that it's also possible to manually set $parts to pick specific files and a specific order for the files - $div must still be set

if (!isset($parsediv)) { echo '<div class="parsebox">'; } // The 'if' here means you can put the parsebox div in manually earlier if you wish to add other content above or below - just set $parsediv to have a value.
	
	foreach ($parts as $part) {
    unset($filename); // Clears previous filename, in case this part doesn't have one
		$file = explode("~",$part);
    if (isset($file[1])) { // All correctly named files should start with NUMBER~ so if there's no ~ at all, just ignore that file (it's broken, not needed or it's hidden)
    if (isset($file[2])) { // This indicates that there's definitely a special instruction, like GALLERY or CUSTOM
      $filename = $file[1];
      }
    $type = array_pop($file);
    $type = explode(".",$type);
    $filevalue = $type[0];
    if (isset($type[1])) { $filetype = $type[1]; }
    $filevalue = strtolower($filevalue);
    // These three values are what you need - filename is optional
    $filedir   = $dir.'/'.$part;
    $filevalue = explode("=",$filevalue);
    if (isset($filetype)) { $filetype  = strtolower($filetype); }
      
    // Before we start, check to see if it's a SHARED file, in which case re-route to there
    if ($filevalue[0] == "shared" && isset($filename)) { // Shared files need to have the same title in their filename, so they can be matched - as such the ~SHARED pointer file *must* have a title
      $shared = scandir($sharedpath,1);
      foreach ($shared as $file) { 
        if (strpos($file,$filename) !== false) {
          // Now replace everything for parsing
          // See above for what's happening - note that because shared files aren't numbered, all the elements are one step earlier in the array
          $filedir = $sharedpath.$file;
          $newfile = explode('~',$file);
          if (isset($newfile[1])) { // There's a special instruction
            $filename = $newfile[0];
            $file = explode('.',strtolower($newfile[1]));
            $filetype = $file[1];
            $filevalue = explode("=",$filevalue);
          }
          else {
            $file = explode('.',$newfile[0]);
            $filename  = $file[0];
            $filetype  = strtolower($file[1]);
            $filevalue = array('');
          }
          break;
        }
      }
    }
    
    switch($filevalue[0]) {
      
        case "left": // Images to left-align
        case "right": // Images to right-align
        case "wide": // Images that fit across the full width of the content column
          $filevalue = $filevalue[0];
          include('parsebox_image.php');
				break;
      
        case "table":
          if (isset($filename)) { echo "<h2>$filename</h2>"; }
          $table = file($filedir);
          echo "<table>";
            $t = 0;
            foreach ($table as $row) {
              // Turn blank lines in the CSV file into a border between rows: turn double blank lines into a second table
              if (trim(trim($row),',') == '') {
                if (!isset($linebreak)) { $linebreak = 1; }
                else { $t = 0; unset($linebreak); echo "</table>\n<table>"; }
                } 
              else {
                // Because the original file is comma-delimited, we need to stop commas within cells being read as cell breaks
                // If there are commas within a cell, the cell will be wrapped in quotes. Need to identify these cells and lift out their data - but also avoid cells that have quotes within them.
                $row = trim($row).',';
                preg_match_all('/(?<!")"(.+?)",/',$row,$escapes);
                $escapes = $escapes[0];
                $e = 0; foreach ($escapes as $escape) {
                  $escape = rtrim($escape,',');
                  $row = str_replace($escape,'~E'.$e.'~',$row);
                  $e++;
                  }
                // Now that the commas we want to keep are preserved, the others can be converted into a more unique string for later breaking apart.
                $row = str_replace(',','~BR~',$row);
                $e = 0; foreach ($escapes as $escape) {
                  $escape = trim(rtrim($escape,','),'"');
                  $row = str_replace('~E'.$e.'~',$escape,$row);
                  $e++;
                  }
                echo '<tr';
                if ($t % 2 == 0) { if (isset($rowclass)) { echo ' alt'; } else { echo ' class="alt'; $rowclass = 1; } }
                if (isset($linebreak)) { if (isset($rowclass)) { echo ' breakrow'; } else { echo ' class="breakrow'; $rowclass = 1; } }
                if (isset($rowclass)) { echo '"'; }
                echo '>';
                  $cells = explode('~BR~',$row);
                  foreach ($cells as $cell) {
                    if ($t == 0) { echo "<th>"; } else { echo "<td>"; }
                      $cell = str_replace('""','"',$cell);
                      echo Parsedown::instance()->parse($cell);
                    if ($t == 0) { echo "</th>"; } else { echo "</td>"; }
                    }
                echo "</tr>";
                $t++; unset($linebreak,$rowclass);
                }
              }
          echo "</table>";
        break;
      
        case "gallery":
          if (isset($filename)) { // If the gallery has been given a name, then make it a dropdown
            $gallery_id = strtolower(preg_replace("/[^A-Za-z0-9]/", '', $filename));
					  echo '<div class="dropdown" name="gallery" id="'.$gallery_id.'">';
						echo '<p class="linkout"><a href="javascript:boxOpen(\''.$gallery_id.'\',\'gallery\')">';
            echo '<img src="/'.$codepath.'icons/Gallery.png" alt="Gallery: " class="icon" />';
						echo $filename.'</a></p>';
          }
					include ('gallery.php');
          if (isset($filename)) {
            echo '<p class="closeBox"><a href="javascript:boxOpen(\''.$gallery_id.'\',\'gallery\')">&#x2715; Close</a></p>';
            echo '</div>';
          }
				break;
      
        case "row":
          // Not interested in using the filename as a title for this feature
          echo '<table class="imgRow"><tr>';
            $rowdir = $filedir;
            $imgs = scandir($rowdir);
            unset($filevalue); // Not going to need it now, and it will just mess up the image parsing
            foreach ($imgs as $img) {
              if (strpos($img,"~") !== false) {
                $filedir = $rowdir.'/'.$img;
                $filename = explode("~",$img)[1];
                $filename = explode(".",$filename)[0];
                echo '<td>';
                  include('parsebox_image.php');
                echo '</td>';
              }
            }
          echo '</tr></table>';
        break;
      
        case "blog":
          if (isset($filename)) { echo "<h2>$filename</h2>"; }
          $blog = scandir($filedir);
          $blog = array_reverse($blog);
          $blog_posts = array(); $blog_other = array();
          foreach ($blog as $line) {
            unset ($pic);
            if (substr($line,-4) == ".txt") { array_push($blog_posts,$line); }
            else { array_push($blog_other,$line); }
            }
            if (isset($filevalue[1])) { $end = $filevalue[1]; } else { $end = 10; } // Number of posts to display
            for ($b = 1; $b <= $end; $b++) {
            $post = array_shift($blog_posts);
            $content = file_get_contents($filedir."/".$post);
            if(empty($content) != true) {
              $post = explode("~",substr($post,0,-4));
              $title = date("jS F Y",mktime(0,0,0,substr($post[0],4,2),substr($post[0],6,2),substr($post[0],0,4)));
              if ($post[1] != "") { $title .= ": ".$post[1]; }
              echo "<h3>".$title."</h3>";
              foreach ($blog_other as $line) {
                if (strpos($line,$post[0]) === 0) {
                  echo '<div class="blogimg" style="background-image: url(\'/'.$rootpath.$filedir.'/'.$line.'\');">';
                    echo "<a href=\"/".$rootpath.$filedir."/".$line."\" ";
			              echo "data-lightbox=\"gallery\" "; // All images in all galleries on a page can be flicked through as one set
			              $caption = explode("~",explode(".",$line)[0]);
                    if (isset($caption[1])) { $caption = $caption[1]; } //Should be a name for the image - if not, use the post title
                    else { $caption = $title; }
			              echo "data-title=\"$caption\"></a>";
                  echo '</div>';
                }
              }
            echo Parsedown::instance()->parse($content);
            }
            }
        break;
      
        case "custom":
          include("custom_parsing.php");
        break;
      
        default: // The filevalue hasn't been recognised, so assume it's the filename and then try working on the filetype
          if ($type[0] != "") { $filename = $type[0]; }
          switch ($filetype) {
            
            case "txt": // This is the big one. First look through the text, see if there's any special ParseBox style urls that indicate specific ParseBox plugins - these are converted to html and then the whole lot is parsed as markdown.
              $content = file($filedir);
              $text = "";
              foreach ($content as $line) {
                unset($name,$url,$iframe_type);
                if (substr($line,0,2) == "~[") {
                  $line = substr($line,2,strrpos($line,")")-2);
                  $line = explode("](",$line);
                  $url = $line[1]; if ($line[0] != "") { $name = $line[0]; }
                  // Now detect certain patterns of urls, to get info to include iframes etc
                  // YouTube videos
                  if (strpos($url,"youtu.be") !== false) {
                    $id = strpos($url,"e/");
					          $id = substr($url,$id+2);
                    $iframe_url  = "//www.youtube-nocookie.com/embed/$id?rel=0";
                    $iframe_type = "YouTube video";
                    }
                  elseif (strpos($url,"youtube") !== false && strpos($url,"watch")) {
                    $id = strpos($url,"v=");
					          $id = substr($url,$id+2);
                    $iframe_url  = "//www.youtube-nocookie.com/embed/$id?rel=0";
                    $iframe_type = "YouTube video";
                    }
                  elseif (strpos($url,"youtube") !== false && strpos($url,"edit")) {
                    $id = strpos($url,"d=");
					          $id = substr($url,$id);
                    $iframe_url  = "//www.youtube-nocookie.com/embed/$id?rel=0";
                    $iframe_type = "YouTube video";
                    }
                  //SoundCloud audio player
                  elseif (strpos($url,"soundcloud") !== false && substr_count($url,"/") == 4) {
                    $sc = file_get_contents('http://api.soundcloud.com/resolve.json?url='.$url.'&client_id=59f4a725f3d9f62a3057e87a9a19b3c6');
                    $sc = json_decode($sc);
                    $id = $sc->id;
                    if (isset($colour)) { $colour = strtolower(str_replace("hex","",$colour)); } else { $colour = "666666"; }
                    $iframe_url  = "https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/$id&amp;color=$colour&amp;auto_play=false&amp;hide_related=false&amp;show_artwork=false";
                    $iframe_type = "SoundCloud audio";
                    }
                  //Embedded Google form
                  elseif (strpos($url,"docs.google") !== false && strpos($url,"forms") !== false) {
                    $id = strpos($url,"d/");
					          $id = substr($url,$id+2);
                    $id = explode("/",$id)[0];
                    $iframe_url  = "https://docs.google.com/forms/d/$id/viewform?embedded=true";
                    $iframe_type = "Google form";
                    }
                  else { //If nothing special is detected about the url, then it's just an ordinary external link or an e-mail address
                    $icon = "External link";
                    if (strpos($url, "@") !== false) { // It's an email address, and we're going to include some basic robot baffling
                      $icon = "Email";
                      $address = ""; $i = 0;
                      for ($i = 0; $i < strlen($url); $i++) { $address .= '&#'.ord($url[$i]).';'; }
                      $url = "mailto:".$address;
                      }
                    // Detect and give icons to various common external links
                    elseif (strpos($url,"twitter.com") !== false) {
						          $icon = "Twitter feed";	}
                    elseif (strpos($url,"facebook.com") !== false) {
						          $icon = "Facebook page";	}
                    elseif (strpos($url,"apple.com") !== false && strpos($url,"app") !== false) {
						          $icon = "iTunes app";	}
					          elseif (strpos($url,"play.google") !== false && strpos($url,"app") !== false) {
						          $icon = "Android app"; }
					          elseif (strpos($url,"wolframalpha.com") !== false) {
						          $icon = "Wolfram|Alpha"; }
                    elseif (strpos($url,"codecademy.com") !== false) {
						          $icon = "Codecademy"; }
                    elseif (strpos($url,"khanacademy.org") !== false) {
						          $icon = "Khan Academy"; }
                    elseif (strpos($url,"docs.google") !== false && strpos($url,"presentation") !== false) {
						          $icon = "Google slides"; }
                    elseif (strpos($url,"docs.google") !== false && strpos($url,"spreadsheets") !== false) {
						          $icon = "Google sheets"; }
                    elseif (strpos($url,"docs.google") !== false && strpos($url,"document") !== false) {
						          $icon = "Google docs"; }
                    elseif (strpos($url,"youtube.com") !== false) {
                      $icon = "YouTube video"; }
                    elseif (strpos($url,"soundcloud.com") !== false) {
                      $icon = "SoundCloud audio"; }
                    // Now create the link
					          $line  = '<p class="linkout">';
                    $line .= '<a target="page'.mt_rand().'" href="'.$url.'">';
                    $line .= '<img src="/'.$codepath.'icons/'.str_replace("|","",$icon).'.png" alt="'.$icon.': " class="icon" />';
                    if (isset($name)) { $line .= $name; } else { $line .= 'Link'; } // There *should* be a name for this type of link, but prevent errors if there isn't
                    $line .= '</a></p>';
                    }
                  // If there's been the setup for an iframe, generate this now
                  if (isset($iframe_type)) {
                    $line = '';
                    $iframe_class = strtolower(str_replace(" ","",$iframe_type));
                    if (isset($name)) { // If the file to go in an iframe has been given a name, then make it a dropdown
                      $iframe_id = strtolower(preg_replace("/[^A-Za-z0-9]/", '', $name));
					            $line .= '<div class="dropdown" name="'.$iframe_class.'" id="'.$iframe_id.'">';
						          $line .= '<p class="linkout"><a href="javascript:boxOpen(\''.$iframe_id.'\',\''.$iframe_class.'\')">';
                      $line .= '<img src="/'.$codepath.'icons/'.$iframe_type.'.png" alt="'.$iframe_type.': " class="icon" />';
						          $line .= $name.'</a></p>';
                      }
                    $line .= '<iframe class="'.$iframe_class.'" src="'.$iframe_url.'" ';
                    $line .= 'frameborder="no" allowfullscreen></iframe>';
                    if (isset($name)) {
                      $line .= '<p class="closeBox"><a href="javascript:boxOpen(\''.$iframe_id.'\',\''.$iframe_class.'\')">&#x2715; Close</a></p>';
                      $line .= '</div>';
                      }
                    }
                  
                  }
                $text .= $line;
                }
              // Pull out any maths in the document, to protect it from Parsedown
              preg_match_all('/\\\\\[.*?\\\\\]/',$text,$m_blok);
              preg_match_all('/\\\\\(.*?\\\\\)/',$text,$m_span);
              $m_blok = $m_blok[0]; $m_span = $m_span[0];
              $ms = 0; foreach ($m_span as $span) {
                $text = str_replace($span,'~S'.$ms.'~',$text);
                $ms++;
                }
              $mb = 0; foreach ($m_blok as $blok) {
                $text = str_replace($blok,'~B'.$mb.'~',$text);
                $mb++;
                }
              // Maths is now gone - parse all the remaining text, then put the maths back in
              $text = Parsedown::instance()->parse($text);
              $katex = 0; // Count the number of instances of maths for KaTeX rendering
              $m = 0; foreach ($m_span as $span) {
                //$katekID = mt_rand();
                //$katekStart = "<span id=\"kx_s".$katekID."\">...</span><script>katex.render('";
                //$katekEnd   = "', kx_s".$katekID.");</script>";
                //$span = str_replace('\(',$katekStart,$span);
                //$span = str_replace('\)',$katekEnd,$span);
                //$span = str_replace("\\","\\\\",$span);
                $text = str_replace('~S'.$m.'~',$span,$text);
                $m++;
                $katex++;
                }
              $m = 0; foreach ($m_blok as $blok) {
                $text = str_replace('~B'.$m.'~',$blok,$text);
                $m++;
                $katex++;
                }
              if ($katex > 0) { // Preps LaTeX for rendering with KaTeX
                for ($k = 0; $k <= $katex; $k++) {
                  $kID = mt_rand();
                  $blokStart = "<p class=\"maths\" id=\"kx".$kID."\">...</p><script>katex.render('\\displaystyle ' + '";
                  $blokEnd   = "', kx".$kID.");</script>";
                  $text = str_replace_first("<p>\[",$blokStart,$text);
                  $text = str_replace_first("\]</p>",$blokEnd,$text);
                  $kID = mt_rand();
                  $spanStart = "<span id=\"kx".$kID."\">...</span><script>katex.render('";
                  $spanEnd   = "', kx".$kID.");</script>";
                  $text = str_replace_first("\(",$spanStart,$text);
                  $text = str_replace_first("\)",$spanEnd,$text);
                }
                $text = str_replace("\\","\\\\",$text);
              }
              echo $text;
					  break;
            
            case "jpg":
					  case "jpeg":
					  case "png":
					  case "gif":
              $filevalue = "mid";
              include('parsebox_image.php');
					  break;
            
            case "xls":	case "xlsx":
					  case "ods": // Excel or OpenOffice spreadsheets
						  echo '<p class="linkout"><a href="/'.$rootpath.$filedir.'"><img src="/'.$codepath.'icons/Spreadsheet.png" alt="Spreadsheet: " class="icon" />';
						  echo $type[0]."</a></p>";
					  break;
            
            case "doc":	case "docx":
					  case "odt": // Word or OpenOffice document
					  	echo '<p class="linkout"><a href="/'.$rootpath.$filedir.'"><img src="/'.$codepath.'icons/Document.png" alt="Document: " class="icon" />';
					  	echo $type[0]."</a></p>";
            break;
            
            case "ppt":	case "pptx":
					  case "odp": // PowerPoint or OpenOffice presentation
              echo '<p class="linkout"><a href="/'.$rootpath.$filedir.'"><img src="/'.$codepath.'icons/Presentation.png" alt="Presentation: " class="icon" />';
						  echo $type[0]."</a></p>";
					  break;
            
            case "psd": // Adobe Photoshop
              echo '<p class="linkout"><a href="/'.$rootpath.$filedir.'"><img src="/'.$codepath.'icons/Adobe Photoshop.png" alt="Adobe Photoshop: " class="icon" />';
						  echo $type[0]."</a></p>";
					  break;
            
            case "ai": // Adobe Illustrator
              echo '<p class="linkout"><a href="/'.$rootpath.$filedir.'"><img src="/'.$codepath.'icons/Adobe Illustrator.png" alt="Adobe Illustrator: " class="icon" />';
						  echo $type[0]."</a></p>";
					  break;
            
            case "ma": case "mb": // Autodesk Maya file
              echo '<p class="linkout"><a href="/'.$rootpath.$filedir.'"><img src="/'.$codepath.'icons/Autodesk Maya.png" alt="Autodesk Maya: " class="icon" />';
						  echo $type[0]."</a></p>";
					  break;
            
            case "pdf":
              // This will open in a new tab
						  echo '<p class="linkout"><a href="/'.$rootpath.$filedir.'" target="_BLANK"><img src="/'.$codepath.'icons/PDF.png" alt="PDF document: " class="icon" />';
						  echo $type[0].'</a></p>';
					  break;
            
            
            
            case "php":
					  case "html": case "htm":
					  case "js":
						  include($filedir);
					  break;
            
            case "css":
						  echo "<style>";
						    include($filedir);
						  echo "</style>";
					  break;
            
            }
        }
      }
    }

if (!isset($parsediv)) { echo '</div>'; }

?>