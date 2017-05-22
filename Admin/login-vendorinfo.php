<?php

require_once ('../config.php');

$accesstoken = $_COOKIE["bungie_access_token"];
$membership_id = $_COOKIE["membership_id"];
$membership_type = $_COOKIE["membership_type"];
$titan_character_id =  $_COOKIE["titan_character_id"];
$warlock_character_id =  $_COOKIE["warlock_character_id"];
$hunter_character_id =  $_COOKIE["hunter_character_id"];

class Armor {
        public $itemhash;
        public $itemname;
        public $itemtype;
        public $intellect;
        public $discipline;
        public $strength;
        public $perk1;
        public $perk2;
        public $perk3;
        public $roll;
        public $t12;
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

function getArmorInfo($jsoninput) {   

        $salesitems = json_decode($jsoninput);
        $armorArray = array();
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
                                if(!isGun($type)) {
                                       $armor = new Armor();                                        
                                        //Hash
                                        $armor->itemhash = $saleitem->item->itemHash;
                                        //Item Name
                                        $itemhash = $armor->itemhash;
                                        $armor->itemname = str_replace("'", "", $salesitems->Response->definitions->items->$itemhash->itemName);
                                        //Item Type
                                        $armor->itemtype = "";
                                        if(isset($salesitems->Response->definitions->items->$itemhash->itemTypeName)) {
                                                $armor->itemtype = $salesitems->Response->definitions->items->$itemhash->itemTypeName;
                                        }
                                        //Intellect
                                        $armor->intellect = 0;
                                        foreach($saleitem->item->stats as $stat) {
                                                if($stat->statHash == 144602215) 
                                                        $armor->intellect = $stat->value;                               
                                        }                                        
                                        //Discipline
                                        $armor->discipline = 0;
                                        foreach($saleitem->item->stats as $stat) {
                                        if($stat->statHash == 1735777505) 
                                                $armor->discipline = $stat->value;
                                        }
                                        //Strength
                                        $armor->strength = 0;
                                        foreach($saleitem->item->stats as $stat) {
                                                if($stat->statHash == 4244567218) 
                                                        $armor->strength = $stat->value;
                                        }
                                        //Perk 1
                                        $armor->perk1 = "";
                                        if(isset($saleitem->item->perks[0]->perkHash)) {
                                                $perk1hash = $saleitem->item->perks[0]->perkHash;
                                                $armor->perk1 = $salesitems->Response->definitions->perks->$perk1hash->displayName;
                                        }
                                        //Perk 2
                                        $armor->perk2 = "";
                                        if(isset($saleitem->item->perks[1]->perkHash)) {
                                                $perk2hash = $saleitem->item->perks[1]->perkHash;
                                                $armor->perk2 = $salesitems->Response->definitions->perks->$perk2hash->displayName;
                                        }
                                        //Perk 3
                                        $armor->perk3 = "";
                                        if(isset($saleitem->item->perks[2]->perkHash)) {
                                                $perk3hash = $saleitem->item->perks[2]->perkHash;
                                                $armor->perk3 = $salesitems->Response->definitions->perks->$perk3hash->displayName;
                                        }

                                        //Roll %
                                        $armor->roll = 0;
                                        $pos = strpos(strtolower($armor->itemtype), 'gauntlets');
                                        if($pos !== false) {
                                                $armor->roll = round((($armor->intellect/41 + $armor->discipline/41 + $armor->strength/41) / 2) * 100);
                                        }
                                        $pos = strpos(strtolower($armor->itemtype), 'leg');
                                        if($pos !== false) {
                                                $armor->roll = round((($armor->intellect/56 + $armor->discipline/56 + $armor->strength/56) / 2) * 100);
                                        }
                                        $pos = strpos(strtolower($armor->itemtype), 'warlock bond');
                                        if($pos !== false) {
                                                $armor->roll = round((($armor->intellect/25 + $armor->discipline/25 + $armor->strength/25) / 2) * 100);
                                        }
                                        $pos = strpos(strtolower($armor->itemtype), 'chest armor');
                                        if($pos !== false) {
                                                $armor->roll = round((($armor->intellect/61 + $armor->discipline/61 + $armor->strength/61) / 2) * 100);
                                        }
                                        $pos = strpos(strtolower($armor->itemtype), 'shell');
                                        if($pos !== false) {
                                                $armor->roll = round((($armor->intellect/25 + $armor->discipline/25 + $armor->strength/25) / 2) * 100);
                                        }
                                        $pos = strpos(strtolower($armor->itemtype), 'helmet');
                                        if($pos !== false) {
                                                $armor->roll = round((($armor->intellect/46 + $armor->discipline/46 + $armor->strength/46) / 2) * 100);
                                        }

                                        //T12
                                        $armor->t12 = "";
                                        if($armor->roll >= 96) {
                                                $armor->t12 = "T12";
                                        }
                                        array_push($armorArray, $armor);
                                }
                        }
                }                
        }
        return $armorArray;
}

$ch = curl_init();

//TITAN
$titanVanguardURL = "https://www.bungie.net/Platform/Destiny/" . $membership_type . "/MyAccount/Character/" . $titan_character_id . "/Vendor/1990950/" . "?definitions=true";
curl_setopt($ch, CURLOPT_URL, $titanVanguardURL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-API-KEY: '.$BUNGIE_API_X,
        'Authorization: Bearer '.$accesstoken
));
$jsonstr1 = curl_exec($ch);
$titanArmorArray = getArmorInfo($jsonstr1);

//WARLOCK
$warlockVanguardURL = "https://www.bungie.net/Platform/Destiny/" . $membership_type . "/MyAccount/Character/" . $warlock_character_id . "/Vendor/1575820975/" . "?definitions=true";
curl_setopt($ch, CURLOPT_URL, $warlockVanguardURL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-API-KEY: '.$BUNGIE_API_X,
        'Authorization: Bearer '.$accesstoken
));
$jsonstr2 = curl_exec($ch);
$warlockArmorArray = getArmorInfo($jsonstr2);

//HUNTER
$hunterVanguardURL = "https://www.bungie.net/Platform/Destiny/" . $membership_type . "/MyAccount/Character/" . $hunter_character_id . "/Vendor/3003633346/" . "?definitions=true";
curl_setopt($ch, CURLOPT_URL, $hunterVanguardURL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-API-KEY: '.$BUNGIE_API_X,
        'Authorization: Bearer '.$accesstoken
));
$jsonstr3 = curl_exec($ch);
$hunterArmorArray = getArmorInfo($jsonstr3);

//CRUCIBLE
$crucibleURL = "https://www.bungie.net/Platform/Destiny/" . $membership_type . "/MyAccount/Character/" . $warlock_character_id . "/Vendor/3746647075/" . "?definitions=true";
curl_setopt($ch, CURLOPT_URL, $crucibleURL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-API-KEY: '.$BUNGIE_API_X,
        'Authorization: Bearer '.$accesstoken
));
$jsonstr4 = curl_exec($ch);
$crucibleArray = getArmorInfo($jsonstr4);

//NEW MONARCHY TITAN
$newmonarchy_titan_url = "https://www.bungie.net/Platform/Destiny/" . $membership_type . "/MyAccount/Character/" . $titan_character_id . "/Vendor/1808244981/" . "?definitions=true";
curl_setopt($ch, CURLOPT_URL, $newmonarchy_titan_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-API-KEY: '.$BUNGIE_API_X,
        'Authorization: Bearer '.$accesstoken
));
$newmonarchy_titan_jsonstr = curl_exec($ch);
$newmonarchy_titan_array = getArmorInfo($newmonarchy_titan_jsonstr);

//NEW MONARCHY HUNTER
$newmonarchy_hunter_url = "https://www.bungie.net/Platform/Destiny/" . $membership_type . "/MyAccount/Character/" . $hunter_character_id . "/Vendor/1808244981/" . "?definitions=true";
curl_setopt($ch, CURLOPT_URL, $newmonarchy_hunter_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-API-KEY: '.$BUNGIE_API_X,
        'Authorization: Bearer '.$accesstoken
));
$newmonarchy_hunter_jsonstr = curl_exec($ch);
$newmonarchy_hunter_array = getArmorInfo($newmonarchy_hunter_jsonstr);

//NEW MONARCHY WARLOCK
$newmonarchy_warlock_url = "https://www.bungie.net/Platform/Destiny/" . $membership_type . "/MyAccount/Character/" . $warlock_character_id . "/Vendor/1808244981/" . "?definitions=true";
curl_setopt($ch, CURLOPT_URL, $newmonarchy_warlock_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-API-KEY: '.$BUNGIE_API_X,
        'Authorization: Bearer '.$accesstoken
));
$newmonarchy_warlock_jsonstr = curl_exec($ch);
$newmonarchy_warlock_array = getArmorInfo($newmonarchy_warlock_jsonstr);

//DEAD ORBIT TITAN
$deadorbit_titan_url = "https://www.bungie.net/Platform/Destiny/" . $membership_type . "/MyAccount/Character/" . $titan_character_id . "/Vendor/3611686524/" . "?definitions=true";
curl_setopt($ch, CURLOPT_URL, $deadorbit_titan_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-API-KEY: '.$BUNGIE_API_X,
        'Authorization: Bearer '.$accesstoken
));
$deadorbit_titan_jsonstr = curl_exec($ch);
$deadorbit_titan_array = getArmorInfo($deadorbit_titan_jsonstr);


//DEAD ORBIT HUNTER
$deadorbit_hunter_url = "https://www.bungie.net/Platform/Destiny/" . $membership_type . "/MyAccount/Character/" . $hunter_character_id . "/Vendor/3611686524/" . "?definitions=true";
curl_setopt($ch, CURLOPT_URL, $deadorbit_hunter_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-API-KEY: '.$BUNGIE_API_X,
        'Authorization: Bearer '.$accesstoken
));
$deadorbit_hunter_jsonstr = curl_exec($ch);
$deadorbit_hunter_array = getArmorInfo($deadorbit_hunter_jsonstr);

//DEAD ORBIT WARLOCK
$deadorbit_warlock_url = "https://www.bungie.net/Platform/Destiny/" . $membership_type . "/MyAccount/Character/" . $warlock_character_id . "/Vendor/3611686524/" . "?definitions=true";
curl_setopt($ch, CURLOPT_URL, $deadorbit_warlock_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-API-KEY: '.$BUNGIE_API_X,
        'Authorization: Bearer '.$accesstoken
));
$deadorbit_warlock_jsonstr = curl_exec($ch);
$deadorbit_warlock_array = getArmorInfo($deadorbit_warlock_jsonstr);

//FWC TITAN
$fwc_titan_url = "https://www.bungie.net/Platform/Destiny/" . $membership_type . "/MyAccount/Character/" . $titan_character_id . "/Vendor/1821699360/" . "?definitions=true";
curl_setopt($ch, CURLOPT_URL, $fwc_titan_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-API-KEY: '.$BUNGIE_API_X,
        'Authorization: Bearer '.$accesstoken
));
$fwc_titan_jsonstr = curl_exec($ch);
$fwc_titan_array = getArmorInfo($fwc_titan_jsonstr);

//FWC HUNTER
$fwc_hunter_url = "https://www.bungie.net/Platform/Destiny/" . $membership_type . "/MyAccount/Character/" . $hunter_character_id . "/Vendor/1821699360/" . "?definitions=true";
curl_setopt($ch, CURLOPT_URL, $fwc_hunter_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-API-KEY: '.$BUNGIE_API_X,
        'Authorization: Bearer '.$accesstoken
));
$fwc_hunter_jsonstr = curl_exec($ch);
$fwc_hunter_array = getArmorInfo($fwc_hunter_jsonstr);

//FWC WARLOCK
$fwc_warlock_url = "https://www.bungie.net/Platform/Destiny/" . $membership_type . "/MyAccount/Character/" . $warlock_character_id . "/Vendor/1821699360/" . "?definitions=true";
curl_setopt($ch, CURLOPT_URL, $fwc_warlock_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-API-KEY: '.$BUNGIE_API_X,
        'Authorization: Bearer '.$accesstoken
));
$fwc_warlock_jsonstr = curl_exec($ch);
$fwc_warlock_array = getArmorInfo($fwc_warlock_jsonstr);

//SPEAKER TITAN
$speaker_titan_url = "https://www.bungie.net/Platform/Destiny/" . $membership_type . "/MyAccount/Character/" . $titan_character_id . "/Vendor/2680694281/" . "?definitions=true";
curl_setopt($ch, CURLOPT_URL, $speaker_titan_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-API-KEY: '.$BUNGIE_API_X,
        'Authorization: Bearer '.$accesstoken
));
$speaker_titan_jsonstr = curl_exec($ch);
$speaker_titan_array = getArmorInfo($speaker_titan_jsonstr);

//SPEAKER HUNTER
$speaker_hunter_url = "https://www.bungie.net/Platform/Destiny/" . $membership_type . "/MyAccount/Character/" . $hunter_character_id . "/Vendor/2680694281/" . "?definitions=true";
curl_setopt($ch, CURLOPT_URL, $speaker_hunter_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-API-KEY: '.$BUNGIE_API_X,
        'Authorization: Bearer '.$accesstoken
));
$speaker_hunter_jsonstr = curl_exec($ch);
$speaker_hunter_array = getArmorInfo($speaker_hunter_jsonstr);

//SPEAKER WARLOCK
$speaker_warlock_url = "https://www.bungie.net/Platform/Destiny/" . $membership_type . "/MyAccount/Character/" . $warlock_character_id . "/Vendor/2680694281/" . "?definitions=true";
curl_setopt($ch, CURLOPT_URL, $speaker_warlock_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-API-KEY: '.$BUNGIE_API_X,
        'Authorization: Bearer '.$accesstoken
));
$speaker_warlock_jsonstr = curl_exec($ch);
$speaker_warlock_array = getArmorInfo($speaker_warlock_jsonstr);

curl_close($ch);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $db = pg_connect($POSTGRES_DB_STR) or die('Could not connect: ' . pg_last_error());

        //Delete old records
        $query = "DELETE FROM VendorArmor";  
        $result = pg_query($query);

        foreach($titanArmorArray as $armor) {       
                $query = "INSERT INTO VendorArmor(VendorName,ArmorName,ArmorType,Perks1,Perks2,Perks3,Intelligence,Discipline,Strength,RollPercent,T12) 
                          VALUES ('Titan Vanguard','$armor->itemname','$armor->itemtype','$armor->perk1','$armor->perk2','$armor->perk3','$armor->intellect','$armor->discipline','$armor->strength','$armor->roll','$armor->t12')";  
                $result = pg_query($query);
        }

        foreach($warlockArmorArray as $armor) {       
                $query = "INSERT INTO VendorArmor(VendorName,ArmorName,ArmorType,Perks1,Perks2,Perks3,Intelligence,Discipline,Strength,RollPercent,T12) 
                          VALUES ('Warlock Vanguard','$armor->itemname','$armor->itemtype','$armor->perk1','$armor->perk2','$armor->perk3','$armor->intellect','$armor->discipline','$armor->strength','$armor->roll','$armor->t12')";  
                $result = pg_query($query);
        }

        foreach($hunterArmorArray as $armor) {       
                $query = "INSERT INTO VendorArmor(VendorName,ArmorName,ArmorType,Perks1,Perks2,Perks3,Intelligence,Discipline,Strength,RollPercent,T12) 
                          VALUES ('Hunter Vanguard','$armor->itemname','$armor->itemtype','$armor->perk1','$armor->perk2','$armor->perk3','$armor->intellect','$armor->discipline','$armor->strength','$armor->roll','$armor->t12')";  
                $result = pg_query($query);
        }

        foreach($crucibleArray as $armor) {       
                $query = "INSERT INTO VendorArmor(VendorName,ArmorName,ArmorType,Perks1,Perks2,Perks3,Intelligence,Discipline,Strength,RollPercent,T12) 
                          VALUES ('Crucible','$armor->itemname','$armor->itemtype','$armor->perk1','$armor->perk2','$armor->perk3','$armor->intellect','$armor->discipline','$armor->strength','$armor->roll','$armor->t12')";  
                $result = pg_query($query);
        }

        foreach($newmonarchy_titan_array as $armor) {       
                $query = "INSERT INTO VendorArmor(VendorName,ArmorName,ArmorType,Perks1,Perks2,Perks3,Intelligence,Discipline,Strength,RollPercent,T12) 
                          VALUES ('New Monarchy(Titan)','$armor->itemname','$armor->itemtype','$armor->perk1','$armor->perk2','$armor->perk3','$armor->intellect','$armor->discipline','$armor->strength','$armor->roll','$armor->t12')";  
                $result = pg_query($query);
        }

        foreach($newmonarchy_hunter_array as $armor) {       
                $query = "INSERT INTO VendorArmor(VendorName,ArmorName,ArmorType,Perks1,Perks2,Perks3,Intelligence,Discipline,Strength,RollPercent,T12) 
                          VALUES ('New Monarchy(Hunter)','$armor->itemname','$armor->itemtype','$armor->perk1','$armor->perk2','$armor->perk3','$armor->intellect','$armor->discipline','$armor->strength','$armor->roll','$armor->t12')";  
                $result = pg_query($query);
        }

        foreach($newmonarchy_warlock_array as $armor) {       
                $query = "INSERT INTO VendorArmor(VendorName,ArmorName,ArmorType,Perks1,Perks2,Perks3,Intelligence,Discipline,Strength,RollPercent,T12) 
                          VALUES ('New Monarchy(Warlock)','$armor->itemname','$armor->itemtype','$armor->perk1','$armor->perk2','$armor->perk3','$armor->intellect','$armor->discipline','$armor->strength','$armor->roll','$armor->t12')";  
                $result = pg_query($query);
        }

        foreach($deadorbit_titan_array as $armor) {       
                $query = "INSERT INTO VendorArmor(VendorName,ArmorName,ArmorType,Perks1,Perks2,Perks3,Intelligence,Discipline,Strength,RollPercent,T12) 
                          VALUES ('Dead Orbit(Titan)','$armor->itemname','$armor->itemtype','$armor->perk1','$armor->perk2','$armor->perk3','$armor->intellect','$armor->discipline','$armor->strength','$armor->roll','$armor->t12')";  
                $result = pg_query($query);
        }

        foreach($deadorbit_hunter_array as $armor) {       
                $query = "INSERT INTO VendorArmor(VendorName,ArmorName,ArmorType,Perks1,Perks2,Perks3,Intelligence,Discipline,Strength,RollPercent,T12) 
                          VALUES ('Dead Orbit(Hunter)','$armor->itemname','$armor->itemtype','$armor->perk1','$armor->perk2','$armor->perk3','$armor->intellect','$armor->discipline','$armor->strength','$armor->roll','$armor->t12')";  
                $result = pg_query($query);
        }

        foreach($deadorbit_warlock_array as $armor) {       
                $query = "INSERT INTO VendorArmor(VendorName,ArmorName,ArmorType,Perks1,Perks2,Perks3,Intelligence,Discipline,Strength,RollPercent,T12) 
                          VALUES ('Dead Orbit(Warlock)','$armor->itemname','$armor->itemtype','$armor->perk1','$armor->perk2','$armor->perk3','$armor->intellect','$armor->discipline','$armor->strength','$armor->roll','$armor->t12')";  
                $result = pg_query($query);
        }

        foreach($fwc_titan_array as $armor) {       
                $query = "INSERT INTO VendorArmor(VendorName,ArmorName,ArmorType,Perks1,Perks2,Perks3,Intelligence,Discipline,Strength,RollPercent,T12) 
                          VALUES ('Future War cult(Titan)','$armor->itemname','$armor->itemtype','$armor->perk1','$armor->perk2','$armor->perk3','$armor->intellect','$armor->discipline','$armor->strength','$armor->roll','$armor->t12')";  
                $result = pg_query($query);
        }

        foreach($fwc_hunter_array as $armor) {       
                $query = "INSERT INTO VendorArmor(VendorName,ArmorName,ArmorType,Perks1,Perks2,Perks3,Intelligence,Discipline,Strength,RollPercent,T12) 
                          VALUES ('Future War cult(Hunter)','$armor->itemname','$armor->itemtype','$armor->perk1','$armor->perk2','$armor->perk3','$armor->intellect','$armor->discipline','$armor->strength','$armor->roll','$armor->t12')";  
                $result = pg_query($query);
        }

        foreach($fwc_warlock_array as $armor) {       
                $query = "INSERT INTO VendorArmor(VendorName,ArmorName,ArmorType,Perks1,Perks2,Perks3,Intelligence,Discipline,Strength,RollPercent,T12) 
                          VALUES ('Future War cult(Warlock)','$armor->itemname','$armor->itemtype','$armor->perk1','$armor->perk2','$armor->perk3','$armor->intellect','$armor->discipline','$armor->strength','$armor->roll','$armor->t12')";  
                $result = pg_query($query);
        }

        foreach($speaker_titan_array as $armor) {       
                $query = "INSERT INTO VendorArmor(VendorName,ArmorName,ArmorType,Perks1,Perks2,Perks3,Intelligence,Discipline,Strength,RollPercent,T12) 
                          VALUES ('Speaker(Titan)','$armor->itemname','$armor->itemtype','$armor->perk1','$armor->perk2','$armor->perk3','$armor->intellect','$armor->discipline','$armor->strength','$armor->roll','$armor->t12')";  
                $result = pg_query($query);
        }

        foreach($speaker_hunter_array as $armor) {       
                $query = "INSERT INTO VendorArmor(VendorName,ArmorName,ArmorType,Perks1,Perks2,Perks3,Intelligence,Discipline,Strength,RollPercent,T12) 
                          VALUES ('Speaker(Hunter)','$armor->itemname','$armor->itemtype','$armor->perk1','$armor->perk2','$armor->perk3','$armor->intellect','$armor->discipline','$armor->strength','$armor->roll','$armor->t12')";  
                $result = pg_query($query);
        }

        foreach($speaker_warlock_array as $armor) {       
                $query = "INSERT INTO VendorArmor(VendorName,ArmorName,ArmorType,Perks1,Perks2,Perks3,Intelligence,Discipline,Strength,RollPercent,T12) 
                          VALUES ('Speaker(Warlock)','$armor->itemname','$armor->itemtype','$armor->perk1','$armor->perk2','$armor->perk3','$armor->intellect','$armor->discipline','$armor->strength','$armor->roll','$armor->t12')";  
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

    <h1>Admin - Vendor Armor</h1>
    
    <?php
        
        echo "<div class='container'>";
        echo "<div class='row'>";
        echo "<div class='col-md-12'>";

        echo "<div class='table-responsive'>";
        echo "<table class='table table-bordered table-striped'>";
        echo "<thead>";
        echo "<tr><th>Vendor</th> <th>Name</th> <th>Type</th> <th>Perks 1</th> <th>Perks 2</th> <th>Perks 3</th> <th>Int</th> <th>Dis</th> <th>Str</th> <th>Roll %</th> <th>T12</th> </tr>";
        echo "</thead>";
        
        foreach($titanArmorArray as $armor) {       
                echo "<tr>" ;
                echo "<td>" . "Titan Vanguard" . "</td>";
                echo "<td>" . $armor->itemname . "" . "</td>";
                echo "<td>" . $armor->itemtype . "" . "</td>";                        
                echo "<td>" . $armor->perk1 . "</td>";
                echo "<td>" . $armor->perk2 . "</td>";
                echo "<td>" . $armor->perk3 . "</td>";
                echo "<td>" . $armor->intellect . "</td>";
                echo "<td>" . $armor->discipline . "</td>";
                echo "<td>" . $armor->strength . "</td>";
                echo "<td>" . $armor->roll . "%" . "</td>";
                echo "<td>" . $armor->t12 . "</td>";
                echo "</tr>";
        }

        foreach($warlockArmorArray as $armor) {       
                echo "<tr>" ;
                echo "<td>" . "Warlock Vanguard" . "</td>";
                echo "<td>" . $armor->itemname . "" . "</td>";
                echo "<td>" . $armor->itemtype . "" . "</td>";                        
                echo "<td>" . $armor->perk1 . "</td>";
                echo "<td>" . $armor->perk2 . "</td>";
                echo "<td>" . $armor->perk3 . "</td>";
                echo "<td>" . $armor->intellect . "</td>";
                echo "<td>" . $armor->discipline . "</td>";
                echo "<td>" . $armor->strength . "</td>";
                echo "<td>" . $armor->roll . "%" . "</td>";
                echo "<td>" . $armor->t12 . "</td>";
                echo "</tr>";
        }
        
        foreach($hunterArmorArray as $armor) {       
                echo "<tr>" ;
                echo "<td>" . "Hunter Vanguard" . "</td>";
                echo "<td>" . $armor->itemname . "" . "</td>";
                echo "<td>" . $armor->itemtype . "" . "</td>";                        
                echo "<td>" . $armor->perk1 . "</td>";
                echo "<td>" . $armor->perk2 . "</td>";
                echo "<td>" . $armor->perk3 . "</td>";
                echo "<td>" . $armor->intellect . "</td>";
                echo "<td>" . $armor->discipline . "</td>";
                echo "<td>" . $armor->strength . "</td>";
                echo "<td>" . $armor->roll . "%" . "</td>";
                echo "<td>" . $armor->t12 . "</td>";
                echo "</tr>";
        }

         foreach($crucibleArray as $armor) {       
                echo "<tr>" ;
                echo "<td>" . "Crucible" . "</td>";
                echo "<td>" . $armor->itemname . "" . "</td>";
                echo "<td>" . $armor->itemtype . "" . "</td>";                        
                echo "<td>" . $armor->perk1 . "</td>";
                echo "<td>" . $armor->perk2 . "</td>";
                echo "<td>" . $armor->perk3 . "</td>";
                echo "<td>" . $armor->intellect . "</td>";
                echo "<td>" . $armor->discipline . "</td>";
                echo "<td>" . $armor->strength . "</td>";
                echo "<td>" . $armor->roll . "%" . "</td>";
                echo "<td>" . $armor->t12 . "</td>";
                echo "</tr>";
        }

        foreach($newmonarchy_titan_array as $armor) {       
                echo "<tr>" ;
                echo "<td>" . "New Monarchy(Titan)" . "</td>";
                echo "<td>" . $armor->itemname . "" . "</td>";
                echo "<td>" . $armor->itemtype . "" . "</td>";                        
                echo "<td>" . $armor->perk1 . "</td>";
                echo "<td>" . $armor->perk2 . "</td>";
                echo "<td>" . $armor->perk3 . "</td>";
                echo "<td>" . $armor->intellect . "</td>";
                echo "<td>" . $armor->discipline . "</td>";
                echo "<td>" . $armor->strength . "</td>";
                echo "<td>" . $armor->roll . "%" . "</td>";
                echo "<td>" . $armor->t12 . "</td>";
                echo "</tr>";
        }

        foreach($newmonarchy_hunter_array as $armor) {       
                echo "<tr>" ;
                echo "<td>" . "New Monarchy(Hunter)" . "</td>";
                echo "<td>" . $armor->itemname . "" . "</td>";
                echo "<td>" . $armor->itemtype . "" . "</td>";                        
                echo "<td>" . $armor->perk1 . "</td>";
                echo "<td>" . $armor->perk2 . "</td>";
                echo "<td>" . $armor->perk3 . "</td>";
                echo "<td>" . $armor->intellect . "</td>";
                echo "<td>" . $armor->discipline . "</td>";
                echo "<td>" . $armor->strength . "</td>";
                echo "<td>" . $armor->roll . "%" . "</td>";
                echo "<td>" . $armor->t12 . "</td>";
                echo "</tr>";
        }

        foreach($newmonarchy_warlock_array as $armor) {       
                echo "<tr>" ;
                echo "<td>" . "New Monarchy(Warlock)" . "</td>";
                echo "<td>" . $armor->itemname . "" . "</td>";
                echo "<td>" . $armor->itemtype . "" . "</td>";                        
                echo "<td>" . $armor->perk1 . "</td>";
                echo "<td>" . $armor->perk2 . "</td>";
                echo "<td>" . $armor->perk3 . "</td>";
                echo "<td>" . $armor->intellect . "</td>";
                echo "<td>" . $armor->discipline . "</td>";
                echo "<td>" . $armor->strength . "</td>";
                echo "<td>" . $armor->roll . "%" . "</td>";
                echo "<td>" . $armor->t12 . "</td>";
                echo "</tr>";
        }

         foreach($deadorbit_titan_array as $armor) {       
                echo "<tr>" ;
                echo "<td>" . "Dead Orbit(Titan)" . "</td>";
                echo "<td>" . $armor->itemname . "" . "</td>";
                echo "<td>" . $armor->itemtype . "" . "</td>";                        
                echo "<td>" . $armor->perk1 . "</td>";
                echo "<td>" . $armor->perk2 . "</td>";
                echo "<td>" . $armor->perk3 . "</td>";
                echo "<td>" . $armor->intellect . "</td>";
                echo "<td>" . $armor->discipline . "</td>";
                echo "<td>" . $armor->strength . "</td>";
                echo "<td>" . $armor->roll . "%" . "</td>";
                echo "<td>" . $armor->t12 . "</td>";
                echo "</tr>";
        }

        foreach($deadorbit_hunter_array as $armor) {       
                echo "<tr>" ;
                echo "<td>" . "Dead Orbit(Hunter)" . "</td>";
                echo "<td>" . $armor->itemname . "" . "</td>";
                echo "<td>" . $armor->itemtype . "" . "</td>";                        
                echo "<td>" . $armor->perk1 . "</td>";
                echo "<td>" . $armor->perk2 . "</td>";
                echo "<td>" . $armor->perk3 . "</td>";
                echo "<td>" . $armor->intellect . "</td>";
                echo "<td>" . $armor->discipline . "</td>";
                echo "<td>" . $armor->strength . "</td>";
                echo "<td>" . $armor->roll . "%" . "</td>";
                echo "<td>" . $armor->t12 . "</td>";
                echo "</tr>";
        }

        foreach($deadorbit_warlock_array as $armor) {       
                echo "<tr>" ;
                echo "<td>" . "Dead Orbit(Warlock)" . "</td>";
                echo "<td>" . $armor->itemname . "" . "</td>";
                echo "<td>" . $armor->itemtype . "" . "</td>";                        
                echo "<td>" . $armor->perk1 . "</td>";
                echo "<td>" . $armor->perk2 . "</td>";
                echo "<td>" . $armor->perk3 . "</td>";
                echo "<td>" . $armor->intellect . "</td>";
                echo "<td>" . $armor->discipline . "</td>";
                echo "<td>" . $armor->strength . "</td>";
                echo "<td>" . $armor->roll . "%" . "</td>";
                echo "<td>" . $armor->t12 . "</td>";
                echo "</tr>";
        }

        foreach($fwc_titan_array as $armor) {       
                echo "<tr>" ;
                echo "<td>" . "Future War cult(Titan)" . "</td>";
                echo "<td>" . $armor->itemname . "" . "</td>";
                echo "<td>" . $armor->itemtype . "" . "</td>";                        
                echo "<td>" . $armor->perk1 . "</td>";
                echo "<td>" . $armor->perk2 . "</td>";
                echo "<td>" . $armor->perk3 . "</td>";
                echo "<td>" . $armor->intellect . "</td>";
                echo "<td>" . $armor->discipline . "</td>";
                echo "<td>" . $armor->strength . "</td>";
                echo "<td>" . $armor->roll . "%" . "</td>";
                echo "<td>" . $armor->t12 . "</td>";
                echo "</tr>";
        }

        foreach($fwc_hunter_array as $armor) {       
                echo "<tr>" ;
                echo "<td>" . "Future War cult(Hunter)" . "</td>";
                echo "<td>" . $armor->itemname . "" . "</td>";
                echo "<td>" . $armor->itemtype . "" . "</td>";                        
                echo "<td>" . $armor->perk1 . "</td>";
                echo "<td>" . $armor->perk2 . "</td>";
                echo "<td>" . $armor->perk3 . "</td>";
                echo "<td>" . $armor->intellect . "</td>";
                echo "<td>" . $armor->discipline . "</td>";
                echo "<td>" . $armor->strength . "</td>";
                echo "<td>" . $armor->roll . "%" . "</td>";
                echo "<td>" . $armor->t12 . "</td>";
                echo "</tr>";
        }

        foreach($fwc_warlock_array as $armor) {       
                echo "<tr>" ;
                echo "<td>" . "Future War cult(Warlock)" . "</td>";
                echo "<td>" . $armor->itemname . "" . "</td>";
                echo "<td>" . $armor->itemtype . "" . "</td>";                        
                echo "<td>" . $armor->perk1 . "</td>";
                echo "<td>" . $armor->perk2 . "</td>";
                echo "<td>" . $armor->perk3 . "</td>";
                echo "<td>" . $armor->intellect . "</td>";
                echo "<td>" . $armor->discipline . "</td>";
                echo "<td>" . $armor->strength . "</td>";
                echo "<td>" . $armor->roll . "%" . "</td>";
                echo "<td>" . $armor->t12 . "</td>";
                echo "</tr>";
        }

        foreach($speaker_titan_array as $armor) {       
                echo "<tr>" ;
                echo "<td>" . "Speaker(Titan)" . "</td>";
                echo "<td>" . $armor->itemname . "" . "</td>";
                echo "<td>" . $armor->itemtype . "" . "</td>";                        
                echo "<td>" . $armor->perk1 . "</td>";
                echo "<td>" . $armor->perk2 . "</td>";
                echo "<td>" . $armor->perk3 . "</td>";
                echo "<td>" . $armor->intellect . "</td>";
                echo "<td>" . $armor->discipline . "</td>";
                echo "<td>" . $armor->strength . "</td>";
                echo "<td>" . $armor->roll . "%" . "</td>";
                echo "<td>" . $armor->t12 . "</td>";
                echo "</tr>";
        }

        foreach($speaker_hunter_array as $armor) {       
                echo "<tr>" ;
                echo "<td>" . "Speaker(Hunter)" . "</td>";
                echo "<td>" . $armor->itemname . "" . "</td>";
                echo "<td>" . $armor->itemtype . "" . "</td>";                        
                echo "<td>" . $armor->perk1 . "</td>";
                echo "<td>" . $armor->perk2 . "</td>";
                echo "<td>" . $armor->perk3 . "</td>";
                echo "<td>" . $armor->intellect . "</td>";
                echo "<td>" . $armor->discipline . "</td>";
                echo "<td>" . $armor->strength . "</td>";
                echo "<td>" . $armor->roll . "%" . "</td>";
                echo "<td>" . $armor->t12 . "</td>";
                echo "</tr>";
        }

        foreach($speaker_warlock_array as $armor) {       
                echo "<tr>" ;
                echo "<td>" . "Speaker(Warlock)" . "</td>";
                echo "<td>" . $armor->itemname . "" . "</td>";
                echo "<td>" . $armor->itemtype . "" . "</td>";                        
                echo "<td>" . $armor->perk1 . "</td>";
                echo "<td>" . $armor->perk2 . "</td>";
                echo "<td>" . $armor->perk3 . "</td>";
                echo "<td>" . $armor->intellect . "</td>";
                echo "<td>" . $armor->discipline . "</td>";
                echo "<td>" . $armor->strength . "</td>";
                echo "<td>" . $armor->roll . "%" . "</td>";
                echo "<td>" . $armor->t12 . "</td>";
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