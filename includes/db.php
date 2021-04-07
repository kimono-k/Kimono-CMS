<?php
$db['db_host'] = "localhost";
$db['db_user'] = "root";
$db['db_pass'] = "";
$db['db_name'] = "cms";

// Makes keys constants.
foreach ($db as $key => $value) {
    define(strtoupper($key), $value); 
}

// Now you can use those key constants.
$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
?>