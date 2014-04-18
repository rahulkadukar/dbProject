<?php

$mysql_host 	= "localhost";
$mysql_database = "steamdata";
$mysql_user 	= "root";

$connection = mysql_connect($mysql_host, $mysql_user);
if(!$connection) 
	die('Could not connect: ' . mysql_error());
	
$database = mysql_select_db($mysql_database, $connection);
if(!$database)
	die('Could not select database: ' . mysql_error());

?>