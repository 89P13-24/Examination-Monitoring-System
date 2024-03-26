<?php
    include("db_connect.php");
    if(isset($_POST["Submit"])) {
        $id = $_POST["ssn"];
        $name = $_POST["name"];
        $password = $_POST["password"];
        $mob = $_POST["number"];
        $college = $_POST["cname"];
        $sql = "INSERT INTO user(id,name,mobile,college,password) VALUES('$id','$name','$mob','$college','$password')";
        if(mysqli_query($conn, $sql)){
            header("location: account.php?id=$id");
            //echo 'query error: '. mysqli_error($conn);
        }

    }
?>
<!DOCTYPE html>
<html lang="en">
    <?php include 'templates/header.php';?>
    <!-- <h4 class="center grey-text">Student Registration</h4> -->
    <!-- <img src="image/front.jpg" alt=""> -->
    <section class = "container grey-text">
        
        <form action="studregister.php" class = "white " method = "POST">
            
            <label for="ssn">Enter your ID:</label>
            <input type="text" name = "ssn" required>
            
            <label for="name">Enter your Name:</label>
            <input type="text" name = "name" required>
            
            <label for="number">Enter your Mobile Number:</label><br>
            <input type="number" name = "number" class = "block" required><br>
            <label for="cname">Enter your College Name:</label>
            <input type="text" name = "cname" required>
            
            <label for="password">Create your Password:</label><br>
            <input type="password" name = "password" required><br>
            
            <label for="password2">Confirm your Password:</label><br>
            <input type="password" name = "password2" required><br>
            
            <div class="center">
            <input type="submit" name ="Submit" class = "btn brand z-depth-0" value = "Sign Up">
            </div>
        </form>
    </section>
    <?php include 'templates/footer.php';?>

</html>