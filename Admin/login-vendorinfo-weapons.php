<?php

require_once ('../config.php');

$accesstoken = $_COOKIE["bungie_access_token"];
$membership_id = $_COOKIE["membership_id"];
$membership_type = $_COOKIE["membership_type"];
$titan_character_id =  $_COOKIE["titan_character_id"];
$warlock_character_id =  $_COOKIE["warlock_character_id"];
$hunter_character_id =  $_COOKIE["hunter_character_id"];

class Weapon {
        public $itemhash;
        public $itemname;
        public $itemtype;        
        public $perk1;
        public $perk2;
        public $perk3;        
}


function unneededType($type) {   
        if($type == ""   or $type == "sword" or $type == "crucible bounty" or $type == "vehicle" or $type == "material" or $type == "consumable"
        or $type == "material exchange" or $type == "weekly elite bounty" or $type == "engram") {
             return true;   
        }
        else {
             return false;
        }
}

function isGun($type) {   
        if($type == "auto rifle"   or $type == "hand cannon" or $type == "sidearm" or $type == "shotgun" or $type == "sniper rifle" or $type == "machine gun"
        or $type == "rocket launcher" or $type == "pulse rifle" or $type == "fusion rifle" or $type == "scout rifle") {
             return true;   
        }
        else {
             return false;
        }
}

function getWeaponInfo($jsoninput) {   

        $salesitems = json_decode($jsoninput);
        
        $weaponArray = array();
        foreach($salesitems->Response->data->saleItemCategories as $saleCategories) {
                                
                foreach($saleCategories->saleItems as $saleitem) {                               

                        //Hash
                        $hash = $saleitem->item->itemHash;                        
                        $name =  strtolower($salesitems->Response->definitions->items->$hash->itemName);
                        $type = "";
                        if(isset($salesitems->Response->definitions->items->$hash->itemTypeName)) {
                                $type = strtolower($salesitems->Response->definitions->items->$hash->itemTypeName);
                        }

                        if(unneededType($type) == false)
                        {
                                if(isGun($type)) {
                                        $weapon = new Weapon();                                        
                                        //Hash
                                        $weapon->itemhash = $saleitem->item->itemHash;
                                        //Item Name
                                        $itemhash = $weapon->itemhash;
                                        $weapon->itemname = str_replace("'", "", $salesitems->Response->definitions->items->$itemhash->itemName);
                                        //Item Type
                                        $weapon->itemtype = "";
                                        if(isset($salesitems->Response->definitions->items->$itemhash->itemTypeName)) {
                                                $weapon->itemtype = $salesitems->Response->definitions->items->$itemhash->itemTypeName;
                                        }
                                        
                                        //Perk 1
                                        $weapon->perk1 = "";
                                        if(isset($saleitem->item->perks[0]->perkHash)) {
                                                $perk1hash = $saleitem->item->perks[0]->perkHash;
                                                $weapon->perk1 = str_replace("'","",$salesitems->Response->definitions->perks->$perk1hash->displayName);
                                        }
                                        //Perk 2
                                        $weapon->perk2 = "";
                                        if(isset($saleitem->item->perks[1]->perkHash)) {
                                                $perk2hash = $saleitem->item->perks[1]->perkHash;
                                                $weapon->perk2 = str_replace("'","",$salesitems->Response->definitions->perks->$perk2hash->displayName);
                                        }
                                        //Perk 3
                                        $weapon->perk3 = "";
                                        if(isset($saleitem->item->perks[2]->perkHash)) {
                                                $perk3hash = $saleitem->item->perks[2]->perkHash;
                                                $weapon->perk3 = str_replace("'","",$salesitems->Response->definitions->perks->$perk3hash->displayName);
                                        }

                                         //Perk 4
                                        $weapon->perk4 = "";
                                        if(isset($saleitem->item->perks[3]->perkHash)) {
                                                $perk4hash = $saleitem->item->perks[3]->perkHash;
                                                $weapon->perk4 = str_replace("'","",$salesitems->Response->definitions->perks->$perk4hash->displayName);
                                        }

                                         //Perk 5
                                        $weapon->perk5 = "";
                                        if(isset($saleitem->item->perks[4]->perkHash)) {
                                                $perk5hash = $saleitem->item->perks[4]->perkHash;
                                                $weapon->perk5 = str_replace("'","",$salesitems->Response->definitions->perks->$perk5hash->displayName);
                                        }
                                       
                                        array_push($weaponArray, $weapon);
                                }                                
                        }
                }

                
        }
        return $weaponArray;
}

$ch = curl_init();

//NEW MONARCHY WARLOCK
$newmonarchy_warlock_url = "https://www.bungie.net/Platform/Destiny/" . $membership_type . "/MyAccount/Character/" . $warlock_character_id . "/Vendor/1808244981/" . "?definitions=true";
curl_setopt($ch, CURLOPT_URL, $newmonarchy_warlock_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-API-KEY: '.$BUNGIE_API_X,
        'Authorization: Bearer '.$accesstoken
));
$newmonarchy_warlock_jsonstr = curl_exec($ch);
$newmonarchy_warlock_weapon_array = getWeaponInfo($newmonarchy_warlock_jsonstr);

//DEAD ORBIT WARLOCK
$deadorbit_warlock_url = "https://www.bungie.net/Platform/Destiny/" . $membership_type . "/MyAccount/Character/" . $warlock_character_id . "/Vendor/3611686524/" . "?definitions=true";
curl_setopt($ch, CURLOPT_URL, $deadorbit_warlock_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-API-KEY: '.$BUNGIE_API_X,
        'Authorization: Bearer '.$accesstoken
));
$deadorbit_warlock_jsonstr = curl_exec($ch);
$deadorbit_warlock_weapon_array = getWeaponInfo($deadorbit_warlock_jsonstr);

//FWC WARLOCK
$fwc_warlock_url = "https://www.bungie.net/Platform/Destiny/" . $membership_type . "/MyAccount/Character/" . $warlock_character_id . "/Vendor/1821699360/" . "?definitions=true";
curl_setopt($ch, CURLOPT_URL, $fwc_warlock_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-API-KEY: '.$BUNGIE_API_X,
        'Authorization: Bearer '.$accesstoken
));
$fwc_warlock_jsonstr = curl_exec($ch);
$fwc_warlock_weapon_array = getWeaponInfo($fwc_warlock_jsonstr);

//VANGUARD QUARTER MASTER
$vqmURL = "https://www.bungie.net/Platform/Destiny/" . $membership_type . "/MyAccount/Character/" . $warlock_character_id . "/Vendor/2668878854/" . "?definitions=true";
curl_setopt($ch, CURLOPT_URL, $vqmURL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-API-KEY: '.$BUNGIE_API_X,
        'Authorization: Bearer '.$accesstoken
));
$jsonstr8 = curl_exec($ch);
$vqmWeaponArray = getWeaponInfo($jsonstr8);

//CRUCIBLE QUARTER MASTER
$cqmURL = "https://www.bungie.net/Platform/Destiny/" . $membership_type . "/MyAccount/Character/" . $warlock_character_id . "/Vendor/3658200622/" . "?definitions=true";
curl_setopt($ch, CURLOPT_URL, $cqmURL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-API-KEY: '.$BUNGIE_API_X,
        'Authorization: Bearer '.$accesstoken
));
$jsonstr9 = curl_exec($ch);
$cqmWeaponArray = getWeaponInfo($jsonstr9);

curl_close($ch);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $db = pg_connect($POSTGRES_DB_STR) or die('Could not connect: ' . pg_last_error());

        //Delete old records
        $query = "DELETE FROM VendorWeapon";  
        $result = pg_query($query);
        
        foreach($newmonarchy_warlock_weapon_array as $weapon) {       
                $query = "INSERT INTO VendorWeapon(VendorName,WeaponName,WeaponType,Perks1,Perks2,Perks3,Perks4,Perks5) 
                          VALUES ('New Monarchy','$weapon->itemname','$weapon->itemtype','$weapon->perk1','$weapon->perk2','$weapon->perk3','$weapon->perk4','$weapon->perk5')";  
                $result = pg_query($query);
        }

        foreach($deadorbit_warlock_weapon_array as $weapon) {       
                $query = "INSERT INTO VendorWeapon(VendorName,WeaponName,WeaponType,Perks1,Perks2,Perks3,Perks4,Perks5) 
                          VALUES ('Dead Orbit','$weapon->itemname','$weapon->itemtype','$weapon->perk1','$weapon->perk2','$weapon->perk3','$weapon->perk4','$weapon->perk5')";  
                $result = pg_query($query);
        }

        foreach($fwc_warlock_weapon_array as $weapon) {       
                $query = "INSERT INTO VendorWeapon(VendorName,WeaponName,WeaponType,Perks1,Perks2,Perks3,Perks4,Perks5) 
                          VALUES ('Future War Cult','$weapon->itemname','$weapon->itemtype','$weapon->perk1','$weapon->perk2','$weapon->perk3','$weapon->perk4','$weapon->perk5')";  
                $result = pg_query($query);
        }

        foreach($vqmWeaponArray as $weapon) {       
                $query = "INSERT INTO VendorWeapon(VendorName,WeaponName,WeaponType,Perks1,Perks2,Perks3,Perks4,Perks5) 
                          VALUES ('Vanguard Quartermaster','$weapon->itemname','$weapon->itemtype','$weapon->perk1','$weapon->perk2','$weapon->perk3','$weapon->perk4','$weapon->perk5')";  
                $result = pg_query($query);
        }

        foreach($cqmWeaponArray as $weapon) {       
                $query = "INSERT INTO VendorWeapon(VendorName,WeaponName,WeaponType,Perks1,Perks2,Perks3,Perks4,Perks5) 
                          VALUES ('Crucible Quartermaster','$weapon->itemname','$weapon->itemtype','$weapon->perk1','$weapon->perk2','$weapon->perk3','$weapon->perk4','$weapon->perk5')";  
                $result = pg_query($query);
        }

        pg_close($db);
}
?>


<html>
 <head>
  <title>Welcome to /r/DTG/</title>
  <link rel="stylesheet" type="text/css"href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <style>
        * {
                font-size: 14px;
                line-height: 1.428;
        }
  </style>
 </head>
 <body>
 <div class="container">
 <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
        <button class="btn btn-primary" type="submit">Save</button>
 </form>

 <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                echo "<p><b>Saved!</b></p>";
        }
   ?>
    
    <?php
        
        echo "<h1>Admin - Vendor Weapon</h1>";
        echo "<div class='table-responsive'>";
        echo "<table class='table table-bordered table-striped'>";
        echo "<thead>";
        echo "<tr><th>Vendor</th> <th>Name</th> <th>Type</th> <th>Perks 1</th> <th>Perks 2</th> <th>Perks 3</th> <th>Perks 4</th> <th>Perks 5</th> </tr>";
        echo "</thead>";

         foreach($newmonarchy_warlock_weapon_array as $weapon) {       
                echo "<tr>" ;
                echo "<td>" . "New Monarchy" . "</td>";
                echo "<td>" . $weapon->itemname . "" . "</td>";
                echo "<td>" . $weapon->itemtype . "" . "</td>";                        
                echo "<td>" . $weapon->perk1 . "</td>";
                echo "<td>" . $weapon->perk2 . "</td>";
                echo "<td>" . $weapon->perk3 . "</td>";                
                echo "<td>" . $weapon->perk4 . "</td>";
                echo "<td>" . $weapon->perk5 . "</td>";
                echo "</tr>";
        }

        foreach($deadorbit_warlock_weapon_array as $weapon) {       
                echo "<tr>" ;
                echo "<td>" . "Dead Orbit" . "</td>";
                echo "<td>" . $weapon->itemname . "" . "</td>";
                echo "<td>" . $weapon->itemtype . "" . "</td>";                        
                echo "<td>" . $weapon->perk1 . "</td>";
                echo "<td>" . $weapon->perk2 . "</td>";
                echo "<td>" . $weapon->perk3 . "</td>";   
                echo "<td>" . $weapon->perk4 . "</td>";
                echo "<td>" . $weapon->perk5 . "</td>";             
                echo "</tr>";
        }

        foreach($fwc_warlock_weapon_array as $weapon) {       
                echo "<tr>" ;
                echo "<td>" . "Future War Cult" . "</td>";
                echo "<td>" . $weapon->itemname . "" . "</td>";
                echo "<td>" . $weapon->itemtype . "" . "</td>";                        
                echo "<td>" . $weapon->perk1 . "</td>";
                echo "<td>" . $weapon->perk2 . "</td>";
                echo "<td>" . $weapon->perk3 . "</td>";  
                echo "<td>" . $weapon->perk4 . "</td>";
                echo "<td>" . $weapon->perk5 . "</td>";              
                echo "</tr>";
        }

        foreach($vqmWeaponArray as $weapon) {       
                echo "<tr>" ;
                echo "<td>" . "Vanguard Quartermaster" . "</td>";
                echo "<td>" . $weapon->itemname . "" . "</td>";
                echo "<td>" . $weapon->itemtype . "" . "</td>";                        
                echo "<td>" . $weapon->perk1 . "</td>";
                echo "<td>" . $weapon->perk2 . "</td>";
                echo "<td>" . $weapon->perk3 . "</td>";   
                echo "<td>" . $weapon->perk4 . "</td>";
                echo "<td>" . $weapon->perk5 . "</td>";             
                echo "</tr>";
        }

        foreach($cqmWeaponArray as $weapon) {       
                echo "<tr>" ;
                echo "<td>" . "Crucible Quartermaster" . "</td>";
                echo "<td>" . $weapon->itemname . "" . "</td>";
                echo "<td>" . $weapon->itemtype . "" . "</td>";                        
                echo "<td>" . $weapon->perk1 . "</td>";
                echo "<td>" . $weapon->perk2 . "</td>";
                echo "<td>" . $weapon->perk3 . "</td>";  
                echo "<td>" . $weapon->perk4 . "</td>";
                echo "<td>" . $weapon->perk5 . "</td>";              
                echo "</tr>";
        }

        echo "</table>";
        echo "</div>";

        echo "</div>";
        echo "</div>";
        echo "</div>";

       
    ?>
 </div>   
 </body>
</html>