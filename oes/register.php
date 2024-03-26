
<?php
    include("db_connect.php");
    if(isset($_GET['eid']) && isset( $_GET['userid'])){
        $id = $_GET['userid'];
        $eid = $_GET['eid'];
        $sql = "INSERT INTO registration VALUES('$id','$eid')";
        if(mysqli_query($conn, $sql)){
            header("location: account.php?id=$id");
            //echo 'query error: '. mysqli_error($conn);
        }
        else{
            echo 'query error: '. mysqli_error($conn);
        }
    }
?>