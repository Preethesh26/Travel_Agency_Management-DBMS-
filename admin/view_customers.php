<?php
session_start();
include('../config/db.php'); // Ensure this path is correct

// Check if the user is logged in and has admin role
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php"); // Redirect to login if not authorized
    exit();
}

// Fetch customer records from the database
$query = "SELECT * FROM customer";
$result = $db->query($query);

// Check for errors
if (!$result) {
    die('Error in SQL query: ' . $db->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Customers</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, rgb(255, 0, 150), rgb(0, 204, 255), rgb(255, 159, 64)); /* Gradient background */
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            overflow: hidden;
        }

        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
            z-index: 1000; 
        }

        .back-button:hover {
            background-color: #218838;
        }

        .header {
            background: rgba(0, 0, 0, 0.8); 
            padding: 30px;
            width: 90%;
            max-width: 1200px;
            text-align: center;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.6);
            border-radius: 10px;
            margin-top: 70px; 
        }

        .header h2 {
            margin: 0;
            font-size: 40px;
            color: #fff;
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.7);
        }

        table {
            width: 90%;
            border-collapse: collapse;
            background: rgba(0, 0, 0, 0.8); 
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.6);
            margin: 20px 0; 
            max-width: 1200px;
            box-sizing: border-box;
        }

        th, td {
            border: 1px solid #ddd;
            text-align: left;
            padding: 15px;
        }

        th {
            background-color: #555;
            color: #fff;
            font-size: 18px;
        }

        td {
            color: #eee;
            font-size: 16px;
        }

        @media (max-width: 768px) {
            .header h2 {
                font-size: 32px;
            }

            table {
                padding: 10px;
            }

            th, td {
                padding: 10px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
<a href="admin_dashboard.php" class="back-button">Back to Admin Dashboard</a>
<div class="header">
    <h2>Customer Records</h2>
</div>
<table id="customerTable">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Address</th>
    </tr>
    <?php
   
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
        echo "<td>" . htmlspecialchars($row['address']) . "</td>";
        echo "</tr>";
    }

    $result->free();
    ?>
</table>
</body>
</html>
