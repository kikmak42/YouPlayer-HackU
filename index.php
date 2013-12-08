<?php /**/ ?><?php 

require 'src/facebook.php';
$facebook = new Facebook(array(
'appId' => '256550774435796',
'secret' => '449ddc65ea7a0675da17f9e1cbcd1390',
'cookie' => true,

));

$user = $facebook->getUser();

if ($user) 
{ try 
{ 

  $list1 = $facebook->api('/me/music');
  $list2 = $facebook->api('/me/movies');

}

catch (FacebookApiException $e) 

{  
error_log($e);
$user = null;

}
 
}

if ($user) 
{
$logoutUrl = $facebook->getLogoutUrl();
} 

else 

{ $loginUrl = $facebook->getLoginUrl();
}


?>

<!doctype html>

<html xmlns:fb="http://www.facebook.com/2008/fbml"
<script type="text/javascript"></script><link id="page_favicon" href="/favicon.ico" rel="icon" type="image/x-icon" />
<title>YouPlayer</title>
<link rel="stylesheet" type="text/css" href="css/style.css" />

</head>

<body>
<div class="wrap">
<img src="images/folder_music.png" height="100px"/>


<?php if ($user): ?>
<div class="logout">
<h2>Play the music you like..</h2>
<div class="logout-inner">
<img src="https://graph.facebook.com/<?php echo $user; ?>/picture"> 
<a href="<?php echo $logoutUrl; ?>" class="white">Logout</a>
</div>
</div>

<?php else: ?>
<h2>Play the music you like..</h2>
<a href="<?php echo $loginUrl; ?>" class="fb_connect"><img src="images/fb_connect.png" /></a>



<?php endif ?> 
<br class="clear" />
<a href="http://dev.ecell-iitkgp.org/kaushik/hacku/audio/welcome.mp3"><img src="images/play-button.png" /></a>


<?php if ($user): ?>
<br><br><br><br><br><br><br>
<pre><?php 

$list1 = $list1['data'];
$list2 = $list2['data'];

find_centrality($list1);
find_centrality($list2);

?></pre>

<?php else: ?>

<p>You are not Connected............!</p>
<?php endif ?>

<br class="clear" /><br/><br/><br/>

<div id="test"></div> 

<?php

function find_centrality($list)

{

global $facebook;

$i=0 ; $totalcount = 0;

foreach($list as $item)

{  

$id = $item['id'];

$name = $item['name'];
  
echo "<br>---------------------------";
echo "<br>" .$name . "<br>";
  
   $query = $name;
    $events="";
     
    // Form YQL query and build URI to YQL Web service

    $yql_query_url = "https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20youtube.search%20where%20query%3D%22official%20".urlencode($query)."%20-award%20-show%22%20and%20start_index%3D".rand(1,5)."%20and%20max_results%3D50%20and%20order_by%3D'rating'&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys";

    // Make call with cURL
    $session = curl_init($yql_query_url);
    curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
    $json = curl_exec($session);
    // Convert JSON to PHP object 
    $phpObj =  json_decode($json);

    // Confirm that results were returned before parsing
    if(!is_null($phpObj->query->results)){
      // Parse results and extract data to display
	  $n=0;
      foreach($phpObj->query->results->video as $event){
	  if($event->categories->category =="Music")
	  {
        $events = "<a href=" . $event->url . "></a>";
		$n++;
		if($n<=7)
                {echo $events;}
		
      }}
    }
    // No results were returned
    if(empty($events)){
      $events = "Sorry, we are experiencing some technical difficulties. $query in $location";
    }

}



}

?>

<script type="text/javascript" src="http://webplayer.yahooapis.com/player.js"></script> 
<br class="clear" />
</div>




</body>
</html>	