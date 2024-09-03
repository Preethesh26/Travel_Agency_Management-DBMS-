<?php
include('../config/db.php');
if (!$db) {
    die('Database connection not established.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Vehicles</title>
    <link rel="stylesheet" type="text/css" href="style.css">
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

        .header {
            background: rgba(0, 0, 0, 0.8);
            padding: 30px;
            width: 90%;
            max-width: 1200px;
            text-align: center;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.6);
            border-radius: 10px;
            margin-top: 20px;
        }

        .header h2 {
            margin: 0;
            font-size: 40px;
            color: #fff;
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.7);
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
    <h2>Vehicles</h2>
</div>

<table id="vehicleTable">
    <tr>
        <th>Vehicle ID</th>
        <th>Vehicle Type</th>
        <th>Model</th>
        <th>Registration Number</th>
        <th>Capacity</th>
    </tr>
    <?php
    $query = "SELECT * FROM vehicles";
    $result = $db->query($query);

    if (!$result) {
        die('Error in SQL query: ' . $db->error);
    }

    while ($row = $result->fetch_assoc()) {
        echo "<tr class='clickable-row' data-vid='" . htmlspecialchars($row['vehicle_id']) . "' data-vtype='" . htmlspecialchars($row['vehicle_type']) . "' data-model='" . htmlspecialchars($row['model']) . "' data-regnum='" . htmlspecialchars($row['registration_number']) . "' data-capacity='" . htmlspecialchars($row['capacity']) . "'>";
        echo "<td>" . htmlspecialchars($row['vehicle_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['vehicle_type']) . "</td>";
        echo "<td>" . htmlspecialchars($row['model']) . "</td>";
        echo "<td>" . htmlspecialchars($row['registration_number']) . "</td>";
        echo "<td>" . htmlspecialchars($row['capacity']) . "</td>";
        echo "</tr>";
    }

    $result->free();
    ?>
</table>

<script>
    document.querySelectorAll('.clickable-row').forEach(row => {
        row.addEventListener('click', () => {
            const vid = row.getAttribute('data-vid');
            const vtype = row.getAttribute('data-vtype');
            const model = row.getAttribute('data-model');
            const regnum = row.getAttribute('data-regnum');
            const capacity = row.getAttribute('data-capacity');

            document.querySelector('input[name="vid"]').value = vid;
            document.querySelector('input[name="vtype"]').value = vtype;
            document.querySelector('input[name="model"]').value = model;
            document.querySelector('input[name="regnum"]').value = regnum;
            document.querySelector('input[name="capacity"]').value = capacity;
        });
    });
</script>

</body>
</html>
