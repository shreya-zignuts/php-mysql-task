<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "password", "QuizManagement");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['id'])) {
    $testId = $_GET['id'];

    // Fetch test details
    $test_query = "SELECT * FROM tests WHERE id = $testId";
    $test_result = mysqli_query($conn, $test_query);
    $test = mysqli_fetch_assoc($test_result);

    // Fetch questions related to the test
    $questions_query = "SELECT * FROM questions WHERE test_id = $testId";
    $questions_result = mysqli_query($conn, $questions_query);
} else {
    echo "Test ID not provided.";
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
    <title><?php echo $test['name']; ?> Details</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title"><?php echo $test['name']; ?> Details</h2>
            </div>
            <div class="card-body">
                <p><strong>Description:</strong> <?php echo $test['description']; ?></p>
                <p><strong>Level:</strong> <?php echo $test['level']; ?></p>
            </div>
        </div>

        <div class="card-footer">
            <a href="/admin_dashboard.php" class="btn btn-primary">Back to Dashboard</a>
            <a href="/questions/create.php?test_id=<?php echo $testId; ?>" class="btn btn-secondary">Create Question</a>
            <a href="/questions/index.php?test_id=<?php echo $testId; ?>" class="btn btn-primary">View Questions</a>

        </div>
    </div>
</body>
</html>
