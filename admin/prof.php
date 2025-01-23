<?php
session_start();  // Start the session

// Check if the session variable is set
if (!isset($_SESSION['admin1_user'])) {  // Use consistent session variable name
    header('Location: ../login-page-with-background-image/login.php'); // Redirect to login if not logged in
    exit();
}

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

// Fetch user data
$user_id = $_SESSION['admin1_user'];  // Get user ID from session
$sql = "SELECT name, admin_user FROM admin WHERE admin_user = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);  // Assuming admin_user is a string; update if it's an integer
$stmt->execute();
$stmt->bind_result($name, $username);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="./style.css">
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

    <main>
        <section class="welc">
            <h1>Profile</h1>
            <hr id="line">
        </section>

        <div class="profile">
            <div class="pro1">
                <?php
                if (isset($username) && isset($name)) {
                    // Display user information inside the profile class
                    echo "<p><strong>Username:</strong> " . htmlspecialchars($username) . "</p>";
                    echo "<p><strong>Name:</strong> " . htmlspecialchars($name) . "</p>";
                } else {
                    echo "<p>No user data found.</p>";
                }
                ?>
            </div>

            <form id="pro" action="..\login-page-with-background-image\login.php" method="post">
                <input type="submit" name="logout" value="Logout">
            </form>
        </div>
    </main>

    <!-- Footer Section -->
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
</body>
</html>
