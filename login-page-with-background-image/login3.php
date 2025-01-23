<?php
session_start();  // Start the session to use session variables

$error_message = '';  // Initialize error message variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $userid = $_POST['userid'];
    $password = $_POST['password'];

    // Database connection
    $connection = mysqli_connect("localhost", "root", "", "erestraunt");
    if (!$connection) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Check login credentials
    $sql = "SELECT * FROM admin WHERE admin_user = ? AND admin_pass = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ss", $userid, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Fetch user data
        $row = $result->fetch_assoc();
        $_SESSION['admin1_user'] = $row['admin_user'];  // Store session variable
        $_SESSION['name'] = $row['Name'];  // Store session variable

        header("Location: /DBMSPROJECT/admin/1.php");  // Redirect to the admin dashboard
        exit();
    } else {
        // Set error message if login failed
        $error_message = 'Invalid Admin ID or Password.';
    }

    $stmt->close();
    $connection->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login Page</title>
  <link rel="stylesheet" href="./style.css">
</head>
<body>
  <form action="login3.php" method="post">
    <div class="container">
      <img id="logo" src="../images/logo.jpg" alt="Foodie Bay Logo">
      <div class="form-field">
        <input type="text" placeholder="Name" name="name" required />
      </div>
      <div class="form-field">
        <input type="text" placeholder="Admin ID" name="userid" required />
      </div>
      <div class="form-field">
        <input type="password" placeholder="Password" name="password" required />
      </div>
      <div class="form-field">
        <button class="btn" type="submit" name="login">Login</button>
      </div>
      
      <!-- Display error message if it exists -->
      <?php if (!empty($error_message)) : ?>
        <p class="error-message"><?php echo $error_message; ?></p>
      <?php endif; ?>
    </div>
  </form>
</body>
</html>
