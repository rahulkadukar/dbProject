<?php
	include 'database.php';	

	$key  = 'FCCAA3E90D04C71D59EAD2822B2AF90B';
	$uid  = '76561197960563532';
	$url  = "http://steamcommunity.com/id/demomenz/games?tab=all&xml=1";
	$curl = curl_init($url);	

	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);                         
	curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);                                          
	curl_setopt($curl, CURLOPT_USERAGENT, 'gameList');

	$response = curl_exec($curl);                                          
	$resultStatus = curl_getinfo($curl);                                   

	if($resultStatus['http_code'] == 200){
		$new = xml2array($response);
		
		foreach($new["gamesList"]["games"]["game"] as $game){	
			if (array_key_exists('statsLink', $game)){
				$sql 	= "INSERT INTO `steam`.`achievement_links` (`appID`, `url`) VALUES (".$game['appID'].",'".$game['statsLink']."')";
				$result = mysql_query($sql);	
			}
		}
	}
?>
