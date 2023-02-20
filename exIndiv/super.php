<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    $name = $_GET['first_name'];
    if (!isset($name)) {
        $name = "anonyme";
    }
    echo "Bonjour" . " " . $name
        ?>
    <form action="super.php" method="post">
        <p>Votre nom : <input type="text" name="first_name" /></p>
        <p><input type="submit" value="OK"></p>
    </form>
</body>
</html>