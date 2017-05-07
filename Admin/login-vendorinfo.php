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
    
        echo "<table class='table table-condensed table-striped'>";
        echo "<tr><th>Vendor</th> <th>Name</th> <th>Type</th> <th>Perks 1</th> <th>Perks 2</th> <th>Perks 3</th> <th>Int</th> <th>Dis</th> <th>Str</th> <th>T12</th> </tr>";
        foreach($salesitems->Response->data->saleItemCategories as $saleCategories)
        {
                foreach($saleCategories->saleItems as $saleitem)
                {       
                        //Hash
                        $itemhash = $saleitem->item->itemHash;

                        //Item Name
                        $itemname = $salesitems->Response->definitions->items->$itemhash->itemName;

                        //Item Type
                        if(isset($salesitems->Response->definitions->items->$itemhash->itemTypeName)) {
                                $itemtype = $salesitems->Response->definitions->items->$itemhash->itemTypeName;
                        } else {
                                $itemtype = "";
                        }

                        //Intellect
                        $intellect = 0;
                        foreach($saleitem->item->stats as $stat) {
                                if($stat->statHash == 144602215) 
                                        $intellect = $stat->value;                               
                        }
                        
                         //Discipline
                         $discipline = 0;
                        foreach($saleitem->item->stats as $stat) {
                                if($stat->statHash == 1735777505) 
                                        $discipline = $stat->value;
                        }


                        //Strength
                        $strength = 0;
                        foreach($saleitem->item->stats as $stat) {
                                if($stat->statHash == 4244567218) 
                                        $strength = $stat->value;
                        }

                        //Perk 1
                        $perk1 = "";
                        if(isset($saleitem->item->perks[0]->perkHash)) {
                                $perk1hash = $saleitem->item->perks[0]->perkHash;
                                $perk1 = $salesitems->Response->definitions->perks->$perk1hash->displayName;
                        }

                        //Perk 2
                        $perk2 = "";
                        if(isset($saleitem->item->perks[1]->perkHash)) {
                                $perk2hash = $saleitem->item->perks[1]->perkHash;
                                $perk2 = $salesitems->Response->definitions->perks->$perk2hash->displayName;
                        }

                        //Perk 3
                        $perk3 = "";
                        if(isset($saleitem->item->perks[2]->perkHash)) {
                                $perk3hash = $saleitem->item->perks[2]->perkHash;
                                $perk3 = $salesitems->Response->definitions->perks->$perk2hash->displayName;
                        }

                        echo "<tr>" ;
                        echo "<td>" . "Warlock Vanguard" . "</td>";
                        echo "<td>" . $itemname . "" . "</td>";
                        echo "<td>" . $itemtype . "" . "</td>";                        
                        echo "<td>" . $perk1 . "</td>";
                        echo "<td>" . $perk2 . "</td>";
                        echo "<td>" . $perk3 . "</td>";
                        echo "<td>" . $intellect . "</td>";
                        echo "<td>" . $discipline . "</td>";
                        echo "<td>" . $strength . "</td>";
                        echo "<td>" . "" . "</td>";
                        echo "</tr>";
                }
        }
        echo "</table>";
    
    ?>
 </div>   
 </body>
</html>