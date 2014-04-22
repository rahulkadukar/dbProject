<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="../Index.css" type="text/css" />
		<link rel="stylesheet" href="Steam.css" type="text/css" />
        <title>Steam Profile Analysis</title>
        
        <script type="text/javascript" src="../jquery.js"></script>
		<script type="text/javascript" src="JavaScript/jquery.xml2json.js"></script>
		<script type="text/javascript" src="JavaScript/jquery.tablesorter.min.js"></script>
		<script type="text/javascript">
		$(document).ready(function(){				
			var startTime = new Date().getTime();
			var data_array = new Array;
			var TotalGames = 0;
			var TotalHours = 0;
			var TotalPrice = 0;
			var TotalStats = 0;
			var TotalDone = 0;
			var TotalGameStats = 0;
			var NeverPlayed = 0;
			var uid;
			$(".table").hide();
			 
			<?php
				error_reporting(E_ALL ^ E_NOTICE);
				include 'Steam.php';
				include '../database.php';	
				
				$uid = $_GET['UID'];
				if($uid)
				{
					$index	= 0;
					$query = 'SELECT * FROM steam_hide_appid';
					$result	= mysql_query($query);
					while($row = mysql_fetch_row($result))
					{					
						$t_hide[$index] = $row[0];
						$index++;
					}			
					$t_hide = json_encode($t_hide);
					
					$index	= 0;
					$query = 'SELECT * FROM sc_steamgameprices';
					$result	= mysql_query($query);
					while($row = mysql_fetch_row($result))
					{					
						$t_prices[$row[0]] = $row[1];
						$index++;
					}			
					$t_prices = json_encode($t_prices);
					if(substr($uid,0,4) == '7656')
					{
						$t_json = xmlToArray(simplexml_load_file('http://steamcommunity.com/profiles/'.$uid.'/games?tab=all&xml=1'));
						$t_user = xmlToArray(simplexml_load_file('http://steamcommunity.com/profiles/7'.$uid.'?xml=1'));
					}
					else
					{
						$t_json = xmlToArray(simplexml_load_file('http://steamcommunity.com/id/'.$uid.'/games?tab=all&xml=1'));
						$t_user = xmlToArray(simplexml_load_file('http://steamcommunity.com/id/'.$uid.'?xml=1'));	
					}
					$t_json = json_encode($t_json);
					$t_user = json_encode($t_user);
					echo "data_array = $t_json;";
					echo "var hide_array = $t_hide;";
					echo "var user_array = $t_user;";
					echo "var prices_array = $t_prices;";
					echo "uid = '$uid';";
				}
			?>
					
			if(data_array.gamesList.hasOwnProperty('games')){
				var NumberOfGames = 0;
				var Requests = 0;
				var NotFound = -1;
				var AchievementUrl;
				var StatsData = [];
				
				$(".table").show();
				$("#stats").hide();
				
				if(user_array.profile.hasOwnProperty('privacyMessage'))
					$("#Result").html(user_array.profile.privacyMessage.$);
				else{
					$("#stats").show();
					if(user_array.profile.hasOwnProperty('realname'))
						$('<tr><td>Real Name</td><td style="text-align:left;">'+ user_array.profile.realname +'</td></tr>').appendTo('#stats');
					
					if(user_array.profile.hasOwnProperty('hoursPlayed2Wk'))
						$('<tr><td>2 week playtime</td><td style="text-align:left;">'+user_array.profile.hoursPlayed2Wk+'</td></tr>').appendTo('#stats');
						
					if(user_array.profile.hasOwnProperty('memberSince'))
						$('<tr><td>Member since</td><td style="text-align:left;">'+user_array.profile.memberSince+'</td></tr>').appendTo('#stats');
					
					if(user_array.profile.hasOwnProperty('location'))
						$('<tr><td>Location</td><td style="text-align:left;">'+user_array.profile.location+'</td></tr>').appendTo('#stats');
						
					if(user_array.profile.hasOwnProperty('steamRating'))
						$('<tr><td>Steam Rating</td><td style="text-align:left;">'+user_array.profile.steamRating+'</td></tr>').appendTo('#stats');
				}
				
				$('<tr><td>Number of Games</td><td style="text-align:left;" id="TotalGames"></td></tr>').appendTo('#numbers1');
				$('<tr><td>Total Hours played</td><td style="text-align:left;" id="TotalHours"></td></tr>').appendTo('#numbers1');
				$('<tr><td>Account Price</td><td style="text-align:left;" id="TotalPrice"></td></tr>').appendTo('#numbers1');
				$('<tr><td>Games with Achievements</td><td style="text-align:left;" id="TotalGameStats"></td></tr>').appendTo('#numbers1');
				$('<tr><td>Number of Achievements</td><td style="text-align:left;" id="TotalStats"></td></tr>').appendTo('#numbers1');
				$('<tr><td>Completed Achievements</td><td style="text-align:left;" id="TotalDone"></td></tr>').appendTo('#numbers1');

				$('<tr><td>Cost per game</td><td style="text-align:left;" id="CostGame"></td></tr>').appendTo('#numbers2');
				$('<tr><td>Cost per hour</td><td style="text-align:left;" id="CostHour"></td></tr>').appendTo('#numbers2');
				$('<tr><td>Time per game</td><td style="text-align:left;" id="TimeGame"></td></tr>').appendTo('#numbers2');
				$('<tr><td>Achievements done in %</td><td style="text-align:left;" id="StatsInP"></td></tr>').appendTo('#numbers2');
				$('<tr><td>Games Never Played</td><td style="text-align:left;" id="NeverPlayed"></td></tr>').appendTo('#numbers2');
				$('<tr><td>Total Load Time</td><td style="text-align:left;" id="StatsTime"></td></tr>').appendTo('#numbers2');
				
				$("#GameList").html('Games List');
				$("#Profile").html('Player Profile');
				$("#Summary").html('Summary');
				
				for (i in data_array.gamesList.games.game)
				{			
					Name  = data_array.gamesList.games.game[i].name;
					AppID = data_array.gamesList.games.game[i].appID;
					if((hide_array.indexOf(AppID)) === NotFound)
					{
						if(data_array.gamesList.games.game[i].hasOwnProperty('hoursOnRecord'))
							Hours = data_array.gamesList.games.game[i].hoursOnRecord;
						else
						{
							Hours = 0;
							NeverPlayed++;
						}
						Price = prices_array[AppID] / 100;
						if(isNaN(Price) || Price == 0)
								Price = 'Free';
						else
							TotalPrice = TotalPrice + parseFloat(Price);
						HideFlag = (hide_array.indexOf(AppID));
						//<td><img src="http://cdn.steampowered.com/v/gfx/apps/'+AppID+'/header.jpg"/></td>
						$('<tr id="'+AppID+'"><td>'+Name+'</td><td>'+Price+'</td><td>'+Hours+'</td><td style="display:none;" class="stats" id="stats_'+AppID+'"></td><td style="display:none;" class="stats" id="done_'+AppID+'"></td></tr>').appendTo('#games_tbody');
						$('<tr id="data_'+AppID+'" style="display:none;"></tr>').appendTo('#games');
						NumberOfGames++;
						TotalHours = TotalHours + parseFloat(Hours);
						TotalGames ++;
					
						if(data_array.gamesList.games.game[i].hasOwnProperty('statsLink'))
						{
							try{
							Requests+=1;
							AchievementUrl = data_array.gamesList.games.game[i].statsLink;
							StatsData[AppID] = $.ajax({url: "RemoteFetch.php", 
												type: "GET",
												async: true,
												data: { url: AchievementUrl }});

							StatsData[AppID].done(function(data)
							{
								Requests--;
								if(Requests == 0)
									$.Stats();
							});
							}
									catch(e)
									{
										console.log(e);
										console.log(AchievementUrl);
									}
						}
					}
				}
				
				$('#TotalGames').html(TotalGames);
				$('#NeverPlayed').html(NeverPlayed);
				$('#TotalPrice').html(TotalPrice.toFixed(2));
				$('#TotalHours').html(TotalHours.toFixed(2));
							
				$('#CostGame').html((TotalPrice.toFixed(2)/TotalGames).toFixed(2));
				$('#CostHour').html((TotalPrice.toFixed(2)/TotalHours).toFixed(2));
				$('#TimeGame').html((TotalHours.toFixed(2)/TotalGames).toFixed(2));
				
				
				$(function()
				{ 
					$.Stats = function()
					{
						for(i in StatsData)
						{							
							var Complete = 0;
							if(StatsData[i].responseText.substr(2,10) == "playerstat")
							{
								var stats = $.parseJSON(StatsData[i].responseText);
								if(stats.playerstats.hasOwnProperty('achievements'))
								{
									var GameLink = stats.playerstats.game.gameLink;
									var AppID = GameLink.split("/").reverse();
									$("#stats_"+AppID).html(stats.playerstats.achievements.achievement.length);
									if(!isNaN(stats.playerstats.achievements.achievement.length))
										TotalStats = TotalStats + stats.playerstats.achievements.achievement.length;
									for(j in stats.playerstats.achievements.achievement)
										if(stats.playerstats.achievements.achievement[j]['@closed'] == '1')
											Complete++;
									TotalGameStats++;
									TotalDone = TotalDone + Complete;
									$("#done_"+AppID).html(Complete);
								}
							}
						}
					
						$('#TotalStats').html(TotalStats);
						$('#TotalDone').html(TotalDone);
						$('#StatsInP').html(100*(TotalDone/TotalStats).toFixed(2));
						$('#TotalGameStats').html(TotalGameStats);
						$(".stats").show();
						var endTime = new Date().getTime();
						$('#StatsTime').html((endTime - startTime)/1000);
						$("#games").tablesorter(); 
					}
				})
				
				var endTime = new Date().getTime();
				$('#StatsTime').html((endTime - startTime)/1000);
				$("#games").tablesorter(); 
			}
			else
			{
				if(data_array.gamesList.hasOwnProperty('error'))
					$('#Result').html(data_array.gamesList.error);
				else
					if(uid)
						$('#Result').html('Enter a valid Steam ID');
			}
        });
		</script>
            
    </head>
    
    <body>		
        <div id="main">
            <div class="content">
                <p class="quote">"Simplicity is the ultimate sophistication"- Leonardo da Vinci</p>
            </div>      

			<div id="nav">
				<?php
					$t_file = FILE('../File_1');
					foreach($t_file as $v_file_index => $wa_file)
					{
						$contents = explode(",",$wa_file);
						echo '<div class="nav"><a href="'.$contents[1].'">'.$contents[0].'</a></div>';
					}
				?>
			</div>			
            
            <div class="content">
                <p class="title">Steam Analyser</p>
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
					<input class="button" type="text" name="UID" placeholder="Enter Steam ID or Custom URL">
				</form>
				
				<p id="Profile" class="title"></p>
				<p id="Result"></p>
				<table id="stats" class="table"></table>
				
				<p id="Summary" class="title"></p>
				<div style= "float:left;"><table id="numbers1" class="table"></table></div>
				<div style= "float:right;"><table id="numbers2" class="table"></table></div>
				
				<div style= "clear:both;"></div>
				<p id="GameList" class="title"></p>
				<table id="games" class="table">
					<thead>
						<tr>
							<th style="color:black; text-align:left; padding-left:20px">Name</th>
							<th style="color:black;">Price</th>
							<th style="color:black;">Hours</th>
							<th style="display:none; color:black;" class="stats">Total</th>
							<th style="display:none; color:black;" class="stats">Done</th>
						</tr>
					</thead>
					<tbody id="games_tbody">
					</tbody>
				</table>
            </div>
        </div>
        
        <div id="footer">
            &copy; Rahul Kadukar 2012
        </div>
    </body>
</html>