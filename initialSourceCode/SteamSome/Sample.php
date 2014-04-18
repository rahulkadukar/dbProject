<?php
include 'Steam.php';
include 'database.php';
echo "<table>";

if(isset($_POST["commence"]))
{
	ini_set('max_execution_time', 3000);
	
	
	$query 	= "SELECT * FROM `steamdata`.`initial_data` WHERE `Final` = 0 AND `Private` = 0 ORDER BY  `initial_data`.`Summary` DESC ,  `Games` DESC ,  `Friends` DESC";
	$finals = mysql_query($query);
	$key = 'FCCAA3E90D04C71D59EAD2822B2AF90B';
	while($row = mysql_fetch_row($finals))
	{
		$id = $row[0];
		if($row[1] == 0)
		{
			$link 		= simplexml_load_file('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key='.$key.'&steamids='.$id.'&format=xml');
			$link 		= xmlToArray($link);
			$user_data 	= $link['response']['players']['player'];
			
			if(isset($user_data['steamid']))
				$d1 = $user_data['steamid'];
			if(isset($user_data['personaname']))
				$d2	= $user_data['personaname'];
			if(isset($user_data['avatarfull']))
				$d3 = $user_data['avatarfull'];
			if(isset($user_data['loccountrycode']))
				$d4 = $user_data['loccountrycode'];
			if(isset($user_data['profileurl']))
				$d5 = $user_data['profileurl'];
			if(isset($user_data['timecreated']))
				$d7 = $user_data['timecreated'];
			$sql = "INSERT INTO `steamdata`.`user_summary` (`SteamID`, `RealName`, `TimeCreated`, `Country`, `Avatar`, `ProfileURL`) VALUES ('".$id."','".$d2."','".$d7."','".$d4."','".$d3."','".$d5."');";
			$result = mysql_query($sql);
			$sql = "UPDATE `steamdata`.`initial_data` SET `Summary` = '1' WHERE `SteamID` = '".$id."'";
			$result = mysql_query($sql);
			$row[1] = 1;
		}
			
		if($row[3] == 0)
		{
			$game 	= 0;
			$hours 	= 0;
			$never	= 0;
			
			$link = simplexml_load_file('http://steamcommunity.com/profiles/'.$id.'/games?tab=all&xml=1');
			$link = xmlToArray($link);
			if(isset($link['gamesList']['error']))
			{	
				$sql = "UPDATE `steamdata`.`initial_data` SET `Private` = '1' WHERE `SteamID` = '".$id."'";
				$result = mysql_query($sql);
				$sql = "UPDATE `steamdata`.`user_summary` SET `Private` = '1' WHERE `SteamID` = '".$id."'";
				$result = mysql_query($sql);
				$row[2] = 1;
				$row[3] = 1;
			}
			else
			{
				foreach($link['gamesList']['games']['game'] as $value)
				{
					$f1 = $value['appID'];
					if(isset($value['hoursOnRecord']))
						$f2 = str_replace(",", "",$value['hoursOnRecord']);
					else
						$f2 = 0;
						
					if($f2 == 0)
						$never++;
						
					$hours += $f2;
					$game++;
					$sql = "INSERT INTO `steamdata`.`game_data` (`SteamID`, `GameID`, `Hours`) VALUES ( '".$id."', '".$f1."' , '".$f2."' );";
					$result = mysql_query($sql);
				}	
					$sql = "INSERT INTO `steamdata`.`game_summary` (`SteamID`, `Games`, `Hours`, `Never`) VALUES ( '".$id."', '".$game."' , '".$hours."', '".$never."' );";
					$result = mysql_query($sql);
					$sql = "UPDATE `steamdata`.`initial_data` SET `Games` = '1' WHERE `SteamID` = '".$id."'";
					$result = mysql_query($sql);
					$row[3] = 1;
			}
		}

		if($row[2] == 0)
		{
			$incr = 0;
			$link = simplexml_load_file('http://api.steampowered.com/ISteamUser/GetFriendList/v0001/?key='.$key.'&steamid='.$id.'&relationship=friend&format=xml');
			$link = xmlToArray($link);
			foreach($link['friendslist']['friends']['friend'] as $value)
			{
				$e1 = $value['steamid'];
				$e2 = $value['friend_since'];
				$incr++;
				$sql = "INSERT INTO `steamdata`.`friend_data` (`SteamID`, `FriendID`, `FriendSince`) VALUES ( '".$id."', '".$e1."' , '".$e2."' );";
				$result = mysql_query($sql);
				$sql = "INSERT INTO `steamdata`.`initial_data` (`SteamID`, `Summary`, `Friends`, `Games`, `Final`, `Private`, `LastUpdate`) VALUES ( '".$e1."','0','0','0','0','0','0');";
				$result = mysql_query($sql);
			}
			$sql = "INSERT INTO `steamdata`.`friend_summary` (`SteamID`, `Friends`) VALUES ( '".$id."', '".$incr."');";
			$result = mysql_query($sql);
			$sql = "UPDATE `steamdata`.`initial_data` SET `Friends` = '1' WHERE `SteamID` = '".$id."'";
			$result = mysql_query($sql);
			$row[2] = 1;
		}
		
		if($row[1] == 1 && $row[2] == 1 && $row[3] == 1)
		{
			$sql = "UPDATE `steamdata`.`initial_data` SET `Final` = '1' WHERE `SteamID` = '".$id."'";
			$result = mysql_query($sql);
			
			$d8 = time();
			$sql = "UPDATE `steamdata`.`initial_data` SET `LastUpdate` = '".$d8."' WHERE `SteamID` = '".$id."'";
			$result = mysql_query($sql);
		}
	}
}
else
{
	$sql = "SELECT * FROM `steamdata`.`initial_data` WHERE `Final` = 0 AND `Private` = 0 ORDER BY  `initial_data`.`Summary` DESC ,  `Games` DESC ,  `Friends` DESC LIMIT 0, 10";
	$result = mysql_query($sql);
	while($row = mysql_fetch_row($result))
	{
		echo"<tr><td>".$row[0]."</td></tr>";
	}
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Steam Profile Analysis</title>
	</head>
	<body>
		<form action="Sample.php" method="POST">
			<input type="hidden" name="commence" value="commence">
			<input type="submit" name="Submit" value="Start">
		</form>
		<table id="Sample">
	</body>
</html>