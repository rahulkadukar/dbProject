This section contains the code that we are really going to use for our project. The project can best be described as shown below

###**STEAM**###

Steam is a digital distribution, digital rights management, and multiplayer platform developed by 
Valve Corporation. It is mainly used to distribute games as downloads and to simplify the process 
of online matchmaking. Steam provides the user with installation and automatic management of 
software across multiple computers, as well as community features such as friend’s lists and 
groups, cloud saving, and in-game voice and chat functionality. As of January 2014, there are over 
3000 games available on Steam and 75 million active users. It is estimated that 75% of all 
purchased PC games are downloaded through Steam. It is the largest gaming social network of its 
kind. 

 

###**WHAT WE PLAN TO DO**###

The software provides a freely available application programming interface (API), Steamworks, 
which can be used to access the public information of steam users. Our project is aimed to help 
users in choosing to play or download games and collaborate with their friends and the online 
community to make their gaming experience better. For this purpose we plan to fetch a user’s data 
and show him a visual graph of the games he owns and plays, sorted by playtime and genre. We 
will also fetch the data of the user’s friends and then show him a comparison of the types of games 
that their friend’s play so that they can purchase those same games and hence have a better gaming 
experience by collaborating with the online community. 

 

####Popularity by app genre/category####
Apps often have multiple genres and categories and it impacts other stats. For example, say we 
want to calculate the fraction of time we spend playing different genres, like Action games vs 
Adventure games. What if there is a game which has both Action and Adventure in its genres? 
Here are the two simplest approaches that we are considering: 
1. Split evenly: Split the playtime into equal-sized chunks for each genre that an app belongs. 
2. Overcount: Count the playtime fully (100%) in each genre that an app belongs to. 

 
We are going to provide a toggle whereby the user will be able to view the data in one of the two 
views and hence can see the data as they see fit. Analytically they represent the same information 
and the decision to view it is left to the user. 

 
####Value of an app by playtime ####

Say we want to figure out which game we should purchase. If we look at the games our friends 
own we can predict the dollars/hours based on the current prices and the playtime of our friends. 
However, maybe we have some friends who play Steam games 100 hours a week, and some who 
only play ten. If we calculate the value by raw playtime, the players who use Steam 100 hours a 
week can skew results. If we calculate the value by fractional playtime (aka "indexed"), the players 
who use Steam 10 hours a week can skew results. 

The data can be shown in 2 ways as shown below and the user can then view the data as per the 
requirement, and analytically the two views are equivalent. 

####Raw####

**Example**: Of all your friends, the total play time on Game A is 250 hours. The current price is 25.00 USD. 

The raw score is $25/250hr = $0.10 per hour 

 

#### Indexed ####

**Formula**: Current price of game / ((Friends' total play time for this game) / (Friends total 
Steam play time)) 

**Example**: Of all your friends, the total play time on Game A is 250 hours. However, their total lifetime Steam play time adds up to 2500 hours. So, their fraction of time played on Game A, out of all Steam play time is 10 percent. The current price is 25.00 USD. 

The indexed score is $25/ (250/2500) = $2.5/% playtime 

###**DATA VISUALIZATION**###

Apart from the data analytics done above we are planning to view the data from the following perspectives. 

1. **Most active time of friends** 
The Steam API allows access to achievement unlock times of a user for a game, based on this information we plan to use the timestamp to determine at what time a particular user is active. 
2. **Game playtime by Region (Country):** This will be used to show the most popular games in a particular country by the number of users who play a particular game based on the number of hours spent on a particular game. 
3. **Genre playtime by Region (Country):** This will be used to show the most popular genres in a particular country by the number of users who play a particular game based on the number of hours spent on a particular game. 


We are planning to do the data retrieval, integration and analytics. Each one of us will be responsible for one of the above features.
