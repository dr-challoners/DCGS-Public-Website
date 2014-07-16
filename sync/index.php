<pre>
<?php

  //Handling authorisation with Box API
	include('BoxAPI.class.php');

	$client_id		= 'noh636jyorxaw6e4f72pbvh66jr56h1h';
	$client_secret 	= '1SCFdIfqXH6zGwaI44yekwOEjV3QdRLp';
	$redirect_uri 	= 'https://kings-landing-php-89376.use1.nitrousbox.com/sync/system_upload.php';
	
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
  echo "<p><li><a href=\"./system_upload.php?initial=2176714940\">Main content</a></li></p>";
  echo "<p><li><a href=\"./system_upload.php?initial=2194227433\">News stories</a></li></p>";
  echo "<p><li><a href=\"./system_upload.php?initial=2176731094\">Intranet links</a><br /></li></p>";
  echo "<p><li><a href=\"./system_upload.php?initial=2176721210\">Override messages</a></li></p>";
echo "</ul>";
echo "<h2>'Learn' websites</h2>";
echo "<ul>";
  $learnfolder = $box->get_folder_details('2194837001'); //This needs to correspond to the 'content_learn' folder ID on Box
  foreach($learnfolder['item_collection']['entries'] as $entry) {
    //print_r($entry);
    $sitename = $entry['name'];
    $sitename = str_replace("_"," ",$sitename);
    $sitename = ucwords($sitename);
    echo "<p><li><a href=\"./system_upload.php?initial=".$entry['id']."\">".$sitename."</a></li></p>";
    }
echo "</ul>";

?>
</pre>