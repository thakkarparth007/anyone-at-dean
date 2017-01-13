<?php

$db_host = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "at_dean";

$db_conn = new mysqli($db_host, $db_username, $db_password, $db_name);
if($db_conn->connect_error) {
    die("Unable to connect to database");
}

?>
