<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "password", "QuizManagement");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['id'])) {
    $testId = $_GET['id'];

    // Fetch the test details from the database
    $query = "SELECT * FROM tests WHERE id = $testId";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $test = mysqli_fetch_assoc($result);
    } else {
        echo "Test not found.";
        exit;
    }
} else {
    echo "Test ID not provided.";
    exit;
}

if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $level = $_POST['level'];

    // Update the test in the database
    $update_query = "UPDATE tests SET name = '$name', description = '$description', level = '$level' WHERE id = $testId";
    if (mysqli_query($conn, $update_query)) {
        echo "Test updated successfully!<br>";
        // Redirect back to create_test.php
        header("Location: ../admin_dashboard.php");
        exit;
    } else {
        echo "Error updating test: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Test</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Edit Test</h1>
        <form action="" method="post">
            <div class="form-group">
                <label for="name">Test Name</label>
                <input type="text" id="name" name="name" value="<?php echo $test['name']; ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control"><?php echo $test['description']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="level">Level</label>
                <select name="level" id="level" class="form-control">
                    <option value="High" <?php if ($test['level'] == 'High') echo 'selected'; ?>>High</option>
                    <option value="Medium" <?php if ($test['level'] == 'Medium') echo 'selected'; ?>>Medium</option>
                    <option value="Low" <?php if ($test['level'] == 'Low') echo 'selected'; ?>>Low</option>
                </select>
            </div>
            <button type="submit" name="update" class="btn btn-primary">Update Test</button>
            <a href="/admin_dashboard.php" class="btn btn-primary">Back to Dashboard</a>

        </form>
    </div>

    <!-- Bootstrap JS (optional, if needed) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>


<?php
// Close connection
mysqli_close($conn);
?>
