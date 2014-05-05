<?php
include 'database.php';		

$aql = "SELECT * FROM `game_master`";
$dataFrom = $link->query($aql);
$i = 0;
while($rows = $dataFrom->fetch_array()){
	$t_price[$rows["appID"]]["price"] = $rows["price"];
	$t_price[$rows["appID"]]["name"] = $rows["name"];

}
	echo json_encode($t_price);
?>