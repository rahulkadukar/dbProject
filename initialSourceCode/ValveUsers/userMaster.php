<?php
	error_reporting(E_ALL ^ E_NOTICE);
	include 'database.php';		
	ini_set('max_execution_time', 3000);
	$time = date("U");
	$time -= 3000000;
	
	$sql = "SELECT * FROM `valve`.`user_list` WHERE lastUpdate < ".$time." LIMIT 0,50";
	$result = mysql_query($sql);
	while($row = mysql_fetch_row($result)){	
		$url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=FCCAA3E90D04C71D59EAD2822B2AF90B&steamids=".$row[0];
		$curl = curl_init($url);	
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);                         
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);                                          
		curl_setopt($curl, CURLOPT_USERAGENT, 'gameInfo');
		
		$response = curl_exec($curl);                                          
		$resultStatus = curl_getinfo($curl);
		if($resultStatus['http_code'] == 200){
			$members = json_decode($response,true);
			if($members["response"]["players"][0]["communityvisibilitystate"] == 3)
				$visible = 3;
			else
				$visible = 1;
				
			if($members["response"]["players"][0]["avatarfull"])
				$avatar = $members["response"]["players"][0]["avatarfull"];
			else
				$avatar = 'Not provided';
			
			if($members["response"]["players"][0]["timecreated"])
				$time = $members["response"]["players"][0]["timecreated"];
			else
				$time = 0;
			
			if($members["response"]["players"][0]["realname"])
				$name = $members["response"]["players"][0]["realname"];
			else
				$name = 'Not provided';
			
			if($members["response"]["players"][0]["loccountrycode"])
				$country = $members["response"]["players"][0]["loccountrycode"];
			else
				$country = "XX";
			
			$sql = "INSERT INTO `valve`.`user_details` (`steamID64`, `avatar`, `time`, `realname`, `country`, `visibility`) VALUES ('".$row[0]."','".$avatar."','".$time."','".$name."','".$country."','".$visible."')";
			$insert = mysql_query($sql);
			
			$sql = "UPDATE `valve`.`user_list` SET `lastUpdate` =  '".date('U')."' WHERE `steamID64` = ".$row[0];
			$insert = mysql_query($sql);
		}
	}
?>