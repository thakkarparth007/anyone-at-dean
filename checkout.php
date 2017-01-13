<?php
session_start();

if(!isset($_SESSION["loggedIn"])) {
    header("Location: /login.php");
    exit(0);
}

require("config.php");

var_dump($_GET);
$who = isset($_GET["username"]) ? $_GET["username"] : $_SESSION['username'];

$result = $db_conn->query("Select who_uname, who_name, `when` from at_dean");

$found = false;
if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        if($row["who_uname"] == $who) {
            $found = true;
        }
    }
}

if(!$found) {
    $your_good_name = $row["who_name"];
    die("$your_good_name already checked-out. Don't screw with me.");
}

$stmt = $db_conn->prepare("delete from at_dean where who_uname=?");

if(!$stmt) {
    die("Unknown database error");
}

$stmt->bind_param("s", $who);
$stmt->execute();

if($stmt->error) {
    die("Something went wrong.");
}

// ??

header("Location: /");
exit(0);
