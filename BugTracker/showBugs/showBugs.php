<body>
<div id="content">

<?php

include("connection.php");
include("check.php");


    $sql = "SELECT * FROM bugs WHERE bugID = '1'";
    $result=mysqli_query($db,$sql);

    $row = mysqli_fetch_assoc($result);
    $bugTitle = $row['title'];
    $bugID = $row['bugID'];
    $bugDesc = $row['desc'];

    echo $bugTitle;
    echo $bugDesc;
    echo "Comeon ye eejit";
    echo "I was passed the variable".$_GET["id"];
    echo "<em>$bugID<em>";

   // echo '<a href="bug.php?id="'.$bugID.'>'.$bugTitle.'</a></br>';
 ?>

</div>
</body>




