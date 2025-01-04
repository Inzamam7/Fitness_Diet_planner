<!DOCTYPE html> 
<html lang="en">
<head>
    <title>Register</title>
    <link rel="stylesheet" href="st.css">
</head>
<body>
    <div class="container">
        <h2>Registration Form</h2>
        <form action="register.php" method="post">
            <input type="text" name="username" placeholder="Enter Username" required>
            <input type="password" name="password" placeholder="Enter Password" required>
            <input type="email" name="email" placeholder="Enter Email" required>
            <input type="tel" name="contact" placeholder="Enter Phone Number" required>
            
            <div class="gender-container">
                <label for="gender">Gender:</label>
                <input type="radio" id="male" name="gender" value="Male" required>
                <label for="male">Male</label>
                <input type="radio" id="female" name="gender" value="Female">
                <label for="female">Female</label>
                <input type="radio" id="other" name="gender" value="Other">
                <label for="other">Other</label>
            </div>
            <button type="submit">Register</button>
        </form>
    </div>

    <?php
    include 'db_connect.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $password = $_POST['password'];
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $contact = filter_var($_POST['contact'], FILTER_SANITIZE_STRING);
        $gender = $_POST['gender'];

        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO user (username, password, email, contact, gender) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $password, $email, $contact, $gender);

        if ($stmt->execute()) {
            header("Location:index.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html>
