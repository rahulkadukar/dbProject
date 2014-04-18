<?php
	error_reporting(E_ALL ^ E_NOTICE);
	include 'database.php';		
	ini_set('max_execution_time', 3000);
	
	$url  = "http://steamcommunity.com/groups/Valve/memberslistxml/";
	$curl = curl_init($url);	
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);                         
	curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);                                          
	curl_setopt($curl, CURLOPT_USERAGENT, 'gameInfo');
	
	$response = curl_exec($curl);                                          
	$resultStatus = curl_getinfo($curl);                                   

	if($resultStatus['http_code'] == 200){
		$members = xml2array($response);
		foreach($members["memberList"]["members"]["steamID64"] as $member){
			$sql = "INSERT INTO `valve`.`user_list` (`steamID64`) VALUES (".$member.")";
			$result = mysql_query($sql);
		}
	}
?>