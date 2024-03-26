<?php
    include "db_Connect.php";
?>
<?php
    if(isset($_GET['overwrite'])){
        $examid = $_GET['eid'];
        $userid = $_GET['userid'];
        $sql = "DELETE From answers where examid = '$examid' and userid = '$userid'";
        if(!mysqli_query($conn, $sql)){
            echo "error";
        }
    }
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

function fetchoptionsFromDatabase($conn, $examid, $qid) {
    $sql = "SELECT * FROM options where eid = '$examid' and qid = '$qid'";
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

function displayOptions($options) {
    echo "<ol>";
    foreach ($options as $option):
        echo "<li>" . $option['value'] . "</li><br>";
    endforeach;
    echo "</ol>";
}

function recordTimeAndMoveToNext($conn, $examid, $questionIndex, $timeTaken, $ans, $userid) {
    
    $sql = "INSERT INTO answers(examid, userid, qid, answer, time_taken) VALUES('$examid', '$userid', '$questionIndex', '$ans', '$timeTaken')";
    mysqli_query($conn, $sql);
}

function displayQuestionsOneByOne($questions, $conn, $examid) {
    
    if (isset($_GET['time'])) {
        $time = time() - $_GET['time'];
        recordTimeAndMoveToNext($conn, $examid, $_GET['index'],$time, $_GET['ans'], $_GET['userid']);
    }
    $startTime = time();

    $currentIndex = isset($_GET['index']) ? $_GET['index'] : 0;

    if ($currentIndex < count($questions)) {
        echo "<input type='hidden' id='currentIndex' value='" . $currentIndex . "' onkeypress='handleKeyPress(event)' />";
        echo '<input type="hidden" id="timeRemaining" name="timeRemaining" value="">';
        echo '<input type = "hidden" id = "perQuestion" name ="perQuestion" value="">';
        $currentQuestion = $questions[$currentIndex];
        displayQuestion($questions, $currentIndex);
        $qid = $currentQuestion['qid'];
        $options = fetchoptionsFromDatabase($conn, $examid, $qid);
        displayOptions($options);

        echo "<input type ='text' id = 'answer' name='answer[" . $currentIndex . "]' placeholder='Enter Your Answer' onkeypress='handleKeyPress(event)' /><br><br>";
        $userid = $_GET['userid'];

        //echo "<a href='?eid=" . $examid . "&index=" . ($currentIndex + 1) . "&userid=" . $userid . "&time=" . $startTime . "&ans=" . $startTime . "&time_taken=" . $startTime  . "' >Next</a>";
    } else {
        $userid = $_GET['userid'];
        $eid = $_GET['eid'];
        $sql = "DELETE from registration where userid = '$userid' and eid = '$eid' ";
        if(!mysqli_query($conn, $sql)) {
            echo "Error";
        }
        echo "<h2>Your Assessment has been completed <br>click &nbsp;'Home' &nbsp;to return your account</h2><br><br>";

        echo "<input class = 'btn' id='submitButton' type='submit' name='submit' value='Home'/>";
        // You can handle what to do when all questions are displayed
    }
}

$examid = $_GET['eid'];

// Fetch questions from the database
$questions = fetchQuestionsFromDatabase($conn, $examid);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Exam Timer</title>
<style>
    a {
        text-decoration: none;
        color: black;
    }

    form {
        max-width: 490px;
        margin: 20px;
        padding: 20px;
    }

    header {
        text-align: center;
        background-color: #36454F;
        color: goldenrod;
    }

    .top {
        display: inline-block;
    }

    .timer {
        display: inline-block;
        text-align: right;
    }
    body{
        font-size:20px;
    }
    .btn{
        font-size : 22px;
        background-color: #36454F;
        color:white;
    }
</style>
<script>
    function handleKeyPress(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            var currentIndex = parseInt(document.getElementById("currentIndex").value);
            var answer = parseInt(document.getElementById("answer").value);
            var perQuestion = parseInt(document.getElementById("perQuestion").value);
            console.log(perQuestion);
            var nextIndex = currentIndex + 1;
            var examid = "<?php echo $_GET['eid']; ?>";
            var userid = "<?php echo $_GET['userid']; ?>";
            var startTime = "<?php echo isset($_GET['startTime']) ? $_GET['startTime'] : time(); ?>";
            window.location.href = "exam.php?eid=" + examid + "&index=" + nextIndex + "&userid=" + userid + "&startTime=" + startTime + "&ans=" + answer + "&time=" + perQuestion;
        }
    }

    window.onload = function() {
            var startTime = <?php echo isset($_GET['startTime']) ? $_GET['startTime'] : time(); ?>;
            var currentTime = <?php echo time();?>;
            console.log(startTime,currentTime);
        var elapsedTime = currentTime - startTime;
        var examDuration = 60 * 30; // Exam duration in seconds (e.g., 30 minutes)
        examDuration = examDuration - elapsedTime;
        console.log(elapsedTime);
        var timeRemainingElement = document.getElementById("timeRemaining");
        var submitButton = document.getElementById("submitButton");
        var perQuestion = document.getElementById("perQuestion");
        perQuestion.value = currentTime;
        function updateTimerDisplay(timeRemaining) {
        var minutes = Math.floor(timeRemaining / 60);
        var seconds = timeRemaining % 60;
        document.getElementById("timer").innerText = "Time Remaining: " + minutes + "m " + seconds + "s";
        }

        function submitExam() {
        submitButton.click(); // Simulate click on the submit button
        }

        var interval = setInterval(function() {
        examDuration--;
        updateTimerDisplay(examDuration);

        if (examDuration <= 0) {
            clearInterval(interval);
            timeRemainingElement.value = 0; // Set the remaining time value in the form
            submitExam(); // Automatically submit the exam when time is up
        } else {
            timeRemainingElement.value = examDuration; // Update the remaining time value in the form
        }
        }, 1000);
        };

</script>
<script>
    // Disable caching and prevent going back in history
    window.history.forward();
    
    // Prevent user from right-clicking and selecting 'Back'
    document.addEventListener('contextmenu', event => event.preventDefault());
</script>

</head>
<body>
<header>
    <h1 class="top"><?php echo $_GET['eid']; ?></h1>
    <div id="timer" class="timer"></div>
</header>
<?php if(!isset($_GET['index'])):?>
    <h3>Write 1, 2, 3, or 4 as per your answer in the box given below</h3>
<?php endif;?>
<?php if(isset($_GET['index']) && $_GET['index'] != count($questions)):?>
    <h3>Write 1, 2, 3, or 4 as per your answer in the box given below</h3>
<?php endif;?>

<form action="account.php?id=<?php echo $_GET['userid'];?>" method="post">
    <?php displayQuestionsOneByOne($questions, $conn, $examid); ?>
    <input type="hidden" id="perQuestion" name="perQuestion" value="" />
    <input type='hidden' id='currentIndex' value='<?php echo isset($_GET['index']) ? $_GET['index'] : 0; ?>' onkeypress='handleKeyPress(event)' />
    <input type="hidden" id="timeRemaining" name="timeRemaining" value="<?php echo isset($_GET['startTime']) ? (60 * 30) - (time() - $_GET['startTime']) : 60 * 30; ?>" />
    <input id='submitButton' type='submit' name='submit' value='Submit' style="display: none;" />
    <br>
    <?php if(!isset($_GET['index'])):?>
    <div>Click 'Enter' to move to Next Question..</h3>
<?php endif;?>
<?php if(isset($_GET['index']) && $_GET['index'] != count($questions)):?>
    <div>Click 'Enter' to move to Next Question..</h3>
<?php endif;?>

</form>



</body>
</html>
