<?php
include 'database.php';		

$aql = "SELECT a.`location`, b.`appID`, SUM(`playtime`) AS playtime FROM `user_data` AS a JOIN `user_games` AS b ON a.`steamID` = b.`steamID` WHERE `playtime` <> 0 AND `location` = '".$_GET['location']."'
 GROUP BY `location`, `appID`";
$dataFrom = $link->query($aql);
while($rows = $dataFrom->fetch_array()){
	$t_price[$rows["appID"]] = $rows["playtime"];
}
	echo json_encode($t_price);
?>