<?php
	include 'database.php';	
	
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
			$sql 	= "INSERT INTO `valve`.`valve_app_master` (`appID`, `lastUpdate`) VALUES (".$game['appid'].",".$time.");";
			$result = mysql_query($sql);
			$incr++;
		}
		
		echo $incr;
	}
?>