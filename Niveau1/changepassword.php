<?php
include "fonctions.php";
session_start();
$idU = $_SESSION['connected_id'];

// check name of idU
$laQuestionEnSql = "SELECT * FROM users WHERE id= '$idU' ";
$lesInformations = $mysqli->query($laQuestionEnSql);
$user = $lesInformations->fetch_assoc();
if ($user) {
    $idU = $user['alias'];
} else {
    echo "This user was not found" . " :" . $search;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
    <div class="container container-glob">
        <div class="container container-password">
        <h2>Changer le mot de passe de <?php echo $idU ?></h2>
        <form action="changepassword.php" method="post" id="form-container">
            <input type="password" name="oldpassword" id="password" placeholder="Ancien mot de passe">
            <br>
            <input type="password" name="newpassword" id="password" placeholder="Nouveau mot de passe">
            <br>
            <input type="password" name="newpassword2" id="password" placeholder="Confirmer le mot de passe">
            <br><br>
            <input type="submit" value="Changer le mot de passe" id="submit-password">
        </form>
        </div>
    </div>
</body>
</html>

<?php
$idU = $_SESSION['connected_id'];

$laQuestionEnSql = "SELECT * FROM users WHERE id= '$idU' ";
$lesInformations = $mysqli->query($laQuestionEnSql);
$user = $lesInformations->fetch_assoc();

$enCoursNewPassword = isset($_POST['newpassword']);

if ($enCoursNewPassword) {
    $oldpassword = $_POST['oldpassword'];
    $newpassword = $_POST['newpassword'];
    $newpassword2 = $_POST['newpassword2'];

    if ($newpassword == $newpassword2) {
        $newpassword = md5($newpassword);

        $lInstructionSql = "UPDATE users SET password = '" . $newpassword . "' WHERE id = '$idU'";

        $ok = $mysqli->query($lInstructionSql);
        if (!$ok) {
            echo ("Échec de la requete : " . $mysqli->error);
        } else {
            unset($_SESSION['connected_id']);
            header("Location: login.php");
        }
    } else {
        echo "Les mots de passe ne correspondent pas";
    }
}
?>