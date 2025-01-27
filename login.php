<?php
session_start();
include 'db_connect.php'; // Include your database connection file

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Fitness Planner</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        body {
            width: 100vw;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            background-image: url(bg.jpg);
            background-size: cover;
            background-position: center;
        }

        .nav__links {
            display: flex;
            align-items: center;
            list-style: none;
            width: 100%;
            padding: 15px 20px;
            background: rgba(0, 0, 0, 0.7);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .nav__links .link {
            margin-right: 30px;
        }

        .nav__links .link a {
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.2em;
            transition: color 0.3s;
        }

        .nav__links .link a:hover {
            color: #ddd;
        }

        .main-content {
            width: 100%;
            height: calc(100vh - 70px);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 70px;
        }

        .container {
            width: 400px;
            background: rgba(0, 0, 0, 0.7);
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.8);
            padding: 30px;
            border-radius: 20px;
            border: 3px solid rgba(255, 255, 255, 0.5);
            color: white;
        }

        h2 {
            color: #f1f8fa;
            font-size: 2em;
            text-transform: uppercase;
            text-align: center;
        }

        .form-group {
            position: relative;
            width: 330px;
            margin: 30px 0;
            border-bottom: 3px solid #fafeff;
        }

        .form-group input {
            width: 100%;
            height: 50px;
            padding: 0 35px 0 10px;
            font-size: 1.2em;
            background-color: transparent;
            border: none;
            outline: none;
            color: white;
        }

        .form-group label {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            font-size: 1.2em;
            color: white;
            transition: 0.5s;
        }

        input:focus ~ label,
        input:valid ~ label {
            top: -5px;
        }

        .form-group i {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            color: white;
            font-size: 1.2em;
        }

        #btn {
            width: 100%;
            height: 50px;
            border-radius: 40px;
            border: none;
            font-size: 1.5em;
            text-transform: uppercase;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.5s;
            background: linear-gradient(to right, #1b0702, #37100e);
            color: white;
            margin-top: 10px;
        }

        #btn:hover {
            background: linear-gradient(to right, #2e2725, #481d1b);
        }

        p {
            text-align: center;
            color: white;
        }

        p>a {
            text-decoration: none;
            color: #eff6f8;
            font-weight: 600;
        }

        p>a:hover {
            text-decoration: underline;
            font-style: italic;
        }

        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }

        @media (max-width: 480px) {
            .container {
                width: 90%;
                padding: 20px;
            }

            h2 {
                font-size: 1.5em;
            }

            .form-group {
                width: 100%;
            }

            #btn {
                font-size: 1.2em;
            }
        }
    </style>
</head>
<body>

<!-- Main Content -->
<div class="main-content">
    <div class="container">
        <h2>Login</h2>
        
        <form action="" method="post">
            <div class="form-group">
                <input type="text" name="username" id="username" required aria-label="Username">
                <label for="username">Username</label>
                <i class="fas fa-user"></i>
            </div>

            <div class="form-group">
                <input type="password" name="password" id="password" required aria-label="Password">
                <label for="password">Password</label>
                <i class="fas fa-lock"></i>
            </div>

            <input id="btn" type="submit" value="Login">
            
            <?php if (isset($error)): ?>
                <p class="error"><?= $error ?></p>
            <?php endif; ?>

            <p>Don't have an account? <a href="register.php">Register</a></p>
        </form>
    </div>
</div>

</body>
</html>
