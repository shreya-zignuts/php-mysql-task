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
    <title>View Question</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">View Question</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?php echo $question['question']; ?></h5>
                <p class="card-text"><strong>Option 1:</strong> <?php echo $question['option1']; ?></p>
                <p class="card-text"><strong>Option 2:</strong> <?php echo $question['option2']; ?></p>
                <p class="card-text"><strong>Option 3:</strong> <?php echo $question['option3']; ?></p>
                <p class="card-text"><strong>Option 4:</strong> <?php echo $question['option4']; ?></p>
                <p class="card-text"><strong>Correct Option:</strong> <?php echo $question['correct_option']; ?></p>
            </div>
        </div>
        <a href="/questions/index.php?test_id=<?php echo $question['test_id']; ?>" class="btn btn-secondary mt-4">Back to Questions</a>
    </div>

    <!-- Bootstrap JS (optional, if needed) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
