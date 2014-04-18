<?php
	error_reporting(E_ALL ^ E_NOTICE);
	include 'database.php';		
	
	$time  = date("U");
	$time  -= 3000000;
	
	{
		$appid = '10540';
	
		$url  = "http://store.steampowered.com/api/appdetails/?appids=".$appid;
		$url = "http://127.0.0.1/Test/Steam/Sample.txt";
		$curl = curl_init($url);	
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);                         
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);                                          
		curl_setopt($curl, CURLOPT_USERAGENT, 'gameInfo');
		
		$response = curl_exec($curl);                                          
		$resultStatus = curl_getinfo($curl);                                   

		if($resultStatus['http_code'] == 200){
			$new = json_decode($response,true);
			if($new[$appid]["success"]){
				$gameInfo = $new[$appid]["data"];
				if($gameInfo["type"]=="game"){
					if(!$gameInfo["release_date"]["coming_soon"])
						$release = $gameInfo["release_date"]["date"];
					else						
						$release = "Coming Soon";
					
					$sql = "INSERT INTO `steam`.`game_master` (`appID`, `name`, `metacritic`, `currency`, `price`, `recommendation`, `age`, `achievements`, `website`, `releaseDate`) VALUES ('".$appid."','".mysql_real_escape_string($gameInfo['name'])."','".$gameInfo['metacritic']['score']."','".$gameInfo['price_overview']['currency']."','".$gameInfo['price_overview']['initial']."','".$gameInfo['recommendations']['total']."','".$gameInfo['required_age']."','".$gameInfo['achievements']['total']."','".$gameInfo['website']."','".$release."')";
					$result = mysql_query($sql);
					echo $sql;
					
					if(isset($gameInfo["about_the_game"])){
						$sql = "INSERT INTO `steam`.`game_description` (`appID`, `description` ) VALUES ('".$appid."','".mysql_real_escape_string(strip_tags($gameInfo['about_the_game']))."')";
						$result = mysql_query($sql);
					}
														
					if(isset($gameInfo["dlc"])){
						foreach($gameInfo["dlc"] as $dlcInfo){
							$sql = "INSERT INTO `steam`.`dlc_list` (`appID`, `DLC` ) VALUES ('".$appid."','".$dlcInfo."')";
							$result = mysql_query($sql);
						}
					}
					
					if(isset($gameInfo["developers"])){
						foreach($gameInfo["developers"] as $developer){
							$sql = "INSERT INTO `steam`.`game_developers` (`appID`, `developer` ) VALUES ('".$appid."','".mysql_real_escape_string($developer)."')";
							$result = mysql_query($sql);
						}
					}
					
					if(isset($gameInfo["publishers"])){
						foreach($gameInfo["publishers"] as $publisher){
							$sql = "INSERT INTO `steam`.`game_publishers` (`appID`, `publisher` ) VALUES ('".$appid."','".mysql_real_escape_string($publisher)."')";
							$result = mysql_query($sql);
						}
					}
					
					if(isset($gameInfo["genres"])){
						foreach($gameInfo["genres"] as $genre){
							$sql = "INSERT INTO `steam`.`game_genres` (`appID`, `genre` ) VALUES ('".$appid."','".mysql_real_escape_string($genre['description'])."')";
							$result = mysql_query($sql);
						}
					}
					
					if(isset($gameInfo["packages"])){
						foreach($gameInfo["packages"] as $package){
							$sql = "INSERT INTO `steam`.`package_games` (`packageID`, `appID` ) VALUES ('".$package."','".$appid."')";
							$result = mysql_query($sql);
						}
					}
					
					$sql = "UPDATE `steam`.`valve_user_games` SET `lastUpdate` =  '".date('U')."' WHERE `appID` = ".$appid;
					$result = mysql_query($sql);
				}
			}	
		}
	}
?>