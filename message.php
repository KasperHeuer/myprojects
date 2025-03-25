<?php
$method = $_SERVER["REQUEST_METHOD"];
if ($method === "POST") {
    try {
        session_start();

        if (!isset($_SESSION["name"])) {
            throw new Exception("User not logged in.");
        }

        $username = $_SESSION["name"];
        $to = htmlspecialchars($_POST["to"]);
        if ($username === $to) {
            echo "<div class='success'><i class='fas fa-check-circle'></i> You can't send a message to yourself</div>";
        } else {
            $message = htmlspecialchars($_POST["message"]);

            $host = "localhost";
            $dbUsername = "root"; // Renamed to avoid conflict with $username
            $password = "";
            $db = "myprojects";

            $connection = new mysqli($host, $dbUsername, $password, $db);
            if ($connection->connect_error) {
                throw new Exception("Database connection error: " . $connection->connect_error);
            }

            $queryInsert = "INSERT INTO chats(chatFrom, chatTo, chat) VALUES (?, ?, ?)";
            $statementInsert = $connection->prepare($queryInsert);

            if (!$statementInsert) {
                throw new Exception("Statement preparation failed: " . $connection->error);
            }

            $statementInsert->bind_param("sss", $username, $to, $message);

            if ($statementInsert->execute()) {
                echo "<div class='success'><i class='fas fa-check-circle'></i> Message sent successfully!</div>";
            } else {
                throw new Exception("Message sending failed: " . $statementInsert->error);
            }
        }
    } catch (Exception $e) {
        echo "<div class='error'><i class='fas fa-exclamation-circle'></i> " . $e->getMessage() . "</div>";
    } finally {
        if (isset($statementInsert)) {
            $statementInsert->close();
        }
        if (isset($connection)) {
            $connection->close();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New message</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <form action="message.php" method="POST">
        <label for="to">Send to</label>
        <select name="to" id="to" required>
            <option value="" disabled selected>Select a name</option>
            <?php
            try {
                $host = "localhost";
                $dbUsername = "root"; // Avoid conflict with the 'username' variable in the query.
                $password = "";
                $db = "myprojects";

                // Establish a database connection
                $connection = new mysqli($host, $dbUsername, $password, $db);
                if ($connection->connect_error) {
                    throw new Exception("Database connection error: " . $connection->connect_error);
                }

                // Query to fetch users
                $querySelect = "SELECT username FROM users";
                $statementSelect = $connection->prepare($querySelect);

                if (!$statementSelect) {
                    throw new Exception("Statement preparation failed: " . $connection->error);
                }

                // Execute the query
                $statementSelect->execute();
                $statementSelect->bind_result($name);

                // Fetch the results and populate the dropdown
                while ($statementSelect->fetch()) {
                    echo "<option value='" . htmlspecialchars($name) . "'>" . htmlspecialchars($name) . "</option>";
                }
            } catch (Exception $e) {
                echo "<div class='error'><i class='fas fa-exclamation-circle'></i> " . htmlspecialchars($e->getMessage()) . "</div>";
            } finally {
                // Clean up resources
                if (isset($statementSelect)) {
                    $statementSelect->close();
                }
                if (isset($connection)) {
                    $connection->close();
                }
            }
            ?>
        </select>

        <label for="message">Your message</label>
        <textarea name="message" id="message" rows="5" required></textarea>
        <input type="submit" value="Send">
    </form>

    <a href="index.php" class="Link">Back</a>
</body>

</html>
<?php

?>