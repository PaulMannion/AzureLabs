<?php

    include("connection.php");
    include("check.php");

    $id = $_POST["id"];
    $title = $_POST["title"];
    $name = $_POST["name"];

?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Home</title>
    <link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>

<header>
    <h1>My Bug Tracker Website</h1>
    <h1><class="hello">Hello, <em><?php echo $login_user;?>!</em></h1>
    <nav>
        <ul>
            <li><a href="/BugTracker/login/logout.php" style="font-size:18px">Logout?</a></li>
            <li><a href="/BugTracker/addBugReport/newbug.php">Submit New Bug Report</a></li>
        </ul>
    </nav>
</header>

<?php
        $sql = "UPDATE bugs SET isFixed='1' WHERE bugID=$id";

        if (mysqli_query($db, $sql)) {
            echo "<h1>Bug: $title submitted by $name has been approved as FIXED.</h1>";
        } else {
            echo "Error updating record: " . mysqli_error($db);
        }

?>

        <h1><a href="/BugTracker/login/loggedin.php" style="font-size:18px">Return to Admin Page</a></li></h1>

</body>
</html>