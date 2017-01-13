<?php
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    require("config.php");
    $stmt = $db_conn->prepare("Select * from delta_guys where username=? and password=md5(?)");
    if(!$stmt) {
        die("Unknown database error");
    }

    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();

    $result = $stmt->get_result();
    if($result->num_rows > 0) {
        $row = $result->fetch_assoc(); 
        $_SESSION["loggedIn"] = "true";
        $_SESSION["name"] = $row["name"];
        $_SESSION["username"] = $row["username"];
    }
    else {
        require("insults.php");
        $insult = $insults[array_rand($insults)];
    }
}

if(isset($_SESSION["loggedIn"])) {
    header("Location: /");
    exit(0);
}

?>

<html>
<head>
<title>Anyone @ Dean</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
html, body {
    margin: 0;
    padding: 0;
    font-family: Ubuntu
}
#header {
    background: #222;
    color: white;
    margin: 0 0 10px 0;
    padding: 10px;
    font-weight: bold;
    text-align: center;
}
#footer {
    background: #222;
    color: white;
    padding: 10px;
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    text-align: center;
}
form {
    padding: 20px;
}
form * {
    display: block;
    margin: 0 auto;
}
input[type="text"], input[type="password"] {
    padding: 10px;
    border-radius: 5px;
    border: 1px solid black;
    outline: none;
}
input:focus, input:hover {
    box-shadow: 0 0 5px #0000f8;//  #1E90FF;
}

input[type="submit"] {
    padding: 10px;
    border-radius: 5px;
    border: 1px solid black;
    outline: none;
    background: #1E90FF;
    color: white;
    font-weight: bold;
    font-size: 20px;
    cursor: pointer;
}
.insult {
    display: block;
    color: red;
    text-align: center;
}
</style>
</head>
<body>
    <div id="header">
        Anyone @ Dean
    </div>

<h1 style="text-align: center">Login</h1>
<form action="/login.php" method="post">
<input name="username" type="text" placeholder="Username"><br>
<input name="password" type="password" placeholder="Password"><br>
<input type="submit">
</form>
<?php if(isset($insult)) { ?>
    <span class="insult">
        <?php echo $insult ?>
    </span><br>
<?php } ?>

<div id="footer">Made with ♥ by Delta Force"</div>
</body>
</html>
