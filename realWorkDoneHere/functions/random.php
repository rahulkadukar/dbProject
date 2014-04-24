UPDATE `user_master` SET `lastUpdate` = 0 WHERE `steamID` NOT IN (SELECT a.`steamID` FROM `user_master` AS a)
SELECT `steamID` FROM `user_master` WHERE `lastUpdate` <> 0 AND `steamID` NOT IN (SELECT a.`steamID` FROM `user_data` AS a)
SELECT COUNT(*) FROM user_master WHERE lastUPdate <> 0
SELECT COUNT(*) FROM `user_data` WHERE `type` <> 1
SELECT DISTINCT(`steamID`) FROM `user_friends`
SELECT DISTINCT(`steamID`) FROM `user_games`
SELECT `steamID` FROM user_data WHERE `steamID` NOT IN (SELECT a.`steamID` FROM `user_data` AS a INNER JOIN `user_friends` AS b WHERE a.steamID = b.steamID) AND type = 1