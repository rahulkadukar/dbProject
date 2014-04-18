<?php
$ch = curl_init();

$url = $_POST['picLink'];
$uid = $_POST['steamID'];
$uid = $uid.'.jpg';

if(!$url)
	$url = "http://media.steampowered.com/steamcommunity/public/images/avatars/f7/f74f41fb433e5564b3837dee3701ca3aafd8d20f_full.jpg";

$ch = curl_init($url);
$fp = fopen($uid, 'wb');
curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_exec($ch);
curl_close($ch);
fclose($fp);

$path = $uid;
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

$data = $base64;
list($type, $data) = explode(';', $data);
list(, $data)      = explode(',', $data);
$data = base64_decode($data);
file_put_contents($path, $data);
?>