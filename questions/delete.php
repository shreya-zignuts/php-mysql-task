<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "password", "QuizManagement");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['id']) && isset($_GET['test_id'])) {
    $questionId = $_GET['id'];
    $testId = $_GET['test_id'];

    // Delete the question from the database
    $delete_query = "DELETE FROM questions WHERE id = $questionId";
    if (mysqli_query($conn, $delete_query)) {
        echo "Question deleted successfully!<br>";
        // Redirect back to view questions page
        header("Location: /questions/index.php?test_id=$testId");
        exit;
    } else {
        echo "Error deleting question: " . mysqli_error($conn);
    }
} else {
    echo "Question ID or Test ID not provided.";
    exit;
}

// Close connection
mysqli_close($conn);
?>
