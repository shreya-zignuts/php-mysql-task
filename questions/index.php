<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "password", "QuizManagement");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the test ID from the URL
if (isset($_GET['test_id'])) {
    $testId = $_GET['test_id'];

    // Fetch questions for the specific test ID
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
    <title>Questions for Test ID: <?php echo $testId; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Questions for Test ID: <?php echo $testId; ?></h1>

        <div class="mt-4">
            <table class="table text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Question</th>
                        <th>Options</th>
                        <th>Correct Option</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($questions_result) === 0) {
                        echo "<tr><td colspan='5'>No questions are created for this test.</td></tr>";
                    } else {
                        $counter = 1;
                        while ($row = mysqli_fetch_assoc($questions_result)) {
                    ?>
                        <tr>
                            <td><?php echo $counter; ?></td>
                            <td><?php echo $row['question']; ?></td>
                            <td>
                                <ul>
                                    <li><?php echo $row['option1']; ?></li>
                                    <li><?php echo $row['option2']; ?></li>
                                    <li><?php echo $row['option3']; ?></li>
                                    <li><?php echo $row['option4']; ?></li>
                                </ul>
                            </td>
                            <td><?php echo $row['correct_option']; ?></td>
                            <td>
                                <a href='/questions/view.php?id=<?php echo $row['id']; ?>' class='btn btn-info btn-sm'>View</a>
                                <a href='/questions/edit.php?id=<?php echo $row['id']; ?>' class='btn btn-primary btn-sm'>Edit</a>
                                <a href='/questions/delete.php?id=<?php echo $row['id']; ?>&test_id=<?php echo $testId; ?>' class='btn btn-danger btn-sm' onclick="return confirm('Are you sure you want to delete this question?')">Delete</a>
                            </td>
                        </tr>
                    <?php
                            $counter++;
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <a href="/admin_dashboard.php" class="btn btn-primary">Back to Dashboard</a>
        <a href="/questions/create.php?test_id=<?php echo $testId; ?>" class="btn btn-secondary">Create Question</a>
    </div>

    <!-- Bootstrap JS (optional, if needed) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

