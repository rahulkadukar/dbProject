<?php
	error_reporting(E_ALL ^ E_NOTICE);
	include 'database.php';		
	ini_set('max_execution_time', 3000);
	$time = date("U");
	$time -= 3000000;
	
	$sql = "SELECT * FROM `valve`.`user_details` WHERE lastUpdate < ".$time." AND visibility = 3 LIMIT 0,50";
	$result = mysql_query($sql);
	while($row = mysql_fetch_row($result)){	
		$url = "http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=FCCAA3E90D04C71D59EAD2822B2AF90B&include_played_free_games=1&steamid=".$row[0];
		
		$curl = curl_init($url);	
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);                         
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);                                          
		curl_setopt($curl, CURLOPT_USERAGENT, 'gameInfo');
		
		$response = curl_exec($curl);                                          
		$resultStatus = curl_getinfo($curl);
		if($resultStatus['http_code'] == 200){
			$members = json_decode($response,true);
			foreach($members["response"]["games"] as $game){
				if($game["playtime_forever"]){
					$time = ($game["playtime_forever"]);
					$sql = "INSERT INTO `valve`.`game_details` (`steamID64`, `appID`, `time` ) VALUES ('".$row[0]."','".$game["appid"]."','".$time."')";
					$insert = mysql_query($sql);	
				}
			}
			$gameCount = $members["response"]["game_count"];
			$sql = "UPDATE `valve`.`user_details` SET `gameCount` = '".$gameCount."',`lastUpdate` = '".date('U')."' WHERE `steamID64` = ".$row[0];
			$insert = mysql_query($sql);
		}
	}
?>