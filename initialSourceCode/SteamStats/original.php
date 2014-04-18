<!DOCTYPE html>
<html>
    <head>
		<link rel="stylesheet" href="CSS/steam.css" type="text/css" />
        <link rel="stylesheet" href="CSS/jquery.mobile.css" type="text/css" />
        <title>Steam Profile Analysis</title>
		<script type="text/javascript" src="JavaScript/jquery.js"></script>
        <script type="text/javascript" src="JavaScript/jquery.xml2json.js"></script>
		<script type="text/javascript" src="JavaScript/jquery.tablesorter.min.js"></script>
        <script type="text/javascript" src="JavaScript/jquery.mobile.js"></script>
		<script type="text/javascript">
        $(document).ready(function(){                
			var startTime = new Date().getTime();
			var data_array = new Array;
			var TotalGames = 0;
			var TotalHours = 0;
			var TotalPrice = 0;
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
					
					$content = utf8_encode(file_get_contents('Sample.xml'));
					$t_json = xmlToArray(simplexml_load_string($content));
					$content = utf8_encode(file_get_contents('Sample1.xml'));
					$t_user = xmlToArray(simplexml_load_string($content));
					$t_json = json_encode($t_json);
					$t_user = json_encode($t_user);
					echo "data_array = $t_json;";
					echo "var hide_array = $t_hide;";
					echo "var user_array = $t_user;";
					echo "var prices_array = $t_prices;";
					echo "uid = '$uid';";
					echo "console.log(user_array);";
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
					if(user_array.profile.hasOwnProperty('avatarFull'))
						$('#statsPicture').html('<img src='+ user_array.profile.avatarFull + '></img>');
					
					if(user_array.profile.hasOwnProperty('realname'))
						$('#statsName').html(user_array.profile.realname);
					
					if(user_array.profile.hasOwnProperty('hoursPlayed2Wk'))
						$('#statsRecent').html(user_array.profile.hoursPlayed2Wk + ' hours played in the last 2 weeks');
						
					if(user_array.profile.hasOwnProperty('memberSince'))
						$('#statsMember').html('Member since ' + user_array.profile.memberSince);
					
					if(user_array.profile.hasOwnProperty('location'))
						$('#statsLocation').html(user_array.profile.location);
					else
						$('#statsLocation').html('Location not known');
						
					if(user_array.profile.hasOwnProperty('steamRating'))
						$('#statsRating').html('Steam Rating ' + user_array.profile.steamRating);
				}
				
				$('<tr><td>Number of Games</td><td style="text-align:left;" id="TotalGames"></td></tr>').appendTo('#numbers1');
				$('<tr><td>Total Hours played</td><td style="text-align:left;" id="TotalHours"></td></tr>').appendTo('#numbers1');
				$('<tr><td>Account Price</td><td style="text-align:left;" id="TotalPrice"></td></tr>').appendTo('#numbers1');
				$('<tr><td>Games Never Played</td><td style="text-align:left;" id="NeverPlayed"></td></tr>').appendTo('#numbers1');


				$('<tr><td>Cost per game</td><td style="text-align:left;" id="CostGame"></td></tr>').appendTo('#numbers2');
				$('<tr><td>Cost per hour</td><td style="text-align:left;" id="CostHour"></td></tr>').appendTo('#numbers2');
				$('<tr><td>Time per game</td><td style="text-align:left;" id="TimeGame"></td></tr>').appendTo('#numbers2');
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
					}
				}
				
				$('#TotalGames').html(TotalGames);
				$('#NeverPlayed').html(NeverPlayed);
				$('#TotalPrice').html(TotalPrice.toFixed(2));
				$('#TotalHours').html(TotalHours.toFixed(2));
							
				$('#CostGame').html((TotalPrice.toFixed(2)/TotalGames).toFixed(2));
				$('#CostHour').html((TotalPrice.toFixed(2)/TotalHours).toFixed(2));
				$('#TimeGame').html((TotalHours.toFixed(2)/TotalGames).toFixed(2));
				
				var endTime = new Date().getTime();
				$('#StatsTime').html((endTime - startTime)/1000);
				$("#games").tablesorter(); 
						
				/*$('tr').click(function(event)
				{
					 var dataID = $(this).attr('id');
					 $('#data_'+dataID).append('<div class="stats"><p><img src="http://cdn.steampowered.com/v/gfx/apps/'+dataID+'/header.jpg"/></p></div>');
					 var stats = $.parseJSON(StatsData[dataID].responseText);
					 console.log(stats);
					 $('#data_'+dataID).show();
				});*/
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
	<div data-theme="a" data-role="page">

<!-- This part is the page header -->
	<div data-role="header" data-theme="b">
		<h1>Steam Profile Analyser</h1>
	</div>

<!-- This part is the page content -->	
	<div data-role="content" data-theme="a">
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" data-ajax="false">
			<input type="text" name="UID" id="UID" style="font-family: Segoe UI; font-size: 20px" class="ui-input-text ui-body-a" placeholder="Enter Steam ID or Custom URL" />
			<input type="submit" data-theme="b" value="submit" aria-disabled="false" />
		</form>
		
		<div id="stats">
			<div id="statsPicture"></div>
			<div style="margin-left:40px; margin-right:40px; margin-top:20px;">
				<ul data-role="listview" data-theme="a" data-divider-theme="b" class="ui-listview">
					<li id="statsName"		class="ui-li ui-li-static ui-btn-up-a"></li>
					<li id="statsRecent" 	class="ui-li ui-li-static ui-btn-up-a"></li>
					<li id="statsMember" 	class="ui-li ui-li-static ui-btn-up-a"></li>
					<li id="statsLocation" 	class="ui-li ui-li-static ui-btn-up-a"></li>
					<li id="statsRating" 	class="ui-li ui-li-static ui-btn-up-a"></li>
				</ul>
			</div>
		</div>
					
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
	</div>
	</div>
		</body>
</html>