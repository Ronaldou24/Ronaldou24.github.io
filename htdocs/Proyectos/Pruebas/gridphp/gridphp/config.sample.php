<?php

// PHP Grid database connection settings, Only need to update these in new project

define("PHPGRID_DBTYPE","{{dbtype}}"); // mysql,oci8(for oracle),mssql,postgres,sybase
define("PHPGRID_DBHOST","{{dbhost}}");
define("PHPGRID_DBUSER","{{dbuser}}");
define("PHPGRID_DBPASS","{{dbpass}}");
define("PHPGRID_DBNAME","{{dbname}}");

// example configuration
// define("PHPGRID_DBTYPE","mysqli");
// define("PHPGRID_DBHOST","localhost");
// define("PHPGRID_DBUSER","root");
// define("PHPGRID_DBPASS","secret123");
// define("PHPGRID_DBNAME","griddemo");

// Basepath for lib
define("PHPGRID_LIBPATH",dirname(__FILE__).DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR);