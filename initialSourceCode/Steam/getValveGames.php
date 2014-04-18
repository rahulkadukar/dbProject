<!DOCTYPE html>
<html>
    <head>
		<link rel="stylesheet" href="CSS/steam.css" type="text/css" />
        <link rel="stylesheet" href="CSS/jquery.mobile.css" type="text/css" />
        <title>Valve Employee</title>
		<script type="text/javascript" src="JavaScript/jquery.js"></script>
        <script type="text/javascript" src="JavaScript/jquery.xml2json.js"></script>
		<script type="text/javascript" src="JavaScript/jquery.tablesorter.min.js"></script>
        <script type="text/javascript" src="JavaScript/jquery.mobile.js"></script>
		<script type="text/javascript" src="JavaScript/json2.js"></script>
		<script type="text/javascript" src="JavaScript/highcharts.js"></script>
		<script type="text/javascript" src="JavaScript/charts.js"></script>
		<script type="text/javascript">
		$(document).ready(function(){
			<?php
				error_reporting(E_ALL ^ E_NOTICE);
				include 'Functions/database.php';			
			?>
			
			var gameList = [];
			
			$("#startFetch").click(function(){
				$.get('Functions/gameFetch.php', function(data){
					var game_array = JSON.parse(data);
					$("#gameCount").html("Number of games found for the user "+game_array.response.game_count);
					$(".contents").show();
					console.log(game_array.response.game_count);
					for(i in game_array.response.games){
						gameList.push(game_array.response.games[i].appid);
					}
				});
			});
		});
		</script>
	</head>
	<body>
		<div data-theme="a" data-role="page">
			<div data-role="header" data-theme="b">
				<h1>Valve User Analysis</h1>
			</div>

			<div data-role="content" id="content">
				<div>
					<input type="button" data-corners="false" id="startFetch" data-theme="b" value="Click here to start processing" />
				</div>
				
				<div class="contents" style="display:none">
					<p id="gameCount" />
				</div>
			</div>
	</body>
</html>