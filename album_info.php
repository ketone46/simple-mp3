<?php
    $album_a = $_GET['album'];  
	
	$artist_a = $_GET['artist'];
  $videos = "";
	//Data From Last.fm
	$artist = urlencode($artist_a);   
	$album = urlencode($album_a);
	$json = file_get_contents("http://ws.audioscrobbler.com/2.0/?method=album.getinfo&album={$album}&artist={$artist}&api_key=a9b50f586805b069a23f929fb0399e3a&format=json");
    $data = json_decode($json,TRUE);
	
	//Looping JSON data to get all tracks name from Last.fm
    foreach ($data['album']['tracks']['track'] as $song)
	    {
	      $q3 = "{$artist} {$song['name']}"; //Search term for Youtube.
    
	       //Including Google api with youtube service.
          require_once ('google/Google_Client.php');
          require_once ('google/contrib/Google_YouTubeService.php');
 
          $DEVELOPER_KEY = 'AIzaSyDOkg-u9jnhP-WnzX5WPJyV1sc5QQrtuyc';

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
				  
$title_enc = urlencode($q3); //Preview Search Term			  
				  //Final Links.Download Link includes the youtube id which will be  forwarded to download page.
   $videos .= sprintf('<p>%s &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (%s) &nbsp;&nbsp;&nbsp;&nbsp;(%s)</p>', $searchResult['snippet']['title'],"<a href=download.php?id=".$searchResult['id']['videoId'].">Download</a>","<a href=play.php?id=".$title_enc.">Play Preview</a>");

  
     } //Last.fm loop ends.

echo $videos;
?>
