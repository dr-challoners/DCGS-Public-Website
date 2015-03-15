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

div.parsebox p, div.parsebox ul, div.parsebox ol {
  font-size: 14px;
  line-height: 22px; margin: 0 0 16px 0;
  }

div.parsebox ul, div.parsebox ol { margin-left: 30px; }
.parsebox li { padding-left: 6px; }

div.parsebox a {
  font-weight: bold;
  text-decoration: none;
  }
  div.parsebox a:hover { text-decoration: underline; }

div.parsebox a.external {
  cursor: url(external.cur), auto;
  }

div.parsebox table.simple, .parsebox table.imgRow {
    width: 100%; margin-bottom: 16px;
    border-collapse: collapse;
    }
div.parsebox table.simple p {
    margin: 0;
    line-height: 20px;
    text-align: left;
    }
div.parsebox table.simple th {
    background-color: #eeeeee;
    border-bottom: 2px solid;
    <?php echo "border-color: ".$colour.";\n"; ?>
    padding: 7px 4px;
    }
div.parsebox table.simple td {
    padding: 4px;
    vertical-align: top;
    }
div.parsebox table.simple tr:nth-child(2n+1) { background-color: #f7f7f7; }
div.parsebox table.simple tr.breakrow {
    border-top: 1px solid;
    border-color: #cccccc;
    }

.parsebox table.imgRow td {
  padding: 0px 5px;
  vertical-align: middle;
  }
.parsebox table.imgRow td:first-child { padding: 0 5px 0 0; }
.parsebox table.imgRow td:last-child  { padding: 0 0 0 5px; }
.parsebox table.imgRow img { width: 100%; }

div.parsebox code {
    display: block; padding: 5px 10px; margin: 16px 0;
    font-family: "Courier New", monospace;
    border: 1px dotted #bbbbbb; background-color: #f7f7f7;
    }

.parsebox .linkout a { display: block;}
div.parsebox p.linkout img.icon {
    <?php echo "background-color: ".$colour.";\n"; ?>
    height: 26px;
    vertical-align: middle; margin-right: 10px;
    }
.parsebox .linkout a:hover {
    margin: -1px;
    border: 1px solid #dddddd;
    background-color: #fdfdfd;
    <?php echo "color: ".$colour.";\n"; ?>
    text-decoration: none;
    }

/* iFrames and Dropdowns */

div.parsebox iframe { width: 100%; margin: 16px 0px 16px 0px; }
div.parsebox iframe.youtubevideo, div.parsebox iframe.vimeovideo { height: 30em; }
div.parsebox iframe.googleform {
  height: 19em; margin-bottom: 20px;
  }

div.parsebox div.dropdown iframe, .parsebox div.dropdown div.gallery { display: none; }
div.parsebox div.open iframe, .parsebox div.open div.gallery { display: block; margin-top: 16px; }
div.parsebox div.open p.linkout { display: none; }
.parsebox p.closeBox {
    display: none;
    font-size: 11px;
    text-align: right; margin-top: -14px;
    font-weight: bolder; text-transform: uppercase;
    color: #888888;
    }
    .parsebox div.open p.closeBox { display: block; }
    p.closeBox a { color: inherit; }
    p.closeBox a:hover {
        text-decoration: none;
        <?php echo "color: ".$colour.";\n"; ?>
    }

/* General images */

.parsebox div.imgDiv img {
    width: 100%;
    outline: 6px solid transparent;
    outline-offset: -6px;
    transition: outline-color 0.2s;
    -webkit-transition: outline-color 0.2s;
    }
    <?php echo ".parsebox div.imgDiv img:hover { outline-color: ".$colour."; }\n"; ?>

.parsebox div.imgDiv p, .parsebox div.gallery p {
    position: absolute;
    float: right; margin: -24px 0 0 3px;
    font-size: 11px; font-weight: bold;
    color: white;
    text-shadow:
     -1px -1px 0 #000,
      1px -1px 0 #000,
     -1px  1px 0 #000,
      1px  1px 0 #000;
    }

.parsebox div.mid {
    display: block;
    margin: 16px auto;
    max-width: 66.7%;
    }
.parsebox div.wide {
  width: 100%;
  margin: 0 0 16px 0;
  }
.parsebox div.left, div.right {
    width: 40%;
    margin: 8px 0;
    }
.parsebox div.left {
    margin-right: 2%;
    float: left; clear: left;
    }
.parsebox div.right {
    margin-left: 2%;
    float: right; clear: right;
    }

/* Galleries - note the styling for the image credit is above */

div.parsebox div.gallery { margin: 16px 0 4px -12px; }

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

/* Maths with KaTeX */

.parsebox p.maths { text-align: center; }