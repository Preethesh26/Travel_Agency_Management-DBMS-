<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php"); 
    exit();
}

$payment_id = '';
$customer_id = '';
$amount = '';
$date = '';
$status = '';
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $payment_id = $_POST['payment_id'] ?? '';
    $customer_id = $_POST['customer_id'] ?? '';
    $amount = $_POST['amount'] ?? '';
    $date = $_POST['date'] ?? '';
    $status = $_POST['status'] ?? '';

    if (isset($_POST['add_payment'])) {
        $stmt = $db->prepare("SELECT payment_id FROM payments WHERE payment_id=?");
        $stmt->bind_param("s", $payment_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error_message = "Error: Payment ID already exists.";
        } else {
            
            $stmt = $db->prepare("INSERT INTO payments (payment_id, customer_id, amount, date, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $payment_id, $customer_id, $amount, $date, $status);

            if ($stmt->execute()) {
                $success_message = "Payment added successfully.";
                $payment_id = $customer_id = $amount = $date = $status = ''; 
            } else {
                $error_message = "Error adding payment: " . $stmt->error;
            }
            $stmt->close();
        }
    } elseif (isset($_POST['update_payment'])) {
      
        $stmt = $db->prepare("UPDATE payments SET customer_id=?, amount=?, date=?, status=? WHERE payment_id=?");
        $stmt->bind_param("sssss", $customer_id, $amount, $date, $status, $payment_id);

        if ($stmt->execute()) {
            $success_message = "Payment updated successfully.";
        } else {
            $error_message = "Error updating payment: " . $stmt->error;
        }
        $stmt->close();
    } elseif (isset($_POST['delete_payment'])) {
        $stmt = $db->prepare("DELETE FROM payments WHERE payment_id=?");
        $stmt->bind_param("s", $payment_id);

        if ($stmt->execute()) {
            $success_message = "Payment deleted successfully.";
            $payment_id = $customer_id = $amount = $date = $status = ''; 
        } else {
            $error_message = "Error deleting payment: " . $stmt->error;
        }
        $stmt->close();
    }
}

$query = "SELECT * FROM payments";
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
    <title>Manage Payments</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="manage.css">
</head>
<body>
<div class="header">
    <h2>Manage Payments</h2>
</div>
<a href="../admin/admin_dashboard.php" class="back-button">Back to Dashboard</a>
<div class="form-container">
    <div id="messageContainer">
        <p id="error-message" class="error-message">Error message here.</p>
        <p id="success-message" class="success-message">Operation successful!</p>
    </div>
    <h2>Payment Form</h2>
    <form method="post">
        <label for="payment_id">Payment ID:</label>
        <input type="text" id="payment_id" name="payment_id" value="<?php echo htmlspecialchars($payment_id, ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="customer_id">Customer ID:</label>
        <input type="text" id="customer_id" name="customer_id" value="<?php echo htmlspecialchars($customer_id, ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="amount">Amount:</label>
        <input type="text" id="amount" name="amount" value="<?php echo htmlspecialchars($amount, ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="date">Date:</label>
        <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($date, ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="status">Status:</label>
        <input type="text" id="status" name="status" value="<?php echo htmlspecialchars($status, ENT_QUOTES, 'UTF-8'); ?>" required>

        <div class="button-container">
            <input type="submit" name="add_payment" value="Add Payment">
            <input type="submit" name="update_payment" value="Update Payment">
            <input type="submit" name="delete_payment" value="Delete Payment">
            <input type="button" value="Clear" onclick="clearForm()">
        </div>
    </form>
</div>

<div class="table-container">
    <table id="paymentTable">
        <tr>
            <th>Payment ID</th>
            <th>Customer ID</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Status</th>
        </tr>
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<tr data-id='" . htmlspecialchars($row['payment_id'], ENT_QUOTES, 'UTF-8') . "' data-customer_id='" . htmlspecialchars($row['customer_id'], ENT_QUOTES, 'UTF-8') . "' data-amount='" . htmlspecialchars($row['amount'], ENT_QUOTES, 'UTF-8') . "' data-date='" . htmlspecialchars($row['date'], ENT_QUOTES, 'UTF-8') . "' data-status='" . htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8') . "'>";
            echo "<td>" . htmlspecialchars($row['payment_id'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['customer_id'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['amount'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['date'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "</tr>";
        }
        $result->free();
        ?>
    </table>
</div>

<script>
    document.querySelectorAll('#paymentTable tr').forEach(row => {
        row.addEventListener('click', () => {
            const payment_id = row.getAttribute('data-id');
            const customer_id = row.getAttribute('data-customer_id');
            const amount = row.getAttribute('data-amount');
            const date = row.getAttribute('data-date');
            const status = row.getAttribute('data-status');

            document.getElementById('payment_id').value = payment_id;
            document.getElementById('customer_id').value = customer_id;
            document.getElementById('amount').value = amount;
            document.getElementById('date').value = date;
            document.getElementById('status').value = status;
        });
    });

    function clearForm() {
        document.getElementById('payment_id').value = '';
        document.getElementById('customer_id').value = '';
        document.getElementById('amount').value = '';
        document.getElementById('date').value = '';
        document.getElementById('status').value = '';
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

    <?php if (isset($error_message)) { ?>
        showError("<?php echo $error_message; ?>");
    <?php } ?>
    <?php if (isset($success_message)) { ?>
        showSuccess("<?php echo $success_message; ?>");
    <?php } ?>
</script>
</body>
</html>
