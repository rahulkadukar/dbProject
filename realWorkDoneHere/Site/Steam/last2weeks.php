<?php

$key 	= 'FCCAA3E90D04C71D59EAD2822B2AF90B';
$uid 	= $_GET['name'];
$url = "http://api.steampowered.com/IPlayerService/GetRecentlyPlayedGames/v0001/?steamid=".$uid."&key=FCCAA3E90D04C71D59EAD2822B2AF90B&format=json";
	
$curl = curl_init($url);	
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);                         
curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);                                          
curl_setopt($curl, CURLOPT_USERAGENT, 'Sample Code');
$response = curl_exec($curl);                                          
$resultStatus = curl_getinfo($curl);                                   
if($resultStatus['http_code'] == 200)
	echo $response;
?>