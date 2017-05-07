<?php
require_once ('../config.php');
?>
<html>
 <head>
  <title>Welcome to /r/DTG/</title>
  <link rel="stylesheet" type="text/css"href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
 </head>
 <body>
     <div class="container">
        <h1>Admin - Login to Bungie</h1>    
        <a class="btn btn-primary" href="<?php echo $BUNGIE_AUTH_URL ?>">Login</a>
    </div>
</html>