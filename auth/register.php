<?php
session_start();
include('../config/db.php');

$registration_success = false;
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    if ($username === $password) {
        $error_message = "Username and password cannot be the same!";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match!";
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $check_user_sql = "SELECT * FROM users WHERE username=? AND role=?";
        $stmt = $conn->prepare($check_user_sql);
        $stmt->bind_param("ss", $username, $role);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "Username already exists for this role!";
        } else {
            $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $username, $password_hash, $role);

            if ($stmt->execute()) {
                $registration_success = true;
                $redirect_url = 'home.php'; 
            } else {
                $error_message = "Error: " . $stmt->error;
            }
        }
        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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

        .input-group input, 
        .input-group select {
            width: calc(100% - 20px);
            padding: 15px;
            margin: 5px 0 15px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 22px;
        }

        .input-group select {
            height: 60px; 
            padding: 15px; 
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
        <h1>Travel Agency</h1>
    </div>
    <div class="container">
        <h2>Register</h2>
        <?php if ($registration_success): ?>
            <p>Registration successful! Redirecting to home page...</p>
            <script>
                setTimeout(function() {
                    window.location.href = '<?php echo $redirect_url; ?>';
                }, 2000);
            </script>
        <?php else: ?>
            <?php if (!empty($error_message)): ?>
                <div class="error"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <form method="post" action="">
                <div class="input-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="input-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="input-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <div class="input-group">
                    <label for="role">Role:</label>
                    <select id="role" name="role">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn">Register</button>
            </form>
            <p>Already have an account? <a href="login.php">Login here</a></p>
        <?php endif; ?>
    </div>
</body>
</html>
