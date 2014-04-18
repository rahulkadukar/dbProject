<!DOCTYPE html>
<html>
    <head>
		<link rel="stylesheet" href="CSS/steam.css" type="text/css" />
		<link rel="stylesheet" href="CSS/jquery.mobile.css" type="text/css" />
        <title>Steam Profile Analysis</title>
		<script type="text/javascript" src="JavaScript/jquery.js"></script>
        <script type="text/javascript" src="JavaScript/jquery.xml2json.js"></script>
		<script type="text/javascript" src="JavaScript/json2.js"></script>
		<script type="text/javascript" src="JavaScript/highcharts.js"></script>
		<script type="text/javascript" src="JavaScript/date.js"></script>
		<script type="text/javascript" src="JavaScript/charts.js"></script>
		<script type="text/javascript" src="JavaScript/jquery.dataTables.js"></script>
		<script type="text/javascript">
        $(document).ready(function(){
			$("#back").click(function(){
				window.location.href = "index.php";
			});
		
			var startTime = new Date().getTime();
			var data_array = new Array;
			var TotalGames = 0;
			var TotalHours = 0;
			var TotalPrice = 0;
			var TotalGameStats = 0;
			var NeverPlayed = 0;
			var uid;
			var type;
			var fail;
			 
			<?php
				error_reporting(E_ALL ^ E_NOTICE);
				include 'Steam.php';
				include '../database.php';	
                $uid  = $_GET['UID'];
				$type = $_GET['type'];
				$fail = $_GET['fail'];
				echo "type = '$type';";
				echo "fail = '$fail';";
				if($uid)
				{
					$key = 'FCCAA3E90D04C71D59EAD2822B2AF90B';
					$index	= 0;
					$join = 0;
					$query = 'SELECT * FROM steam_hide_appid';
					$result	= mysql_query($query);
					while($row = mysql_fetch_row($result))
					{					
						$t_hide[$index] = $row[0];
						if($row[1] == 'MultiPlayer')
						{
							$t_join[$row[0]]['appID'] = $row[0];							
							$t_join[$row[0]]['main'] = $row[2];
						}
						$index++;
					}			
					$t_hide = json_encode($t_hide);
					$t_join = json_encode($t_join);

					$index	= 0;
					$query = 'SELECT * FROM sc_steamgameprices';
					$result	= mysql_query($query);
					while($row = mysql_fetch_row($result))
					{					
						$t_prices[$row[0]] = $row[1];
						$index++;
					}			
					$t_prices = json_encode($t_prices);

					$index	= 0;
					$query = 'SELECT * FROM sc_steamgames';
					$result	= mysql_query($query);
					while($row = mysql_fetch_row($result))
					{					
						$t_games[$row[0]] = $row[1];
						$index++;
					}			
					$t_games = json_encode($t_games);
					
					echo "var hide_array = $t_hide;";
					echo "var prices_array = $t_prices;";
					echo "var join_array = $t_join;";
					echo "var game_array = $t_games;";
					echo "uid = '$uid';";
                }
				
            ?>
			if(type==1){
				$("#private").show();
				$("#private").html("<p>The ID that you entered '"+fail+"' is a private ID. Please enter a new ID or make the ID public</p>");
				$("#private").css("border-color","red");
			}
			
			if(type==2){
				$("#private").show();
				$("#private").html("<p>It seems that the ID that you have entered is incorrect. Please try with a proper ID</p>");
				$("#private").css("border-color","red");
			}
			
			if(uid){
				var data_array;
				var user_array;
				var marginTop = $("body").height();
				marginTop = marginTop - 450;
				marginTop /= 2;
				$("#dialog").css('margin-top',marginTop);
				$("#contents").hide();
				$("#dialog").show();

				$.get('steamFetch.php',{'name': uid,},function(userdata){
					user_array = $.xml2json(userdata);					
					if(user_array.hasOwnProperty('privacyState')){
						if(user_array.privacyState != 'public'){
							window.location.href = "index.php?fail="+uid+"&type=1";
							return false;
						}
					}
					else{
						window.location.href = "index.php?type=2";
						return false;
					}

					if(user_array.hasOwnProperty('avatarFull'))
						$('#statsPicture').html('<img id="avatar" src='+ user_array.avatarFull + '></img>');
					
					if(user_array.realname)
						$('#statsName').html(user_array.realname);
					else
						$('#statsName').html('Name not provided');
						
					if(user_array.hasOwnProperty('hoursPlayed2Wk'))
						$('#statsRecent').html(user_array.hoursPlayed2Wk + ' hours played in the last 2 weeks');
						
					if(user_array.hasOwnProperty('memberSince'))
						$('#statsMember').html('Member since ' + user_array.memberSince);
					
					if(user_array.location)
						$('#statsLocation').html(user_array.location);
					else
						$('#statsLocation').html("Location not known");
						
					if(user_array.hasOwnProperty('steamRating'))
						$('#statsRating').html('Steam Rating ' + user_array.steamRating);
					
					var date	= new Date();												
					date = Date.parse(user_array.memberSince)/1000;
					var today	= new Date().getTime()/1000;
					$('#memberDays').html(Math.ceil((today - date)/86400));

					$.get('gameFetch.php',{'name': user_array.steamID64},function(data){
						var data_array = JSON.parse(data);				
						if(data_array.hasOwnProperty('response')){
							if(data_array.response.hasOwnProperty('games')){			
								var NumberOfGames = 0;
								var Requests = 0;
								var hours;
								var NotFound = -1;
								var AchievementUrl;
								var StatsData = [];
								$("#stats").hide();			
								$("#GameList").html('Games List');
								$("#Profile").html('Player Profile');
								$("#Summary").html('Summary');
								
								var elite = 0, hardcore = 0, dedicated = 0, experienced = 0, amateur = 0, newbie = 0;
								var aaaTitle = 0, expensive = 0, pricey = 0, normal = 0, cheap = 0, free = 0;
								var TopGames = [];
								var gameStats = {games: []};
								
								for (i in data_array.response.games){
									var hours = 0;
									AppID = data_array.response.games[i].appid;
									Name  = data_array.response.games[i].name;
									if(data_array.response.games[i].hasOwnProperty('playtime_forever'))
										hours = data_array.response.games[i].playtime_forever;
									price = prices_array[AppID] / 100;
									if(isNaN(price) || price == 0)
										price = 0.00;
									if(Name || hours != 0){
										if(hide_array.indexOf(AppID) === NotFound || join_array[AppID]){
											if(join_array[AppID]){
												mainGame = join_array[AppID].main;
												name = 'X7F4C3';
											}
											else{
												mainGame = data_array.response.games[i].appid;
												name = data_array.response.games[i].name;
											}
											
											if(gameStats.games[mainGame]){
												hours = parseFloat(gameStats.games[mainGame].stats.hours) + parseFloat(hours);
												gameStats.games[mainGame].stats.hours = hours.toFixed(1);
												if(gameStats.games[mainGame].stats.name == 'X7F4C3'){
													gameStats.games[mainGame].stats.name = name;
													gameStats.games[mainGame].stats.price = price;
												}
											}
											else{
												gameStats.games[mainGame] = { 
													"appID" : mainGame,
													"stats"	: {
														"name"  : name,
														"hours"	: hours,
														"price"	: price
													}
												};
											}
										}
									}
								}		
								
								for(i in gameStats.games){
									Hours = parseFloat(gameStats.games[i].stats.hours/60).toFixed(2);
									Price = gameStats.games[i].stats.price;
									Name = gameStats.games[i].stats.name;
									AppID = gameStats.games[i].appID;
									if(Price != 0.00)
										TotalPrice = TotalPrice + parseFloat(Price);
									Average = 0;
										if(Hours != 0 && Price != 'Free')
										{	
											var Average = (Price/Hours).toFixed(2);
											if(Average > Price)
												Average = Price;
										}
										//<td><img src="http://cdn.steampowered.com/v/gfx/apps/'+AppID+'/header.jpg"/></td>
										$('<tr><td>'+Name+'</td><td>'+Price+'</td><td id="'+AppID+'">'+Hours+'</td><td class="stats">'+Average+'</td></tr>').appendTo('#games_tbody');
										NumberOfGames++;
										TotalHours = TotalHours + parseFloat(Hours);
										TotalGames ++;
										if(Hours >= 100)
											elite++;
										else if(Hours >= 50)
											hardcore++;
										else if(Hours >= 25)
											experienced++;
										else if(Hours >= 10)
											dedicated++;
										else if(Hours >= 5)
											amateur++;
										else if(Hours > 0)
											newbie++;
										
										if(Hours == 0.0)
											NeverPlayed++;
											
										if(Price >= 49.98)
											aaaTitle++;
										else if(Price >= 29.98)
											expensive++;
										else if(Price >= 19.98)
											pricey++;
										else if(Price >= 9.98)
											normal++;
										else if(Price >= 0.01)
											cheap++;
										else if(Price == 0.00)
											free++;
									
										var GameData = {};
										GameData.name 	= Name;
										GameData.time	= parseFloat(Hours);
										GameData.appID	= AppID;
										TopGames.push(GameData);
								}
						
								TopGames.sort(function(a,b){return b.time-a.time});
								TopGames = TopGames.slice(0,10);
								
								var Top10Games = [];
								var Top10Names = [];
								for(i in TopGames)
								{
									if(TopGames[i].time == 0.00)
										continue;
									Top10Games.push(TopGames[i].time);
									Top10Names.push(TopGames[i].name);
								}
						
						
								$('#newPic').html('<img id="gridPic" width="308" height="144" src="http://cdn.steampowered.com/v/gfx/apps/'+TopGames[0].appID+
								'/header.jpg"/><div id="favGame"><div id="prev">Prev</div><div id="stdText" style="float:left">Number 1 most played </div> <div id="next">Next</div>');
								if(Top10Games.length > 1)
								{
									$('#prev').html('&nbsp');
									$('#next').show();
									gamePics = 1;
								}
								$('#totalGames').html(TotalGames);
								$('#gamePlayed').html(TotalGames - NeverPlayed);
								$('#neverPlayed').html(NeverPlayed);
								$('#percentage').html(((100*(TotalGames-NeverPlayed))/TotalGames).toFixed(2));
								$('#totalPrice').html(TotalPrice.toFixed(2));
								$('#totalHours').html(TotalHours.toFixed(2));
								
								$('#totalGames').removeClass('ui-corner-top');
								$('#neverPlayed').removeClass('ui-corner-bottom');
								$('#costGame').removeClass('ui-corner-top');
								$('#statsTime').removeClass('ui-corner-bottom');
								$('#print').removeClass('ui-btn-inner ui-btn-corner-all');
								
								$('.removeTop').removeClass('ui-corner-top');
								$('.removeBottom').removeClass('ui-corner-bottom');
											
								$('#CostGame').html((TotalPrice.toFixed(2)/TotalGames).toFixed(2));
								$('#CostHour').html((TotalPrice.toFixed(2)/TotalHours).toFixed(2));
								$('#TimeGame').html((TotalHours.toFixed(2)/TotalGames).toFixed(2));						
								
								$('#elite').html(elite);
								$('#hardcore').html(hardcore);
								$('#experienced').html(experienced);
								$('#dedicated').html(dedicated);
								$('#amateur').html(amateur);
								$('#newbie').html(newbie);
								
								var heightContainer = Top10Games.length;
								var nameContainer	= "#Top10GamesBar";
								heightContainer = heightContainer * 40 + 100;
								$(nameContainer).height(heightContainer);
								var titleBar = "Top "+Top10Games.length+" games by play time";
								
								$("#games").dataTable({
									"sPaginationType": "full_numbers",
									"aLengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
									"iDisplayLength": 25,
									"aaSorting": [[ 2, "desc" ]],
									"aoColumns": [
										{ "sWidth": "800px" },
										{ "sWidth": "50px", "sClass": "right"},
										{ "sWidth": "50px", "sClass": "right", "sType": "numeric" },
										{ "sWidth": "50px", "sClass": "right" }
									]
								});
								var barChart = 
								{
									"container" 	: "Top10GamesBar",
									"title"			: titleBar,
									"xAxisCaption"	: "Name of game",
									"xAxisCategory"	: Top10Names,
									"yAxisCaption"	: "Number of hours",
									"unit"			: " hours",
									"dataValue"		: [{name: 'Gameplay Time', data: Top10Games}]
								};
								
								$.barChart(barChart);
								$(".barCharts").show();
								$(".table").show();
								$(".gameSummary").show();
								$("#stats").show();
								$(".headerButton").show();
								$("#dialog").hide();
								if(date < 1237118400)
									date = 1237118400;
								$('#gameDay').html((Math.ceil((TotalHours.toFixed(2)*60)/Math.ceil((today - date)/86400))) + " mins");
								var endTime = new Date().getTime();
								$('#StatsTime').html((endTime - startTime)/1000);
								
								$("#next").click(function(){
									gamePics ++;
									if(gamePics == Top10Games.length)
										$("#next").hide();
									else
										$("#next").show();
									$('#gridPic').attr('src','http://cdn.steampowered.com/v/gfx/apps/'+TopGames[gamePics - 1].appID+'/header.jpg');
									$("#prev").html('Prev');
									$("#stdText").html('Number '+gamePics+' most played');
								});
								
								
								$("#prev").click(function(){
									gamePics --;
									$('#gridPic').attr('src','http://cdn.steampowered.com/v/gfx/apps/'+TopGames[gamePics - 1].appID+'/header.jpg');						
									if(gamePics == 1)
										$("#prev").html('&nbsp');
									else
										$("#prev").show();
									$("#stdText").html('Number '+gamePics+' most played');
									$("#next").show();
								});	
							}
						}
						else{
							$('#Result').html('Enter a valid Steam ID');
						}
					});
				});
					
				$("#print").click(function(){
					var canvas = document.getElementById("myCanvas");
					var ctx = canvas.getContext("2d");
					$("#canvasImg").show();					
					baseimage = new Image();
					baseimage.src = user_array.steamID64+'.jpg';
					baseimage.onload = function(){
						ctx.drawImage(baseimage,1,1);
						var dataURL = canvas.toDataURL("image/png");
						document.getElementById('canvasImg').src = dataURL;
					}
					$("#myCanvas").show();
				});
			}
		});
        </script>         
    </head>
<body>
<div>
<!-- This part is the page header -->
	<div id="header">
		<div style="float:left"><a href="index.php" style=" display:none" class="headerButton" id="back">Back</a></div>
		<div style="width:300px; margin:auto" id="titleText">Steam Profile Analyser</div>
	</div>

<!-- This part is the page content -->	
	<div id="content">
		<div id="dialog" style="display:none">
			<p style="margin:20px">Welcome to the Steam profile analyser. Please wait while your data is being fetched from the mighty Steam servers, this should not take more than 5 seconds under any circumstance. If you are still reading this then you either have a large Steam library or Steam servers are not performing at their peak and it will take more than 5 seconds.</p>
		</div>
	
		<div id="contents">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" data-ajax="false">
				<input type="text" name="UID" id="uid" placeholder="Enter Steam ID or Custom URL" />
				<input type="submit" id="submit" value="Submit"/>
			</form>
			<div id="intro" class="someText">
				<p>Enter your Steam ID in the box above and click Submit.</p>
				
				<p>To find you Steam ID follow these steps:
				<ul>
					<li>Open Steam Website and login to Steam.</li>
					<li>Click on your name and go to Profile tab.</li>
					<li>This will show a URL similar to http://steamcommunity.com/id/rahulkadukar/</li>
					<li>You may also see a number starting with 7656</li>
					<li>Copy the part after id in case you have a id or copy the number and put into box above and click Submit.</li>
					<li>The game time per day is shown assuming that Steam started tracking time on 15 March 2009.</li>
				</ul>
				</p>
			</div>
			
			<div id="private" class="someText" style="display:none">
				This ID is private and its contents cannot be displayed
			</div>
		</div>
		
		<div id="stats" style="display:none">
			<div id="statsPicture" style="float:left"></div>
			<div id="statsSummary" style="float:left">
				<ul class="list">
					<li id="statsName" class="list summaryList" style="border-top: 1px solid #111111;" ></li>
					<li id="statsRecent" class="list summaryList"></li>
					<li id="statsMember" class="list summaryList"></li>
					<li id="statsLocation" class="list summaryList"></li>
					<li id="statsRating" class="list summaryList"></li>
				</ul>
			</div>
			<div id="newPic" style="float:left"></div>
			<div style="clear:both"></div>
		</div>
					
		<div class="gameSummary" style="float:left; width: 310px">
			<div class="summaryHeader">
				<ul class="list">
					<li class="list" style="border-top: 1px solid #111111;" >Number of games</li>
					<li class="list">Games played</li>
					<li class="list">Games never played</li>
					<li class="list">Total hours played</li>
					<li class="list">Account Price</li>
					<li class="list">Percentage played</li>
				</ul>
			</div>
			<div class="summaryData" style="width: 110px">
				<ul class="list">
					<li id="totalGames" class="list" style="border-top: 1px solid #111111;" ></li>
					<li id="gamePlayed" class="list"></li>
					<li id="neverPlayed" class="list"></li>
					<li id="totalHours" class="list"></li>
					<li id="totalPrice" class="list"></li>
					<li id="percentage" class="list">NA</li>
				</ul>		
			</div>
		</div>

		<div class="gameSummary" style= "float:left;">
			<div class="summaryHeader" style="width: 225px; margin-left:25px;">
				<ul class="list">
					<li class="list" style="border-top: 1px solid #111111;" >Elite<div style="float:right">100 hours</div></li>
					<li class="list">Hardcore<div style="float:right">50 hours</div></li>
					<li class="list">Experienced<div style="float:right">25 hours</div></li>
					<li class="list">Dedicated<div style="float:right">10 hours</div></li>
					<li class="list">Amateur<div style="float:right">5 hours</div></li>
					<li class="list">Newbie<div style="float:right">0 hours</div></li>
				</ul>
			</div>
			<div class="summaryData" style="width: 75px;">
				<ul class="list">
					<li id="elite" class="list" style="border-top: 1px solid #111111;" ></li>
					<li id="hardcore" class="list"></li>
					<li id="experienced" class="list"></li>
					<li id="dedicated" class="list"></li>
					<li id="amateur" class="list"></li>
					<li id="newbie" class="list"></li>
				</ul>		
			</div>
		</div>
		
		<div class="gameSummary" style= "float:left; margin-left:25px;">
			<div class="summaryHeader">
				<ul class="list">
					<li class="list" style="border-top: 1px solid #111111;" >Cost per game</li>
					<li class="list">Cost per hour</li>
					<li class="list">Time per game</li>
					<li class="list">Game time per day</li>
					<li class="list">Membership in days</li>
					<li class="list">Total Load Time</li>
				</ul>
			</div>
			<div class="summaryData">
				<ul class="list">
					<li id="CostGame" class="list" style="border-top: 1px solid #111111;" ></li>
					<li id="CostHour" class="list"></li>
					<li id="TimeGame" class="list"></li>
					<li id="gameDay" class="list"></li>
					<li id="memberDays" class="list"></li>
					<li id="StatsTime" class="list"></li>
				</ul>		
			</div>
		</div>
		<div style= "clear:both;"></div>

		<div id="Top10GamesBar" class="barCharts" style="width: 956px; height: 400px; margin-top: 20px"></div>
				
		<div style= "clear:both;"></div>
		<div id="table">
			<table id="games" class="table">
				<thead>
					<tr>
						<th style="text-align:left; padding-left:20px">Name</th>
						<th>Price</th>
						<th>Hours</th>
						<th>Cost</th>
					</tr>
				</thead>
				<tbody id="games_tbody">
				</tbody>
			</table>
		</div>
	</div>
</div>
<div>
</div>
</body>
</html>