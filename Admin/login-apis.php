<?php
//REFERENCE:http://destinydevs.github.io/BungieNetPlatform/docs/Authentication

require_once ('../config.php');

$code = htmlspecialchars($_GET['code']);

// Exchange code for Token
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,'https://www.bungie.net/Platform/App/GetAccessTokensFromCode/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-API-KEY: '.$BUNGIE_API_X));
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array('code' => $code)));

$response = curl_exec($ch);
$response = json_decode($response);
curl_close($ch);

// Using access token to get user's bungie account
$accesstoken = $response->Response->accessToken->value;

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,'https://www.bungie.net/Platform/User/GetCurrentBungieAccount/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-API-KEY: '.$BUNGIE_API_X,
        'Authorization: Bearer '.$accesstoken
));

$userinfojson = curl_exec($ch);
$userinfo = json_decode($userinfojson);
$membership_id = $userinfo->Response->destinyAccounts[0]->userInfo->membershipId;
$membership_type = $userinfo->Response->destinyAccounts[0]->userInfo->membershipType;
$titan_character_id = "";
$warlock_character_id = "";
$hunter_character_id = "";

foreach($userinfo->Response->destinyAccounts[0]->characters as $character) { 
    
    if($character->characterClass->classHash == 3655393761) {
        $titan_character_id = $character->characterId;
    }   
    if($character->characterClass->classHash == 2271682572) {
        $warlock_character_id = $character->characterId;
    }   
    if($character->characterClass->classHash == 671679327) {
        $hunter_character_id = $character->characterId;
    }
    
}

curl_close($ch);

setcookie("bungie_access_token", $accesstoken, time() + 3600);
setcookie("membership_id", $membership_id, time() + 3600);
setcookie("membership_type", $membership_type, time() + 3600);
setcookie("titan_character_id", $titan_character_id, time() + 3600);
setcookie("warlock_character_id", $warlock_character_id, time() + 3600);
setcookie("hunter_character_id", $hunter_character_id, time() + 3600);

?>

<html>
 <head>
  <title>Welcome to /r/DTG/</title>
    <link rel="stylesheet" type="text/css"href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
 </head>
 <body>
     <div class="container">
        <h1>Admin - Login APIs</h1>
        <ul>
            <li><a class="btn btn-primary" href="login-vendorinfo.php">Vendor Armor</a></li>
        </ul>
    </div>
 </body>
</html>