<?php
session_start();
include('../config/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = $_POST['username'];
    $password = $_POST['password'];

   
    $sql = "SELECT * FROM users WHERE username = ? AND role = 'admin'";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'admin';
            
            header("Location: ../admin/admin_dashboard.php");
            exit(); 
        } else {
            $error = "Invalid password.";
        }
    } else {
        
        $error = "No user found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Travel Agency Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #6A82FB, #FC5C7D);
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .header {
            background-color: #5F9EA0;
            color: #fff;
            padding: 30px;
            width: 100%;
            text-align: center;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .header h1 {
            font-size: 36px;
            margin: 0;
            letter-spacing: 1px;
        }

        .container {
            background-color: #ffffff;
            padding: 50px 40px;
            border-radius: 15px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 100%;
            text-align: center;
            margin-top: 120px; 
        }

        .container h2 {
            margin-bottom: 30px;
            font-size: 28px;
            color: #333;
        }

        .input-group {
            margin: 20px 0;
            text-align: left;
        }

        .input-group label {
            display: block;
            margin-bottom: 10px;
            color: #333;
            font-size: 22px;
            font-weight: bold; 
            text-align: left; 
        }

        .input-group input {
            width: calc(100% - 20px);
            padding: 15px;
            margin: 5px 0 15px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 22px;
        }

        .btn {
            width: 100%;
            background-color: #5F9EA0;
            color: white;
            padding: 16px 0;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 22px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .btn:hover {
            background-color: #4e8383;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .error {
            width: calc(100% - 20px);
            padding: 15px;
            border: 1px solid #a94442;
            color: #a94442;
            background-color: #f2dede;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: left;
            font-size: 16px;
        }

        p {
            font-size: 22px;
            margin: 20px 0;
        }

        a {
            color: #5F9EA0;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Travel Agency Login Credentials</h1>
    </div>
    <div class="container">
        <h2>Login</h2>
        <form method="POST" action="admin_login.php">
            <?php
            if (isset($error)) {
                echo "<div class='error'>$error</div>";
            }
            ?>
            <div class="input-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <div class="input-group">
                <button type="submit" class="btn">Login</button>
            </div>
            <p>
                Not yet a member? <a href="register.php">Sign up</a>
            </p>
        </form>
    </div>
</body>
</html>
