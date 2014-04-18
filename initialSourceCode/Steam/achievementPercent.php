<?php
	error_reporting(E_ALL ^ E_NOTICE);
	include 'Functions/database.php';		
	ini_set('max_execution_time', 3000);
	
	$time = date("U");
	$time -= 3000000;
	
	$sql   = "SELECT * FROM `steam`.`achievement_status` WHERE lastUpdate < ".$time." LIMIT 0,10";
	$games = mysql_query($sql);
	while($rowo = mysql_fetch_row($games)){	
		$url = "http://api.steampowered.com/ISteamUserStats/GetGlobalAchievementPercentagesForApp/v0002/?gameid=".$rowo[0]."&format=xml";
		$curl = curl_init($url);	
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);                         
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);                                          
		curl_setopt($curl, CURLOPT_USERAGENT, 'gameList');

		$response = curl_exec($curl);                                          
		$resultStatus = curl_getinfo($curl);   
		$number = 0;
		$count = 0;
		
		if($resultStatus['http_code'] == 200){
			$new = xml2array($response);
			$sql = "SELECT * FROM `achievement_master` WHERE `appID` = ".$rowo[0];
			$result = mysql_query($sql);
			while($row = mysql_fetch_row($result)){	
				$ach[$row[1]] = strtoupper($row[6]);
				$count++;
			}
			foreach($new["achievementpercentages"]["achievements"]["achievement"] as $ac){
				$percent = round($ac['percent'], 3);
				$key = array_search(strtoupper($ac["name"]), $ach);
				if($key){
					$sql = "INSERT INTO `achievement_percent` (`appID`, `achNumber`, `percent`) VALUES (".$rowo[0].",".$key.",".$percent.")";
					$result = mysql_query($sql);
					$number++;
				}
			}
			if($number == $count){
				$sql = "UPDATE `steam`.`achievement_status` SET `lastUpdate` =  '".date('U')."' WHERE `appID` = ".$rowo[0];
				$result = mysql_query($sql);
			}
			else{
				$sql   = "SELECT COUNT(*) FROM `steam`.`achievement_master` WHERE `appID` = ".$rowo[0];
				$games = mysql_query($sql);
				while($row = mysql_fetch_row($games)){	
					$master = $row[0];
				}
				
				$sql   = "SELECT COUNT(*) FROM `steam`.`achievement_percent` WHERE `appID` = ".$rowo[0];
				$games = mysql_query($sql);
				while($row = mysql_fetch_row($games)){	
					$percent = $row[0];
				}
				
				if($percent == $master){
					$sql = "UPDATE `steam`.`achievement_status` SET `lastUpdate` =  '".date('U')."' WHERE `appID` = ".$rowo[0];
					$result = mysql_query($sql);
				}
			}
		}
	}
?>