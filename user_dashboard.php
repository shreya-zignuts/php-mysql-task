<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "QuizManagement";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Select tests with associated questions and check if the user has already taken them
$sql = "SELECT t.id, t.name, t.description,
               CASE WHEN EXISTS (SELECT 1 FROM results WHERE test_id = t.id AND user_id = ?)
                    THEN 1
                    ELSE 0
               END AS taken
        FROM tests t
        JOIN questions q ON t.id = q.test_id
        GROUP BY t.id, t.name, t.description";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Tests</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php require 'partials/_user_nav.php' ?>

    <div class="container mt-5">
        <h1 class="mb-4">Available Tests</h1>
        <?php if ($result->num_rows > 0): ?>
            <div class="row">
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $row["name"]; ?></h5>
                                <p class="card-text"><?php echo $row["description"]; ?></p>
                                <?php if ($row["taken"] == 1): ?>
                                    <button class="btn btn-primary" disabled>Test Taken</button>
                                <?php else: ?>
                                    <button class="btn btn-primary" onclick="startTest(<?php echo $row["id"]; ?>)">Take Test</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="alert alert-warning">No tests available.</p>
        <?php endif; ?>
    </div>

    <script>
        function startTest(testId) {
            if (confirm("Are you sure you want to take this test?")) {
                window.location.href = "/user_test/start_test.php?test_id=" + testId;
            }
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
