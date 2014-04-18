<?php
	include 'database.php';	
	if($_GET["sort"] == 1)
		$sql = "SELECT * FROM `steam`.`game_master` ORDER BY `metacritic` DESC LIMIT 0,18";
	else
		$sql = "SELECT * FROM `steam`.`game_master` ORDER BY `metacritic` ASC LIMIT 0,18";
	$result	= mysql_query($sql);
	while($row = mysql_fetch_row($result))
	{					
		$t_game[$row[0]]["name"] 	= $row[1];
		$t_game[$row[0]]["date"] 	= $row[2];
		$t_game[$row[0]]["critic"] 	= $row[3];
		$t_game[$row[0]]["curr"] 	= $row[4];
		$t_game[$row[0]]["price"] 	= $row[5];
		$t_game[$row[0]]["people"] 	= $row[6];
		$t_game[$row[0]]["stats"] 	= $row[8];
	}	
	echo json_encode($t_game);
?>