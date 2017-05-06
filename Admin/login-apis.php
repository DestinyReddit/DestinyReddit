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
$first_character_id = $userinfo->Response->destinyAccounts[0]->characters[0]->characterId;
curl_close($ch);

setcookie("bungie_access_token", $accesstoken, time() + 3600);
setcookie("membership_id", $membership_id, time() + 3600);
setcookie("membership_type", $membership_type, time() + 3600);
setcookie("first_character_id", $first_character_id, time() + 3600);

?>

<html>
 <head>
  <title>Welcome to /r/DTG/</title>
 </head>
 <body>
    <h1>Admin - Login APIs</h1>
    <h3><?php echo $membership_id ?></h3>    
    <h3><?php echo $first_character_id ?></h3>    
    <ul>
        <li><a href="login-userinfo.php">Login User Info</a></li>
        <li><a href="login-vendorinfo.php">Login Vendor Info</a></li>
    </ul>
 </body>
</html>