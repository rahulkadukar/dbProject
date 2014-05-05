$(document).ready(function() {
    var allGameData = {};

    $("#back").click(function() {
        window.location.href = "index.php";
    });

    $("#submit").click(function() {
        var startTime = new Date().getTime();
        var data_array = new Array;
        var TotalGames = 0;
        var price = 0;
        var totalPrice = 0;
        var TotalGameStats = 0;
        var NeverPlayed = 0;
        var TopGames = [];
        var uid;
        var type;
        var fail;

        $("#loadingcircle   ").show();
        uid = $('#uid').val();
        //uid = 'shilp'
        if (uid) {
            $.get('functions/steamUserFetch.php', {'name': uid, }, function(userdata) {
                user_array = JSON.parse(userdata);
                if (user_array.hasOwnProperty('profile'))
                    if (user_array.profile.privacyState != 'public') {
                        showError("The ID that you entered '" + uid + "' is a private ID. Please enter a new ID or make the ID public");
                        return false;
                    }
                    else {
                        $.get('functions/allGameData.php', function(userdata) {
                            allGameData = JSON.parse(userdata);


                            /* At this part we have confirmed the existence of the user and now we want to fetch information pertaining to this user */
                            $.get('functions/userData.php', {'steamID': user_array.profile.steamID64, }, function(userdata) {
                                $("#initial").hide();
                                $("#final").show();
                                allData = JSON.parse(userdata);
                                $('#statsPicture').html("<img  class='img-responsive profile-border' src=" + allData.userData.avatarfull + '></img>');

                                highest = 0;
                                totalHours = 0;
                                for (i in allData.gameData) {
                                    if (highest < parseInt(allData.gameData[i].playtime_forever)) {
                                        mostPlayedappID = parseInt(allData.gameData[i].appid);
                                        highest = allData.gameData[i].playtime_forever;
                                    }
                                    totalHours += parseInt(allData.gameData[i].playtime_forever);
                                }

                                $('#mostPlayed').html("<img class='img-responsive bestgame-border' height='184px' src='http://cdn.steampowered.com/v/gfx/apps/" + mostPlayedappID + "/header.jpg'></img>");

                                totalHours /= 60;
                                var date = new Date(allData.userData.timecreated * 1000);
                                var month = date.getDate() + '-' + (date.getMonth() + 1) + '-' + date.getFullYear();
                                $('#statsName').html(allData.userData.realname);
                                $('#statsMember').html('Member since ' + month);
                                $('#statsLocation').html('Location ' + allData.userData.location);
                                $('#statsTotal').html('You Have Played for '+totalHours.toFixed(2) + ' hours');

                                count = 0;
                                for(i in allData.extraData.friendData)
                                    count++;
                                $('#statsFriends').html('You have '+ count + ' friends');
                                $('#statsGames').html('You Own ' + allData.gameData.length + ' games');
                                
                                //console.log(allGameData);
                                var newppid;
                                for(i in allData.gameData){
                                    newappid = parseInt(allData.gameData[i].appid);
                                  
                                   if(newappid)
                                   {
                                       if(allGameData[newappid])
                                        totalPrice += parseInt(allGameData[newappid].price);
                                }
                                }
                                
                                $('#statsPrice').html("You Own $"+ totalPrice/100 + " Worth of games");
                                  $('#steamMessage').html("Powered by steam.");

                                for (i in allData.gameData) {
                                    var GameData = {};
                                    var nameOfGame;
                                    if (allGameData[allData.gameData[i].appid])
                                        nameOfGame = allGameData[allData.gameData[i].appid].name;

                                    GameData.name = nameOfGame;
                                    hoursPlayed = allData.gameData[i].playtime_forever /= 60;
                                    hoursPlayed = parseFloat(allData.gameData[i].playtime_forever);
                                    GameData.time = parseFloat(hoursPlayed.toFixed(2));
                                    GameData.appID = allData.gameData[i].appid;
                                    TopGames.push(GameData);
                                }

                                TopGames.sort(function(a, b) {
                                    return b.time - a.time
                                });

                                TopGames = TopGames.slice(0, 10);

                                var Top10Games = [];
                                var Top10Names = [];
                                for (i in TopGames)
                                {
                                    if (TopGames[i].time == 0.00)
                                        continue;
                                    Top10Games.push(TopGames[i].time);
                                    Top10Names.push(TopGames[i].name);
                                }

                                var heightContainer = Top10Games.length;
                                var nameContainer = "#Top10GamesBar";
                                heightContainer = heightContainer * 40 + 100;
                                $(nameContainer).height(heightContainer);
                                var titleBar = "Top 10 games by play time";
                                var barChart =
                                        {
                                            "container": "Top10GamesBar",
                                            "title": titleBar,
                                            "xAxisCaption": "Name of game",
                                            "xAxisCategory": Top10Names,
                                            "yAxisCaption": "Number of hours",
                                            "unit": " hours",
                                            "dataValue": [{name: 'Gameplay Time', data: Top10Games}]
                                        };

                                $.barChart(barChart);

                                TopGameFriend = [];
                                for (j in allData.extraData.gameData) {
                                    var friendGames = allData.extraData.gameData[j];
                                    for (i in friendGames) {
                                        var friendappID = parseInt(friendGames[i].appID);
                                        if (TopGameFriend[friendappID])
                                            TopGameFriend[friendappID] += parseInt(friendGames[i].playtime);
                                        else
                                            TopGameFriend[friendappID] = parseInt(friendGames[i].playtime);
                                    }
                                }

                                TopGames = [];
                                for (i in TopGameFriend) {
                                    var GameData = {};
                                    var nameOfGame;
                                    if (allGameData[i])
                                        nameOfGame = allGameData[i].name;

                                    GameData.name = nameOfGame;
                                    hoursPlayed = TopGameFriend[i] /= 60;
                                    hoursPlayed = parseFloat(TopGameFriend[i]);
                                    GameData.time = parseFloat(hoursPlayed.toFixed(2));
                                    GameData.appID = i;
                                    TopGames.push(GameData);
                                }

                                TopGames.sort(function(a, b) {
                                    return b.time - a.time
                                });

                                var allOwnedIndex = [];
                                for (x in allData.gameData)
                                    allOwnedIndex[allData.gameData[x].appid] = 1;

                                var mustBuy = [];
                                count = 0;
                                for (i in TopGames) {
                                    if (!allOwnedIndex[TopGames[i].appID]) {
                                        count++;
                                        mustBuy.push(TopGames[i].appID);
                                    }
                                    if (count == 4)
                                        break;
                                }

                                TopGames = TopGames.slice(0, 10);

                                var Top10Games = [];
                                var Top10Names = [];
                                for (i in TopGames)
                                {
                                    if (TopGames[i].time == 0.00)
                                        continue;
                                    Top10Games.push(TopGames[i].time);
                                    Top10Names.push(TopGames[i].name);
                                }

                                var heightContainer = Top10Games.length;
                                var nameContainer = "#Top20GamesBar";
                                heightContainer = heightContainer * 40 + 100;
                                $(nameContainer).height(heightContainer);
                                var titleBar = "Top 10 games for friends by play time";
                                var barChart =
                                        {
                                            "container": "Top20GamesBar",
                                            "title": titleBar,
                                            "xAxisCaption": "Name of game",
                                            "xAxisCategory": Top10Names,
                                            "yAxisCaption": "Number of hours",
                                            "unit": " hours",
                                            "dataValue": [{name: 'Gameplay Time', data: Top10Games}]
                                        };

                                $.barChart(barChart);
                                count = 1;
                                for (x in mustBuy) {
                                    var image = "#" + count;
                                    $(image).html("<img class='img-thumbnail' src=http://cdn.steampowered.com/v/gfx/apps/" + mustBuy[x] + "/header.jpg></img>");
                                    count++;
                                }

                                var gamesplayed = [0, 0, 0, 0, 0];

                                for (i in allData.gameData)
                                {
                                    if (allData.gameData[i].playtime_forever == 0)
                                    {

                                        gamesplayed["0"] += 1;
                                    }
                                    else if (allData.gameData[i].playtime_forever > 0 & allData.gameData[i].playtime_forever < 1)
                                    {
                                        gamesplayed["1"] += 1;
                                    }
                                    else if (allData.gameData[i].playtime_forever > 1 & allData.gameData[i].playtime_forever < 5)
                                    {

                                        gamesplayed["2"] += 1;
                                    }
                                    else if (allData.gameData[i].playtime_forever > 5 & allData.gameData[i].playtime_forever < 50)
                                    {
                                        gamesplayed["3"] += 1;
                                    }
                                    else if (allData.gameData[i].playtime_forever > 50)
                                    {

                                        gamesplayed["4"] += 1;
                                    }
                                }

                                $('#userpiechart').highcharts({
                                    chart: {
                                        plotBackgroundColor: null,
                                        plotBorderWidth: null,
                                        plotShadow: false
                                    },
                                    title: {
                                        text: 'Time spent on games'
                                    },
                                    tooltip: {
                                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                                    },
                                    plotOptions: {
                                        pie: {
                                            allowPointSelect: true,
                                            cursor: 'pointer',
                                            dataLabels: {
                                                enabled: true,
                                                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                                style: {
                                                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                                }
                                            }
                                        }
                                    },
                                    series: [{
                                            type: 'pie',
                                            name: 'Browser share',
                                            data: [
                                                ['Never Played', gamesplayed[0]],
                                                ['Less than 1 hr', gamesplayed[1]],
                                                {
                                                    name: 'Between 1 hr & 5 hrs',
                                                    y: gamesplayed[2],
                                                    sliced: true,
                                                    selected: true
                                                },
                                                ['Between 5hrs & 50 hrs', gamesplayed[3]],
                                                ['More than 50hrs', gamesplayed[4]]
                                            ]
                                        }]
                                });

                                //----------------End of pie chart-----------                  


                                $.get('functions/locationData.php', {'location': allData.userData.location}, function(userdata) {
                                    locationData = JSON.parse(userdata);
                                    console.log(locationData);
                                    TopGames = [];
                                    for (i in locationData) {
                                        var GameData = {};
                                        var nameOfGame;
                                        if (allGameData[i])
                                            nameOfGame = allGameData[i].name;

                                        GameData.name = nameOfGame;

                                        hoursPlayed = locationData[i] /= 60;
                                        hoursPlayed = parseFloat(locationData[i]);
                                        GameData.time = parseFloat(hoursPlayed.toFixed(2));
                                        GameData.appID = i;
                                        TopGames.push(GameData);
                                    }


                                    TopGames.sort(function(a, b) {
                                        return b.time - a.time
                                    });

                                    var mustBuy = [];
                                    count = 0;
                                    for (i in TopGames) {
                                        if (!allOwnedIndex[TopGames[i].appID]) {
                                            count++;
                                            mustBuy.push(TopGames[i].appID);
                                        }
                                        if (count == 4)
                                            break;
                                    }
                                    console.log(mustBuy);

                                    TopGames = TopGames.slice(0, 10);

                                    var Top10Games = [];
                                    var Top10Names = [];
                                    for (i in TopGames)
                                    {
                                        if (TopGames[i].time == 0.00)
                                            continue;
                                        Top10Games.push(TopGames[i].time);
                                        Top10Names.push(TopGames[i].name);
                                    }

                                    var heightContainer = Top10Games.length;
                                    var nameContainer = "#Top10GamesCountry";
                                    heightContainer = heightContainer * 40 + 100;
                                    $(nameContainer).height(heightContainer);
                                    var titleBar = "Top 10 games by play time for country " + allData.userData.location;
                                    var barChart =
                                            {
                                                "container": "Top10GamesCountry",
                                                "title": titleBar,
                                                "xAxisCaption": "Name of game",
                                                "xAxisCategory": Top10Names,
                                                "yAxisCaption": "Number of hours",
                                                "unit": " hours",
                                                "dataValue": [{name: 'Gameplay Time', data: Top10Games}]
                                            };

                                    $.barChart(barChart);
                                    count = 5;
                                    for (x in mustBuy) {
                                        var image = "#" + count;
                                        $(image).html("<img class='img-thumbnail' src=http://cdn.steampowered.com/v/gfx/apps/" + mustBuy[x] + "/header.jpg></img>");
                                        count++;
                                    }

                                });
                                $("#loadingcircle").hide();
                                console.log(allData);
                            });
                        });

                    }
                else {
                    showError("It seems that the ID that you have entered is incorrect. Please try with a proper ID");
                    return false;
                }
            });

        }
        else {
            var url = document.URL;
            if ((url.substr(url.length - 4, 3)) == "UID")
                showError("Enter a Steam ID to proceed further");
        }
    });
});


function showError(text) {
    $("#errorMessage").html(text);
    $("#errorblock").show();
     $("#loadingcircle").hide();
}