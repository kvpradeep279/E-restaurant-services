<?php
session_start();  // Start the session

// Check if user is logged in (if session variable is set)
if (!isset($_SESSION['admin1_user'])) {  // Use consistent session variable name
    header('Location: ../login-page-with-background-image/login.php'); // Redirect to login if not logged in
    exit;
}

$user_id = $_SESSION['admin1_user'];  // Get user ID from session

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "erestraunt";  // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle the POST request to update order status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $sql = "UPDATE orders SET order_status = 1 WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    if ($stmt->execute()) {
        echo "Order status updated successfully.";
    } else {
        echo "Failed to update order status.";
    }
    $stmt->close();
    exit;
}

// Fetch orders and their details
$sql = "SELECT o.order_id, c.name as customer_name, oi.cart_id, oi.qty, m.food_name, m.price, o.order_status
        FROM orders o
        JOIN cart_items oi ON o.cart_id = oi.cart_id
        JOIN cart on oi.cart_id = cart.cart_id
        JOIN menu m ON oi.food_id = m.food_id
        JOIN users c ON cart.user_id = c.user_id";

$result = $conn->query($sql);

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Admin Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="logo">Admin Panel</div>
        <div class="nav-links">
            <a href="1.php">Orders</a>
            <a href="prof.php">Profile</a>
        </div>
    </div>

    <!-- Page Header -->
    <header id="page-header">
        <div class="header-content">
            <h1>Order Management</h1>
            <p>Manage all customer orders from here.</p>
        </div>
    </header>

    <!-- Orders Table -->
    <table class="order-table" id="order-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Items</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php
                $previous_order_id = null;
                $total_amount = 0;
                $items_list = '';
                while($row = $result->fetch_assoc()) {
                    if ($previous_order_id != $row['order_id']) {
                        // Output the previous order if there's a change in order_id
                        if ($previous_order_id != null) {
                            $status_text = ($previous_order_status == 1) ? 'Completed' : 'Pending';
                            $approve_button = ($previous_order_status == 0) ? '<button class="approve-btn" data-order-id="' . $previous_order_id . '">Approve</button>' : '';
                            echo "<tr id='order-{$previous_order_id}'><td>#{$previous_order_id}</td><td>{$previous_customer_name}</td><td>{$items_list}</td><td>\${$total_amount}</td><td id='status-{$previous_order_id}'>{$status_text}</td><td id='action-{$previous_order_id}'>{$approve_button}</td></tr>";
                        }
                        // Reset for new order
                        $previous_order_id = $row['order_id'];
                        $previous_customer_name = $row['customer_name'];
                        $items_list = '';
                        $total_amount = 0;
                    }
                    // Add current item to the list
                    $items_list .= $row['food_name'] . " (x" . $row['qty'] . ")<br>";
                    $total_amount += $row['price'] * $row['qty'];
                    $previous_order_status = $row['order_status'];
                }
                // Output the last order
                $status_text = ($previous_order_status == 1) ? 'Completed' : 'Pending';
                $approve_button = ($previous_order_status == 0) ? '<button class="approve-btn" data-order-id="' . $previous_order_id . '">Approve</button>' : '';
                echo "<tr id='order-{$previous_order_id}'><td>#{$previous_order_id}</td><td>{$previous_customer_name}</td><td>{$items_list}</td><td>\${$total_amount}</td><td id='status-{$previous_order_id}'>{$status_text}</td><td id='action-{$previous_order_id}'>{$approve_button}</td></tr>";
                ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No orders found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <footer>
        <div class="footer-content">
            <div class="footer-section about">
                <h3>About Us</h3>
                <p>We manage orders to provide the best service to our users.</p>
            </div>
            <div class="footer-section contact">
                <h3>Contact Us</h3>
                <p>Email: support@orderadmin.com</p>
                <p>Phone: +123456789</p>
            </div>
            <div class="footer-section quick-links">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms & Conditions</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <script>
        // Event listener for the "Approve" buttons
        document.querySelectorAll('.approve-btn').forEach(button => {
            button.addEventListener('click', function() {
                const orderId = this.getAttribute('data-order-id');
                updateOrderStatus(orderId);
            });
        });

        // Function to update order status
        function updateOrderStatus(orderId) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    document.getElementById('status-' + orderId).textContent = 'Completed';
                    document.getElementById('action-' + orderId).innerHTML = '';
                } else {
                    alert('Failed to update order status');
                }
            };
            xhr.send('order_id=' + orderId);
        }
    </script>
</body>
</html>