<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Travel Agency Management System</title>
    <style>
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
            background: linear-gradient(to right, rgb(255, 0, 150), rgb(0, 204, 255), rgb(255, 159, 64));
            color: #fff;
        }

        .sidebar {
            background: rgba(0, 0, 0, 0.8);
            color: #fff;
            width: 300px;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        .sidebar h2 {
            font-size: 36px;
            margin: 0;
            padding-bottom: 20px;
        }
        .sidebar nav ul {
            list-style: none;
            padding: 0;
        }
        .sidebar nav ul li {
            margin: 15px 0;
        }
        .sidebar nav ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
            transition: background-color 0.3s, padding-left 0.3s;
            display: block;
            padding: 10px;
        }
        .sidebar nav ul li a:hover {
            background-color: #333;
            padding-left: 20px;
        }

        .main-content {
            margin-left: 320px;
            padding: 30px;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .main-content h2 {
            font-size: 32px;
            margin-bottom: 20px;
        }
        .main-content p {
            font-size: 20px;
            margin-bottom: 30px;
        }
        .button-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            max-width: 1200px;
            width: 100%;
        }
        .button-container form {
            display: inline-block;
        }
        .butt {
            display: inline-block;
            padding: 15px 30px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.3s;
            position: relative;
            overflow: hidden;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .butt::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            width: 300%;
            height: 300%;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%) scale(0);
            transition: transform 0.4s;
            z-index: 0;
        }
        .butt:hover::before {
            transform: translate(-50%, -50%) scale(1);
        }
        .butt:hover {
            background-color: #0056b3;
            transform: translateY(-4px);
        }
        .logout-btn {
            background-color: #dc3545;
        }
        .logout-btn:hover {
            background-color: #c82333;
        }

        footer {
            background: rgba(0, 0, 0, 0.8);
            color: #fff;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: calc(100% - 300px);
            margin-left: 300px;
        }

        table {
            width: 90%;
            border-collapse: collapse;
            background: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.6);
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            text-align: left;
            padding: 10px;
        }
        th {
            background-color: #333;
            color: #fff;
        }
        td {
            color: #ddd;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
            .butt {
                padding: 10px 20px;
                font-size: 16px;
            }
            table {
                padding: 10px;
            }
            th, td {
                padding: 8px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Dashboard</h2>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
                <li><a href="manage_customers.php">Manage Customers</a></li>
                <li><a href="manage_vehicle.php">Manage Vehicles</a></li>
                <li><a href="manage_trips.php">Manage Trips</a></li>
                <li><a href="manage_payments.php">Manage Payments</a></li>
                <li><a href="manage_bookings.php">Manage Bookings</a></li>
                <li><a href="../auth/logout.php" class="logout-btn">Logout</a></li>
            </ul>
        </nav>
    </div>

    <div class="main-content">
        <h2>Admin Panel</h2>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>

        <div class="button-container">
            <form method="post" action="view_customers.php">
                <button type="submit" class="butt" name="view_customers">View Customers</button>
            </form>
            <form method="post" action="view_vehicles.php">
                <button type="submit" class="butt" name="view_vehicles">View Vehicles</button>
            </form>
            <form method="post" action="view_trips.php">
                <button type="submit" class="butt" name="view_trips">View Trips</button>
            </form>
            <form method="post" action="view_payments.php">
                <button type="submit" class="butt" name="view_payments">View Payments</button>
            </form>
            <form method="post" action="view_bookings.php">
                <button type="submit" class="butt" name="view_bookings">View Bookings</button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Travel Agency. All rights reserved.</p>
    </footer>
</body>
</html>
