<?php
	error_reporting(E_ALL ^ E_NOTICE);
	include 'database.php';		
	ini_set('max_execution_time', 3000);
	
	$key  = 'FCCAA3E90D04C71D59EAD2822B2AF90B';
	$uid  = '76561197960563532';
	$time = date("U");
	$time -= 3000000;
	
    {	
		$appid = "234900";
		$url = "http://127.0.0.1/Test/Steam/Functions/Sample.txt";
		$curl = curl_init($url);	
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);                         
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);                                          
		curl_setopt($curl, CURLOPT_USERAGENT, 'gameList');

		$response = curl_exec($curl);                                          
		$resultStatus = curl_getinfo($curl);   
		$number = 1;
		
		if($resultStatus['http_code'] == 200){
			//var_dump($response);
			$new = xml2array($response);
			//var_dump($new["playerstats"]["achievements"]["achievement"]);
			foreach($new["playerstats"]["achievements"]["achievement"] as $list){
				if($list['iconOpen']){
					$sql = "INSERT INTO `steam`.`achievement_master` (`appID`, `achNumber`, `iconClosed`, `iconOpen`, `name`, `description`, `apiname`) VALUES (".$appid.",".$number.",'".$list['iconClosed']."','".$list['iconOpen']."','".mysql_real_escape_string($list['name'])."','".mysql_real_escape_string($list['description'])."','".mysql_real_escape_string($list['apiname'])."')";
					$result = mysql_query($sql);
					$number++;
				}
			}
			
			$sql = "UPDATE `steam`.`achievement_links` SET `lastUpdate` =  '".date('U')."' WHERE `appID` = ".$appid;
			$result = mysql_query($sql);
		}
	}
?>