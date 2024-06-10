<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process submitted answers
    if (isset($_POST['answers']) && !empty($_POST['answers'])) {
        // Connect to the database
        $conn = mysqli_connect("localhost", "root", "password", "QuizManagement");

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Prepare and execute query to save answers
        $testId = $_POST['test_id'];
        $answers = $_POST['answers'];

        // Iterate through submitted answers and save them
        foreach ($answers as $questionId => $selectedOption) {
            $insertQuery = "INSERT INTO user_answers (test_id, question_id, selected_option) 
                            VALUES ('$testId', '$questionId', '$selectedOption')";
            mysqli_query($conn, $insertQuery);
        }

        // Close connection
        mysqli_close($conn);

        // Redirect user to some page indicating successful submission
        header("Location: test_submitted.php");
        exit;
    } else {
        // Handle if no answers are submitted
        echo "No answers submitted.";
        exit;
    }
} else {
    // Handle if form is not submitted directly without POST method
    echo "Invalid request.";
    exit;
}
?>
