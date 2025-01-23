<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login Page</title>
  <link rel="stylesheet" href="./style.css">
</head>
<body>
<form action="login.php" method="POST">
  <div class="container">
    <img id="logo" src="../images/logo.jpg" alt="Logo">
    <div class="form-field">
      <input type="text" name="user" placeholder="Email / Username" required>
    </div>
    <div class="form-field">
      <input type="password" name="pass" placeholder="Password" required>
    </div>
    <div class="form-field">
      <button class="btn" type="submit" name="submit">Submit</button>
      <button class="btn" type="button" onclick="window.location.href='login2.php';">Sign up</button>
    </div>
    <div id="error-message">
    <?php
    // Enable error reporting for debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Start session
    session_start();

    if (isset($_POST["submit"])) {
        // Get input values
        $Username = $_POST["user"];
        $Password = $_POST["pass"];
        // Database connection
        $mycon = mysqli_connect("localhost", "root", "", "erestraunt") or die("Connection failed: " . mysqli_connect_error());

        // Prepare and execute query to check user
        $sql = "SELECT username, password FROM users WHERE username = ?";
        $stmt = $mycon->prepare($sql);
        $stmt->bind_param("s", $Username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($Password === $row["password"]) {
                // Set session and redirect to home page
                $_SESSION["username"] = $Username;
                $_SESSION["user_id"] = $row["user_id"]; // Store userid in session

                // Fetch the corresponding name
                $userid = $row["username"]; // Get the userid
                $name_sql = "SELECT Name FROM users WHERE username = ?";
                $name_stmt = $mycon->prepare($name_sql);
                $name_stmt->bind_param("i", $userid); // Assuming userid is an integer
                $name_stmt->execute();
                $name_result = $name_stmt->get_result();
                
                if ($name_result->num_rows > 0) {
                    $name_row = $name_result->fetch_assoc();
                    $_SESSION['name'] = $name_row['Name']; // Store name in session
                    var_dump($_SESSION); // Check session variables
                }

                header("Location: ../1.php");
                exit();
            } else {
                echo "Incorrect password. <br> Please try again.";
            }
        } else {
            echo "Username does not exist. <br> Would you like to <a href='login2.php'>create a new account</a>?";
        }

        // Close the statement and connection
        $stmt->close();
        $mycon->close();
    }
    ?>
    </div>
  </div>
</form>
</body>
</html>
