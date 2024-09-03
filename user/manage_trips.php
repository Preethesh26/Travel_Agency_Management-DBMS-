<?php
session_start();
include('../config/db.php'); 

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php"); 
    exit();
}

$trip_id = '';
$destination = '';
$departure_date = '';
$return_date = '';
$price = '';
$status = '';
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $trip_id = $_POST['trip_id'] ?? '';
    $destination = $_POST['destination'] ?? '';
    $departure_date = $_POST['departure_date'] ?? '';
    $return_date = $_POST['return_date'] ?? '';
    $price = $_POST['price'] ?? '';
    $status = $_POST['status'] ?? '';

    if (isset($_POST['add_trip'])) {
        $stmt = $db->prepare("SELECT trip_id FROM trips WHERE trip_id=?");
        $stmt->bind_param("s", $trip_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error_message = "Error: Trip ID already exists.";
        } else {
            
            $stmt = $db->prepare("INSERT INTO trips (trip_id, destination, departure_date, return_date, price, status) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $trip_id, $destination, $departure_date, $return_date, $price, $status);

            if ($stmt->execute()) {
                $success_message = "Trip added successfully.";
                $trip_id = $destination = $departure_date = $return_date = $price = $status = ''; // Clear fields
            } else {
                $error_message = "Error adding trip: " . $stmt->error;
            }
            $stmt->close();
        }
    } elseif (isset($_POST['update_trip'])) {
        
        $stmt = $db->prepare("UPDATE trips SET destination=?, departure_date=?, return_date=?, price=?, status=? WHERE trip_id=?");
        $stmt->bind_param("ssssss", $destination, $departure_date, $return_date, $price, $status, $trip_id);

        if ($stmt->execute()) {
            $success_message = "Trip updated successfully.";
        } else {
            $error_message = "Error updating trip: " . $stmt->error;
        }
        $stmt->close();
    } elseif (isset($_POST['delete_trip'])) {
       
        $stmt = $db->prepare("DELETE FROM trips WHERE trip_id=?");
        $stmt->bind_param("s", $trip_id);

        if ($stmt->execute()) {
            $success_message = "Trip deleted successfully.";
            $trip_id = $destination = $departure_date = $return_date = $price = $status = ''; // Clear fields
        } else {
            $error_message = "Error deleting trip: " . $stmt->error;
        }
        $stmt->close();
    }
}

$query = "SELECT * FROM trips";
$result = $db->query($query);

if (!$result) {
    die('Error in SQL query: ' . $db->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Trips</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="manage.css">
</head>
<body>
<div class="header">
    <h2>Manage Trips</h2>
</div>
<a href="../admin/admin_dashboard.php" class="back-button">Back to Dashboard</a>
<div class="form-container">
    <div id="messageContainer">
        <p id="error-message" class="error-message">Error message will appear here.</p>
        <p id="success-message" class="success-message">Operation successful!</p>
    </div>
    <h2>Trip Form</h2>
    <form method="post">
        <label for="trip_id">Trip ID:</label>
        <input type="text" id="trip_id" name="trip_id" value="<?php echo htmlspecialchars($trip_id, ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="destination">Destination:</label>
        <input type="text" id="destination" name="destination" value="<?php echo htmlspecialchars($destination, ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="departure_date">Departure Date:</label>
        <input type="date" id="departure_date" name="departure_date" value="<?php echo htmlspecialchars($departure_date, ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="return_date">Return Date:</label>
        <input type="date" id="return_date" name="return_date" value="<?php echo htmlspecialchars($return_date, ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="price">Price:</label>
        <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($price, ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="status">Status:</label>
        <input type="text" id="status" name="status" value="<?php echo htmlspecialchars($status, ENT_QUOTES, 'UTF-8'); ?>" required>

        <div class="button-container">
            <input type="submit" name="add_trip" value="Add Trip">
            <input type="submit" name="update_trip" value="Update Trip">
            <input type="submit" name="delete_trip" value="Delete Trip">
            <input type="button" value="Clear" onclick="clearForm()">
        </div>
    </form>
</div>

<div class="table-container">
    <table id="tripTable">
        <tr>
            <th>Trip ID</th>
            <th>Destination</th>
            <th>Departure Date</th>
            <th>Return Date</th>
            <th>Price</th>
            <th>Status</th>
        </tr>
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<tr data-id='" . htmlspecialchars($row['trip_id'], ENT_QUOTES, 'UTF-8') . "' data-destination='" . htmlspecialchars($row['destination'], ENT_QUOTES, 'UTF-8') . "' data-departure_date='" . htmlspecialchars($row['departure_date'], ENT_QUOTES, 'UTF-8') . "' data-return_date='" . htmlspecialchars($row['return_date'], ENT_QUOTES, 'UTF-8') . "' data-price='" . htmlspecialchars($row['price'], ENT_QUOTES, 'UTF-8') . "' data-status='" . htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8') . "'>";
            echo "<td>" . htmlspecialchars($row['trip_id'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['destination'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['departure_date'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['return_date'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['price'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "</tr>";
        }
        $result->free();
        ?>
    </table>
</div>
<script>
    document.querySelectorAll('#tripTable tr').forEach(row => {
        row.addEventListener('click', () => {
            const trip_id = row.getAttribute('data-id');
            const destination = row.getAttribute('data-destination');
            const departure_date = row.getAttribute('data-departure_date');
            const return_date = row.getAttribute('data-return_date');
            const price = row.getAttribute('data-price');
            const status = row.getAttribute('data-status');

            document.getElementById('trip_id').value = trip_id;
            document.getElementById('destination').value = destination;
            document.getElementById('departure_date').value = departure_date;
            document.getElementById('return_date').value = return_date;
            document.getElementById('price').value = price;
            document.getElementById('status').value = status;
        });
    });

    function clearForm() {
        document.getElementById('trip_id').value = '';
        document.getElementById('destination').value = '';
        document.getElementById('departure_date').value = '';
        document.getElementById('return_date').value = '';
        document.getElementById('price').value = '';
        document.getElementById('status').value = '';
    }

    <?php if ($error_message): ?>
    const errorMessageElement = document.getElementById('error-message');
    errorMessageElement.textContent = '<?php echo addslashes($error_message); ?>';
    errorMessageElement.style.display = 'block';

    setTimeout(() => {
        errorMessageElement.style.display = 'none';
    }, 3000); 
    <?php endif; ?>

    <?php if ($success_message): ?>
    const successMessageElement = document.getElementById('success-message');
    successMessageElement.textContent = '<?php echo addslashes($success_message); ?>';
    successMessageElement.style.display = 'block';
    <?php endif; ?>
</script>

</body>
</html>
