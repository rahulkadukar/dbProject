<!DOCTYPE html>
<html>

<head>
	<link rel="stylesheet" href="CSS/Steam.css" type="text/css" />
	<title>Steam Profile data fetcher</title>
		<script type="text/javascript" src="JavaScript/jquery.js"></script>
		<script type="text/javascript" src="JavaScript/jquery.xml2json.js"></script>
		<script type="text/javascript">
        $(document).ready(function(){
			var uid;
			var finalData = {gameData:{}, userData:{}, friendData:{}};

			$("#submit").click(function(){
				uid = $("#uid").val();
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
									userData = JSON.parse(userdata);
									var friendsData = [];
									var friend = {};
									for(i in userData.friendData)
										friendsData.push(userData.friendData[i].steamid);
									
									friend = JSON.stringify(friendsData);
									console.log(friend);
									console.log(friendsData);
									$.get('functions/fetchAll.php',{'friendsData': friendsData},function(userdata){
									
									});
								});
							}
						else{
							showError("It seems that the ID that you have entered is incorrect. Please try with a proper ID");
							return false;
						}
					});
				}
				else{
					showError("Enter a Steam ID to proceed further");
				}
			});

			function showError(text){
				$("#errorMessage").html(text);
				$("#errorMessage").show();
			}
		});
		</script>
</head>

<body>
<!-- This part is the page header -->
	<div id="header">
		<div style="width:300px; margin:auto" id="titleText">Steam Profile Analyser</div>
	</div>

<!-- This part is the main contents for the page -->
	<div id="content">
		<input type="text" name="uid" id="uid" placeholder="Enter Steam ID or Custom URL" />
		<input type="submit" id="submit" value="Submit"/>
		
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

		<div id="errorMessage" class="someText errorMessage" style="display:none"></div>
		<div id="statsPicture" style="float:left"></div>
	</div>
</body>

</html>