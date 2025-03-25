<?php
try {
    session_start();

    $method = $_SERVER["REQUEST_METHOD"];
    if ($method === "POST" && isset($_SESSION["key"]) && $_SESSION["key"] === $_POST["key"]) {
        // Input sanitization
        $name = trim($_POST["name"]);
        $password = trim($_POST["password"]);

        // Database credentials
        $host = "localhost";
        $username = "root";
        $dbPassword = ""; // Renamed to avoid conflict with $password
        $db = "myprojects";

        // Database connection
        $connection = new mysqli($host, $username, $dbPassword, $db);

        if ($connection->connect_error) {
            throw ("Database connection failed: " . $connection->connect_error);
        }

        // Query to fetch user by username
        $query = "SELECT * FROM users WHERE username = ?";
        $statement = $connection->prepare($query);

        if (!$statement) {
            throw ("Statement preparation failed: " . $connection->error);
        }

        $statement->bind_param('s', $name);
        $statement->execute();
        $statement->bind_result($id, $dbUsername, $dbPassword, $dbSalt);

        if ($statement->fetch()) {
            $password = $password . $dbSalt;
            echo $password;
            if (password_verify($password, $dbPassword)) {
                // Login successful
                $_SESSION["isLoggedIn"] = true;
                $_SESSION["name"] = $dbUsername;

                header('Location: chat.php');
                exit();
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "Username not found.";
        }

        // Close statement and connection
        $statement->close();
        $connection->close();
    } else {
        // Generate a new session key for CSRF protection
        $_SESSION['key'] = bin2hex(random_bytes(16));
    }
} catch (Exception $e) {
    echo "De error is " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <form action="login.php" method="POST">
        <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>">

        <label for="name">Name</label>
        <input type="text" name="name" required />

        <label for="password">Password</label>
        <input type="password" name="password" required>



        <input type="submit" value="Login">
    </form>
    <a href="register.php" class="Link">Register</a>
    <a href="index.php" class="Link">Back</a>
</body>

</html>