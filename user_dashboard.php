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

$user_id = $_SESSION['user_id'];

// Total test conducted count for the specific user
$total_tests_query = "SELECT COUNT(*) AS total_tests FROM results WHERE user_id = $user_id";
$total_tests_result = mysqli_query($conn, $total_tests_query);
$total_tests_row = mysqli_fetch_assoc($total_tests_result);
$total_tests_count = $total_tests_row['total_tests'];

// Average of percentage from the database for the specific user
$average_percentage_from_db_query = "SELECT AVG(percentage) AS average_percentage FROM results WHERE user_id = $user_id";
$average_percentage_from_db_result = mysqli_query($conn, $average_percentage_from_db_query);
$average_percentage_from_db_row = mysqli_fetch_assoc($average_percentage_from_db_result);
$average_percentage_from_db = isset($average_percentage_from_db_row['average_percentage']) ? round($average_percentage_from_db_row['average_percentage'], 2) : 0;

// Test-wise percentage count for the specific user based on percentage
$test_wise_percentage_query = "SELECT t.name AS test_name, AVG(r.percentage) AS average_percentage 
                               FROM tests t
                               LEFT JOIN results r ON t.id = r.test_id
                               WHERE r.user_id = $user_id
                               GROUP BY t.id, t.name";
$test_wise_percentage_result = mysqli_query($conn, $test_wise_percentage_query);

// Available tests for the specific user
$available_tests_query = "SELECT * FROM tests WHERE id NOT IN (SELECT test_id FROM results WHERE user_id = $user_id)";
$available_tests_result = mysqli_query($conn, $available_tests_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Analytics</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .test-card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php require 'partials/_user_nav.php' ?>

    <div class="container mt-5">
        <h1>Test Analytics</h1>
        <div class="row">
            <div class="col-md-6">
                <div class="card test-card">
                    <div class="card-body">
                        <h5 class="card-title">Total Tests Conducted</h5>
                        <p class="card-text"><?php echo $total_tests_count; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card test-card">
                    <div class="card-body">
                        <h5 class="card-title">Average Percentage (From Database)</h5>
                        <p class="card-text"><?php echo $average_percentage_from_db; ?>%</p>
                    </div>
                </div>
            </div>
        </div>

        <h2>Test-wise Percentage Count (Based on Percentage)</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Test Name</th>
                    <th>Average Percentage</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($test_wise_percentage_result)): ?>
                    <tr>
                        <td><?php echo $row['test_name']; ?></td>
                        <td><?php echo isset($row['average_percentage']) ? round($row['average_percentage'], 2) : 0; ?>%</td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h2>Available Tests</h2>
        <div class="row">
            <?php while ($test_row = mysqli_fetch_assoc($available_tests_result)): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $test_row["name"]; ?></h5>
                            <p class="card-text"><?php echo $test_row["description"]; ?></p>
                            <a href="/user_test/start_test.php?test_id=<?php echo $test_row['id']; ?>" class="btn btn-primary">Take Test</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>


<?php
// Close the database connection
mysqli_close($conn);
?>
