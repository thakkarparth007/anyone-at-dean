<?php
require("config.php");
session_start();

function time_since($since) {
	$since = time() - strtotime($since);
    $chunks = array(
        array(60 * 60 * 24 * 365 , 'year'),
        array(60 * 60 * 24 * 30 , 'month'),
        array(60 * 60 * 24 * 7, 'week'),
        array(60 * 60 * 24 , 'day'),
        array(60 * 60 , 'hour'),
        array(60 , 'minute'),
        array(1 , 'second')
    );

    for ($i = 0, $j = count($chunks); $i < $j; $i++) {
        $seconds = $chunks[$i][0];
        $name = $chunks[$i][1];
        if (($count = floor($since / $seconds)) != 0) {
            break;
        }
    }

    $print = ($count == 1) ? '1 '.$name : "$count {$name}s";
    return $print . " ago";
}

if(!isset($_SESSION["loggedIn"])) {
    header("Location: /login.php");
    exit(0);
}

$who_at_dean = [];

$result = $db_conn->query("Select who_uname, who_name, `when` from at_dean");

if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $who_at_dean[$row["who_uname"]] = $row;
    }
}
$self_at_dean = isset($who_at_dean[$_SESSION["username"]]);

/*
$key_owners = [];
$result = $db_conn->query("Select username, name, contact from delta_guys where has_key=true");

if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $key_owners[$row["username"]] = $row;
    }
}
 */
?>

<html>
<head>
<title>Anyone @ Dean</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
* {
box-sizing: border-box;
}
html, body {
    margin: 0;
    padding: 0;
    font-family: Ubuntu;
    min-height: 100%;
    position: relative;
}
table {
    border: 1px solid black;
    margin: 0 auto;
	//width: 60%;
}
table thead {
    background: #483D8B;
    padding: 5px;
    color: white;
}
#header {
    background: #222;
    color: white;
    margin: 0 0 10px 0;
    padding: 10px;
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
    height: 40px;
}
.checkin, .checkout {
    float: right;
    display: inline-block;
    padding: 5px;
    background: green;
    color: white;
    text-decoration: none;
    font-weight: bold;
    border-radius: 5px;
}
.checkin::after, .checkout::after {
    clear: both;
}
table .checkin, table .checkout { float: left; }
img {
    display: block;
    margin: 0 auto;
    width: 50%;
    height: auto;
    max-width: 400px;
    max-height: 400px;
    min-width: 100px;
    min-height: 100px;
}
#main {
    width: 90%;
    display: block;
    margin: 0 auto; 
    overflow: auto;
    padding-bottom: 50px;
}
.sad, .happy {
    display: block;
    margin: 0 auto;
    text-align: center;
    font-size: 36px;
    font-weight: bold;
    color: red;
    padding: 10px;
}

#header {
    text-align: center;
}
.happy {
    color: green;
}
.title, .greet {
    font-weight: bold;
    padding: 5px;
    display: inline-block;
}
.greet {
    float: left;
}
</style>
</head>
<body>
    <div id="header">
        <span class="title">Anyone @ Dean?</span>
        <span class="greet"><?php echo "Hi " . $_SESSION['name']; ?></span>
        <?php if(!$self_at_dean) { ?>
            <a class="checkin" href="/checkin.php">Check-in</a>
        <?php } else { ?>
            <a class="checkout" href="/checkout.php">Check-out</a>
        <?php } ?>
        <br style="clear: both">
    </div>

    <div id="main">
        <?php if(count($who_at_dean) == 0) { ?>
            <img src="/images/sad.gif">
            <span class="sad">No one at dean</span>
			<a class="checkin" href="/checkin.php" style="float: none; margin: 0 auto; display: block; width: 200px; padding: 10px; text-align: center; font-size: 30px;">Check-in</a>
        <?php
        } else {
        ?>
            <img src="/images/happy.gif">
            <span class="happy">They're there!</span>

            <table border="1" cellspacing="0">
				<colgroup>
					<col span="1" style="width: 40%">
					<col span="1" style="width: 40%">
					<col span="1" style="width: 20%">
				</colgroup>
                <thead>
                     <tr>
                         <td>Name</td>
                         <td>Check-In time</td>
                         <td>Check him/her out</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($who_at_dean as $uname => $details) { ?>
                        <tr>
                            <td><?php echo $details["who_name"] ?></td>
                            <td><?php echo time_since($details["when"]) ?></td>
                            <td><?php echo "<a class='checkout' href='/checkout.php?username=$uname'>Check-out</a>"; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        <?php
        }
        ?>
    </div>

    <div id="footer">Made with ♥ by Delta Force"</div>
</body>
</html>
