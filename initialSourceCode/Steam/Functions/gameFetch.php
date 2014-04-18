<?php
	include 'database.php';	

	$key  = 'FCCAA3E90D04C71D59EAD2822B2AF90B';
	$uid  = '76561197960563532';
	$url  = "http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?&include_played_free_games=1&steamid=".$uid."&key=".$key."&format=json";	
	$curl = curl_init($url);	

	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);                         
	curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);                                          
	curl_setopt($curl, CURLOPT_USERAGENT, 'gameList');

	$response = curl_exec($curl);                                          
	$resultStatus = curl_getinfo($curl);                                   

	if($resultStatus['http_code'] == 200){
		$new = json_decode($response,true);
		
		$sql 	= "TRUNCATE `valve`.`valve_user_games`";
		$result = mysql_query($sql);
		
		foreach($new["response"]["games"] as $game){	
			$sql 	= "INSERT INTO `valve`.`valve_user_games` (`appID`) VALUES (".$game['appid'].");";
			$result = mysql_query($sql);
		}
		echo $response;
	}
?>