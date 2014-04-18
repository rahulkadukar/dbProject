<!DOCTYPE html>
<html>
    <head>
		<link rel="stylesheet" href="CSS/jquery.mobile.css" type="text/css" />
        <link rel="stylesheet" href="CSS/main.css" type="text/css" />
		<title>Steam Reader</title>
		<script type="text/javascript" src="JavaScript/jquery.js"></script>
		<script type="text/javascript" src="JavaScript/jquery.mobile.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				var critic_sort = 0;
			
				$.get('gameList.php',function(gameData){
					gameData = JSON.parse(gameData);
					for(i in gameData){
						console.log(gameData[i]);
						var name 	= gameData[i].name;
						var price 	= gameData[i].price;
						var critic	= gameData[i].critic;
						var people	= gameData[i].people;
						var date	= gameData[i].date;
						var stats	= gameData[i].stats;
						var tableRow = '<tr><td>'+name+'</td><td>'+price+'</td><td>'+critic+'</td><td>'+people+'</td><td>'+date+'</td>';
						tableRow += '<td>'+stats+'</td></tr>';
						$(tableRow).appendTo('#games_tbody');
					}
				});
				
				$("#critic_sort").click(function(){
					if(critic_sort == 0){
						critic_sort = 1;
						$.get('gameSort.php',{'sort':1},function(gameData){
							gameData = JSON.parse(gameData);
							 $("#games_tbody").find("tr").remove();
							for(i in gameData){
								console.log(gameData[i]);
								var name 	= gameData[i].name;
								var price 	= gameData[i].price;
								var critic	= gameData[i].critic;
								var people	= gameData[i].people;
								var date	= gameData[i].date;
								var stats	= gameData[i].stats;
								var tableRow = '<tr><td>'+name+'</td><td>'+price+'</td><td>'+critic+'</td><td>'+people+'</td><td>'+date+'</td>';
								tableRow += '<td>'+stats+'</td></tr>';
								$(tableRow).appendTo('#games_tbody');
							}
						});
					}
					else{
						critic_sort = 0;
						$.get('gameSort.php',{'sort':0},function(gameData){
							gameData = JSON.parse(gameData);
							$("#games_tbody").find("tr").remove();
							for(i in gameData){
								console.log(gameData[i]);
								var name 	= gameData[i].name;
								var price 	= gameData[i].price;
								var critic	= gameData[i].critic;
								var people	= gameData[i].people;
								var date	= gameData[i].date;
								var stats	= gameData[i].stats;
								var tableRow = '<tr><td>'+name+'</td><td>'+price+'</td><td>'+critic+'</td><td>'+people+'</td><td>'+date+'</td>';
								tableRow += '<td>'+stats+'</td></tr>';
								$(tableRow).appendTo('#games_tbody');
							}
						});
					}
				});
			});
		</script>
	</head>
	<body>
		<div data-role="page" data-theme="a">
			<div data-role="header">
				<h1>Page Title</h1>
			</div>
			

			<div id="table">
				<table id="games" class="table">
					<thead>
						<tr>
							<th>Name</th>
							<th>Price</th>
							<th id="critic_sort">Metacritic</th>
							<th>Recommended by</th>
							<th>Released On</th>
							<th>Achievements</th>
						</tr>
					</thead>
					<tbody id="games_tbody">
					</tbody>
				</table>
			</div>				
		
			<div id="footer" data-role="footer">
				<h4>Page Footer</h4>
		  </div>
		</div>
	</body>
</html>