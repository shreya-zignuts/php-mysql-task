<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "password", "QuizManagement");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['id'])) {
    $testId = $_GET['id'];

    // Delete associated results first
    $delete_results_query = "DELETE FROM results WHERE test_id = $testId";
    if (mysqli_query($conn, $delete_results_query)) {
        // Now delete the test
        $delete_query = "DELETE FROM tests WHERE id = $testId";
        if (mysqli_query($conn, $delete_query)) {
            echo "Test deleted successfully!<br>";
            // Redirect back to admin dashboard
            header("Location: ../admin_dashboard.php");
            exit;
        } else {
            echo "Error deleting test: " . mysqli_error($conn);
        }
    } else {
        echo "Error deleting associated results: " . mysqli_error($conn);
    }
} else {
    echo "Test ID not provided.";
    exit;
}


// Close connection
mysqli_close($conn);
?>
