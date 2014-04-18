<?php
	include 'Functions/database.php';		
	
	$time  = date("U");
	$time  -= 3000000;
	
	$sql   = "SELECT * FROM `valve`.`valve_app_master` WHERE lastUpdate < ".$time." LIMIT 0,250";
	$games = mysql_query($sql);
	while($row = mysql_fetch_row($games)){
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
					
					$sql = "INSERT INTO `valve`.`game_master` (`appID`, `name`, `metacritic`, `currency`, `price`, `recommendation`, `age`, `achievements`, `website`, `releaseDate`) VALUES ('".$appid."','".mysql_real_escape_string($gameInfo['name'])."','".$gameInfo['metacritic']['score']."','".$gameInfo['price_overview']['currency']."','".$gameInfo['price_overview']['initial']."','".$gameInfo['recommendations']['total']."','".$gameInfo['required_age']."','".$gameInfo['achievements']['total']."','".$gameInfo['website']."','".$release."')";
					$result = mysql_query($sql);
					
					if(isset($gameInfo["about_the_game"])){
						$sql = "INSERT INTO `valve`.`app_description` (`appID`, `description` ) VALUES ('".$appid."','".mysql_real_escape_string(strip_tags($gameInfo['about_the_game']))."')";
						$result = mysql_query($sql);
					}
														
					if(isset($gameInfo["dlc"])){
						foreach($gameInfo["dlc"] as $dlcInfo){
							$sql = "INSERT INTO `valve`.`dlc_list` (`appID`, `DLC` ) VALUES ('".$appid."','".$dlcInfo."')";
							$result = mysql_query($sql);
						}
					}
					
					if(isset($gameInfo["developers"])){
						foreach($gameInfo["developers"] as $developer){
							$sql = "INSERT INTO `valve`.`app_developers` (`appID`, `developer` ) VALUES ('".$appid."','".mysql_real_escape_string($developer)."')";
							$result = mysql_query($sql);
						}
					}
					
					if(isset($gameInfo["publishers"])){
						foreach($gameInfo["publishers"] as $publisher){
							$sql = "INSERT INTO `valve`.`app_publishers` (`appID`, `publisher` ) VALUES ('".$appid."','".mysql_real_escape_string($publisher)."')";
							$result = mysql_query($sql);
						}
					}
					
					if(isset($gameInfo["genres"])){
						foreach($gameInfo["genres"] as $genre){
							$sql = "INSERT INTO `valve`.`app_genres` (`appID`, `genre` ) VALUES ('".$appid."','".mysql_real_escape_string($genre['description'])."')";
							$result = mysql_query($sql);
						}
					}
					
					if(isset($gameInfo["packages"])){
						foreach($gameInfo["packages"] as $package){
							$sql = "INSERT INTO `valve`.`package_list` (`packageID`, `appID` ) VALUES ('".$package."','".$appid."')";
							$result = mysql_query($sql);
						}
					}
					
					$sql = "UPDATE `valve`.`valve_app_master` SET `lastUpdate` =  '".date('U')."' WHERE `appID` = ".$appid;
					$result = mysql_query($sql);
				}
				elseif($gameInfo["type"]=="mod"){
					$sql = "INSERT INTO `valve`.`mod_list` (`appID` ) VALUES ('".$appid."')";
					$result = mysql_query($sql);
					
					$sql = "DELETE FROM `valve`.`valve_app_master` WHERE `appID` = ".$appid;
					$result = mysql_query($sql);
				}
				elseif($gameInfo["type"]=="advertising"){
					$sql = "INSERT INTO `valve`.`advert_list` (`appID` ) VALUES ('".$appid."')";
					$result = mysql_query($sql);
					
					$sql = "DELETE FROM `valve`.`valve_app_master` WHERE `appID` = ".$appid;
					$result = mysql_query($sql);
				}
				elseif($gameInfo["type"]=="demo"){
					$sql = "INSERT INTO `valve`.`demo_list` (`appID` ) VALUES ('".$appid."')";
					$result = mysql_query($sql);
					
					$sql = "DELETE FROM `valve`.`valve_app_master` WHERE `appID` = ".$appid;
					$result = mysql_query($sql);
				}
				elseif($gameInfo["type"]=="movie"){
					$sql = "INSERT INTO `valve`.`movie_list` (`appID` ) VALUES ('".$appid."')";
					$result = mysql_query($sql);
					
					$sql = "DELETE FROM `valve`.`valve_app_master` WHERE `appID` = ".$appid;
					$result = mysql_query($sql);
				}
			}
			else{
				$sql = "INSERT INTO `valve`.`random_list` (`appID` ) VALUES ('".$appid."')";
				$result = mysql_query($sql);
				
				$sql = "DELETE FROM `valve`.`valve_app_master` WHERE `appID` = ".$appid;
				$result = mysql_query($sql);
			}
		}
	}
	
	echo "Done";
?>