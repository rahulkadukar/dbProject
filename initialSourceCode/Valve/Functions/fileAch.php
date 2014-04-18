<?php
	error_reporting(E_ALL ^ E_NOTICE);
	include 'database.php';		
	
	$time  = date("U");
	$time  -= 3000000;
	echo "<table>";
	{
		$url = "http://127.0.0.1/Test/Steam/Functions/Sample.txt";
		$curl = curl_init($url);	
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);                         
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);                                          
		curl_setopt($curl, CURLOPT_USERAGENT, 'gameInfo');
		
		$response = curl_exec($curl);                                          
		$resultStatus = curl_getinfo($curl);                                   
		
		if($resultStatus['http_code'] == 200){
			$new = json_decode($response,true);
			var_dump($new);
			foreach($new["div"]["div"] as $image){
				//var_dump($image);
				//echo"<br><br>";
				if($image["-class"] == "achieveImgHolder")
					echo "<tr><td>".$image["img"]["-src"]."</td>";
				
				if($image["-class"] == "achieveTxtHolder")
					echo "<td>".$image["div"]["3"]["h3"]."</td><td>".$image["div"]["3"]["h5"]."</td>";
				
				if($image["-class"] == "compareImg")
					echo "<td>".$image["img"]["-src"]."</td></tr>";
			}
		}
	}
?>