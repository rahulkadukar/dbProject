<?php
/* The one script to rule them all */
include 'database.php';		
$key 		= 'FCCAA3E90D04C71D59EAD2822B2AF90B';
$friend 	= $_GET['userData'];
//$uid = '76561198025011263';
echo $friend;
$userData = [];

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

		$userData["userData"]["visibility"]		= $response["response"]["players"]["0"]["communityvisibilitystate"];
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
			echo json_encode($userData);
			return;
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

	echo json_encode($userData);
?>