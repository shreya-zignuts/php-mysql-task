<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Database connection
$conn = mysqli_connect("localhost", "root", "password", "QuizManagement");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if test_id is provided
if (!isset($_POST['test_id']) || empty($_POST['test_id'])) {
    header("Location: ../user_dashboard.php");
    exit;
}

$test_id = $_POST['test_id'];

// Fetch test details
$test_query = "SELECT * FROM tests WHERE id = $test_id";
$test_result = mysqli_query($conn, $test_query);
$test = mysqli_fetch_assoc($test_result);

// Fetch questions and user answers for the test
$questions_query = "SELECT * FROM questions WHERE test_id = $test_id";
$questions_result = mysqli_query($conn, $questions_query);

$score = 0;
$total_questions = 0;

while ($question = mysqli_fetch_assoc($questions_result)) {
    $total_questions++;
    $question_id = $question['id'];
    $correct_option = $question['correct_option'];
    if (isset($_POST['answer'][$question_id])) {
        $user_answer = $_POST['answer'][$question_id];
        if ($user_answer == $correct_option) {
            $score++;
            // Update the database to mark this question as correct
            $update_query = "UPDATE questions SET is_right = 1 WHERE id = $question_id";
            mysqli_query($conn, $update_query);
        } else {
            // Update the database to mark this question as incorrect
            $update_query = "UPDATE questions SET is_right = 0 WHERE id = $question_id";
            mysqli_query($conn, $update_query);
        }
    }
}

// Calculate percentage score
$percentage_score = ($score / $total_questions) * 100;

// Retrieve total correct and incorrect answers
$total_correct_answers_query = "SELECT COUNT(*) AS total_correct FROM questions WHERE test_id = $test_id AND is_right = 1";
$total_correct_result = mysqli_query($conn, $total_correct_answers_query);
$total_correct = mysqli_fetch_assoc($total_correct_result)['total_correct'];

$total_incorrect = $total_questions - $total_correct;

// Close connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Result</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Test Result</h1>
        <div class="mt-3">
            <p>You have completed the test: <?php echo $test['name']; ?></p>
            <p>Total Questions: <?php echo $total_questions; ?></p>
            <p>Correct Answers: <?php echo $score; ?></p>
            <p>Percentage Score: <?php echo number_format($percentage_score, 2); ?>%</p>
            <p>Total Correct Answers: <?php echo $total_correct; ?></p>
            <p>Total Incorrect Answers: <?php echo $total_incorrect; ?></p>
            <a href="user_dashboard.php" class="btn btn-primary">Back to Dashboard</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
