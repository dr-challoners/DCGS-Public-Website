<!DOCTYPE HTML>
<html>
  <head>
    <title>DCGS Sync</title>
  </head>
  <body>

<pre><?php

  //Handling authorisation with Box API
	include('BoxAPI.class.php');
  include('client_config.php');
	
	$box = new Box_API($client_id, $client_secret, $redirect_uri);
	
	if(!$box->load_token()){
		if(isset($_GET['code'])){
			$token = $box->get_token($_GET['code'], true);
			if($box->write_token($token, 'file')){
				$box->load_token();
			}
		} else {
			$box->get_code();
		}
	}


echo "<h1>Content synchronisation</h1>";
echo "<p>Please select one of the directories below and choose 'update' to synchronise.</p>";
echo "<p>Choose 'reset' to clear the entire directory and synchronise it from scratch:<br />this is much slower, and should be used only if something has gone wrong.</p>";
echo "<h2>DCGS public website</h2>";
echo '<p style="color:red;">The sync program has been improved - it is much faster now.<br />As such, the main content is back to being updated from a single button press.</p>';
echo "<ul>"; //Change the initial values as appropriate for the Box folder being referred to
  echo '<p><li>Main content:      <a href="./system_update.php?initial=2176714940">update</a> or <a href="./system_update.php?initial=2176714940&reset=y">reset</a></li></p>'; //content_main
  echo "<p><li>Intranet links:    <a href=\"./system_update.php?initial=2176731094\">update</a> or <a href=\"./system_update.php?initial=2176731094&reset=y\">reset</a><br /></li></p>"; //content_system/intranet
  echo "<p><li>Override messages: <a href=\"./system_update.php?initial=2176721210\">update</a> or <a href=\"./system_update.php?initial=2176721210&reset=y\">reset</a></li></p>"; //content_system/override
echo "</ul>";
echo '<h2>DCGS public website - News section</h2>';
echo '<p>The partial update option only checks the most recent 20 stories.<br />A full update is a <i>very</i> slow process, so a partial update should be used unless<br />it is necessary to update the archives.</p>';
echo '<ul>';
  echo '<p><li><a href="./system_update.php?initial=2209250189&partial=y">Partial update</a>, <a href="./system_update.php?initial=2209250189">full update</a> or <a href="./system_update.php?initial=2209250189&reset=y">full reset</a></li></p>'; //content_news
echo '</ul>';
echo "<h2>'Learn' websites</h2>";
echo "<ul>";
  $learnfolder = $box->get_folder_details('2194837001'); //This needs to correspond to the 'content_learn' folder ID on Box
  foreach($learnfolder['item_collection']['entries'] as $entry) {
    //print_r($entry);
    $sitename = $entry['name'];
    $sitename = str_replace("_"," ",$sitename);
    $sitename = ucwords($sitename);
    echo "<p><li>".$sitename.": <a href=\"./system_update.php?initial=".$entry['id']."\">update</a> or <a href=\"./system_update.php?initial=".$entry['id']."&reset=y\">reset</a></li></p>";
    }
echo "</ul>";

?></pre>
    
  </body>
</html>