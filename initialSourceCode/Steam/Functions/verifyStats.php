<?php
	error_reporting(E_ALL ^ E_NOTICE);
	include 'database.php';		
	echo"<table>";
	
	$sql   = "SELECT appID, COUNT( * ) AS TotalItem FROM achievement_master GROUP BY appID";
	$eee = mysql_query($sql);
	while($fff = mysql_fetch_row($eee)){
		//echo "<tr><td>".$fff[0]."</td><td>".$fff[1]."</td>";
		$sql   = "SELECT COUNT( * ) FROM achievement_percent WHERE appID = ".$fff[0];
		$games = mysql_query($sql);
		while($row = mysql_fetch_row($games)){
			if($row[0] != $fff[1])
				echo "WHAT";
			
			//echo "<td>".$row[0]."</td></tr>";
		}
	}

	//echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$new[$row[0]]."</td></tr>";
?>