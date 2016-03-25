<?php
    include("connection.php");
    include("check.php");
    include("submit.php");

    $id=$_GET['id'];            // Get the $bugID
    $title=$_GET['title'];      // Get the $bugtitle

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
    <h1 class="hello">Hello, <em><?php echo $login_user;?>!</em></h1>
    <nav>
        <ul>
            <li><a href="/BugTracker/login/logout.php" style="font-size:18px">Logout?</a></li>
            <li><a href="/BugTracker/addBugReport/newbug.php">Submit New Bug Report</a></li>
            <li><a href="/BugTracker/login/loggedin.php" style="font-size:18px">Show Bug Report</a></li>
        </ul>
    </nav>
</header>

<main>
    <h2>Welcome to my Bug Tracker!</h2>
    <p>Here is a list of all the attachments for the bug : <?php echo $title;?>!</p>

    <?php

    $sql="SELECT a.url, u.username FROM attachments a JOIN users u ON a.userID=u.userID WHERE a.bugID=$id";

    $result=mysqli_query($db,$sql);

    ?>

    <table id="bugs">
        <tr>
            <th>URL</th>
            <th>Uploaded By</th>
        </tr>
        <tr>

            <?php
            while($row = mysqli_fetch_assoc($result)) {

                $attachUrl = $row['url'];
                $attachBy = $row['username'];

                
                echo "<td><a href='$attachUrl'>".SomeText."</a></td>";
                echo "<td>$attachBy</td>";
                echo "</tr>\n";
            }

            ?>

        </tr>

    </table>


    <form method="post" action="">
        <fieldset>
            <legend>Attachment Submission Form</legend>
            <table width="400" border="0" cellpadding="10" cellspacing="10">
                <tr>
                    <td colspan="2" align="center" class="error"><?php echo $msg;?></td>
                </tr>
                <tr>
                    <td style="font-weight: bold">
                        <div align="right"><label for="url">Paste URL</label></div>
                    </td>
                    <td>
                        <input name="url" type="text" class="input" size="65" required />
                        <input value="<?php echo $id;?>" type="hidden" name="bugID">
                    </td>
                </tr>
                <tr>
                    <td height="23"></td>
                    <td>
                        <div align="right">
                            <input type="submit" name="submit" value="Upload!" />
                        </div>
                    </td>
                </tr>
            </table>
        </fieldset>
    </form>

</main>

<footer>
    <p>(c) 2016 1506100 Paul Mannion</p>
</footer>

</body>
</html>



