<?php
include_once('parsedown.php'); //Converts markdown text to HTML - see parsedown.org

// $dir is the folder that contains all the parts of the page - this must be passed on by the page that ParseBox is being used in
if (isset($dir) && !isset($parts)) { 
  $parts = scandir($dir, 1);
  $parts = array_reverse($parts); // Puts the array in ascending order first
  }
// You'll note from the above that it's also possible to manually set $parts to pick specific files and a specific order for the files - $div must still be set

if (!isset($parsediv)) { echo '<div class="parsebox">'; } // The 'if' here means you can put the parsebox div in manually earlier if you wish to add other content abve or below - just set $parsediv to have a value.
	
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
    $filevalue = explode("_",$filevalue);
    $filetype  = strtolower($filetype);
    
    switch($filevalue[0]) {
      
        case "left": // Images to left-align
        case "right": // Images to right-align
        case "wide": // Images that fit across the full width of the content column
          $filevalue = $filevalue[0];
          include('parsebox_image.php');
				break;
      
        case "gallery":
          if (isset($filename)) { echo "<h2>$filename</h2>"; }
					include ('gallery.php');
				break;
      
        case "blog":
          if (isset($filename)) { echo "<h2>$filename</h2>"; }
          $blog = scandir($dir."/".$part);
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
            $content = file_get_contents($dir."/".$part."/".$post);
            $post = explode("~",substr($post,0,-4));
            $title = date("jS F Y",mktime(0,0,0,substr($post[0],4,2),substr($post[0],6,2),substr($post[0],0,4)));
            if ($post[1] != "") { $title .= ": ".$post[1]; }
            echo "<h3>".$title."</h3>";
            foreach ($blog_other as $line) {
              if (strpos($line,$post[0]) === 0) {
                echo '<div class="blogimg" style="background-image: url(\'/'.$rootpath.$dir.'/'.$part.'/'.$line.'\');">';
                  echo "<a href=\"/".$rootpath.$dir."/".$part."/".$line."\" ";
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
        break;
      
        case "custom":
          include("custom_parsing.php");
        break;
      
        default: // The filevalue hasn't been recognised, so assume it's the filename and then try working on the filetype
          if ($type[0] != "") { $filename = $type[0]; }
          switch ($filetype) {
            
            case "txt": // This is the big one. First look through the text, see if there's any special ParseBox style urls that indicate specific ParseBox plugins - these are converted to html and then the whole lot is parsed as markdown.
              $content = file($dir."/".$part);
              $text = "";
              //print_r($content);
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
                    elseif (strpos($url,"apple.com") !== false && strpos($url,"app") !== false) {
						          $icon = "iTunes app";	}
					          elseif (strpos($url,"play.google") !== false && strpos($url,"app") !== false) {
						          $icon = "Android app"; }
					          elseif (strpos($url,"wolframalpha.com") !== false) {
						          $icon = "Wolfram|Alpha"; }
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
                    $line  = '<a target="_BLANK" href="'.$url.'">';
					          $line .= '<p class="linkout">';
                    $line .= '<img src="/'.$codepath.'icons/'.str_replace("|","",$icon).'.png" alt="'.$icon.': " class="icon" />';
                    // There *should* be a name for this type of link, but prevent errors if there isn't
                    if (isset($name)) { $line .= $name; } else { $line .= 'Link'; }
                    $line .= '</p></a>';
                    }
                  // If there's been the setup for an iframe, generate this now
                  if (isset($iframe_type)) {
                    $line = '';
                    $iframe_class = strtolower(str_replace(" ","",$iframe_type));
                    if (isset($name)) { // If the file to go in an iframe has been given a name, then make it a dropdown
                      $iframe_id = strtolower(preg_replace("/[^A-Za-z0-9]/", '', $name));
					            $line .= '<div class="dropdown" name="'.$iframe_class.'" id="'.$iframe_id.'">';
						          $line .= '<a href="javascript:boxOpen(\''.$iframe_id.'\',\''.$iframe_class.'\')"><p class="linkout">';
                      $line .= '<img src="/'.$codepath.'icons/'.$iframe_type.'.png" alt="'.$iframe_type.': " class="icon" />';
						          $line .= $name.'</p></a>';
                      }
                    $line .= '<iframe class="'.$iframe_class.'" src="'.$iframe_url.'" ';
                    $line .= 'frameborder="no" allowfullscreen></iframe>';
                    if (isset($name)) { $line .= '</div>'; }
                    }
                  
                  }
                $text .= $line;
                }
              // Pull out any maths in the document, to protect it from Parsedown
              preg_match_all('/\\\\\[.*?\\\\\]/',$text,$m_blok);
              preg_match_all('/\\\\\(.*?\\\\\)/',$text,$m_span);
              $m_blok = $m_blok[0]; $m_span = $m_span[0];
              $m = 0; foreach ($m_span as $span) {
                $text = str_replace($span,'~S'.$m.'~',$text);
                $m++;
                }
              $m = 0; foreach ($m_blok as $blok) {
                $text = str_replace($blok,'~B'.$m.'~',$text);
                $m++;
                }
              // Maths is now gone - parse all the remaining text, then put the maths back in
              $text = Parsedown::instance()->parse($text);
              $m = 0; foreach ($m_span as $span) {
                $text = str_replace('~S'.$m.'~',$span,$text);
                $m++;
                }
              $m = 0; foreach ($m_blok as $blok) {
                $text = str_replace('~B'.$m.'~',$blok,$text);
                $m++;
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
              echo "<a href=\"/".$rootpath.$dir."/$part\">";
						  echo "<p class=\"linkout\"><img src=\"/".$codepath."icons/Spreadsheet.png\" alt=\"Spreadsheet: \" class=\"icon\" />";
						  echo $type[0]."</p></a>";
					  break;
            
            case "doc":	case "docx":
					  case "odt": // Word or OpenOffice document
              echo "<a href=\"/".$rootpath.$dir."/$part\">";
					  	echo "<p class=\"linkout\"><img src=\"/".$codepath."icons/Document.png\" alt=\"Document: \" class=\"icon\" />";
					  	echo $type[0]."</p></a>";
            break;
            
            case "ppt":	case "pptx":
					  case "odp": // PowerPoint or OpenOffice presentation
              echo "<a href=\"/".$rootpath.$dir."/$part\">";
              echo "<p class=\"linkout\"><img src=\"/".$codepath."icons/Presentation.png\" alt=\"Presentation: \" class=\"icon\" />";
						  echo $type[0]."</p></a>";
					  break;
            
            case "pdf":
              echo "<a href=\"/".$rootpath.$dir."/$part\" target=\"_BLANK\">"; // This will open in a new tab
						  echo "<p class=\"linkout\"><img src=\"/".$codepath."icons/PDF.png\" alt=\"PDF document: \" class=\"icon\" />";
						  echo $type[0]."</p></a>";
					  break;
            
            case "php":
					  case "html": case "htm":
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

if (!isset($parsediv)) { echo '</div>'; }

?>