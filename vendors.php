<?php

require_once ('config.php');

$db = pg_connect($POSTGRES_DB_STR) or die('Could not connect: ' . pg_last_error());

$query = "SELECT * FROM VendorArmor";  
$result = pg_query($query);

?>
    <html>

    <head>
        <title>Welcome to /r/DTG/</title>

        <!-- CSS -->
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="./static/css/main.css">
        <link rel="stylesheet" type="text/css" href="./static/css/nav.css">
        <link rel="stylesheet" type="text/css" href="./static/css/tables.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.css">

        <!-- JS -->
        <script src="./static/js/jquery-3.2.1.min.js"></script>
        <script src="./static/js/bootstrap.min.js"></script>

        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.js"></script>
        <style>
            * {
                font-size: 13px;
                line-height: 1.4;
            }

        </style>
    </head>

    <body>
        <div class="masthead-armor"> </div>

        <div id="nav-wrap">
            <div id="main-nav" class="navbar">
                <div class="navbar-inner">
                    <div class="container">
                        <div class="navbar-header">
                            <div class="navbar-brand navbar-brand-centered"></div>
                        </div>
                    </div>
                </div>
                <!-- navbar -->
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2 sidebar1">
                    <div class="container sidebar1" id="sidebar">
                        <div class="logo"> <img src="./static/img/Raid_Emblem.png" class="rounded" alt="Logo" style="max-width:175px; border:0; padding-left: 40px;"> </div>
                        <br><br><br><br>
                        <div class="left-navigation">
                            <ul class="list">
                                <h5><strong>LINKS</strong></h5>
                                <li><a href="vendors.php">Vendor Armor</a></li>
                                <li><a href="vendorweapons.php">Vendor Weapons</a></li>
                            </ul>
                            <br>
                            <ul class="list">
                                <h5><strong>/r/DTG LINKS</strong></h5>
                                <li><a href="http://redd.it/6e05bz">Mentor Monday</a></li>
                                <li><a href="http://redd.it/6e76y4">Team Up Tuesday</a></li>
                                <li><a href="http://redd.it/6eenr2">RANT WEDNESDAY</a></li>
                                <li><a href="http://redd.it/6emkvm">Lore Thursday</a></li>
                                <li><a href="http://redd.it/6eu84r">Free Talk Friday!</a></li>
                                <li><a href="http://redd.it/6e671f">Weekly Reset Thread</a></li>
                                <li><a href="http://redd.it/6e67fh">Weekly Vendor Reset Megathread</a></li>
                                <li><a href="http://redd.it/6e76zk">Weekly Loot Hub</a></li>
                                <li><a href="http://redd.it/6eenr2">Armsday Rolls, week 91</a></li>
                                <li><a href="http://redd.it/6et8b1">Xur Megathread</a></li>
                                <li><a href="http://redd.it/6evn5g">Trials of Osiris Megathread</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-10 main-content">
                    <div class="container-fluid" id="wrapper">
                        <h1 class="title">Vendor Armor - 6/6/2017</h1>
                        <br><br>
                        <?php

        echo "<div class='container'>";
        echo "<div class='row'>";
        echo "<div class='col-md-12'>";
        
        echo "<div class='table-responsive'>";        
        echo "<table class='table table-bordered table-striped' id='vendorarmor vendor-table'>";
        echo "<thead>";
        echo "<tr><th>Vendor</th> <th>Name</th> <th>Type</th> <th>Perks 1</th> <th>Perks 2</th> <th>Int</th> <th>Dis</th> <th>Str</th> <th>Roll%</th> <th>T12</th> </tr>";
        echo "</thead>";

        echo "<tbody>";
        while ($row = pg_fetch_array($result))  {

            echo "<tr>" ;
            echo "<td>" . $row[1] . "</td>";
            echo "<td>" . $row[2] . "</td>";
            echo "<td>" . $row[3] . "</td>";                        
            echo "<td>" . $row[4] . "</td>";
            echo "<td>" . $row[5] . "</td>";
            echo "<td>" . $row[7] . "</td>";
            echo "<td>" . $row[8] . "</td>";
            echo "<td>" . $row[9] . "</td>";
            echo "<td>" . $row[10] . "%" . "</td>";
            echo "<td>" . $row[11] . "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
        echo "</div>";

        echo "</div>";
        echo "</div>";
        echo "</div>";
      ?>

                    </div>
                </div>
            </div>
        </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#vendorarmor').DataTable({
                    "paging": false
                });
            })

        </script>

        <script type="text/javascript">
            /* navbar affix scrollspy */
            $('#nav-wrap').height($("#nav").height());
            $('#main-nav').affix({
                offset: $('#main-nav').position()
            });
            /* Sidebar affix scrollspy */
            /* activate sidebar */
            $('#sidebar').affix({
                offset: $('#main-nav').position()
            });
            /* activate scrollspy menu */
            var $body = $(document.body);
            var navHeight = $('.navbar').outerHeight(true);
            $body.scrollspy({
                target: '#leftCol',
                offset: navHeight
            });

        </script>
    </body>

    </html>
