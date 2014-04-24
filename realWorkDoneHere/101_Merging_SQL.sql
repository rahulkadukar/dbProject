DELETE FROM `merge`.`user_games` WHERE steamID IN (SELECT a.`steamID` 
FROM `merge`.`user_data` AS a 
INNER JOIN `valve`.`user_data` AS b 
ON a.`steamID` = b.`steamID`);

DELETE FROM `merge`.`user_friends` WHERE steamID IN (SELECT a.`steamID` 
FROM `merge`.`user_data` AS a 
INNER JOIN `valve`.`user_data` AS b 
ON a.`steamID` = b.`steamID`);

DELETE FROM `merge`.`user_master` WHERE steamID IN (SELECT a.`steamID` 
FROM `merge`.`user_data` AS a 
INNER JOIN `valve`.`user_data` AS b 
ON a.`steamID` = b.`steamID`);

DELETE FROM `merge`.`user_data`
WHERE steamID IN (SELECT `steamID` FROM `valve`.`user_data`);

DELETE a.* FROM `valve`.`user_master` AS a
INNER JOIN `merge`.`user_master` AS b 
ON a.`steamID` = b.`steamID`
WHERE b.`lastUpdate` <> 0;

DELETE a.* FROM `valve`.`user_master` AS a
INNER JOIN `merge`.`user_master` AS b 
ON a.`steamID` = b.`steamID`
WHERE a.`lastUpdate` = 0
AND b.`lastUpdate` = 0;