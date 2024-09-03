<?php
session_start();
include('../config/db.php'); 

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php"); 
    exit();
}

$vehicle_id = '';
$vehicle_type = '';
$model = '';
$registration_number = '';
$capacity = '';
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vehicle_id = $_POST['vehicle_id'] ?? '';
    $vehicle_type = $_POST['vehicle_type'] ?? '';
    $model = $_POST['model'] ?? '';
    $registration_number = $_POST['registration_number'] ?? '';
    $capacity = $_POST['capacity'] ?? '';

    if (isset($_POST['add_vehicle'])) {
        $stmt = $db->prepare("SELECT vehicle_id FROM vehicles WHERE vehicle_id=?");
        $stmt->bind_param("s", $vehicle_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error_message = "Error: Vehicle ID already exists.";
        } else {
            $stmt = $db->prepare("INSERT INTO vehicles (vehicle_id, vehicle_type, model, registration_number, capacity) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $vehicle_id, $vehicle_type, $model, $registration_number, $capacity);

            if ($stmt->execute()) {
                $success_message = "Vehicle added successfully.";
                $vehicle_id = $vehicle_type = $model = $registration_number = $capacity = ''; 
            } else {
                $error_message = "Error adding vehicle: " . $stmt->error;
            }
            $stmt->close();
        }
    } elseif (isset($_POST['update_vehicle'])) {
        $stmt = $db->prepare("UPDATE vehicles SET vehicle_type=?, model=?, registration_number=?, capacity=? WHERE vehicle_id=?");
        $stmt->bind_param("sssss", $vehicle_type, $model, $registration_number, $capacity, $vehicle_id);

        if ($stmt->execute()) {
            $success_message = "Vehicle updated successfully.";
        } else {
            $error_message = "Error updating vehicle: " . $stmt->error;
        }
        $stmt->close();
    } elseif (isset($_POST['delete_vehicle'])) {
        $stmt = $db->prepare("DELETE FROM vehicles WHERE vehicle_id=?");
        $stmt->bind_param("s", $vehicle_id);

        if ($stmt->execute()) {
            $success_message = "Vehicle deleted successfully.";
            $vehicle_id = $vehicle_type = $model = $registration_number = $capacity = ''; 
        } else {
            $error_message = "Error deleting vehicle: " . $stmt->error;
        }
        $stmt->close();
    }
}

$query = "SELECT * FROM vehicles";
$result = $db->query($query);

// Check for errors
if (!$result) {
    die('Error in SQL query: ' . $db->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Vehicles</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="manage.css">
</head>
<body>
<div class="header">
    <h2>Manage Vehicles</h2>
</div>
<a href="../admin/admin_dashboard.php" class="back-button">Back to Dashboard</a>
<div class="form-container">
    <div id="messageContainer">
        <p id="error-message" class="error-message">Vehicle ID already exists.</p>
        <p id="success-message" class="success-message">Operation successful!</p>
    </div>
    <h2>Vehicle Form</h2>
    <form method="post">
        <label for="vehicle_id">Vehicle ID:</label>
        <input type="text" id="vehicle_id" name="vehicle_id" value="<?php echo htmlspecialchars($vehicle_id, ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="vehicle_type">Vehicle Type:</label>
        <input type="text" id="vehicle_type" name="vehicle_type" value="<?php echo htmlspecialchars($vehicle_type, ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="model">Model:</label>
        <input type="text" id="model" name="model" value="<?php echo htmlspecialchars($model, ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="registration_number">Registration Number:</label>
        <input type="text" id="registration_number" name="registration_number" value="<?php echo htmlspecialchars($registration_number, ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="capacity">Capacity:</label>
        <input type="text" id="capacity" name="capacity" value="<?php echo htmlspecialchars($capacity, ENT_QUOTES, 'UTF-8'); ?>" required>

        <div class="button-container">
            <input type="submit" name="add_vehicle" value="Add Vehicle">
            <input type="submit" name="update_vehicle" value="Update Vehicle">
            <input type="submit" name="delete_vehicle" value="Delete Vehicle">
            <input type="button" value="Clear" onclick="clearForm()">
        </div>
    </form>
</div>

<div class="table-container">
    <table id="vehicleTable">
        <tr>
            <th>Vehicle ID</th>
            <th>Vehicle Type</th>
            <th>Model</th>
            <th>Registration Number</th>
            <th>Capacity</th>
        </tr>
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<tr data-id='" . htmlspecialchars($row['vehicle_id'], ENT_QUOTES, 'UTF-8') . "' data-vehicle_type='" . htmlspecialchars($row['vehicle_type'], ENT_QUOTES, 'UTF-8') . "' data-model='" . htmlspecialchars($row['model'], ENT_QUOTES, 'UTF-8') . "' data-registration_number='" . htmlspecialchars($row['registration_number'], ENT_QUOTES, 'UTF-8') . "' data-capacity='" . htmlspecialchars($row['capacity'], ENT_QUOTES, 'UTF-8') . "'>";
            echo "<td>" . htmlspecialchars($row['vehicle_id'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['vehicle_type'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['model'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['registration_number'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['capacity'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "</tr>";
        }
        $result->free();
        ?>
    </table>
</div>

<script>
    document.querySelectorAll('#vehicleTable tr').forEach(row => {
        row.addEventListener('click', () => {
            const vehicle_id = row.getAttribute('data-id');
            const vehicle_type = row.getAttribute('data-vehicle_type');
            const model = row.getAttribute('data-model');
            const registration_number = row.getAttribute('data-registration_number');
            const capacity = row.getAttribute('data-capacity');

            document.getElementById('vehicle_id').value = vehicle_id;
            document.getElementById('vehicle_type').value = vehicle_type;
            document.getElementById('model').value = model;
            document.getElementById('registration_number').value = registration_number;
            document.getElementById('capacity').value = capacity;
        });
    });

    function clearForm() {
        document.getElementById('vehicle_id').value = '';
        document.getElementById('vehicle_type').value = '';
        document.getElementById('model').value = '';
        document.getElementById('registration_number').value = '';
        document.getElementById('capacity').value = '';
    }

    function showError(message) {
        const errorMessage = document.getElementById('error-message');
        errorMessage.textContent = message;
        errorMessage.style.display = 'block';
        setTimeout(() => {
            errorMessage.style.display = 'none';
        }, 3000);
    }

    function showSuccess(message) {
        const successMessage = document.getElementById('success-message');
        successMessage.textContent = message;
        successMessage.style.display = 'block';
        setTimeout(() => {
            successMessage.style.display = 'none';
        }, 3000); 
    }
y
    <?php if (isset($error_message)) { ?>
        showError("<?php echo $error_message; ?>");
    <?php } ?>
    <?php if (isset($success_message)) { ?>
        showSuccess("<?php echo $success_message; ?>");
    <?php } ?>
</script>

</body>
</html>
