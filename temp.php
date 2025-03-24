<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Temprature converter</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <form action="temp.php" method="POST">
        <select name="type" id="type" required>
            <option value="C">Celcius to Farenheit</option>
            <option value="F">Farenheit to Celcius</option>
        </select>
        <input type="number" name="temp" required>
        <input type="submit" value="Convert">
    </form>
    <a href="index.php" class="Link">Back</a>
</body>

</html>
<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $type = $_POST["type"];
    $result = $_POST["temp"];
    if ($type === "C") {
        $result = (($result * 9) / 5) + 32;
        echo "<div class='result'>" . $result . " Farenheit </div>";
    } elseif ($type === "F") {
        $result = (($result - 32) * 5) / 9;
        echo "<div class='result'>" . $result . " Celcius </div>";
    }
}
?>