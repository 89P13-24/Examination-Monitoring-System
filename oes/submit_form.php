<?php

// Database connection
$conn = mysqli_connect("localhost","root","","online test");
if(!$conn){
    echo 'Connection error:- '.mysqli_connect_error();
}
// Function to sanitize input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepare and bind SQL statement
    $stmt = $conn->prepare("INSERT INTO questions (question, options) VALUES (?, ?)");
    $stmt->bind_param("ss", $question, $optionsJSON);

    // Sanitize and retrieve data from form
    $questionsData = $_POST["questionsData"];
    foreach ($questionsData as $questionData) {
        $question = sanitizeInput($questionData["question"]);
        $options = array_map('sanitizeInput', $questionData["options"]);
        $optionsJSON = json_encode($options);

        // Execute prepared statement
        if (!$stmt->execute()) {
            echo "Error: " . $stmt->error;
        }
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();

    echo "Data saved successfully";
}

?>
