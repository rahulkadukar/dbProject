<?php
	error_reporting(E_ALL ^ E_NOTICE);
	include 'database.php';		
	echo"<table>";
	$sql   	= "SELECT * FROM game_master WHERE achievements > 0";
	$result = mysql_query($sql);
	while($row = mysql_fetch_row($result)){
		$new[$row[0]] = $row[8];
	}
	
	$sql = "SELECT COUNT(DISTINCT appID) FROM achievement_master";
	$eee = mysql_query($sql);
	while($row = mysql_fetch_row($eee)){
		
	}
	
	$sql   = "SELECT appID, COUNT( * ) AS TotalItem FROM achievement_master WHERE appID < 1000000 GROUP BY appID ORDER BY appID DESC LIMIT 0,1";
	$eee = mysql_query($sql);
	while($fff = mysql_fetch_row($eee)){
		$sql   = "SELECT appID, COUNT( * ) AS Total FROM achievement_master WHERE appID <= ".$fff[0]." GROUP BY appID ORDER BY appID DESC";
		$games = mysql_query($sql);
		while($row = mysql_fetch_row($games)){
			$xyz[$row[0]] = $row[1];
		}
	}
	
	foreach($new as $x => $y)
	{
		if($y != $xyz[$x])
		echo "<tr><td>".$x."</td><td>".$y."</td><td>".$xyz[$x]."</td></tr>";
	}
	//echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$new[$row[0]]."</td></tr>";
?>