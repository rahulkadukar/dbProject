<?php
error_reporting(E_ALL ^ E_NOTICE);
include 'functions/database.php';
?>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title>Steam Profile Analyser</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="./css/bootstrap.css" media="screen">
        <link rel="stylesheet" href="./css/bootswatch.min.css">
        <link rel="stylesheet" href="./css/style.css">
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
            <script src="../bower_components/html5shiv/dist/html5shiv.js"></script>
            <script src="../bower_components/respond/dest/respond.min.js"></script>
        <![endif]-->
        <title>Steam Profile Analyser</title>
    </head>

    <body>
        <div class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header"> <a href="" class="navbar-brand">Home</a>

                    <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main"> <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>

                    </button>
                </div>
                <div class="navbar-collapse collapse" id="navbar-main">
                    <ul class="nav navbar-nav">
                        <li> <a href="#">Help</a>

                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div id="wrap">
            <div id="main" class="container clear-top">
                <div class="container" style="height:auto">
                    <div class="page-header" id="banner">
                        <div id="initial">
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-lg-6"><input type="text" class="form-control" id="uid" placeholder="Enter Steam ID"></div>
                                    <button type="button" id="submit" class="btn btn-default">Submit</button>
                                    <img src="./loading8.gif" style="display: none" id="loadingcircle">
                                </div>
                            </div>

                            <div id="errorblock"class="alert alert-dismissable alert-danger" style="display:none">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>Oh snap!</strong> 
                                <div id="errorMessage" class="someText errorMessage" ></div>
                            </div>

                            <div class="jumbotron">
                                <h1>Steam</h1>
                                <p>Steam is a digital distribution, digital rights management, and multiplayer platform developed by Valve Corporation. It is mainly used to distribute games as downloads and to simplify the process of online matchmaking. Steam provides the user with installation and automatic management of software across multiple computers, as well as community features such as friend’s lists and groups, cloud saving, and in-game voice and chat functionality. As of January 2014, there are over 3000 games available on Steam and 75 million active users. It is estimated that 75% of all purchased PC games are downloaded through Steam. It is the largest gaming social network of its kind.  </p>


                            </div>

                        </div>
                        <div class="row">
                            &nbsp;
                        </div>

                        <div id="final" style="display:none">
                            <div class="row">
                                <div class="col-lg-2">
                                    <div id="statsPicture" class="normal" style="float:left"></div>
                                </div>
                                <div class="col-lg-4">
                                    <div id="mostPlayed" class="normal" style="float:left"></div>
                                </div>
                                <div class="col-lg-3">
                                    <ul class="list-group">
                                        <li id="statsName" class="list-group-item"></li>
                                        <li id="statsTotal" class="list-group-item"></li>
                                        <li id="statsMember" class="list-group-item"></li>
                                        <li id="statsLocation" class="list-group-item"></li>
                                    </ul>
                                </div>
                                <div class="col-lg-3">
                                    <ul class="list-group">
                                        <li id="statsPrice" class="list-group-item"></li>
                                        <li id="statsGames" class="list-group-item"></li>
                                        <li id="statsFriends" class="list-group-item"></li>
                                        <li id="steamMessage" class="list-group-item"></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row">
                                &nbsp;
                            </div>



                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h2 class="panel-title">Top 10 games that you play</h2>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div id="Top10GamesBar" class="col-lg-12 barCharts" ></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                &nbsp;
                            </div>

                            <!-------------Start of pie chart ------------------>

                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h2 class="panel-title">Time spent</h2>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div id="userpiechart" class="col-lg-12" ></div>

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                &nbsp;
                            </div>

                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Recommended games to buy.</h3>
                                </div>
                                <div class="panel-body">

                                    <div class="row">
                                        <div id="1" class="col-lg-3" ></div>
                                        <div id="2" class="col-lg-3"></div>
                                        <div id="3" class="col-lg-3"></div>
                                        <div id="4" class="col-lg-3"></div>
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                &nbsp;
                            </div>


                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Top 10 most popular games among you friends</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div id="Top20GamesBar" class="col-lg-12 barCharts" ></div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                &nbsp;
                            </div>

                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Recommended games to buy based on country</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div id="5" class="col-lg-3" ></div>
                                        <div id="6" class="col-lg-3"></div>
                                        <div id="7" class="col-lg-3"></div>
                                        <div id="8" class="col-lg-3"></div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                &nbsp;
                            </div>

                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Top 10 most popular games in your country</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div id="Top10GamesCountry" class="col-lg-12 barCharts" ></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer">

            <div class="row">
                <div class="col-lg-2">
                    <ul class="list-unstyled">
                        <li><a href="https://github.com/thomaspark/bootswatch/">GitHub</a></li>
                    </ul>
                </div>

                <div class="col-lg-8">
                    <img src="globalheader_logo.png">
                    This website is powered by data collected  from 
                    <a href="www.steampowered.com">Steam</a> and<a href="www.valvesoftware.com/"> Valve</a>
                    <img src="logo_valve_footer.jpg">
                </div>

                <div  class="col-lg-2">
                    <p class="pull-right">Made by <a href="http://thomaspark.me" rel="nofollow">Siddharth Modala, Kalidas Nalla, Rahul Kadukar</a></p>
                </div>
            </div>
        </footer>
        <script src="./js/jquery-1.10.2.min.js"></script>
        <script src="./js/bootstrap.min.js"></script>
        <script src="./js/bootswatch.js"></script>
        <script src="./js/highcharts.js"></script>
        <script src="./js/exporting.js"></script>
        <script src="./js/charts.js"></script>
        <script src="./js/userfunctions.js"></script>
    </body>
</html>