<?php

$consumerKey = '5Py6zvAXxXJXs2ERI94N7e3V0';
$consumerSecret = '0B4oOxWHnSdg0hqEf9tMncwW1tL9ucWCscP15tYbkUaW3aBTvc';

// auth parameters
$api_key = urlencode('5Py6zvAXxXJXs2ERI94N7e3V0'); // Consumer Key (API Key)
$api_secret = urlencode('0B4oOxWHnSdg0hqEf9tMncwW1tL9ucWCscP15tYbkUaW3aBTvc'); // Consumer Secret (API Secret)
$auth_url = 'https://api.twitter.com/oauth2/token'; 

// what we want?
$data_username = 'DCGSVisits'; // username
$data_count = 10; // number of tweets
$data_url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';

// get api access token
$api_credentials = base64_encode($api_key.':'.$api_secret);

$auth_headers = 'Authorization: Basic '.$api_credentials."\r\n".
                'Content-Type: application/x-www-form-urlencoded;charset=UTF-8'."\r\n";

$auth_context = stream_context_create(
    array(
        'http' => array(
            'header' => $auth_headers,
            'method' => 'POST',
            'content'=> http_build_query(array('grant_type' => 'client_credentials', )),
        )
    )
);

// All this calls externally, so needs to be cached to keep the front page speed up

if (!empty('data/override')) {
    $stored = 'data/override/vtu.json';
  }
  if (isset($stored) && file_exists($stored)) {
    $tweetArray = file_get_contents($stored);
    $tweetArray = json_decode($tweetArray, true);
    if (isset($tweetArray['meta']['lastupdate'])) {
      $lastUpdate = $tweetArray['meta']['lastupdate'];
    }
  }

if (!isset($lastUpdate) || (isset($lastUpdate) && $lastUpdate < (time()-600)) || isset($_GET['sync'])) {
  // Either this sheet has never been fetched before, or the record is stale, or we're being forced to refresh

  $auth_response = json_decode(file_get_contents($auth_url, 0, $auth_context), true);
  $auth_token = $auth_response['access_token'];

  // get tweets
  $data_context = stream_context_create( array( 'http' => array( 'header' => 'Authorization: Bearer '.$auth_token."\r\n", ) ) );

  $data = json_decode(file_get_contents($data_url.'?count='.$data_count.'&screen_name='.urlencode($data_username), 0, $data_context), true);

  // Pull out only the interesting data
  $tweetArray = array();
  $tweetArray['meta']['lastupdate'] = time();
  foreach ($data as $row) {
    $tweetArray['data'][$row['id']] = array();
    $tweetArray['data'][$row['id']]['datestamp'] = strtotime($row['created_at']);
    if (isset($row['quoted_status'])) {
      $quoteURL = strrpos($row['text'],'https://t.co/');
      $quoteURL = $quoteURL - strlen($row['text']);
      $text = substr($row['text'],0,$quoteURL);
      $tweetArray['data'][$row['id']]['content'] = $text.PHP_EOL.PHP_EOL.$row['quoted_status']['text'];
    } else {
      $tweetArray['data'][$row['id']]['content'] = $row['text'];
    }
    $tweetArray['data'][$row['id']]['hashtags'] = array();
    foreach ($row['entities']['hashtags'] as $tag) {
      $tweetArray['data'][$row['id']]['hashtags'][] = clean($tag['text']);
    }
  }
  if (!empty('data/override')) {
    // Cache the array as JSON into the specified caching folder
    if (!file_exists('data/override')) {
      mkdir('data/override',0777,true);
    }
    file_put_contents($stored, json_encode($tweetArray));
  }
  
}

// Now that the data is available in $tweetArray, we can look for appropriate tweets display
// Only show the most recent tweet to contain the hashtag vtu, provided it happened today

foreach ($tweetArray['data'] as $tweet) {
  if (in_array('vtu',$tweet['hashtags']) && $tweet['datestamp'] > strtotime('today')) {
    echo '<style>';
      echo '#messageVTU { background-color: RebeccaPurple; }';
      echo '#messageVTU h1 { color: RebeccaPurple; }';
    echo '</style>';
    echo '<div class="row overrideMessage" id="messageVTU">';
      echo '<div class="iconPanel col-xs-2">';
        echo '<i class="fas fa-bus"></i>';
      echo '</div>';
      echo '<div class="messagePanel col-xs-10">';
        echo '<h1>Travel update</h1>';
        //view($tweet['content']);
        echo formatText(str_replace('#vtu','',$tweet['content']));
        echo '<p><i class="fab fa-twitter" style="color:#1b95e0;"></i> For more visits information, see the <a href="https://twitter.com/DCGSVisits">DCGS Visits</a> Twitter feed.</p>';
      echo '</div>';
    echo '</div>';
    break;
  }
}

//view($data);
//view($tweetArray);

?>