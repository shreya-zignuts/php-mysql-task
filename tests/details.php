<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "password", "QuizManagement");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if test ID is provided
if (isset($_GET['id'])) {
    $testId = $_GET['id'];

    // Fetch test details from the database
    $test_query = "SELECT * FROM tests WHERE id = $testId";
    $test_result = mysqli_query($conn, $test_query);
    $test = mysqli_fetch_assoc($test_result);

    // Check if test exists
    if ($test) {
        // Display test details
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
                <!-- Add more details as needed -->
            </div>
            <div class="card-footer">
                <a href="/questions/create.php" class="btn btn-dark btn-sm">Create Questions</a>
                <a href="/admin_dashboard.php" class="btn btn-primary btn-sm">Back to Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>
<?php
    } else {
        echo "Test not found.";
    }
} else {
    echo "Test ID not provided.";
}

// Close connection
mysqli_close($conn);
?>
