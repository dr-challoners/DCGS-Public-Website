<pre>
<?php // In development - more options and instructions will appear over time

  include('sheetCMS.php');
  echo "<style> .warning { color: red; font-weight: bold; } </style>";

  // For now, just grab and store all of the relevant sheets
  $_GET['sync'] = 1;
  $sheets = sheetToArray($mainSheet,'../'.$dataSrc,'manual');

  if ($sheets != 'ERROR') {
    
    echo '<h1>Data updated</h1>';
    echo '<p>Content generated:</p>';
    
    foreach ($sheets['data'] as $name => $section) {
      echo '<h2>'.$name.'</h2>';
      echo '<ol>';
      foreach ($section as $sheet) {
        $check = sheetToArray($sheet['sheetid'],'../'.$dataSrc,'manual');
        if ($check != 'ERROR') {
          echo '<li>'.$sheet['sheetname'].'</li>';
        } else {
          echo '<li class="warning">Failed to synchronise '.$sheet['sheetname'].'</li>';
        }
      }
      echo '</ol>';
    }
  } else { 
    echo '<p class="warning">FAILED TO SYNCHRONISE. Please try again.</p>';
  }

  echo "<hr />";
  echo "<h1>Formatting pages</h1>";
  echo "<h2>Basic text</h2>";
  echo "<p>This system works with Markdown, which is an intuitive way to format text in a plain text environment.</p>";
  echo "<p><a href=\"http://daringfireball.net/projects/markdown/syntax#block\">Full Markdown instructions</a></p>";
  echo "<p>If you want to go onto a new line in a spreadsheet cell, press <b>Alt+Enter</b> (as just enter will move you to the next cell). In this way, you have full access to Markdown's ability to format new paragraphs, lists and so on.</p>";
  echo "<h2>Adding images</h2>";
  echo "Specify the dataType as 'image' and then in the <b>url</b> column put a valid hyperlink to an image. This can be a Google Drive link - see below for more details on this. Anythign you put in <b>content</b> will be displayed in the image's description, and can be see when you click on the image (this brings up a full-screen version of the image).</p>";
  echo "<h3>Options for images</h3>";
  
  echo "<h3>Adding images from Google Drive</h3>";
  echo "<p>Once you have uploaded your image to Drive, right click on it and click <b>Share...</b>. Then click <b>Get shareable link</b> in the top right corner of the window that appears. This will give you the url you need - copy that into the <b>url</b> column of the page's worksheet.</p>";
  echo "<p>You should also check that the image is public, otherwise it won't display on the website. When you click <b>Get shareable link</b>, a box should appear in that window that says <b>Anyone with the link can view</b>. If the box specifies a different, more specific group (such as just anyone in your company with the link), then click on this box and select <b>Anyone with the link can view</b>. This option may be hidden behind <b>More...</b>.</p>";
  echo "<h2>Page titles</h2>";
  echo "<p>The main title displayed on the page is the same as the name of that page's worksheet. Page titles are limited to 50 characters in length.</p>";
  echo "<p>If you want a longer title on the page itself, or just a different title, write the title you want in the first row of the page's worksheet and give it the dataType 'title'.</p>";
  echo "<p>Note that this <u>must</u> be the first piece of data given, otherwise it will not display at all.</p>";

?>
</pre>