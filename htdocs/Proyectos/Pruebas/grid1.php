<?php
include_once("gridphp/gridphp/config.php");

// include and create object
include("gridphp/gridphp/lib/inc/jqgrid_dist.php");

$db_conf = array(
                    "type"      => PHPGRID_DBTYPE,
                    "server"    => PHPGRID_DBHOST,
                    "user"      => PHPGRID_DBUSER,
                    "password"  => PHPGRID_DBPASS,
                    "database"  => PHPGRID_DBNAME
                );

$g = new jqgrid($db_conf);

// set few params
$opt["caption"] = "Patrons";
$g->set_options($opt);

// set database table for CRUD operations
$g->table = "patrons";

// render grid and get html/js output
$out = $g->render("list1");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- these css and js files are required by php grid -->
    <link rel="stylesheet" href="gridphp/gridphp/lib/js/themes/redmond/jquery-ui.custom.css"></link>
    <link rel="stylesheet" href="gridphp/gridphp/lib/js/jqgrid/css/ui.jqgrid.css"></link>
    <script src="gridphp/gridphp/lib/js/jquery.min.js" type="text/javascript"></script>
    <script src="gridphp/gridphp/lib/js/jqgrid/js/i18n/grid.locale-en.js" type="text/javascript"></script>
    <script src="gridphp/gridphp/lib/js/jqgrid/js/jquery.jqGrid.min.js" type="text/javascript"></script>
    <script src="gridphp/gridphp/lib/js/themes/jquery-ui.custom.min.js" type="text/javascript"></script>
    <!-- these css and js files are required by php grid -->
<head>
    <body>
        <div>
            <?php echo $out?>
        </div>
    </body>
</html>