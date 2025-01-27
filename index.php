<?php
session_start();
include 'db_connect.php'; // Include your database connection file

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the user's details
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Start the session
            $_SESSION['username'] = $user['username'];
            header("Location: home.php"); // Redirect to the home page
            exit();
        } else {
            $error = "Invalid password. Please try again.";
        }
    } else {
        $error = "No user found with that username.";
    }

    $stmt->close();
    $conn->close();
}
?>
