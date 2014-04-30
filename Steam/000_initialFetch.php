<?php
/* This file will be used to fetch a list of all games that are currently available on Steam */
	include 'functions/database.php';	
	
	$size = 500;
	$url  = "http://api.steampowered.com/ISteamApps/GetAppList/v2";
	$curl = curl_init($url);	
		
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);                         
	curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);                                          
	curl_setopt($curl, CURLOPT_USERAGENT, 'gameList');

	$response = curl_exec($curl);                                          
	$resultStatus = curl_getinfo($curl);                                   

	if($resultStatus['http_code'] == 200){
		$new = json_decode($response,true);
		$time	= 10000000;
		$incr = 0;
		
		foreach($new["applist"]["apps"] as $game){
			if($incr % $size == 1){
				$sql = " ";
				$sql = "INSERT INTO `steam`.`app_master` (`appID`, `lastUpdate`) VALUES ";
			}
			$sql .= '("'.$game[appid].'","'.$time.'"),';
			
			if($incr % $size == 0)
				$result = $link->query(substr($sql,0,-1));

			$incr++;
		}


		if($incr % $size != 0)
			$result = $link->query(substr($sql,0,-1));
		
		$incr--;
		echo "Total number of games found ".$incr;
	}
?>