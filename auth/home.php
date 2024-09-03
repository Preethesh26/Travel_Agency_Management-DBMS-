<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Agency</title>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            height: 100vh;
            background-image: url('travel.jpg'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .header {
            text-align: center;
            margin-bottom: 50px;
            padding: 20px;
            background: rgba(0, 0, 0, 0.5); 
            border-radius: 15px;
        }

        .header h2 {
            font-size: 70px; 
            font-weight: 700;
            color: #ffdd57;
            text-shadow: 6px 6px 12px rgba(0, 0, 0, 0.7);
            letter-spacing: 2px;
            margin: 0;
            text-transform: uppercase;
            transition: color 0.3s ease, text-shadow 0.3s ease;
        }

        .header h2:hover {
            color: #ff4b2b;
            text-shadow: 6px 6px 15px rgba(0, 0, 0, 0.9); 
        }

        .button-container {
            display: flex;
            gap: 20px;
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .button-container a {
            text-decoration: none;
        }

        .button-container button {
            padding: 20px 60px;
            font-size: 24px;
            font-weight: bold;
            border: 3px solid #ffffff;
            border-radius: 50px;
            cursor: pointer;
            transition: transform 0.3s ease, background-color 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
            background: linear-gradient(135deg, #ff416c, #ff4b2b);
            color: #ffffff;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4), 0 5px 15px rgba(255, 65, 108, 0.7);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .button-container button:before {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            width: 300%;
            height: 300%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%) rotate(45deg);
            opacity: 0;
            transition: opacity 0.6s, transform 0.6s;
            z-index: -1;
        }

        .button-container button:hover {
            background: linear-gradient(135deg, #ff4b2b, #ff416c);
            transform: scale(1.1);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5), 0 10px 20px rgba(255, 75, 43, 0.8);
            border-color: #ffdd57;
        }

        .button-container button:hover:before {
            transform: translate(-50%, -50%) rotate(0deg);
            opacity: 1;
        }

        .button-container button:active {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4), 0 5px 10px rgba(255, 75, 43, 0.6);
        }

        .footer {
            position: absolute;
            bottom: 20px;
            width: 100%;
            text-align: center;
            color: #ffffff;
            font-size: 16px;
            background: rgba(0, 0, 0, 0.5);
            padding: 10px 0;
            border-top: 1px solid #ffffff;
        }
    </style>
</head>
<body>
    <div class="button-container">
        <a href="admin_login.php"><button>Admin Login</button></a>
        <a href="user_login.php"><button>User Login</button></a>
        <a href="register.php"><button>Register</button></a>
    </div>
    <div class="header">
        <h2>Welcome to Our Travel Agency</h2>
    </div>
    <div class="footer">
        © 2024 Travel Agency. All rights reserved.
    </div>
</body>
</html>
