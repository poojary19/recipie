<?php
session_start();

// Check if user is already logged in, redirect to admin page if so
if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
    header("Location: admin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db_connect.php'; // Include the database connection script

    // Retrieve username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to check if the username and password match an admin record
    $sql = "SELECT * FROM admin WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // If a matching admin record is found, set user data in session and redirect to admin page
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION['user'] = array(
            'id' => $user['id'],
            'username' => $user['username'],
            'role' => 'admin' // Set the role to 'admin'
        );
        header("Location: admin.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-image: url('dessert1.jpg'); background-size: cover;
        }

        .container {
            width: 400px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
        }

        header {
            font-family:times-new-roman;
            background-color: rgb(43, 35, 75);
            color: #fff;
            text-align: center;
            padding: 10px;
        }

        h2 {
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 40%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color:  rgb(43, 35, 75);
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: rgb(24, 21, 66);
        }

        .error-message {
            color: red;
            text-align: center;
        }
        .close {
            color: white;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: wheat;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
    <header>
    <span class="close" onclick="window.location.href='index.php'">&times;</span><br>
        <h2>Login</h2>
    </header><br>
        <?php if (isset($error)): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
