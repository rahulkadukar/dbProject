--no. of games owned by all users.

select steamID,count(distinct(appID)) as 'gamescount' from user_games group by steamID	order by gamescount desc

--Info on games owned by a user

select gm.appID,gm.name,ug.playtime,gg.genre,gm.metacritic,gm.price,gm.recommendation,gm.achievements,gm.website
from user_games ug,game_master gm,game_genres gg
where ug.steamID='76561197960434622'  --users steamID
and ug.appID= gm.appID 
and gg.appID = ug.appID 
order by ug.playtime desc

--info on games that friends own.

select *
from user_friends uf,user_games ug,game_master gm
where uf.steamID = '76561197960434622'
and uf.friendID = ug. steamID
and ug.appID = gm.appID

--10 most popular games among friends.

select gm.appID,gm.name,(ug.playtime) as 'sumplaytime'
from user_friends uf,user_games ug,game_master gm
where uf.steamID = '76561197960434622'
and uf.friendID = ug. steamID
and ug.appID = gm.appID
group by gm.appID,gm.name
order by sumplaytime desc
limit 20

--games owned by most number of people.

