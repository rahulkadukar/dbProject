<?php

$url = "http://steamcommunity.com/profiles/76561197960270113/games?tab=all&xml=1";
$contents = get_file_contents($url);

function get_file_contents($url)
{
$tries = 0;
do
{
if ($tries > 0) sleep(1); # Wait for a sec before retrieving again
$contents = @file_get_contents($url);
print_r(simplexml_load_string($contents));
$tries++;
} while (tries <= 5 && $contents === FALSE);
if ($contents == "") $contents = FALSE;
print_r($contents);
}

?>