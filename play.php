<?php
    $search = $_GET['id'];  
  
	$q1 = $search;            //Just Like that.
	
	//Data From spotify
	$q2 = urlencode($q1);   
	$json = file_get_contents("https://api.spotify.com/v1/search?q={$q2}&type=track&limit=1");
    $data = json_decode($json,TRUE);
?>	
	<img src="<?php echo $data['tracks']['items']['0']['album']['images']['1']['url'];?>"> 
	<br>
	<audio controls>
  <source src="<?php echo $data['tracks']['items']['0']['preview_url'] ;?>" type="audio/mpeg"> 
</audio>
	
