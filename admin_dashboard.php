<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "password", "QuizManagement");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch existing tests from the database
$tests_query = "SELECT * FROM tests";
$tests_result = mysqli_query($conn, $tests_query);
$tests = mysqli_fetch_all($tests_result, MYSQLI_ASSOC);

// Close connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Welcome, Admin!</h1>
        <a href="/tests/index.php" class="btn btn-primary mb-4">Create Test</a>

        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th>Test Name</th>
                    <th>Description</th>
                    <th>Level</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tests as $test): ?>
                    <tr>
                        <td><?php echo $test['name']; ?></td>
                        <td><?php echo $test['description']; ?></td>
                        <td><?php echo $test['level']; ?></td>
                        <td>
                       
                        <a href="/tests/details.php?id=<?php echo $test['id']; ?>" class="btn btn-success btn-sm"><?php echo $test['name']; ?> Details</a>
                        <a href='/tests/edit_test.php?id=<?php echo $test['id']; ?>' class="btn btn-primary btn-sm">Edit</a>
                        <a href='/tests/delete.php?id=<?php echo $test['id']; ?>' class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this test?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS (optional, if needed) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
