<?php 
include('../config/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehicle_id = mysqli_real_escape_string($db, $_POST['vid']);
    $customer_id = mysqli_real_escape_string($db, $_POST['cid']);
    $booking_date = mysqli_real_escape_string($db, $_POST['bdate']);
    $return_date = mysqli_real_escape_string($db, $_POST['rdate']);
    $id = isset($_POST['id']) ? mysqli_real_escape_string($db, $_POST['id']) : '';

    if (isset($_POST['add'])) {
        $query = "INSERT INTO bookings (vehicle_id, customer_id, booking_date, return_date) VALUES ('$vehicle_id', '$customer_id', '$booking_date', '$return_date')";
        if (!mysqli_query($db, $query)) {
            die('Error: ' . mysqli_error($db));
        }
    } elseif (isset($_POST['update'])) {
        $query = "UPDATE bookings SET vehicle_id='$vehicle_id', customer_id='$customer_id', booking_date='$booking_date', return_date='$return_date' WHERE id='$id'";
        if (!mysqli_query($db, $query)) {
            die('Error: ' . mysqli_error($db));
        }
    } elseif (isset($_POST['delete'])) {
        $query = "DELETE FROM bookings WHERE id='$id'";
        if (!mysqli_query($db, $query)) {
            die('Error: ' . mysqli_error($db));
        }
    } elseif (isset($_POST['clear'])) {

     }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Bookings</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="manage.css">
    <style>
       body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, rgb(255, 0, 150), rgb(0, 204, 255), rgb(255, 159, 64)); 
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

        form {
            background: rgba(0, 0, 0, 0.8);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.6);
            margin: 20px 0; 
            width: 90%;
            max-width: 1200px;
            box-sizing: border-box;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 18px;
            color: #ccc;
        }

        .input-group input {
            width: 100%;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            background-color: #fff;
            color: #333;
            font-size: 20px; 
            line-height: 1.5; 
        }

        .btn {
            background-color: #3498db;
            border: none;
            color: #fff;
            padding: 15px 25px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 18px;
            margin: 10px;
            cursor: pointer;
            border-radius: 8px;
            transition: background-color 0.3s, box-shadow 0.3s, transform 0.3s;
        }

        .btn:hover {
            background-color: #2980b9;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);
            transform: scale(1.05);
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

            form {
                padding: 20px;
            }

            .input-group input {
                padding: 12px;
                font-size: 18px;
            }

            .btn {
                padding: 12px 20px;
                font-size: 16px;
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
    <div class="header">
        <h2>Manage Bookings</h2>
    </div>

    <form method="post" action="manage_bookings.php">
        <div class="input-group">
            <label>Booking ID</label>
            <input type="text" name="id">
        </div>
        <div class="input-group">
            <label>Vehicle ID</label>
            <input type="text" name="vid">
        </div>
        <div class="input-group">
            <label>Customer ID</label>
            <input type="text" name="cid">
        </div>
        <div class="input-group">
            <label>Booking Date</label>
            <input type="date" name="bdate">
        </div>
        <div class="input-group">
            <label>Return Date</label>
            <input type="date" name="rdate">
        </div>
        <div class="input-group">
            <button type="submit" class="btn" name="add">Add</button>
            <button type="submit" class="btn" name="update">Update</button>
            <button type="submit" class="btn" name="delete">Delete</button>
            <button type="button" class="btn" onclick="clearForm()">Clear</button>
        </div>
    </form>

    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Vehicle ID</th>
                <th>Customer ID</th>
                <th>Booking Date</th>
                <th>Return Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = mysqli_query($db, "SELECT * FROM bookings");
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr class='clickable-row' data-id='{$row['id']}' data-vid='{$row['vehicle_id']}' data-cid='{$row['customer_id']}' data-bdate='{$row['booking_date']}' data-rdate='{$row['return_date']}'>
                            <td>{$row['id']}</td>
                            <td>{$row['vehicle_id']}</td>
                            <td>{$row['customer_id']}</td>
                            <td>{$row['booking_date']}</td>
                            <td>{$row['return_date']}</td>
                          </tr>";
                }
            }
            ?>
        </tbody>
    </table>

    <script>
        function clearForm() {
            document.querySelector('form').reset();
        }

        document.querySelectorAll('.clickable-row').forEach(row => {
            row.addEventListener('click', () => {
                document.querySelector('input[name="id"]').value = row.dataset.id;
                document.querySelector('input[name="vid"]').value = row.dataset.vid;
                document.querySelector('input[name="cid"]').value = row.dataset.cid;
                document.querySelector('input[name="bdate"]').value = row.dataset.bdate;
                document.querySelector('input[name="rdate"]').value = row.dataset.rdate;
            });
        });
    </script>
</body>
</html>
