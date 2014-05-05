<?php

$key 	= 'FCCAA3E90D04C71D59EAD2822B2AF90B';
$uid 	= $_GET['name'];

if(substr($uid,0,4) == '7656')
	$url = 'http://steamcommunity.com/profiles/'.$uid.'?xml=1';
else
	$url = 'http://steamcommunity.com/id/'.$uid.'?xml=1';
	
$curl = curl_init($url);	
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);                         
curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);                                            
curl_setopt($curl, CURLOPT_USERAGENT, 'Sample Code');
$response = curl_exec($curl);                                          
$resultStatus = curl_getinfo($curl);                                   
if($resultStatus['http_code'] == 200)
	echo $response;
?>