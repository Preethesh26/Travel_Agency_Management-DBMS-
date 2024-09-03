<?php
session_start();
include('../config/db.php'); // Ensure this path is correct

// Check if the user is logged in and has admin role
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php"); // Redirect to login if not authorized
    exit();
}

// Initialize variables
$customer_id = '';
$name = '';
$email = '';
$phone = '';
$address = '';
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_POST['customer_id'] ?? '';
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';

    if (isset($_POST['add_customer'])) {
      
        $stmt = $db->prepare("SELECT id FROM customer WHERE id=?");
        $stmt->bind_param("s", $customer_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error_message = "Error: Customer ID already exists.";
        } else {
            
            $stmt = $db->prepare("INSERT INTO customer (id, name, email, phone, address) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $customer_id, $name, $email, $phone, $address);

            if ($stmt->execute()) {
                $success_message = "Customer added successfully.";
                $customer_id = $name = $email = $phone = $address = ''; // Clear fields
            } else {
                $error_message = "Error adding customer: " . $stmt->error;
            }
            $stmt->close();
        }
    } elseif (isset($_POST['update_customer'])) {
        
        $stmt = $db->prepare("UPDATE customer SET name=?, email=?, phone=?, address=? WHERE id=?");
        $stmt->bind_param("sssss", $name, $email, $phone, $address, $customer_id);

        if ($stmt->execute()) {
            $success_message = "Customer updated successfully.";
        } else {
            $error_message = "Error updating customer: " . $stmt->error;
        }
        $stmt->close();
    } elseif (isset($_POST['delete_customer'])) {
       
        $stmt = $db->prepare("DELETE FROM customer WHERE id=?");
        $stmt->bind_param("s", $customer_id);

        if ($stmt->execute()) {
            $success_message = "Customer deleted successfully.";
            $customer_id = $name = $email = $phone = $address = ''; // Clear fields
        } else {
            $error_message = "Error deleting customer: " . $stmt->error;
        }
        $stmt->close();
    }
}

$query = "SELECT * FROM customer";
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
    <title>Manage Customers</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="manage.css">
</head>
<body>
<div class="header">
    <h2>Manage Customers</h2>
</div>
<a href="../admin/admin_dashboard.php" class="back-button">Back to Dashboard</a>
<div class="form-container">
    <div id="messageContainer">
        <p id="error-message" class="error-message">Customer ID already exists.</p>
        <p id="success-message" class="success-message">Operation successful!</p>
    </div>
    <h2>Customer Form</h2>
    <form method="post">
        <label for="customer_id">ID:</label>
        <input type="text" id="customer_id" name="customer_id" value="<?php echo htmlspecialchars($customer_id, ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($phone, ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($address, ENT_QUOTES, 'UTF-8'); ?>" required>

        <div class="button-container">
            <input type="submit" name="add_customer" value="Add Customer">
            <input type="submit" name="update_customer" value="Update Customer">
            <input type="submit" name="delete_customer" value="Delete Customer">
            <input type="button" value="Clear" onclick="clearForm()">
        </div>
    </form>
</div>

<div class="table-container">
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
            echo "<tr data-id='" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "' data-name='" . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . "' data-email='" . htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') . "' data-phone='" . htmlspecialchars($row['phone'], ENT_QUOTES, 'UTF-8') . "' data-address='" . htmlspecialchars($row['address'], ENT_QUOTES, 'UTF-8') . "'>";
            echo "<td>" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['phone'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['address'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "</tr>";
        }
        
        $result->free();
        ?>
    </table>
</div>

<script>
    
    document.querySelectorAll('#customerTable tr').forEach(row => {
        row.addEventListener('click', () => {
            const id = row.getAttribute('data-id');
            const name = row.getAttribute('data-name');
            const email = row.getAttribute('data-email');
            const phone = row.getAttribute('data-phone');
            const address = row.getAttribute('data-address');

            document.getElementById('customer_id').value = id;
            document.getElementById('name').value = name;
            document.getElementById('email').value = email;
            document.getElementById('phone').value = phone;
            document.getElementById('address').value = address;
        });
    });

  
    function clearForm() {
        document.getElementById('customer_id').value = '';
        document.getElementById('name').value = '';
        document.getElementById('email').value = '';
        document.getElementById('phone').value = '';
        document.getElementById('address').value = '';
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

