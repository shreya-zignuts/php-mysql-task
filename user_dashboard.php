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

// Fetch tests with associated questions
$tests_query = "SELECT DISTINCT tests.id, tests.name FROM tests JOIN questions ON tests.id = questions.test_id";
$tests_result = mysqli_query($conn, $tests_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include 'partials/_user_nav.php'; ?>

    <div class="container mt-5">
        <h1>All Tests</h1>
        <div class="list-group mt-3">
            <?php while ($test = mysqli_fetch_assoc($tests_result)): ?>
                <a href="user_test/attempt_test.php?test_id=<?php echo $test['id']; ?>" class="list-group-item list-group-item-action"><?php echo $test['name']; ?></a>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
