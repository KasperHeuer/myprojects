<?php
session_start();
function numberReset()
{
    $_SESSION["Number"] = rand(1, 100);
    $_SESSION["Guess Amount"] = 0;
}


$method = $_SERVER["REQUEST_METHOD"];

if ($method === "POST") {
    $guess = $_POST["guess"];
    if ($guess < $_SESSION["Number"]) {
        echo "<div class='message'> Guess needs to be higher </div>";
        $_SESSION["Guess Amount"]++;
    } elseif ($guess > $_SESSION["Number"]) {
        echo "<div class='message'> Guess needs to be lower </div>";
        $_SESSION["Guess Amount"]++;
    } elseif ($guess == $_SESSION["Number"]) {
        echo "<div class='message'> Correct, the number was $guess</div>";
        numberReset();
    } else {
        echo "Er is iets verkeerd gegaan";
    }
} elseif ($method === "GET") {
    numberReset();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guess</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <form action="guess.php" method="POST">
        <label for="guess">Guess a number between the 1 and 100</label>
        <input type="number" name="guess" required>
        <input type="submit" value="Guess">
    </form>
    <div class="guessAmount"> You've guessed
        <?php print_r($_SESSION["Guess Amount"]);
        if ($_SESSION["Guess Amount"] >= 2 || $_SESSION["Guess Amount"] === 0) {
            echo " times";
        } else {
            echo " time";
        }
        ?></div>
    <a href="index.php" class="Link">Back</a>
</body>

</html>
<?php

?>