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
echo "<p>Please select one of the directories below to synchronise.</p>";
echo "<h2>DCGS public website</h2>";
echo "<ul>"; //Change the initial values as appropriate for the Box folder being referred to
  echo "<p><li>Main content:      <a href=\"./system_update.php?initial=2176714940\">update</a> or <a href=\"./system_update.php?initial=2176714940&reset=y\">reset</a></li></p>"; //content_main
  echo "<p><li>News stories:      <a href=\"./system_update.php?initial=2209250189\">update</a> or <a href=\"./system_update.php?initial=2209250189&reset=y\">reset</a></li></p>"; //content_news
  echo "<p><li>Intranet links:    <a href=\"./system_update.php?initial=2176731094\">update</a> or <a href=\"./system_update.php?initial=2176731094&reset=y\">reset</a><br /></li></p>"; //content_system/intranet
  echo "<p><li>Override messages: <a href=\"./system_update.php?initial=2176721210\">update</a> or <a href=\"./system_update.php?initial=2176721210&reset=y\">reset</a></li></p>"; //content_system/override
echo "</ul>";
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