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

$test_id = $_GET['test_id'];
$user_id = $_SESSION['user_id'];

// Check if user has already taken the test
$check_result_query = "SELECT id FROM results WHERE test_id = ? AND user_id = ?";
$stmt = $conn->prepare($check_result_query);
$stmt->bind_param("ii", $test_id, $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // User has already taken the test
    echo "You have already taken this test. You cannot take it again.";
    exit;
}

// Fetch questions for the test
$questions_query = "SELECT id, question, option1, option2, option3, option4 FROM questions WHERE test_id = ?";
$stmt = $conn->prepare($questions_query);
$stmt->bind_param("i", $test_id);
$stmt->execute();
$questions_result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attempt Test</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            /* margin: auto; */
            margin-top: 200px;
        }
        .question {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .question-number {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .options label {
            display: block;
            margin-bottom: 10px;
        }
        .options input[type="radio"] {
            margin-right: 10px;
        }
        #timer {
            font-size: 14px;
            float: right;
            margin-top: 20px; /* Adjust the margin as needed */
        }
        .submit-btn {
            font-size: 20px;
            padding-left: 30px;
            padding-right: 30px;
            margin-top: 2px;
            margin-bottom: 20px; /* Adjust the margin as needed */
            float: right;

        }
    </style>
</head>
<body>
    <div class="container mt-5">
    <h2 id="timer">Time Left: 5:00</h2>
        <h1>Attempt Test</h1>
        
        <form id="testForm" action="submit_test.php" method="post">
            <input type="hidden" name="test_id" value="<?php echo $test_id; ?>">
             <!-- Timer display -->
            <?php $questionNumber = 1; ?>
            <?php while ($question = mysqli_fetch_assoc($questions_result)): ?>
                <div class="question">
                    <p class="question-number">Question <?php echo $questionNumber++; ?>:</p>
                    <p><?php echo $question['question']; ?></p>
                    <div class="options">
                        <label><input type="radio" name="question_<?php echo $question['id']; ?>" value="1"> <?php echo $question['option1']; ?></label>
                        <label><input type="radio" name="question_<?php echo $question['id']; ?>" value="2"> <?php echo $question['option2']; ?></label>
                        <label><input type="radio" name="question_<?php echo $question['id']; ?>" value="3"> <?php echo $question['option3']; ?></label>
                        <label><input type="radio" name="question_<?php echo $question['id']; ?>" value="4"> <?php echo $question['option4']; ?></label>
                    </div>
                </div>
            <?php endwhile; ?>
            <button type="submit" class="btn btn-primary submit-btn">Submit</button>
        </form>
    </div>

    <script>
        // Countdown timer
        var timer = document.getElementById('timer');
        var testForm = document.getElementById('testForm');
        var secondsRemaining = 300; // 5 minutes in seconds

        function countdown() {
            var minutes = Math.floor(secondsRemaining / 60);
            var seconds = secondsRemaining % 60;
            seconds = seconds < 10 ? '0' + seconds : seconds;
            timer.textContent = 'Time Left: ' + minutes + ':' + seconds;

            if (secondsRemaining <= 0) {
                // Time's up, submit the form
                clearInterval(interval);
                testForm.submit();
            } else {
                secondsRemaining--;
            }
        }

        var interval = setInterval(countdown, 1000);
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>



<?php
$conn->close();
?>
