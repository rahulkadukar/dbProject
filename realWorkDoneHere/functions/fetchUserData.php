<?php
/* The one script to rule them all */
include 'database.php';		
$key 	= 'FCCAA3E90D04C71D59EAD2822B2AF90B';
$count = 0;
$aql = "SELECT `steamID` FROM `user_master` WHERE `lastUpdate` = 0 LIMIT 0,25";
$dataFrom = $link->query($aql);
while($rows = $dataFrom->fetch_array())
{
$uid = $rows[0];
//$uid = '76561198025011263';
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
	
		$sql = "INSERT IGNORE INTO `valve`.`user_data` (`steamID`, `realName`, `personaname`, `location`, `profileurl`, `timecreated`, `avatarfull`, `type`) VALUES ('".$userData["userData"]["steamid"]."','".$userData["userData"]["realname"]."','".$userData["userData"]["personaname"]."','".$userData["userData"]["location"]."','".$userData["userData"]["profileurl"]."','".$userData["userData"]["timecreated"]."','".$userData["userData"]["avatarfull"]."','2');";
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
	$sql = "INSERT IGNORE INTO `valve`.`user_data` (`steamID`, `realName`, `personaname`, `location`, `profileurl`, `timecreated`, `avatarfull`, `type`) VALUES ('".$userData["userData"]["steamid"]."','".$userData["userData"]["realname"]."','".$userData["userData"]["personaname"]."','".$userData["userData"]["location"]."','".$userData["userData"]["profileurl"]."','".$userData["userData"]["timecreated"]."','".$userData["userData"]["avatarfull"]."','1');";
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
	//echo json_encode($userData);
}
}

echo $count." entries were updated";
?>