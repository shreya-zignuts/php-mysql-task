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
