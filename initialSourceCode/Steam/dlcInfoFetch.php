<?php
	error_reporting(E_ALL ^ E_NOTICE);
	include 'Functions/database.php';		
	ini_set('max_execution_time', 3000);
	
	$time  = date("U");
	$time  -= 3000000;
	
	$sql   = "SELECT * FROM `steam`.`dlc_list` WHERE lastUpdate < ".$time." LIMIT 0,50";
	$games = mysql_query($sql);
	while($row = mysql_fetch_row($games)){
		$appid = $row[1];
	
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
				if($gameInfo["type"]=="dlc" ){
					if(!$gameInfo["release_date"]["coming_soon"])
						$release = $gameInfo["release_date"]["date"];
					else						
						$release = "Coming Soon";
					
					$sql = "INSERT INTO `steam`.`dlc_master` (`appID`, `name`, `currency`, `price`, `age`, `website`, `releaseDate`) VALUES ('".$appid."','".mysql_real_escape_string($gameInfo['name'])."','".$gameInfo['price_overview']['currency']."','".$gameInfo['price_overview']['initial']."','".$gameInfo['required_age']."','".$gameInfo['website']."','".$release."')";
					$result = mysql_query($sql);
					
					if(isset($gameInfo["about_the_game"])){
						$sql = "INSERT INTO `steam`.`app_description` (`appID`, `description` ) VALUES ('".$appid."','".mysql_real_escape_string(strip_tags($gameInfo['about_the_game']))."')";
						$result = mysql_query($sql);
					}
					
					if(isset($gameInfo["developers"])){
						foreach($gameInfo["developers"] as $developer){
							$sql = "INSERT INTO `steam`.`app_developers` (`appID`, `developer` ) VALUES ('".$appid."','".mysql_real_escape_string($developer)."')";
							$result = mysql_query($sql);
						}
					}
					
					if(isset($gameInfo["publishers"])){
						foreach($gameInfo["publishers"] as $publisher){
							$sql = "INSERT INTO `steam`.`app_publishers` (`appID`, `publisher` ) VALUES ('".$appid."','".mysql_real_escape_string($publisher)."')";
							$result = mysql_query($sql);
						}
					}
					
					if(isset($gameInfo["genres"])){
						foreach($gameInfo["genres"] as $genre){
							$sql = "INSERT INTO `steam`.`app_genres` (`appID`, `genre` ) VALUES ('".$appid."','".mysql_real_escape_string($genre['description'])."')";
							$result = mysql_query($sql);
						}
					}
					
					if(isset($gameInfo["packages"])){
						foreach($gameInfo["packages"] as $package){
							$sql = "INSERT INTO `steam`.`package_list` (`packageID`, `appID` ) VALUES ('".$package."','".$appid."')";
							$result = mysql_query($sql);
						}
					}
					
					$sql = "UPDATE `steam`.`dlc_list` SET `lastUpdate` =  '".date('U')."' WHERE `DLC` = ".$appid;
					$result = mysql_query($sql);
				}
			}
			else{
				$sql = "INSERT INTO `steam`.`missing_dlc` (`appID` ) VALUES ('".$appid."')";
				$result = mysql_query($sql);
				
				$sql = "DELETE FROM `steam`.`dlc_list` WHERE `DLC` = ".$appid;
				$result = mysql_query($sql);
			}
		}
	}
?>