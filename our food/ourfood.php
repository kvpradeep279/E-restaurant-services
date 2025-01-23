<?php
// Start session
session_start();

// Database connection
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "erestraunt";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all food items from the database
$sql = "SELECT * FROM food_items";  // Assuming your table is called 'food_items'
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Food - FoodieBay Foods</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<!-- Navbar -->
<div class="navbar">
    <div class="logo">
        <img src="..\images\logo.jpg" alt="Logo" class="logo-img">
    </div>
    <div class="nav-links">
        <a href="..\home\1.html">Home</a>
        <a href="..\Menu\1.html">Our Food</a>
        <a href="..\AboutUS\1.html">About Us</a>
        <a href="..\FAQ's\1.html">FAQ</a>
        <a href="..\our food\1.html">Order Now</a>
    </div>
    <div class="profile-btn-container">
        <button class="profile-btn">
            <a href="cart.php"><img src="..\Cart\profile.png" alt="Profile Icon"></a>
        </button>
    </div>
    <div class="CART">
        <button class="cart-btn">
            <a href="cart.php">View Cart</a>
        </button>
    </div>
</div>

<section class="menu-section">
    <h1 class="Heading">Our Food</h1>
    <div class="menu-grid">
        <?php
        // Display all the food items
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<form action="add_to_cart.php" method="POST">';
                echo '<div class="menu-item">';
                echo '<img src="' . $row['image_path'] . '" alt="' . $row['name'] . '">';
                echo '<h4>' . $row['name'] . '</h4>';
                echo '<p>Price: $' . $row['price'] . '</p>';
                echo '<input type="hidden" name="item" value="' . $row['name'] . '">';
                echo '<input type="hidden" name="price" value="' . $row['price'] . '">';
                echo '<button type="submit">+ Add to Cart</button>';
                echo '</div>';
                echo '</form>';
            }
        } else {
            echo "<p>No items found!</p>";
        }
        $conn->close();
        ?>
    </div>
</section>

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
            <p>Email: office@FoodieBay.com</p>
            <p>Phone: (+91) 892 808 5056</p>
        </div>
    </div>
</footer>
</body>
</html>