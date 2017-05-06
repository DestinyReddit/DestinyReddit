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

setcookie("bungie_access_token", $accesstoken, time()+3600);

?>

<html>
 <head>
  <title>Welcome to /r/DTG/</title>
 </head>
 <body>
    <h1>Admin - Login APIs</h1>
    <h2>[This is page should not be visible to public]</h2>
    <h3><?php echo $accesstoken ?></h3>
    <ul>
        <li><a href="login-userinfo.php">Login User Info</a></li>
    </ul>
 </body>
</html>