<?php
  header ("Content-type: text/css");

  if(isset($_GET['colour'])) {
    $colour = str_replace("hex","#",$_GET['colour']);
    }
  else { $colour = "#666666"; }

  $pagewidth = $_GET['pagewidth'];
    $medbox = floor(($pagewidth-24)/3);
    $bigbox = ($pagewidth-($medbox+12));
    $tnybox = floor(($medbox-12)/2);
    $tnycontainer = $medbox+12;
    $linkheight = $medbox-12;
    $tnylinkheight = $tnybox-8;

?>

code {
    display: block; padding: 5px 10px;
    font-family: "Courier New", monospace;
    border: 1px dotted #bbbbbb; background-color: #f7f7f7;
    }

p.linkout { margin-bottom: -5px; }
    p.linkout a {
       display: inline-block;
       width: <?php echo $pagewidth-45; ?>px; height: 29px;
       vertical-align: top; padding: 6px 0px 0px 10px;
       }
    <?php echo "img.icon { background-color: ".$colour."; }\n"; ?>

/* IFRAMES (YOUTUBE, GOOGLE FORMS) */

iframe { width: 100%; margin: 16px 0px 16px 0px; }
iframe.youtube, iframe.gslides { height: 30em; }
iframe.gform {
  height: 17.5em; margin-bottom: 21px;
  box-shadow: 0px 5px 10px #cccccc;
  }

div.dropdown iframe { display: none; }
div.open iframe { display: block; margin-top: 16px; }
div.open p.linkout { display: none; }

/* GENERAL IMAGES */

img.mid {
    display: block;
    margin: 16px auto;
    max-width: 100%;
    }
img.wde {
  width: 100%;
  margin: 16px 0;
  }
img.lft, img.rgt {
    width: 48%;
    margin: 16px 0;
    }
img.lft {
    margin-right: 2%;
    float: left;
    }
img.rgt {
    margin-left: 2%;
    float: right;
    }

a.imagelink img {
    outline: 6px solid transparent;
    outline-offset: -6px;
    transition: outline-color 0.2s;
    -webkit-transition: outline-color 0.2s;
    }
    <?php echo "a.imagelink img:hover { outline-color: ".$colour."; }\n"; ?>

/* GALLERY */

div.gallery { margin: 16px 0px 4px -12px; }

div.photostub {
    float: left;
    margin: 0px 0px 12px 12px;
    background-color: #f0f0f0;
    background-size: cover;
    }
    <?php
      echo "div.med { width: ".$medbox."px; height: ".$medbox."px; }\n";
      echo "div.tny { width: ".$tnybox."px; height: ".$tnybox."px; }\n";
      echo "div.wde { width: ".$bigbox."px; height: ".$medbox."px; }\n";
      ?>

    div.photostub a {
       height: <?php echo $linkheight ?>px;
       display: block; border: 6px solid transparent;
       transition: border-color 0.2s;
       -webkit-transition: border-color 0.2s;
       }
       div.tny a {
         height: <?php echo $tnylinkheight ?>px;
         border-width: 4px;
         }
       <?php echo "div.photostub a:hover { border-color: ".$colour."; }\n"; ?>

div.tny-box {
    float: left;
    <?php echo "width: ".$tnycontainer."px; height: ".$tnycontainer."px;\n"; ?>
    }