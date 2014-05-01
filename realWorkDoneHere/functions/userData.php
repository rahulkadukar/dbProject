<?php
/* The one script to rule them all */
include 'database.php';		
$key 	= 'FCCAA3E90D04C71D59EAD2822B2AF90B';
$uid 	= $_GET['steamID'];
//$uid = '76561198060220242';
$userData = [];

$sql = "SELECT * FROM `user_master` WHERE `steamID` = ".$uid. " AND `lastUpdate` <> 0";
$dataFrom = $link->query($sql);
if($dataFrom->fetch_array()){
	$aql = "SELECT * FROM `user_data` WHERE `steamID` = ".$uid;
	$result = $link->query($aql);
	while($rows = $result->fetch_array()){
		$userData["userData"] = [];
		$userData["userData"]["avatarfull"] 	= $rows["avatarfull"];
		$userData["userData"]["location"] 		= $rows["location"];
		$userData["userData"]["personaname"] 	= $rows["personaname"];
		$userData["userData"]["realname"]		= $rows["realname"];
		$userData["userData"]["timecreated"]	= $rows["timecreated"];
		$userData["userData"]["steamid"] 		= $rows["steamID"];
		$userData["userData"]["profileurl"] 	= $rows["profileurl"];
	}
	
	$aql = "SELECT * FROM `user_games` WHERE `steamID` = ".$uid;
	$result = $link->query($aql);
	$i = 0;
	$userData["gameData"] = [];
	while($rows = $result->fetch_array()){
		$userData["gameData"][$i]["appid"] 		= $rows["appID"];
		$userData["gameData"][$i]["playtime_forever"] 	= $rows["playtime"];
		$i++;
	}
	
	$aql = "SELECT * FROM `user_friends` WHERE `steamID` = ".$uid;
	$result = $link->query($aql);
	$i = 0;
	$userData["friendData"] = [];
	while($rows = $result->fetch_array()){
		$userData["friendData"][$i]["steamid"] 		= $rows["friendID"];
		$userData["friendData"][$i]["relationship"]	= "friend";
		$userData["friendData"][$i]["friend_since"] = $rows["friendSince"];
		$friends[$i] = $rows["friendID"];
		$i++;
	}
}
else{
	$final = 0;
	$url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002?steamids=".$uid."&key=".$key;
	$curl = curl_init($url);	
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);                         
	curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);                                            
	curl_setopt($curl, CURLOPT_USERAGENT, 'Fetch User');
	$response = curl_exec($curl);                                          
	$resultStatus = curl_getinfo($curl); 
	if($resultStatus['http_code'] == 200){
		$response = json_decode($response,true);
		$userData["userData"] = [];
		$userData["userData"]["avatarfull"] 	= "Not provided";
		$userData["userData"]["location"] 		= "Unknown";
		$userData["userData"]["personaname"] 	= "Not provided";
		$userData["userData"]["realname"]		= "Not provided";

		$userData["userData"]["steamid"] 		= $response["response"]["players"]["0"]["steamid"];
		$userData["userData"]["profileurl"] 	= $response["response"]["players"]["0"]["profileurl"];
		if(array_key_exists("timecreated",$response["response"]["players"]["0"]))
			$userData["userData"]["timecreated"] 	= $response["response"]["players"]["0"]["timecreated"];
		if(array_key_exists("avatarfull",$response["response"]["players"]["0"]))
			$userData["userData"]["avatarfull"] 	= $response["response"]["players"]["0"]["avatarfull"];
		if(array_key_exists("loccountrycode",$response["response"]["players"]["0"]))
			$userData["userData"]["location"] 		= $response["response"]["players"]["0"]["loccountrycode"];
		if(array_key_exists("personaname",$response["response"]["players"]["0"]))
			$userData["userData"]["personaname"] 	= $response["response"]["players"]["0"]["personaname"];
		if(array_key_exists("realname",$response["response"]["players"]["0"]))
			$userData["userData"]["realname"] 	= $response["response"]["players"]["0"]["realname"];
		
		if($response["response"]["players"]["0"]["communityvisibilitystate"] == 1){
			$sql = "UPDATE `valve`.`user_master` SET `lastUpdate` = '".date('U')."' WHERE `steamID` = ".$uid;
			$result = $link->query($sql);
	
			$sql = "INSERT IGNORE INTO `valve`.`user_data` (`steamID`, `realName`, `personaname`, `location`, `profileurl`, `timecreated`, `avatarfull`, `type`) VALUES ('".$userData["userData"]["steamid"]."','".$link->real_escape_string($userData["userData"]["realname"])."','".$link->real_escape_string($userData["userData"]["personaname"])."','".$userData["userData"]["location"]."','".$link->real_escape_string($userData["userData"]["profileurl"])."','".$userData["userData"]["timecreated"]."','".$userData["userData"]["avatarfull"]."','2');";
			$result = $link->query($sql);

			$count++;
			echo json_encode($userData);
			return;
		}
		else{
			$final++;
		}
	}


	$url = "http://api.steampowered.com/IPlayerService/GetOwnedGames/v1?steamid=".$uid."&key=".$key."&include_played_free_games=1";	
	$curl = curl_init($url);	
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);                         
	curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);                                            
	curl_setopt($curl, CURLOPT_USERAGENT, 'Fetch Games');
	$response = curl_exec($curl);                                          
	$resultStatus = curl_getinfo($curl);    
	if($resultStatus['http_code'] == 200){
		$response = json_decode($response,true);
		$userData["gameData"] = [];
		$userData["gameData"] = $response["response"]["games"];
		$final++;
	}

	$url = "http://api.steampowered.com/ISteamUser/GetFriendList/v1?steamid=".$uid."&key=".$key;
	$curl = curl_init($url);	
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);                         
	curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);                                            
	curl_setopt($curl, CURLOPT_USERAGENT, 'Fetch Games');
	$response = curl_exec($curl);                                          
	$resultStatus = curl_getinfo($curl);    

	if($resultStatus['http_code'] == 200){
		$response = json_decode($response,true);
		$userData["friendData"] = [];
		$userData["friendData"] = $response["friendslist"]["friends"];
		$final++;
	}
	
	if($final == 3){
		$sql = "INSERT IGNORE INTO `valve`.`user_data` (`steamID`, `realName`, `personaname`, `location`, `profileurl`, `timecreated`, `avatarfull`, `type`) VALUES ('".$userData["userData"]["steamid"]."','".$link->real_escape_string($userData["userData"]["realname"])."','".$link->real_escape_string($userData["userData"]["personaname"])."','".$userData["userData"]["location"]."','".$link->real_escape_string($userData["userData"]["profileurl"])."','".$userData["userData"]["timecreated"]."','".$userData["userData"]["avatarfull"]."','1');";
		$result = $link->query($sql);
	
		$sql = "INSERT INTO `valve`.`user_games` (`steamID`, `appID`, `playtime`) VALUES ";
		foreach($userData["gameData"] as $x){
			$sql .= "('".$userData["userData"]["steamid"]."','".$x["appid"]."','".$x["playtime_forever"]."'),";
		}
		$result = $link->query(substr($sql,0,-1));
	
		$sql = "INSERT INTO `valve`.`user_friends` (`steamID`, `friendID`, `friendSince`) VALUES ";
		$tql = "INSERT IGNORE INTO `valve`.`user_master` (`steamID`, `lastUpdate`) VALUES ";
		$i = 0;
		foreach($userData["friendData"] as $x){
			$friends[$i] = $x["steamid"];
			$sql .= "('".$userData["userData"]["steamid"]."','".$x["steamid"]."','".$x["friend_since"]."'),";
			$tql .= "('".$x["steamid"]."','0'),";
			$i++;
		}
		$result = $link->query(substr($sql,0,-1));
		$result = $link->query(substr($tql,0,-1));
	
		$tql = "SELECT `steamID` FROM `valve`.`user_master` WHERE `steamID` = ".$uid;
		$result = $link->query($tql);
		if($result->fetch_array()){
			$sql = "UPDATE `valve`.`user_master` SET `lastUpdate` = '".date('U')."' WHERE `steamID` = ".$uid;
			$result = $link->query($sql);
		}
		else{
			$sql = "INSERT IGNORE INTO `valve`.`user_master` (`steamID`, `lastUpdate`) VALUES ('".$uid."','".date('U')."')";
			$result = $link->query($sql);
		}
	}
}

if(count($userData["friendData"])>=1){
	$aql = "SELECT `steamID` FROM `user_master` WHERE `lastUpdate` = 0 AND `steamID` IN ( SELECT `friendID` FROM `user_friends` WHERE `steamID` = ".$uid.")";
	$dataFrom = $link->query($aql);
	while($rows = $dataFrom->fetch_array()){
		$uid = $rows[0];
		$userData = [];
		$final = 0;
		$url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002?steamids=".$uid."&key=".$key;
		$curl = curl_init($url);	
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);                         
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);                                            
		curl_setopt($curl, CURLOPT_USERAGENT, 'Fetch User');
		$response = curl_exec($curl);                                          
		$resultStatus = curl_getinfo($curl); 
		if($resultStatus['http_code'] == 200){
			$response = json_decode($response,true);
			$userData["userData"] = [];
			$userData["userData"]["avatarfull"] 	= "Not provided";
			$userData["userData"]["location"] 		= "Unknown";
			$userData["userData"]["personaname"] 	= "Not provided";
			$userData["userData"]["realname"]		= "Not provided";

			$userData["userData"]["steamid"] 		= $response["response"]["players"]["0"]["steamid"];
			$userData["userData"]["profileurl"] 	= $response["response"]["players"]["0"]["profileurl"];
			if(array_key_exists("timecreated",$response["response"]["players"]["0"]))
				$userData["userData"]["timecreated"] 	= $response["response"]["players"]["0"]["timecreated"];
			if(array_key_exists("avatarfull",$response["response"]["players"]["0"]))
				$userData["userData"]["avatarfull"] 	= $response["response"]["players"]["0"]["avatarfull"];
			if(array_key_exists("loccountrycode",$response["response"]["players"]["0"]))
				$userData["userData"]["location"] 		= $response["response"]["players"]["0"]["loccountrycode"];
			if(array_key_exists("personaname",$response["response"]["players"]["0"]))
				$userData["userData"]["personaname"] 	= $response["response"]["players"]["0"]["personaname"];
			if(array_key_exists("realname",$response["response"]["players"]["0"]))
				$userData["userData"]["realname"] 	= $response["response"]["players"]["0"]["realname"];
			$final++;
			
			if($response["response"]["players"]["0"]["communityvisibilitystate"] == 1){
				
				$sql = "UPDATE `valve`.`user_master` SET `lastUpdate` = '".date('U')."' WHERE `steamID` = ".$uid;
				$result = $link->query($sql);
			
				$sql = "INSERT IGNORE INTO `valve`.`user_data` (`steamID`, `realName`, `personaname`, `location`, `profileurl`, `timecreated`, `avatarfull`, `type`) VALUES ('".$userData["userData"]["steamid"]."','".$link->real_escape_string($userData["userData"]["realname"])."','".$link->real_escape_string($userData["userData"]["personaname"])."','".$userData["userData"]["location"]."','".$link->real_escape_string($userData["userData"]["profileurl"])."','".$userData["userData"]["timecreated"]."','".$userData["userData"]["avatarfull"]."','2');";
				$result = $link->query($sql);

				$count++;
				continue;
			}
			
		}


		$url = "http://api.steampowered.com/IPlayerService/GetOwnedGames/v1?steamid=".$uid."&key=".$key."&include_played_free_games=1";	
		$curl = curl_init($url);	
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);                         
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);                                            
		curl_setopt($curl, CURLOPT_USERAGENT, 'Fetch Games');
		$response = curl_exec($curl);                                          
		$resultStatus = curl_getinfo($curl);    
		if($resultStatus['http_code'] == 200){
			$response = json_decode($response,true);
			$userData["gameData"] = [];
			$userData["gameData"] = $response["response"]["games"];
			$final++;
		}

		$url = "http://api.steampowered.com/ISteamUser/GetFriendList/v1?steamid=".$uid."&key=".$key;
		$curl = curl_init($url);	
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);                         
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);                                            
		curl_setopt($curl, CURLOPT_USERAGENT, 'Fetch Games');
		$response = curl_exec($curl);                                          
		$resultStatus = curl_getinfo($curl);    

		if($resultStatus['http_code'] == 200){
			$response = json_decode($response,true);
			$userData["friendData"] = [];
			$userData["friendData"] = $response["friendslist"]["friends"];
			$final++;
		}

		if($final == 3){
			$sql = "INSERT IGNORE INTO `valve`.`user_data` (`steamID`, `realName`, `personaname`, `location`, `profileurl`, `timecreated`, `avatarfull`, `type`) VALUES ('".$userData["userData"]["steamid"]."','".$link->real_escape_string($userData["userData"]["realname"])."','".$link->real_escape_string($userData["userData"]["personaname"])."','".$userData["userData"]["location"]."','".$link->real_escape_string($userData["userData"]["profileurl"])."','".$userData["userData"]["timecreated"]."','".$userData["userData"]["avatarfull"]."','1');";
			$result = $link->query($sql);
			
			$sql = "INSERT INTO `valve`.`user_games` (`steamID`, `appID`, `playtime`) VALUES ";
			foreach($userData["gameData"] as $x){
				$sql .= "('".$userData["userData"]["steamid"]."','".$x["appid"]."','".$x["playtime_forever"]."'),";
			}
			$result = $link->query(substr($sql,0,-1));
			
			$sql = "INSERT INTO `valve`.`user_friends` (`steamID`, `friendID`, `friendSince`) VALUES ";
			$tql = "INSERT IGNORE INTO `valve`.`user_master` (`steamID`, `lastUpdate`) VALUES ";
			foreach($userData["friendData"] as $x){
				$sql .= "('".$userData["userData"]["steamid"]."','".$x["steamid"]."','".$x["friend_since"]."'),";
				$tql .= "('".$x["steamid"]."','0'),";
			}
			$result = $link->query(substr($sql,0,-1));
			$result = $link->query(substr($tql,0,-1));
			
			$tql = "SELECT `steamID` FROM `valve`.`user_master` WHERE `steamID` = ".$uid;
			$result = $link->query($tql);
			if($result->fetch_array()){
				$sql = "UPDATE `valve`.`user_master` SET `lastUpdate` = '".date('U')."' WHERE `steamID` = ".$uid;
				$result = $link->query($sql);
			}
			else{
				$sql = "INSERT IGNORE INTO `valve`.`user_master` (`steamID`, `lastUpdate`) VALUES ('".$uid."','".date('U')."')";
				$result = $link->query($sql);
			}
			$count++;
		}
	}

	$sql = "SELECT * FROM user_data WHERE steamID IN (".implode(",",$friends).")";
	$result = $link->query($sql);
	while($rows = $result->fetch_array()){
		$extraData["userData"][$rows["steamID"]]["avatarfull"] 	= $rows["avatarfull"];
		$extraData["userData"][$rows["steamID"]]["location"] 	= $rows["location"];
		$extraData["userData"][$rows["steamID"]]["personaname"] = $rows["personaname"];
		$extraData["userData"][$rows["steamID"]]["realname"]	= $rows["realname"];
		$extraData["userData"][$rows["steamID"]]["timecreated"]	= $rows["timecreated"];
		$extraData["userData"][$rows["steamID"]]["steamid"] 	= $rows["steamID"];
		$extraData["userData"][$rows["steamID"]]["profileurl"] 	= $rows["profileurl"];
	}
	
	$sql = "SELECT * FROM user_games WHERE steamID IN (".implode(",",$friends).")";
	$i = 0;	
	$result = $link->query($sql);
	while($rows = $result->fetch_array()){
		$extraData["gameData"][$rows["steamID"]][$i]["appID"] 		= $rows["appID"];
		$extraData["gameData"][$rows["steamID"]][$i]["playtime"] 	= $rows["playtime"];
		$i++;
	}

	
	$sql = "SELECT * FROM user_friends WHERE steamID IN (".implode(",",$friends).")";
	$result = $link->query($sql);
	$i = 0;
	while($rows = $result->fetch_array()){
		$extraData["friendData"][$rows["steamID"]][$i]["friend"] 	= $rows["friendID"];
		$i++;
	}
}
$userData["extraData"] = [];
$userData["extraData"] = $extraData;

echo json_encode($userData);
?>