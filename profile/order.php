<?php
session_start();

// Assuming user_id is stored in the session
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login-page-with-background-image/login.php'); // Redirect to login if not logged in
    exit;
}
$user_id = $_SESSION['user_id'];  // Get user ID from session
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "erestraunt";  // Replace with your actual database name

// Create connection
$conn =  new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch orders and their details
$sql = "SELECT o.order_id, c.name as customer_name, oi.cart_id, oi.qty, m.food_name, m.price, o.order_status
        FROM orders o
        JOIN cart_items oi ON o.cart_id = oi.cart_id
        JOIN cart on oi.cart_id = cart.cart_id
        JOIN menu m ON oi.food_id = m.food_id
        JOIN users c ON cart.user_id = c.user_id
        where c.user_id = '$user_id'";
        
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
    <style>
    /* Reset and general styling */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        background-image: url("flat-lay-thanksgiving-food-border-assortment-with-copy-space.jpg");
        color: white;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        background-size: cover;
    }

    /* Navbar styling */
    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 20px;
        padding-left: 20px;
        padding-bottom: 15px;
        padding-right: 50px;
        background-color: #000000;
        color: white;
        margin-right: 0px;
    }

    .navbar .logo {
        font-size: 24px;
        font-weight: bold;
    }

    .navbar .logo .logo-img {
        width: 100px;
        height: 100px;
        border-radius: 50%; /* Makes the logo circular */
    }

    .navbar .nav-links a {
        color: white;
        text-decoration: none;
        margin: 0 15px;
        padding: 5px;
        transition: background-color 0.3s, color 0.3s;
    }

    .navbar .nav-links a:hover {
        background-color: #444;
        color: #47AB11;
        border-radius: 5px;
    }

    /* Orders Table */
    .order-table {
        width: 90%;
        margin: 40px auto;
        background-color: rgba(0, 0, 0, 0.8);
        border-collapse: collapse;
        color: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    .order-table th, .order-table td {
        padding: 12px 20px;
        text-align: left;
    }

    .order-table th {
        background-color: #47AB11;
    }

    .order-table tr:nth-child(odd) {
        background-color: #333;
    }

    .order-table tr:nth-child(even) {
        background-color: #444;
    }

    .order-table tr:hover {
        background-color: #555;
    }

    /* Footer styling */
    footer {
        background-color: #000000;
        color: white;
        padding: 40px 20px;
        text-align: center;
        margin-top: auto;  /* Ensures footer stays at the bottom */
    }

    .footer-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 1200px;
        margin: auto;
        gap: 20px;
    }

    .footer-section {
        flex: 1;
        min-width: 200px;
    }

    .footer-section h3 {
        font-size: 18px;
        margin-bottom: 15px;
        color: #47AB11;
    }

    .footer-section p,
    .footer-section ul,
    .footer-section a {
        color: #bbb;
        font-size: 14px;
        line-height: 1.6;
    }

    .footer-section ul {
        list-style: none;
        padding: 0;
    }

    .footer-section ul li {
        margin-bottom: 10px;
    }

    .footer-section a {
        text-decoration: none;
        color: #47AB11;
        transition: color 0.3s;
    }

    .footer-section a:hover {
        color: #fff;
    }

    /* Footer layout adjustments */
    .footer-content .about {
        text-align: left;
    }

    .footer-content .contact {
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex: 1;
    }

    .footer-content .quick-links {
        text-align: right;
    }

    /* Footer responsive design */
    @media (max-width: 768px) {
        .footer-content {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .footer-section {
            margin-bottom: 20px;
        }

        .footer-newsletter input[type="email"],
        .footer-newsletter button {
            width: 100%;
            border-radius: 5px;
            margin-top: 10px;
        }
    }
</style>

</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="logo">View Orders</div>
        <div class="nav-links">
        <a href="..\home\1.html">Home</a>
        <a href="..\Menu\1.html">Our Food</a>
        <a href="..\AboutUS\1.html">About Us</a>
        <a href="..\FAQ's\1.html">FAQ</a>
        <a href="..\our food\1.php">Order Now</a>
        </div>
    </div>

    <!-- Page Header -->
    <header id="page-header">
        <div class="header-content">
            <h1>Order History</h1>
            
    </header>

    <!-- Orders Table -->
    <table class="order-table" id="order-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Items</th>
                <th>Total Amount</th>
                <th>Status</th>
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
                            echo "<tr id='order-{$previous_order_id}'><td>#{$previous_order_id}</td><td>{$items_list}</td><td>\${$total_amount}</td><td id='status-{$previous_order_id}'>{$status_text}</td></tr>";
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
                echo "<tr id='order-{$previous_order_id}'><td>#{$previous_order_id}</td><td>{$items_list}</td><td>\${$total_amount}</td><td id='status-{$previous_order_id}'>{$status_text}</td></tr>";
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
            <h3>Who Are We?</h3>
            <p>Launched in Jabalpur,FoodieBay has grown from a home project to one of the largest food aggregators in the world, enabling our vision of better food for more people.</p>
        </div>
        
        <div class="footer-section links">
            <h3>Quick Links</h3>
            <ul>
            <li><a href="../home/1.html">Home</a></li>
                <li><a href="../Menu/1.html">Our Food</a></li>
                <li><a href="../AboutUS/1.html">About Us</a></li>
                <li><a href="../FAQ's/1.html">FAQ</a></li>
                <li><a href="..\our food\1.php">Order Now</a></li>
            </ul>
        </div>

        <div class="footer-section contact">
            <h3>Get In Touch</h3>
            <p>Email: office@FoodieBay.com</p>
            <p>Phone: (+91) 892 808 5056</p>
        </div>
    </div>
</footer>
</body>
</html>

<?php
// PHP code to handle the update
if (isset($_POST['order_id']) && $_POST['action'] == 'approve') {
    $order_id = $_POST['order_id'];

    // Database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update order status to 1 (Completed)
    $sql = "UPDATE orders SET order_status = 1 WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update order status']);
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>
