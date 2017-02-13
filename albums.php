<?php
    $search = $_GET['q'];  
  
	$q1 = $search;            //Just Like that.
	
	//Data From Last.fm
	$q2 = urlencode($q1);   
	$json = file_get_contents("http://ws.audioscrobbler.com/2.0/?method=album.search&album={$q2}&api_key=a9b50f586805b069a23f929fb0399e3a&format=json");
    $data = json_decode($json,TRUE);
	
	 foreach ($data['results']['albummatches']['album'] as $album)
	 {
		 $album_q = urlencode($album['name']);
		 $album_a = urlencode($album['artist']);
		 $albums .= "<p><a href=album_info.php?album=".$album_q."&artist=".$album_a.">".$album['name']." by ".$album['artist']."</p>";
	 }

echo $albums;
?>