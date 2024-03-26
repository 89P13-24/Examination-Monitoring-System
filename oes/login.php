<?php
    include("db_connect.php");
    $message = "";
    if(isset($_POST["submit"])){
        $id = mysqli_real_escape_string($conn, $_POST['ssn']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
		// make sql
		$sql = "SELECT * FROM user WHERE id = '$id' ";
        //echo $id;
		// get the query result
		$result = mysqli_query($conn, $sql);
        if(!$result) {
            // Query execution failed, handle the error
            die("Error executing the query: " . mysqli_error($conn));
        }
        if(mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            if($user["password"] != $password) {
                $message = "Incorrect Password";
            }else{
                header("Location:account.php?id=$id");
            }
        }else{
            $message = "User does not exist!!";
        }
		// fetch result in array format
		

		mysqli_free_result($result);
		mysqli_close($conn);    
    }
?>

<!DOCTYPE html>
<html lang="en">
    <?php include 'templates/header1.php';?>
    <!-- <h4 class="center grey-text">Student Registration</h4> -->
    <!-- <img src="image/front.jpg" alt=""> -->
    <section class = "container grey-text">
        
        <form action="login.php" class = "white " method = "POST">
            
            <label for="ssn">Enter your ID:</label>
            <input type="text" name = "ssn" required>
            
            <label for="password">Enter your Password:</label><br>
            <input type="password" name = "password" required><br>
            
            
            <div class="center">
            <input type="submit" name ="submit" class = "btn brand z-depth-0" value = "Log In">
            <div class="red-text"><?php echo $message;?></div>
            </div>
        </form>
    </section>
    <?php include 'templates/footer.php';?>

</html>