<?php
include('../config/db.php');

if (!$db) {
    echo "Database connection not established.";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Payments</title>
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
    <h2>Payments</h2>
</div>

<table id="paymentTable">
    <tr>
        <th>Payment ID</th>
        <th>Customer ID</th>
        <th>Amount</th>
        <th>Date</th>
        <th>Status</th>
    </tr>
    <?php
    if ($db) {
        $query = "SELECT * FROM payments";
        $result = mysqli_query($db, $query);

        if (!$result) {
            die('Error in SQL query: ' . mysqli_error($db));
        }

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr class='clickable-row' data-pid='" . htmlspecialchars($row['payment_id']) . "' data-cid='" . htmlspecialchars($row['customer_id']) . "' data-amount='" . htmlspecialchars($row['amount']) . "' data-date='" . htmlspecialchars($row['date']) . "' data-status='" . htmlspecialchars($row['status']) . "'>";
            echo "<td>" . htmlspecialchars($row['payment_id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['customer_id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['amount']) . "</td>";
            echo "<td>" . htmlspecialchars($row['date']) . "</td>";
            echo "<td>" . htmlspecialchars($row['status']) . "</td>";
            echo "</tr>";
        }

        mysqli_free_result($result);
    } else {
        echo "<tr><td colspan='5'>No payments found</td></tr>";
    }

    mysqli_close($db);
    ?>
</table>

<script>
    document.querySelectorAll('.clickable-row').forEach(row => {
        row.addEventListener('click', () => {
            const pid = row.getAttribute('data-pid');
            const cid = row.getAttribute('data-cid');
            const amount = row.getAttribute('data-amount');
            const date = row.getAttribute('data-date');
            const status = row.getAttribute('data-status');

            document.querySelector('input[name="pid"]').value = pid;
            document.querySelector('input[name="cid"]').value = cid;
            document.querySelector('input[name="amount"]').value = amount;
            document.querySelector('input[name="date"]').value = date;
            document.querySelector('input[name="status"]').value = status;
        });
    });
</script>

</body>
</html>
