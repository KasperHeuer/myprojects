<?php
session_start();

// Check if form is submitted and the session key matches
$method = $_SERVER["REQUEST_METHOD"];
if ($method === "POST" && isset($_SESSION["key"]) && $_SESSION["key"] === $_POST["key"]) {
    // Get POST data and sanitize
    $name = htmlspecialchars($_POST["name"]);
    $password = htmlspecialchars($_POST["password"]);
    $passwordCheck = htmlspecialchars($_POST["passwordCheck"]);

    // Check if passwords match
    if ($password !== $passwordCheck) {
        echo "Passwords do not match!";
        exit();
    }


    $salt = uniqid();


    $passwordSalt = $password . $salt;
    $passwordHash = password_hash($passwordSalt, PASSWORD_DEFAULT);


    $host = "localhost";
    $username = "root";
    $dbPassword = ""; // Set your database password here
    $db = "myprojects";


    $connection = new mysqli($host, $username, $dbPassword, $db);


    if ($connection->connect_error) {
        die("Database connection failed: " . $connection->connect_error);
    }


    $query = "INSERT INTO users (username, wachtwoord, salt) VALUES (?, ?, ?)";
    $statement = $connection->prepare($query);


    if (!$statement) {
        die("Statement preparation failed: " . $connection->error);
    }


    $statement->bind_param('sss', $name, $passwordHash, $salt);


    if ($statement->execute()) {

        $_SESSION["isLoggedIn"] = true;
        $_SESSION["name"] = $name;

        header('Location: chat.php');
        exit();
    } else {
        die("Error executing query: " . $statement->error);
    }


    $statement->close();
    $connection->close();
} else {
    $_SESSION['key'] = uniqid();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <form action="register.php" method="POST">
        <input type="hidden" name="key" value="<?php echo $_SESSION['key']; ?>">

        <label for="name">Name</label>
        <input type="text" name="name" required />

        <label for="password">Password</label>
        <input type="password" name="password" required>

        <label for="passwordCheck">Confirm Password</label>
        <input type="password" name="passwordCheck" required>

        <input type="submit" value="Register">
    </form>
    <a href="login.php" class="Link">Login</a>
    <a href="index.php" class="Link">Back</a>
</body>

</html>