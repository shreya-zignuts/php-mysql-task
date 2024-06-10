<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "password", "QuizManagement");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the test ID from the URL
if (isset($_GET['test_id'])) {
    $testId = $_GET['test_id'];
} else {
    echo "Test ID not provided.";
    exit;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $question = $_POST['question'];
    $option1 = $_POST['option1'];
    $option2 = $_POST['option2'];
    $option3 = $_POST['option3'];
    $option4 = $_POST['option4'];
    $correct_option = $_POST['correct_option'];

    // Insert question into database
    $insert_query = "INSERT INTO questions (test_id, question, option1, option2, option3, option4, correct_option)
                     VALUES ('$testId', '$question', '$option1', '$option2', '$option3', '$option4', '$correct_option')";

    if (mysqli_query($conn, $insert_query)) {
        $message = "Question created successfully.";
        header("Location: index.php?test_id=$testId");
    } else {
        $message = "Error: " . $insert_query . "<br>" . mysqli_error($conn);
    }
}

// Close connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Question</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Create Question for Test ID: <?php echo $testId; ?></h1>
        <?php if (isset($message)): ?>
            <div class="alert alert-success mt-3" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?test_id=' . $testId; ?>" method="post">
            <div class="form-group">
                <label for="question">Question:</label>
                <textarea class="form-control" id="question" name="question" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="option1">Option 1:</label>
                <input type="text" class="form-control" id="option1" name="option1" required>
            </div>
            <div class="form-group">
                <label for="option2">Option 2:</label>
                <input type="text" class="form-control" id="option2" name="option2" required>
            </div>
            <div class="form-group">
                <label for="option3">Option 3:</label>
                <input type="text" class="form-control" id="option3" name="option3">
            </div>
            <div class="form-group">
                <label for="option4">Option 4:</label>
                <input type="text" class="form-control" id="option4" name="option4">
            </div>
            <div class="form-group">
                <label for="correct_option">Correct Option:</label>
                <input type="text" class="form-control" id="correct_option" name="correct_option" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <a href="/tests/details.php?id=<?php echo $testId; ?>" class="btn btn-secondary mt-3">Back to Test Details</a>
    </div>
</body>
</html>
