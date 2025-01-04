<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];

// Fetch current user data
$stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("s", $username);
if ($stmt->execute() === false) {
    die("Error executing statement: " . $conn->error);
}
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$error_msg = '';
$update_mode = false; // Track whether update mode is active

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_profile'])) {
        $new_username = $_POST['username'];
        $new_email = $_POST['email'];
        $old_password = $_POST['old_password']; // User's input for old password
        $new_password = $_POST['new_password']; // New password input
        
        // Verify old password
        if (password_verify($old_password, $user['password'])) {
            // Hash the new password if provided
            $new_password_hashed = !empty($new_password) ? password_hash($new_password, PASSWORD_DEFAULT) : $user['password'];

            // Update the user information
            $stmt = $conn->prepare("UPDATE user SET username = ?, email = ?, password = ? WHERE id = ?");
            if ($stmt === false) {
                die("Error preparing statement: " . $conn->error);
            }
            $stmt->bind_param("sssi", $new_username, $new_email, $new_password_hashed, $user['id']);
            if ($stmt->execute() === false) {
                die("Error executing statement: " . $conn->error);
            }

            $_SESSION['username'] = $new_username;
            $_SESSION['success_msg'] = "Profile updated successfully.";
            header("Location: profile.php");
            exit();
        } else {
            $error_msg = "Old password is incorrect!";
        }
    } elseif (isset($_POST['enable_update'])) {
        $update_mode = true; // Activate update mode
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
            color: #333;
            margin: 0;
            padding: 0;
            background-image: linear-gradient(to right, #000000, #611717, #bc0606); 
            background-size: cover; 
            background-attachment: fixed;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #800000;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .submit-btn, .update-btn {
            background-color: #800000;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            text-align: center;
            display: block;
            margin-bottom: 20px;
        }
        .submit-btn:hover, .update-btn:hover {
            background-color: #6c3434;
        }
        .error {
            color: red;
            text-align: center;
        }
        .hidden {
            display: none;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: rgb(60, 34, 34);   
            background-image: linear-gradient(to right, #000000, #611717, #bc0606); 
            background-size: cover; 
            background-attachment: fixed;
            margin: 0;
            padding: 0;
        }
        #topmain {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #291818;
        }
        #logo img {
            height: 60px;
            width: auto;
        }
        nav {
            display: flex;
            flex-grow: 1;
            margin-left: 110px;
        }
        nav a {
            position: relative;
            color: #ffffff;
            text-decoration: none;
            padding: 5px 15px;
            font-size: 18px;
        }
        nav a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background-color: #ffffff;
            left: 0;
            bottom: 0;
            transition: width 0.3s ease-in-out;
        }
        nav a:hover::after {
            left: 30%;
            width: 40%;
        }
    </style>
    <script>
        function enableEdit() {
            document.getElementById("username").value = ''; // Clear the fields for update
            document.getElementById("email").value = '';
            document.getElementById("old_password").value = '';
            document.getElementById("new_password").value = '';
            document.getElementById("submit-btn").style.display = 'block'; // Show the submit button
            document.getElementById("update-btn").style.display = 'none'; // Hide update button
            document.getElementById("profile-fields").style.display = 'block'; // Show profile fields
        }
    </script>
</head>
<body>
<header id="topmain">
        <div id="logo">
            <img src="logofit.png" alt="Logo">
        </div>
        <nav>
        <a href="home.php">Home</a>
            <a href="plan.html">Planner</a>
            <a href="calci.html">Calculators</a>
            <a href="profile.php">Profile</a>
            <a href="dashboard.html">Dashboard</a>
        </nav>
         
    </header>
    <div class="container">
        <h1>Profile</h1>
        <?php if (!empty($error_msg)) : ?>
            <p class="error"><?php echo $error_msg; ?></p>
        <?php endif; ?>

        <!-- If in update mode, show update form -->
        <form action="profile.php" method="post">
            <?php if ($update_mode) : ?>
                <!-- Display form fields for profile update -->
                <label for="old_password">Old Password:</label>
                <input type="password" id="old_password" name="old_password" placeholder="Enter old password" required>

                <label for="username">New Username:</label>
                <input type="text" id="username" name="username" placeholder="Enter new username">

                <label for="email">New Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter new email">

                <label for="new_password">New Password (optional):</label>
                <input type="password" id="new_password" name="new_password" placeholder="Enter new password (optional)">

                <!-- Submit button to save changes -->
                <input type="submit" id="submit-btn" class="submit-btn" name="update_profile" value="Save Changes">
            <?php else : ?>
                <!-- Display existing Username -->
                <label for="username_display">Username:</label>
                <input type="text" id="username_display" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>

                <!-- Display existing Email -->
                <label for="email_display">Email:</label>
                <input type="email" id="email_display" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>

                <!-- Display existing Password (masked) -->
                <label for="password_display">Password:</label>
                <input type="password" id="password_display" value="*" disabled>

                <!-- Button to enable editing -->
                <button type="submit" name="enable_update" class="update-btn">Update Profile</button>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>