<?php

require_once ('../config.php');

$accesstoken = $_COOKIE["bungie_access_token"];
$membership_id = $_COOKIE["membership_id"];
$membership_type = $_COOKIE["membership_type"];
$first_character_id =  $_COOKIE["first_character_id"];

$ch = curl_init();

$warlockVanguardURL = "https://www.bungie.net/Platform/Destiny/" . $membership_type . "/MyAccount/Character/" . $first_character_id . "/Vendor/1575820975/" . "?definitions=true";

curl_setopt($ch, CURLOPT_URL, $warlockVanguardURL);

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
  <link rel="stylesheet" type="text/css"href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
 </head>
 <body>
 <div class="container">
    <h1>Admin - Vendor Info</h1>
    <?php 
    
        echo "<h3>Warlock Vanguard</h3>";
        echo "<table class='table table-condensed table-striped'>";
        echo "<tr><th>Hash</th> <th>Name</th> <th>Type</th> <th>Perks 1</th> <th>Perks 2</th> <th>Stats</th> <th>T12</th> </tr>";
        foreach($salesitems->Response->data->saleItemCategories as $saleCategories)
        {
                foreach($saleCategories->saleItems as $saleitem)
                {                        
                        $itemhash = $saleitem->item->itemHash;
                        echo "<tr>" ;
                        echo "<td>" . $itemhash . "</td>";
                        echo "<td>" . $salesitems->Response->definitions->items->$itemhash->itemName . "</td>";
                        echo "<td>" . "" . "</td>";
                        echo "<td>" . "" . "</td>";
                        echo "<td>" . "" . "</td>";
                        echo "<td>" . "" . "</td>";
                        echo "<td>" . "" . "</td>";
                        echo "</tr>";
                }
        }
        echo "</table>";
    
    ?>
 </div>   
 </body>
</html>