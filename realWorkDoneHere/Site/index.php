<?php
	error_reporting(E_ALL ^ E_NOTICE);
	include 'functions/database.php';	
?>
<html>
	<head>
		<link rel="stylesheet" href="CSS/main.css" type="text/css" />
		<script type="text/javascript" src="JavaScript/jquery.js"></script>
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
                $uid  = $_GET['UID'];
				if($uid)
				{
					$key = 'FCCAA3E90D04C71D59EAD2822B2AF90B';
					$index	= 0;
					$join = 0;
					$sql   = "SELECT * FROM `valve`.`game_master`";
					$gameData = $link->query($sql);
					while($row = $gameData->fetch_array()){
						$t_prices[$row["appID"]] = $row["price"];
						$t_games[$row["appID"]] = $row["name"];
						$index++;
					}			
					$t_prices = json_encode($t_prices);
					$t_games = json_encode($t_games);
					
					echo "var prices_array = $t_prices;";
					echo "var game_array = $t_games;";
					echo "uid = '$uid';";
                }
            ?>
			
			if(uid){
				$.get('functions/steamUserFetch.php',{'name': uid,},function(userdata){
					user_array = JSON.parse(userdata);
					if(user_array.hasOwnProperty('profile'))
						if(user_array.profile.privacyState != 'public'){
							showError("The ID that you entered '"+uid+"' is a private ID. Please enter a new ID or make the ID public");
							return false;
						}
						else{
						/* At this part we have confirmed the existence of the user and now we want to fetch information pertaining to this user */
							$.get('functions/userData.php',{'steamID': user_array.profile.steamID64,},function(userdata){
								$("#initial").hide();
								allData = JSON.parse(userdata);
								$('#statsPicture').html('<img id="avatar" src='+ allData.userData.avatarfull + '></img>');
								
								highest = 0;
								totalHours = 0;
								for(i in allData.gameData){
									if(highest < parseInt(allData.gameData[i].playtime_forever)){
										mostPlayedappID = parseInt(allData.gameData[i].appid);
										highest = allData.gameData[i].playtime_forever;
									}
									totalHours += parseInt(allData.gameData[i].playtime_forever);
								}
								
								$('#mostPlayed').html('<img height="184px" src="http://cdn.steampowered.com/v/gfx/apps/'+mostPlayedappID+'/header.jpg"></img>');
								
								$('#statsName').html(allData.userData.realname);
								$('#statsMember').html(allData.userData.timecreated);
								$('#statsLocation').html(allData.userData.location);
								$('#statsTotal').html(totalHours);
								console.log(allData);
							});
						}
					else{
						showError("It seems that the ID that you have entered is incorrect. Please try with a proper ID");
						return false;
					}
				});
			}
			else{
				var url = document.URL;
				if((url.substr(url.length - 4,3))=="UID")
					showError("Enter a Steam ID to proceed further");
			}
		});
			
			
			function showError(text){
				$("#errorMessage").html(text);
				$("#errorMessage").show();
			}
		</script>
		<title>My Website</title>
	</head>
	<body>
		<div id="header">
			<ul>
				<li style="color: #41b7d8">Steam Profile Analyser</li>
			</ul>
		</div>
		<div id="container">
			<div id="content">
				<div id="initial">
					<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" data-ajax="false">
						<input type="text" name="UID" id="uid" placeholder="Enter Steam ID or Custom URL" />
						<input type="submit" id="submit" value="Submit"/>
					</form>
					
					<p class="header">Steam</p>
					<p>Steam is a digital distribution, digital rights management, and multiplayer platform developed by Valve Corporation. It is mainly used to distribute games as downloads and to simplify the process of online matchmaking. Steam provides the user with installation and automatic management of software across multiple computers, as well as community features such as friend’s lists and groups, cloud saving, and in-game voice and chat functionality. As of January 2014, there are over 3000 games available on Steam and 75 million active users. It is estimated that 75% of all purchased PC games are downloaded through Steam. It is the largest gaming social network of its kind.</p>
					<div id="errorMessage" class="someText errorMessage" style="display:none"></div>
				</div>
				
				<div id="final">
					<div id="statsPicture" class="normal" style="float:left"></div>
					<div id="mostPlayed" class="normal" style="float:left"></div>
					<div id="statsSummary" style="float:left">
						<ul class="list">
							<li id="statsName" class="list summaryList" style="border-top: 1px solid #111111;" ></li>
							<li id="statsTotal" class="list summaryList"></li>
							<li id="statsMember" class="list summaryList"></li>
							<li id="statsLocation" class="list summaryList"></li>
						</ul>
					</div>
				</div>
			</div>
			<div id="footer">&copy; Siddharth Modala, Kalidas Nalla, Rahul Kadukar 2014</div>
		</div>
	</body>
</html>