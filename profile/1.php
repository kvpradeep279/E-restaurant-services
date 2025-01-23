<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "erestraunt");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle logout action
if (isset($_POST['logout'])) {
    session_unset(); // Unset session variables
    session_destroy(); // Destroy the session
    header("Location: ../login-page-with-background-image/login.php"); // Redirect to login page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Profile</title>
</head>
<body>
    <!-- Navbar -->
    <header class="navbar">
        <div class="logo">
            <img src="../images/logo.jpg" alt="JKS Logo" class="logo-img">
        </div>
        <nav class="nav-links">
            <a href="../home/1.html">Home</a>
            <a href="../Menu/1.html">Our Food</a>
            <a href="../AboutUS/1.html">About Us</a>
            <a href="../FAQ's/1.html">FAQ</a>
            <a href="..\our food\1.php">Order Now</a>
        </nav>
        <div class="profile-btn-container">
            <button class="profile-btn">
                <img src="profile.png" alt="Profile Icon">
            </button>
        </div>
    </header>
    
    <main>
        <section class="welc">
            <h1>Profile</h1>
            <hr id="line">
        </section>

        <div class="profile">
            <div class="pro1">
                <?php
                if (isset($_SESSION['user_id'])) {
                    $user_id= $_SESSION['user_id'];
                    $stmt = $conn->prepare("SELECT name, username FROM users WHERE user_id = ?");
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $stmt->bind_result($name, $username);

                    if ($stmt->fetch()) {
                        // Display user information
                        echo "Username: " . htmlspecialchars($username) . "<br>";
                        echo "Name: " . htmlspecialchars($name);
                    } else {
                        echo "No user found.";
                    }
                    $stmt->close();
                } else {
                    echo "Please log in to view your profile.";
                }
                ?>
            </div>
            <form id="pro" action="order.php">
                <input type="submit" name="preorder" value="Previous orders">
            </form>
            <form id="pro" action="" method="post">
                <input type="submit" name="logout" value="Logout">
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-section about">
                <h3>Who Are We?</h3>
                <p>Launched in Jabalpur, FoodieBay has grown from a home project to one of the largest food aggregators in the world, enabling our vision of better food for more people.</p>
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
                <p>Email: <a href="mailto:office@jks.com">office@FoodieBay.com</a></p>
                <p>Phone: (+91) 892 808 5056</p>
            </div>
        </div>
    </footer>
</body>
</html>
