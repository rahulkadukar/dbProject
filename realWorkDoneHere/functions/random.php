UPDATE `user_master` SET `lastUpdate` = 0 WHERE `steamID` NOT IN (SELECT a.`steamID` FROM `user_data` AS a)
SELECT `steamID` FROM `user_master` WHERE `lastUpdate` <> 0 AND `steamID` NOT IN (SELECT a.`steamID` FROM `user_data` AS a)
SELECT COUNT(*) FROM user_master WHERE lastUPdate <> 0
SELECT COUNT(*) FROM `user_data` WHERE `type` <> 1
SELECT DISTINCT(`steamID`) FROM `user_friends`
SELECT DISTINCT(`steamID`) FROM `user_games`
SELECT `steamID` FROM user_data WHERE `steamID` NOT IN (SELECT a.`steamID` FROM `user_data` AS a INNER JOIN `user_friends` AS b WHERE a.steamID = b.steamID) AND type = 1
SELECT a.`steamID` FROM `merge`.`user_master` AS a INNER JOIN `valve`.`user_master` AS b ON a.`steamID` = b.`steamID` WHERE a.`lastUpdate` = 0;
SELECT DISTINCT(`appID`) FROM `user_games` WHERE `appID` NOT IN (SELECT `appID` FROM `game_master`)
SELECT DISTINCT(`appID`) FROM `game_master` WHERE `appID` NOT IN (SELECT `appID` FROM `user_games`)
SELECT `appID`, SUM(`playtime`) FROM user_games GROUP BY `appID`
SELECT `location`, COUNT(*) FROM user_data  WHERE `type` = 1 GROUP BY `location`

SELECT x.`location`, x.`appID`, MAX(playtime) FROM  (
SELECT a.`location`, b.`appID`, SUM(`playtime`) AS playtime FROM `user_data` AS a JOIN `user_games` AS b ON a.`steamID` = b.`steamID` WHERE `playtime` <> 0 GROUP BY `location`, `appID` ) AS x WHERE x.`appID GROUP BY location

SELECT a.`location`, b.`appID`, SUM(`playtime`) AS playtime FROM `user_data` AS a JOIN `user_games` AS b ON a.`steamID` = b.`steamID` WHERE `playtime` <> 0 GROUP BY `location`, `appID`