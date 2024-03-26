<?php
    include("db_connect.php");
    $user="";
    $id = "";
    if(isset($_GET["id"])){
        $id=$_GET['id'];
        $sql = "SELECT * FROM user WHERE id = '$id' ";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result);
        $user=$row["name"];
        mysqli_free_result($result);
        $sql = "SELECT DISTINCT(examid) From answers where userid = '$id' ";
        $result = mysqli_query($conn, $sql);
        $register = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
        $scores = array();
        foreach($register as $row){
            $examid =$row["examid"];
            $sql = "SELECT count(*) as total from questions where exam_id = '$examid' ";
            $result = mysqli_query($conn, $sql);
            if (!$result) {
                // Query failed
                echo "Error: " . mysqli_error($conn);
                // Handle error as needed, e.g., return an error message
            } 
            else {
            $row = mysqli_fetch_assoc($result);
            $total=$row["total"];
            mysqli_free_result($result);
            $sql = "SELECT count(*) as right_count from questions q, answers a where userid = '$id' and a.examid = q.exam_id and a.examid = '$examid' and a.qid = q.qid and a.answer = q.ans ";
            $result = mysqli_query($conn, $sql);
            if(!$result){
                echo "Error: " . mysqli_error($conn);
            }else{
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $right=$row["right_count"];
                $arr = [$right,$total];
                $scores[$examid] = $arr;
            }
        }
        }
        
    }

?>
<html>
<head>
	<title>Alpha Testing</title>
	<!-- Compiled and minified CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <style type="text/css">
	  .brand{
	  	background: #cbb09c !important;
	  }
  	.brand-text{
  		color: #cbb09c !important;
  	}
  	form{
  		max-width: 460px;
  		margin: 20px auto;
  		padding: 20px;
  	}
    
      /* .container {
        
        display: flex;
        justify-content: space-between;
    }
    .container > div {
        background-color: white;
        width: 48%; /* Adjust as needed */
        /* border: 1px solid #ccc;
        padding: 10px;
    } */
    .a1{
        /* margin:20px; */
        /* padding :20px; */
        margin-left: 50px;
    }
    .instructions{
        color : red;
        margin: 20px;
        padding : 10px;
    }
    .head{
        color : coral;
    }
    ul.navbar {
      list-style-type: none;
      margin: 0;
      padding: 0;
      overflow: hidden;
      background-color: #333;
    }
    
    /* Style for the navigation bar links */
    ul.navbar li {
      float: left; /* Float the list items to make them display horizontally */
    }
    
    /* Style for the navigation bar links */
    ul.navbar li a {
      display: block;
      color: white;
      text-align: center;
      padding: 14px 16px;
      text-decoration: none;
    }
    
    /* Style for the navigation bar links on hover */
    ul.navbar li a:hover {
      background-color: #111;
    }
  </style>
</head>
<body class="grey lighten-4">
	<nav class="black z-depth-0">
    <div class="container1 ">
      <a href="#" class="brand-logo brand-text a1">IIT Guwahati</a>
      <ul id="nav-mobile" class="right hide-on-small-and-down">
        <li class="brand-text">Hello,&nbsp;&nbsp;<?php echo $user?>&nbsp;&nbsp;</li>
        <li><a href="studregister.php" class="btn black z-depth-0">Sign Out</a></li>
      </ul>
    </div>
  </nav>
    <ul class="navbar">
        <li><a href="account.php?id=<?php echo urlencode($id) ?>">Home</a></li>
        <li><a href="#about">About</a></li>
        <li><a href="#">Performance</a></li>
        <li><a href="#contact">Contact</a></li>
    </ul>
  <br>
    
    <div class="container">
		<div class="row">

			<?php foreach($register as $row): ?>

				<div class="col s6 m4">
					<div class="card z-depth-0">
						
						<div class="card-content center">
							<h6><?php echo htmlspecialchars($row['examid']); ?></h6>
							<ul class="grey-text">
                                <li>Total Questons : <?php echo $scores[$row['examid']][1]?></li>
                                <li>Correct Answers : <?php echo $scores[$row['examid']][0]?></li>
							</ul>
						</div>
						<div class="card-action right-align">
							<a class="brand-text" href="display.php?eid=<?php echo $row['examid'];?>&userid=<?php echo $_GET['id'];?>">more info</a>
						</div>
					</div>
				</div>

			<?php endforeach; ?>

		</div>
	</div>


<?php include("templates/footer.php"); ?>
</html>