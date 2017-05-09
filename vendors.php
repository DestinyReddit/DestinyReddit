<?php

require_once ('config.php');

$db = pg_connect($POSTGRES_DB_STR) or die('Could not connect: ' . pg_last_error());

$query = "SELECT * FROM VendorArmor";  
$result = pg_query($query);

?>
<html>
 <head>
  <title>Welcome to /r/DTG/</title>
  <link rel="stylesheet" type="text/css" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="http://cdn.datatables.net/1.10.15/css/jquery.dataTables.css">  
  <script src="http://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
  <script type="text/javascript" charset="utf8" src="http://cdn.datatables.net/1.10.15/js/jquery.dataTables.js"></script>
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>
  
 </head>
 <body>
     <div class="container">
     <h1>Vendor Armor</h1>
     <?php
        echo "<table class='table table-condensed table-striped' id='vendorarmor'>";
        echo "<thead>";
        echo "<tr><th>Vendor</th> <th>Name</th> <th>Type</th> <th>Perks 1</th> <th>Perks 2</th> <th>Perks 3</th> <th>Int</th> <th>Dis</th> <th>Str</th> <th>Roll%</th> <th>T12</th> </tr>";
        echo "</thead>";

        echo "<tbody>";
        while ($row = pg_fetch_array($result))  {

            echo "<tr>" ;
            echo "<td>" . $row[1] . "</td>";
            echo "<td>" . $row[2] . "" . "</td>";
            echo "<td>" . $row[3] . "" . "</td>";                        
            echo "<td>" . $row[4] . "</td>";
            echo "<td>" . $row[5] . "</td>";
            echo "<td>" . $row[6] . "</td>";
            echo "<td>" . $row[7] . "</td>";
            echo "<td>" . $row[8] . "</td>";
            echo "<td>" . $row[9] . "</td>";
            echo "<td>" . $row[10] . "%" . "</td>";
            echo "<td>" . $row[11] . "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
      ?>

      </div>
      <script type="text/javascript">
        $(document).ready( function () {
            $('#vendorarmor').DataTable({
              "paging": false
            });
        } )
      </script>
 </body>
 </html>
