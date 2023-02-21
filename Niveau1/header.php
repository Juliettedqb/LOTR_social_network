<?php
    session_start();
    $idU = $_SESSION['connected_id'];
    if (!isset($idU)) {
        header("Location: login.php");
        exit();
    } else {
        echo $idU;
    }
?>

<header>
    <img src="./assets/LOTR/gollumLogo2.webp" alt="Logo de notre réseau social"/>
    
    <nav id="menu">
        <p id=title>Gollum <br>Book</p>
        <a href="news.php"><button class="button-62" role="button">News</button></a>
        <a href="wall.php?user_id=<?php echo $idU ?>"><button class="button-62" role="button">My Page</button></a>
        <!-- RESEARCH BAR -->
        <a>
            <form id= "searchbox" action="" method="post">
                <input class="research" type="text" size= "15" name="search" placeholder="Rechercher un utilisateur">
                <input id="button-submit" type="submit" value="">
            </form>
        </a>
    </nav>
    <nav id="user">
        <a href="#">Profil ▼</a>
        <ul>
            <li><a href="settings.php?user_id=<?php echo $idU ?>">Paramètres</a></li>
            <li><a href="disconnect.php">Disconnect</a></li>
        </ul>
    </nav>
</header>

<?php
include("fonctions.php");
// PART SEARCH

if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $laQuestionEnSql = "SELECT * FROM `users` WHERE alias= '$search' ";
    $lesInformations = $mysqli->query($laQuestionEnSql);
    $user = $lesInformations->fetch_assoc();
    if ($user) {
        header("Location: wall.php?user_id=" . $user['id']);
    } else {
        echo "This user was not found" . " :" . $search;
    }
}
?>