<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login.php");
    exit;
}

// Database connection
$conn = mysqli_connect("localhost", "root", "password", "QuizManagement");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$test_id = $_POST['test_id'];
$user_id = $_SESSION['user_id']; // Get user ID from session

// Check if user has already taken the test
$check_query = "SELECT id FROM results WHERE test_id = ? AND user_id = ?";
$stmt = $conn->prepare($check_query);
$stmt->bind_param("ii", $test_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

$score = 0;
$total_questions = 0;
$correct_questions = 0;
$incorrect_questions = 0;

// Fetch questions and calculate score
$questions_query = "SELECT id, correct_option FROM questions WHERE test_id = ?";
$stmt = $conn->prepare($questions_query);
$stmt->bind_param("i", $test_id);
$stmt->execute();
$questions_result = $stmt->get_result();

while ($question = mysqli_fetch_assoc($questions_result)) {
    $total_questions++;
    $question_id = $question['id'];
    $correct_option = $question['correct_option'];
    if (isset($_POST["question_$question_id"])) {
        if ($_POST["question_$question_id"] == $correct_option) {
            $score++;
            $correct_questions++;
        } else {
            $incorrect_questions++;
        }
    } else {
        $incorrect_questions++;
    }
}

// Calculate percentage
$percentage = ($score / $total_questions) * 100;

// Insert result into the database using prepared statement
$insert_result_query = "INSERT INTO results (test_id, user_id, score, total_questions, correct_questions, incorrect_questions, percentage, date_taken) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($insert_result_query);
$stmt->bind_param("iiiiiii", $test_id, $user_id, $score, $total_questions, $correct_questions, $incorrect_questions, $percentage);
$stmt->execute();

// Fetch the test name for display
$test_query = "SELECT name FROM tests WHERE id = ?";
$stmt = $conn->prepare($test_query);
$stmt->bind_param("i", $test_id);
$stmt->execute();
$test_result = $stmt->get_result();
$test = mysqli_fetch_assoc($test_result);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Results</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Test Results for <?php echo $test['name']; ?></h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Total Questions</th>
                    <th>Correct Questions</th>
                    <th>Incorrect Questions</th>
                    <th>Score</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $total_questions; ?></td>
                    <td><?php echo $correct_questions; ?></td>
                    <td><?php echo $incorrect_questions; ?></td>
                    <td><?php echo $score; ?></td>
                    <td><?php echo $percentage; ?>%</td>
                </tr>
            </tbody>
        </table>
        <a href="../user_dashboard.php" class="btn btn-primary">Back to Dashboard</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>


