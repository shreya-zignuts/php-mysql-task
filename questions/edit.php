<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "password", "QuizManagement");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['id'])) {
    $questionId = $_GET['id'];

    // Fetch question details
    $question_query = "SELECT * FROM questions WHERE id = $questionId";
    $question_result = mysqli_query($conn, $question_query);
    $question = mysqli_fetch_assoc($question_result);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get form data
        $question_text = $_POST['question'];
        $option1 = $_POST['option1'];
        $option2 = $_POST['option2'];
        $option3 = $_POST['option3'];
        $option4 = $_POST['option4'];
        $correct_option = $_POST['correct_option'];

        // Update question in the database
        $update_query = "UPDATE questions SET question='$question_text', option1='$option1', option2='$option2', option3='$option3', option4='$option4', correct_option='$correct_option' WHERE id = $questionId";
        if (mysqli_query($conn, $update_query)) {
            echo "Question updated successfully!";
            // Redirect to view questions page
            header("Location: /tests/view_questions.php?test_id={$question['test_id']}");
            exit;
        } else {
            echo "Error updating question: " . mysqli_error($conn);
        }
    }
} else {
    echo "Question ID not provided.";
    exit;
}

// Close connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Question</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Edit Question</h1>
        <form method="post" action="">
            <div class="form-group">
                <label for="question">Question</label>
                <input type="text" class="form-control" id="question" name="question" value="<?php echo $question['question']; ?>" required>
            </div>
            <div class="form-group">
                <label for="option1">Option 1</label>
                <input type="text" class="form-control" id="option1" name="option1" value="<?php echo $question['option1']; ?>" required>
            </div>
            <div class="form-group">
                <label for="option2">Option 2</label>
                <input type="text" class="form-control" id="option2" name="option2" value="<?php echo $question['option2']; ?>" required>
            </div>
            <div class="form-group">
                <label for="option3">Option 3</label>
                <input type="text" class="form-control" id="option3" name="option3" value="<?php echo $question['option3']; ?>" required>
            </div>
            <div class="form-group">
                <label for="option4">Option 4</label>
                <input type="text" class="form-control" id="option4" name="option4" value="<?php echo $question['option4']; ?>" required>
            </div>
            <div class="form-group">
                <label for="correct_option">Correct Option</label>
                <input type="text" class="form-control" id="correct_option" name="correct_option" value="<?php echo $question['correct_option']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Question</button>
        </form>
        <a href="/questions/index.php?test_id=<?php echo $question['test_id']; ?>" class="btn btn-secondary mt-4">Back to Questions</a>

    </div>

    <!-- Bootstrap JS (optional, if needed) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
