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
if (!isset($_GET['test_id']) || empty($_GET['test_id'])) {
    header("Location: ../user_dashboard.php");
    exit;
}

$test_id = $_GET['test_id'];

// Fetch test details
$test_query = "SELECT * FROM tests WHERE id = $test_id";
$test_result = mysqli_query($conn, $test_query);
$test = mysqli_fetch_assoc($test_result);

// Fetch questions for the test into an array
$questions = [];
$questions_query = "SELECT * FROM questions WHERE test_id = $test_id";
$questions_result = mysqli_query($conn, $questions_query);
while ($row = mysqli_fetch_assoc($questions_result)) {
    $questions[] = $row;
}

// Close connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attempt Test - <?php echo $test['name']; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

    <div class="container mt-5">
        <h1>Attempt Test - <?php echo $test['name']; ?></h1>
        <div class="mt-3">
            <?php
            // Calculate time limit (in seconds)
            $timeLimit = 300; // 5 minutes
            $startTime = time();
            $endTime = $startTime + $timeLimit;
            ?>

            <div class="alert alert-info" role="alert">
                Time Remaining: <?php echo gmdate("i:s", $endTime - time()); ?>
            </div>

            <form action="submit_test.php" method="post">
    <?php foreach ($questions as $question): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Question <?php echo $question['id']; ?></h5>
                <p class="card-text"><?php echo $question['question']; ?></p>
                <input type="hidden" name="test_id" value="<?php echo $test_id; ?>">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="answers[<?php echo $question['id']; ?>]" id="option_<?php echo $question['id']; ?>_1" value="<?php echo $question['option1']; ?>">
                    <label class="form-check-label" for="option_<?php echo $question['id']; ?>_1"><?php echo $question['option1']; ?></label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="answers[<?php echo $question['id']; ?>]" id="option_<?php echo $question['id']; ?>_2" value="<?php echo $question['option2']; ?>">
                    <label class="form-check-label" for="option_<?php echo $question['id']; ?>_2"><?php echo $question['option2']; ?></label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="answers[<?php echo $question['id']; ?>]" id="option_<?php echo $question['id']; ?>_3" value="<?php echo $question['option3']; ?>">
                    <label class="form-check-label" for="option_<?php echo $question['id']; ?>_3"><?php echo $question['option3']; ?></label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="answers[<?php echo $question['id']; ?>]" id="option_<?php echo $question['id']; ?>_4" value="<?php echo $question['option4']; ?>">
                    <label class="form-check-label" for="option_<?php echo $question['id']; ?>_4"><?php echo $question['option4']; ?></label>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <button type="submit" class="btn btn-primary">Submit Test</button>
</form>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
