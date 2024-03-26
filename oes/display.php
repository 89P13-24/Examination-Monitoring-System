<?php
    include "db_Connect.php";
?>
<?php
function fetchQuestionsFromDatabase($conn, $examid) {
    $sql = "SELECT * FROM questions where exam_id = '$examid'";
    $result = $conn->query($sql);

    $questions = array();
    if ($result->num_rows > 0) {
        // Fetch each row as an associative array
        while ($row = $result->fetch_assoc()) {
            $questions[] = $row;
        }
    }

    return $questions;
}
function fetchAnswersFromDatabase($conn, $examid){
    $userid = $_GET["userid"];
    $sql = "SELECT * FROM answers where examid = '$examid' and userid = '$userid' ORDER BY qid";
    $result = $conn->query($sql);
    $answers = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $answers[] = $row;
        }
    }
    return $answers;
}
function fetchoptionsFromDatabase($conn, $examid, $qid) {
    $sql = "SELECT * FROM options where eid = '$examid' and qid = '$qid' ORDER BY qid";
    $result = $conn->query($sql);

    $options = array();
    if ($result->num_rows > 0) {
        // Fetch each row as an associative array
        while ($row = $result->fetch_assoc()) {
            $options[] = $row;
        }
    }

    return $options;
}

function displayQuestion($questions, $currentIndex) {
    echo "<p>Question " . ($currentIndex + 1) . ": " . $questions[$currentIndex]['question'] . "</p>";
    // You can modify this function to display the question in HTML on your webpage
}

function displayOptions($options,$ans) {
    echo "<ol>";
    $c = 1;
    foreach ($options as $option):
        if($c != $ans):
            echo "<li>" . $option['value'] . "</li><br>";
        endif;
        if($c == $ans):
            echo "<li style = 'color : blue '>" . $option['value'] . "</li><br>";
        endif;
        $c = $c +1;
    endforeach;
    echo "</ol>";

    //echo "<div style = 'color : blue'>$options[$ans]['value']</div>";
}

function recordTimeAndMoveToNext($conn, $examid, $questionIndex, $timeTaken, $ans, $userid) {
    
    $sql = "INSERT INTO answers(examid, userid, qid, answer, time_taken) VALUES('$examid', '$userid', '$questionIndex', '$ans', '$timeTaken')";
    mysqli_query($conn, $sql);
}

function displayAnswer(){

}
function displayQuestionsOneByOne($questions, $answers,$conn, $examid) {
    
    $currentIndex = isset($_GET['index']) ? $_GET['index'] : 0;

    if ($currentIndex < count($questions)) {
        
        $currentQuestion = $questions[$currentIndex];
        $currentAnswer = $answers[$currentIndex];
        displayQuestion($questions, $currentIndex);
        $qid = $currentQuestion['qid'];
        $ans = $currentQuestion['ans'];
        
        $options = fetchoptionsFromDatabase($conn, $examid, $qid);
        displayOptions($options,$ans);
        
        $c = 1;
        foreach ($options as $option):
            if($c == $currentAnswer['answer']):
                $ans_ans = $option['value'];
            endif;
            $c = $c +1;
            endforeach;
        if($ans == $currentAnswer['answer']){
            echo "<div style = 'color : green'><span style = 'color:black'>Your &nbsp;Answer&nbsp;:&nbsp;</span>$ans_ans</div>";
            echo"<br>";
            $time = $currentAnswer['time_taken'];
            echo "<div style = 'color:purple'>Time Taken&nbsp; : &nbsp; $time seconds<div>";
        }else{
            echo "<div style = 'color : red'><span style = 'color:black'>Your &nbsp;Answer&nbsp;:&nbsp;</span>$ans_ans</div>";
            echo"<br>";
            $time = $currentAnswer['time_taken'];
            echo "<div style = 'color:purple'>Time Taken&nbsp; : &nbsp; $time seconds<div>";
        }
        //echo "<input type ='text' id = 'answer' name='answer[" . $currentIndex . "]' placeholder='Enter Your Answer' onkeypress='handleKeyPress(event)' /><br><br>";
        $userid = $_GET['userid'];
        echo'<br><br>';
        echo "<a href='?eid=" . $examid . "&index=" . ($currentIndex + 1) . "&userid=" . $userid . "' >Next</a>";
    } else {
        echo "<h2>Your Assessment has been completed <br>click &nbsp;'Home' &nbsp;to return your account</h2><br><br>";

        echo "<input class = 'btn' id='submitButton' type='submit' name='submit' value='Home'/>";
        // You can handle what to do when all questions are displayed
    }
}

$examid = $_GET['eid'];

// Fetch questions from the database
$questions = fetchQuestionsFromDatabase($conn, $examid);
$answers = fetchAnswersFromDatabase($conn, $examid);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Exam Timer</title>
<style>
    a {
        
        color: steelblue;
    }

    form {
        max-width: 520px;
        margin: 20px;
        padding: 20px;
    }

    header {
        text-align: center;
        background-color: #36454F;
        color: papayawhip;
    }

    .top {
        display: inline-block;
    }

    .timer {
        display: inline-block;
        text-align: right;
    }
    body{
        font-size : 18px;
    }
    .btn{
        font-size : 22px;
        background-color: #36454F;
        color:white;
    }
</style>

</head>
<body>
<header>
    <h1 class="top"><?php echo $_GET['eid']; ?></h1>
</header>

<form action="account.php?id=<?php echo $_GET['userid'];?>" method="post">
    <?php displayQuestionsOneByOne($questions, $answers,$conn, $examid); ?>
</form>  



</body>
</html>
