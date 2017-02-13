<?php
    $q = $_GET['q'];          //Search Term.
	
	$tracks = '';             //For Storing tracks. 
	
	$albums = '';             //For Storing albums. 
	
	$q2 = urlencode($q); 
	//Tracks Data From Last.fm  
	$json1 = file_get_contents("http://ws.audioscrobbler.com/2.0/?method=track.search&track={$q2}&api_key=a9b50f586805b069a23f929fb0399e3a&format=json&limit=5");
    $data1 = json_decode($json1,TRUE);
	
	//Albums Data From Last.fm  
	$json2 = file_get_contents("http://ws.audioscrobbler.com/2.0/?method=album.search&album={$q2}&api_key=a9b50f586805b069a23f929fb0399e3a&format=json&limit=5");
    $data2 = json_decode($json2,TRUE);
	
	
	//Looping Tracks JSON data to get all tracks name from Last.fm
    foreach ($data1['results']['trackmatches']['track'] as $song)
	    {	      $q3 = "{$song['artist']} {$song['name']}"; //Search term for Youtube.
    
	       //Including Google api with youtube service.
          require_once ('google/Google_Client.php');
          require_once ('google/contrib/Google_YouTubeService.php');
 
          $DEVELOPER_KEY = 'AIzaSyAO6e32mq3LQaOY6T323x_AFUoSEwHn2hA';

          $client = new Google_Client();
          $client->setDeveloperKey($DEVELOPER_KEY);

          $youtube = new Google_YoutubeService($client);
          
		  //Setting Search term and number of results to retrive. We will take the first result.
          try {
               $searchResponse = $youtube->search->listSearch('id,snippet', array(
               'q' => $q3,
               'maxResults' => '1',  
               ));
                 //Looping $q3 to get the first result from youtube.
                 foreach ($searchResponse['items'] as $searchResult)
		          {
                    switch ($searchResult['id']['kind'])
		             {
                       case 'youtube#video':
                       break;

                     }
                 }

              }
		  catch (Google_ServiceException $e)
           		  {
                   $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
                   htmlspecialchars($e->getMessage()));
                  }
		  catch (Google_Exception $e)
        		  {
                   $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
                   htmlspecialchars($e->getMessage()));
                  }
				  
				  //Final Links.Download Link includes the youtube id which will be  forwarded to download page.
   $tracks .= sprintf('<p>%s &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (%s)</p>', $searchResult['snippet']['title'],"<a href=download.php?id=".$searchResult['id']['videoId'].">Download</a>");

  
     } //Last.fm loop ends.
	 foreach ($data2['results']['albummatches']['album'] as $album)
	 {
		 $album_q = urlencode($album['name']);
		 $album_a = urlencode($album['artist']);
		 $albums .= "<p><a href=album_info.php?album=".$album_q."&artist=".$album_a.">".$album['name']." by ".$album['artist']."</p>";
	 }

echo "<h2>Tracks</h2>".$tracks."<a href=tracks.php?q=".$q.">Load More</a><br>";
echo "<h2>Albums</h2>".$albums."<a href=albums.php?q=".$q.">Load More</a><br>";

?>
