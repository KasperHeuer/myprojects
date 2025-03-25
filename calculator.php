<?php
session_start();
$method = $_SERVER["REQUEST_METHOD"];
if ($method === "POST") {
    $symbol = $_POST["symbol"];
    $one = $_POST["numberOne"];
    $two = $_POST["numberTwo"];
    switch ($symbol) {
        case "plus":
            $_SESSION["Result"] = $one + $two;
            break;
        case "minus":
            $_SESSION["Result"] = $one - $two;
            break;
        case "times":
            $_SESSION["Result"] = $one * $two;
            break;
        case "divide":
            $_SESSION["Result"] = $one / $two;
            break;
        default:
            echo "Error input incorrect";
            break;
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Title</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <form action="calculator.php" method="POST">
        <label for="numberOne">First Number:</label>
        <input type="number" required name="numberOne" id="numberOne" placeholder="Enter the first number">

        <label for="symbol">Operation:</label>
        <select name="symbol" id="symbol" required>
            <option value="plus">+</option>
            <option value="minus">-</option>
            <option value="times">*</option>
            <option value="divide">/</option>
        </select>

        <label for="numberTwo">Second Number:</label>
        <input type="number" required name="numberTwo" id="numberTwo" placeholder="Enter the second number">

        <input type="submit" value="Calculate">
    </form>
    <div class="result"> <?php print_r($_SESSION["Result"]) ?></div>
    <a href="index.php" class="Link">Back</a>
</body>

</html>
<?php

?>