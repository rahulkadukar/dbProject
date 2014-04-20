<?php
	error_reporting(E_ALL ^ E_NOTICE);
	include 'functions/database.php';		
	ini_set('max_execution_time', 3000);
	
	$time  = date("U");
	$time  -= 3000000;
	
	$sql   = "SELECT * FROM `valve`.`valve_app_master` WHERE lastUpdate < ".$time." LIMIT 0,200";
	$gameData = $link->query($sql);
	while($row = $gameData->fetch_array()){
		$appid = $row[0];
		$url  = "http://store.steampowered.com/api/appdetails/?appids=".$appid;
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
					
					$sql = "INSERT INTO `valve`.`game_master` (`appID`, `name`, `metacritic`, `currency`, `price`, `recommendation`, `age`, `achievements`, `website`, `releaseDate`) VALUES ('".$appid."','".$link->real_escape_string($gameInfo['name'])."','".$gameInfo['metacritic']['score']."','".$gameInfo['price_overview']['currency']."','".$gameInfo['price_overview']['initial']."','".$gameInfo['recommendations']['total']."','".$gameInfo['required_age']."','".$gameInfo['achievements']['total']."','".$gameInfo['website']."','".$release."')";
					$result = $link->query($sql);
					
					if(isset($gameInfo["about_the_game"])){
						$sql = "INSERT INTO `valve`.`game_description` (`appID`, `description` ) VALUES ('".$appid."','".$link->real_escape_string(strip_tags($gameInfo['about_the_game']))."')";
						$result = $link->query($sql);
					}
														
					if(isset($gameInfo["dlc"])){
						foreach($gameInfo["dlc"] as $dlcInfo){
							$sql = "INSERT INTO `valve`.`dlc_list` (`appID`, `DLC` ) VALUES ('".$appid."','".$dlcInfo."')";
							$result = $link->query($sql);
						}
					}
					
					if(isset($gameInfo["developers"])){
						foreach($gameInfo["developers"] as $developer){
							$sql = "INSERT INTO `valve`.`game_developers` (`appID`, `developer` ) VALUES ('".$appid."','".$link->real_escape_string($developer)."')";
							$result = $link->query($sql);
						}
					}
					
					if(isset($gameInfo["publishers"])){
						foreach($gameInfo["publishers"] as $publisher){
							$sql = "INSERT INTO `valve`.`game_publishers` (`appID`, `publisher` ) VALUES ('".$appid."','".$link->real_escape_string($publisher)."')";
							$result = $link->query($sql);
						}
					}
					
					if(isset($gameInfo["genres"])){
						foreach($gameInfo["genres"] as $genre){
							$sql = "INSERT INTO `valve`.`game_genres` (`appID`, `genre` ) VALUES ('".$appid."','".$link->real_escape_string($genre['description'])."')";
							$result = $link->query($sql);
						}
					}
					
					$sql = "UPDATE `valve`.`valve_app_master` SET `lastUpdate` =  '".date('U')."' WHERE `appID` = ".$appid;
					$result = $link->query($sql);
				}
				else{
					$sql = "INSERT INTO `valve`.`random_list` (`appID` ) VALUES ('".$appid."')";
					$result = $link->query($sql);
					
					$sql = "DELETE FROM `valve`.`valve_app_master` WHERE `appID` = ".$appid;
					$result = $link->query($sql);
				}
			}
			else{
				$sql = "INSERT INTO `valve`.`false_data` (`appID` ) VALUES ('".$appid."')";
				$result = $link->query($sql);
				
				$sql = "DELETE FROM `valve`.`valve_app_master` WHERE `appID` = ".$appid;
				$result = $link->query($sql);
			}
		}
	}
?>