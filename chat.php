<?php
session_start();
if (!isset($_SESSION["isLoggedIn"]) || $_SESSION["isLoggedIn"] !== true) {
    header("Location: register.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <a href="message.php" class="Link">Send new message</a>
    <h1>Messages send to you</h1>
    <?php
    try {
        $host = "localhost";
        $username = "root";
        $password = "";
        $db = "myprojects";

        $connection = new mysqli($host, $username, $password, $db);
        if ($connection->connect_error) {
            throw ("Database connection failed: " . $connection->connect_error);
        }
        $username = $_SESSION["name"];
        $query = "SELECT * FROM chats WHERE chatTo = ?";

        $statement = $connection->prepare($query);
        if (!$statement) {
            throw ("Statement preparation failed: " . $connection->error);
        }

        $statement->bind_param("s", $username);
        $statement->execute();
        $statement->bind_result($id, $from, $to, $chat);
        while ($statement->fetch()) {
            echo "<div class='message'>
            <h1 class='from'> $from </h1> <br>
            $chat
            </div>
            ";
        }
    } catch (Exception $e) {
        echo  "De error is " . $e->getMessage();
    } finally {
        if ($statement) {
            $statement->close();
        }
        if ($connection) {
            $connection->close();
        }
    }
    ?>

    <h1>Messages from to you</h1>
    <?php
    try {
        $host = "localhost";
        $username = "root";
        $password = "";
        $db = "myprojects";

        $connection = new mysqli($host, $username, $password, $db);
        if ($connection->connect_error) {
            throw ("Database connection failed: " . $connection->connect_error);
        }
        $username = $_SESSION["name"];
        $query = "SELECT * FROM chats WHERE chatFrom = ?";

        $statement = $connection->prepare($query);
        if (!$statement) {
            throw ("Statement preparation failed: " . $connection->error);
        }

        $statement->bind_param("s", $username);
        $statement->execute();
        $statement->bind_result($id, $from, $to, $chat);
        while ($statement->fetch()) {
            echo "<div class='message'>
            <h1 class='from'> $to </h1> <br>
            $chat
            </div>
            ";
        }
    } catch (Exception $e) {
        echo  "De error is " . $e->getMessage();
    } finally {
        if ($statement) {
            $statement->close();
        }
        if ($connection) {
            $connection->close();
        }
    }
    ?>
</body>

</html>