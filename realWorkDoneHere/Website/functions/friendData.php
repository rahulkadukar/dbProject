<?php
$key 	= 'FCCAA3E90D04C71D59EAD2822B2AF90B';
$uid 	= $_GET['steamID'];

$url = "http://api.steampowered.com/ISteamUser/GetFriendList/v1?steamid=".$uid."&key=".$key;
	
$curl = curl_init($url);	
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);                         
curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);                                            
curl_setopt($curl, CURLOPT_USERAGENT, 'Fetch Games');
$response = curl_exec($curl);                                          
$resultStatus = curl_getinfo($curl);    

if($resultStatus['http_code'] == 200)
	echo $response;
?>