<?php
if (isset($_POST["age"])) {
    $_POST["age"] = (($_POST["age"] * 2) / 2) * 1;
    echo "You are " . $_POST["age"] . " Years old";
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>age calculator</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <form action="age.php" method="POST">
        <label for="age">How old are you</label>
        <input type="number" name="age" required min="0">
        <input type="submit">
    </form>
    <a href="index.php" class="Link">Back</a>
</body>

</html>
<?php

?>