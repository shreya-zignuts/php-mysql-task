<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "password", "QuizManagement");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle form submission
if (isset($_POST['create'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $level = $_POST['level'];

    // Insert record into tests table
    $insert_query = "INSERT INTO tests (name, description, level) VALUES ('$name', '$description', '$level')";
    if (mysqli_query($conn, $insert_query)) {
        header("Location: ../admin_dashboard.php");
        exit;
        $testId = mysqli_insert_id($conn);
        // Fetch users from the database
        $users_query = "SELECT * FROM users";
        $users_result = mysqli_query($conn, $users_query);
        $users = mysqli_fetch_all($users_result, MYSQLI_ASSOC);
        ?>
        <?php
    } else {
        echo "Error: " . $insert_query . "<br>" . mysqli_error($conn);
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
    <title>Create Test</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Create Test</h1>
        <!-- Form for creating a new test -->
        <form action="index.php" method="post">
            <div class="form-group">
                <label for="name">Test Name</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="level">Level</label>
                <select name="level" id="level" class="form-control">
                    <option value="High">High</option>
                    <option value="Medium">Medium</option>
                    <option value="Low">Low</option>
                </select>
            </div>
            <button type="submit" name="create" class="btn btn-primary">Create Test</button>
            <a href="/admin_dashboard.php" class="btn btn-primary">Back to Dashboard</a>
        </form>
    </div>

    <!-- Bootstrap JS (optional, if needed) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

