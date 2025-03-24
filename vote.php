<?php
$method = $_SERVER["REQUEST_METHOD"];

if ($method === "POST") {
    try {
        print_r($_POST);
        $host = "localhost";
        $username = "root";
        $password = "";
        $db = "myprojects";

        $connection = new mysqli($host, $username, $password, $db);

        if ($connection->connect_error) {
            throw new Exception("Database connectiefout: " . $connection->connect_error);
        }

        // Correct the query syntax (use backticks around column names)
        $query = "INSERT INTO guess (`Name`, `Vote`) VALUES (?, ?)";

        $name = htmlspecialchars($_POST["name"]);
        $vote = $_POST["voteOption"];

        $statement = $connection->prepare($query);

        if (!$statement) {
            throw new Exception("Statement preparation failed: " . $connection->error);
        }


        $statement->bind_param('ss', $name, $vote);

        $statement->execute();


        header("Location: vote.php");
        exit;
    } catch (Exception $e) {
        echo "De error is " . $e->getMessage();
    } finally {
        if ($statement) {
            $statement->close();
        }
        if ($connection) {
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
    <title>Vote</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <form action="vote.php" method="POST">
        <label for="name">What is your name</label>
        <input type="text" name="name" required>

        <label for="optionOne">Option one</label>
        <input type="radio" name="voteOption" value="optionOne" id="optionOne" required>

        <label for="optionTwo">Option two</label>
        <input type="radio" name="voteOption" value="optionTwo" id="optionTwo" required>

        <label for="optionTwo">Option two</label>
        <input type="radio" name="voteOption" value="optionTwo" id="optionTwo" required>

        <input type="submit" value="Vote">
    </form>



    <a href="index.php" class="Link">Back</a>
</body>

</html>
<?php

?>