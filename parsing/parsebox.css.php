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

/* WHEN MAKING EDITS IN FIREFOX, DO NOT COPY PASTE BACK INTO THE ORIGINAL FILE AS YOU WILL LOSE THE PHP! */

/* General */

div.parsebox h1 { margin: 18px 0 14px 0; }
div.parsebox h2 { margin: 0 0 12px 0; }
div.parsebox h3, div.parsebox h4 { margin: 0 0 8px 0; }

div.parsebox p {
  font-size: 14px;
  line-height: 22px; margin: 0 0 16px 0;
  }

div.parsebox ul, div.parsebox ol {
  margin: 0 0 16px 32px;
  }

div.parsebox a {
  font-weight: bold;
  text-decoration: none;
  }
  div.parsebox a:hover { text-decoration: underline; }

/* div.parsebox a.external:before {
  content: url('./icons/urlout.png');
  margin-right: 1.5px;
  } */

div.parsebox table {
    width: 100%; margin-bottom: 16px;
    border-collapse: collapse;
    }
div.parsebox table p {
    margin: 0;
    line-height: 20px;
    text-align: left;
    }
div.parsebox table th {
    background-color: #eeeeee;
    border-bottom: 2px solid;
    <?php echo "border-color: ".$colour.";\n"; ?>
    padding: 7px 4px;
    }
div.parsebox table td {
    padding: 4px;
    vertical-align: top;
    }
div.parsebox tr.alt { background-color: #f7f7f7; }
div.parsebox tr.breakrow {
    border-top: 1px solid;
    border-color: #cccccc;
    }

div.parsebox code {
    display: block; padding: 5px 10px; margin: 16px 0;
    font-family: "Courier New", monospace;
    border: 1px dotted #bbbbbb; background-color: #f7f7f7;
    }

div.parsebox p.linkout img.icon {
    <?php echo "background-color: ".$colour.";\n"; ?>
    vertical-align: middle; margin-right: 8px;
    }

/* iFrames and Dropdowns */

div.parsebox iframe { width: 100%; margin: 16px 0px 16px 0px; }
div.parsebox iframe.youtubevideo { height: 30em; }
div.parsebox iframe.googleform {
  height: 19em; margin-bottom: 20px;
  border-radius: 2px; box-shadow: 0px 4px 10px #cccccc;
  }

div.parsebox div.dropdown iframe { display: none; }
div.parsebox div.open iframe { display: block; margin-top: 16px; }
div.parsebox div.open p.linkout { display: none; }

/* General images */

div.parsebox img.mid {
    display: block;
    margin: 16px auto;
    max-width: 100%;
    }
div.parsebox img.wide {
  width: 100%;
  margin: 0 0 16px 0;
  }
div.parsebox img.left, img.right {
    width: 48%;
    margin: 8px 0 16px 0;
    }
div.parsebox img.left {
    margin-right: 2%;
    float: left;
    }
div.parsebox img.right {
    margin-left: 2%;
    float: right;
    }

div.parsebox a.imagelink img {
    outline: 6px solid transparent;
    outline-offset: -6px;
    transition: outline-color 0.2s;
    -webkit-transition: outline-color 0.2s;
    }
    <?php echo "div.parsebox a.imagelink img:hover { outline-color: ".$colour."; }\n"; ?>

/* Galleries */

div.parsebox div.gallery { margin: 16px 0px 4px -12px; }

div.parsebox div.photostub {
    float: left;
    margin: 0px 0px 12px 12px;
    background-color: #f0f0f0;
    background-size: cover;
    }
    <?php
      echo "div.parsebox div.med { width: ".$medbox."px; height: ".$medbox."px; }\n";
      echo "div.parsebox div.tny { width: ".$tnybox."px; height: ".$tnybox."px; }\n";
      echo "div.parsebox div.wde { width: ".$bigbox."px; height: ".$medbox."px; }\n";
      ?>

    div.parsebox div.photostub a {
       height: <?php echo $linkheight ?>px;
       display: block; border: 6px solid transparent;
       transition: border-color 0.2s;
       -webkit-transition: border-color 0.2s;
       }
       div.parsebox div.tny a {
         height: <?php echo $tnylinkheight ?>px;
         border-width: 4px;
         }
       <?php echo "div.parsebox div.photostub a:hover { border-color: ".$colour."; }\n"; ?>

div.parsebox div.tny-box {
    float: left;
    <?php echo "width: ".$tnycontainer."px; height: ".$tnycontainer."px;\n"; ?>
    }

/* Microblogs */

div.parsebox div.blogimg {
    width: 28%;
    float: right; margin: 8px 0 16px 16px;
    background-size: cover;
    background-position: center center;
    }

div.parsebox div.blogimg a {
    display: block; height: 7.5em;
    border: 6px solid transparent;
    transition: border-color 0.2s;
    -webkit-transition: border-color 0.2s;
    }
    <?php echo "div.parsebox div.blogimg a:hover { border-color: ".$colour."; }\n"; ?>