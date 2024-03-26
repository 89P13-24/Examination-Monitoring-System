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
        $sql = "SELECT * From exams where eid NOT IN (SELECT eid from registration where userid = '$id')";
        $result = mysqli_query($conn, $sql);
        $available = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
        $sql = "SELECT * From exams where eid IN (SELECT eid from registration where userid = '$id')";
        $result = mysqli_query($conn, $sql);
        $register = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
        mysqli_close($conn);
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
    
      .container {
        
        display: flex;
        justify-content: space-between;
    }
    .container > div {
        background-color: white;
        width: 48%; /* Adjust as needed */
        border: 1px solid #ccc;
        padding: 10px;
    }
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
	<nav style = "background-color: #36454F">
    <div class="container1 ">
      <a href="#" class="brand-logo brand-text a1">IIT Guwahati</a>
      <ul id="nav-mobile" class="right hide-on-small-and-down">
        <li class="brand-text">Hello,&nbsp;&nbsp;<?php echo $user?>&nbsp;&nbsp;</li>
        <li><a href="studregister.php" class="btn black z-depth-0">Sign Out</a></li>
      </ul>
    </div>
  </nav>
    <ul class="navbar">
        <li><a href="#home">Home</a></li>
        <li><a href="#about">About</a></li>
        <li><a href="performance.php?id=<?php echo urlencode($id) ?>">Performance</a></li>
        <li><a href="#contact">Contact</a></li>
    </ul>
  <br>
    
<div class="container">
    <div id="available">
        <h5 class = "center">Available Exams</h5>
        <ol>
            <?php foreach($available as $row): ?>
                <li><span><?php echo htmlspecialchars($row['eid']) ?>&nbsp;-&nbsp;</span><a href="register.php?eid=<?php echo urlencode($row['eid']) ?>&userid=<?php echo urlencode($id) ?>"><?php echo htmlspecialchars($row['name']) ?></a></li>
            <?php endforeach; ?>
        </ol>
    </div>
    <div id="register">
        <h5 class="center">Registered Exams</h5>
        <!-- Your ongoing exams content goes here -->
        <ol>
            <?php foreach($register as $row): ?>
            <li><span><?php echo htmlspecialchars($row['eid']) ?>&nbsp;-&nbsp;</span><a href="exam.php?eid=<?php echo urlencode($row['eid']) ?>&userid=<?php echo urlencode($id) ?>&overwrite=1"><?php echo htmlspecialchars($row['name']) ?></a></li>
            <?php endforeach; ?>
        </ol>
    </div>
    

</div>
<br>
<div class = "container1">
    <h6 style = "text-align:center ;color:green">You can register for an exam by simply clicking on it...</h6>
</div>
<div class = "instructions head">
    <h5 class = "head">Important Instruction</h5>
    <ol>
        <li>As soon as you click on your registered exam, it will start.</li>
        <li>You will be given 30 minutes for each exam.</li>
        <li>Each exam will have Multiple Choice questions..</li>
        <li>You can attempt an exam multiple times but your last performance will be stored only..</li>
    </ol>
</div>
<?php include("templates/footer.php"); ?>
</html>