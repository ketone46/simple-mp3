<?php
    $search = "Pink Floyd";   //Search Term.
	
	$videos = '';             //For Storing All links. 
	
	$q1 = $search;            //Just Like that.
	
	//Data From Last.fm
	$q2 = urlencode($q1);   
	$json = file_get_contents("http://ws.audioscrobbler.com/2.0/?method=track.search&track={$q2}&api_key=YOUR_API_KEY_FROM_LAST.FM&format=json");
    $data = json_decode($json,TRUE);
	
	//Looping JSON data to get all tracks name from Last.fm
    foreach ($data['results']['trackmatches']['track'] as $song)
	    {
	      $q3 = "{$song['artist']} {$song['name']}"; //Search term for Youtube.
    
	       //Including Google api with youtube service.
          require_once ('google/Google_Client.php');
          require_once ('google/contrib/Google_YouTubeService.php');
 
          $DEVELOPER_KEY = 'API_KEY_FROM_GOOGLE_CONSOLE_WITH_YOUTUBE_ENABLED';

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
   $videos .= sprintf('<p>%s &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (%s)</p>', $searchResult['snippet']['title'],"<a href=download.php?id=".$searchResult['id']['videoId'].">Download</a>");

  
     } //Last.fm loop ends.

echo $videos;
?>
	 
	 
	


