<?php

require_once ('../config.php');

$accesstoken = $_COOKIE["bungie_access_token"];
$membership_id = $_COOKIE["membership_id"];
$membership_type = $_COOKIE["membership_type"];
$first_character_id =  $_COOKIE["first_character_id"];

$ch = curl_init();

$url = "https://www.bungie.net/Platform/Destiny/" . $membership_type . "/MyAccount/Character/" . $first_character_id . "/Vendor/1575820975/" . "?definitions=true";

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-API-KEY: '.$BUNGIE_API_X,
        'Authorization: Bearer '.$accesstoken
));

$jsonstr = curl_exec($ch);
$salesitems = json_decode($jsonstr);
$temp = 
curl_close($ch);

?>

<html>
 <head>
  <title>Welcome to /r/DTG/</title>
 </head>
 <body>
    <h1>Admin - Login Vendor Info</h1>
    <h2><?php echo $url ?></h3>
    <!--<h3><?php echo $jsonstr ?></h3>-->
    <p><?php echo $salesitems->Response->data->saleItemCategories[0]->saleItems[0]->item->itemHash ?></p>
 </body>
</html>