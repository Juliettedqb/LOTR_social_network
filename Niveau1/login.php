<?php
    session_start();
    include "fonctions.php"
?>

<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Connexion</title> 
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
    <header>
    <img src="./assets/LOTR/gollumLogo2.webp" alt="Logo de notre réseau social"/>
    
    <nav id="menu">
        <p id=title>Gollum <br>Book</p>
        <a href="news.php"><button class="button-62" role="button">News</button></a>
        <a href="wall.php?user_id=<?php echo $idU ?>"><button class="button-62" role="button">My Page</button></a>
        <!-- RESEARCH BAR -->
        <a class="search-a">
            <form id= "searchbox" action="" method="post">
                <input class="research" type="text" size= "40" name="search" placeholder=" Rechercher un utilisateur">
                <input class="button-submit" type="submit" value="🔍">
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

        <div id="wrapper" >
            <aside>
                <img class="cercle" src="./assets/LOTR/ring.jpg" alt="Portrait de l'utilisatrice" />
                <h2>Présentation</h2>
                <p>Connectez-vous à votre compte Gollum Book.</p>
            </aside>
            <main>
                <article>
                    <h2>Connexion</h2>
                    <?php
                        $enCoursDeTraitement = isset($_POST['email']);
                        if ($enCoursDeTraitement) {
                            $emailAVerifier = $_POST['email'];
                            $passwdAVerifier = $_POST['motpasse'];
                            
                            $emailAVerifier = $mysqli->real_escape_string($emailAVerifier);
                            $passwdAVerifier = $mysqli->real_escape_string($passwdAVerifier);
                            $passwdAVerifier = md5($passwdAVerifier);
                            $lInstructionSql = "SELECT * "
                                . "FROM users "
                                . "WHERE "
                                . "email LIKE '" . $emailAVerifier . "'"
                                ;
                            $res = $mysqli->query($lInstructionSql);
                            $user = $res->fetch_assoc();
                            if ( ! $user OR $user["password"] != $passwdAVerifier) {
                                echo "La connexion a échouée. ";
                            } else {
                                echo "Votre connexion est un succès : " . $user['alias'] . ".";
                                $_SESSION['connected_id']=$user['id'];
                                header("Location: wall.php?user_id= " . $_SESSION['connected_id']);
                            }
                        }
                    ?>                     
                    <form action="login.php" method="post">
                        <input type='hidden'name='???' value='achanger'>
                        <dl>
                            <dt><label for='email'>E-Mail</label></dt>
                            <dd><input type='email'name='email'></dd>
                            <dt><label for='motpasse'>Mot de passe</label></dt>
                            <dd><input type='password'name='motpasse'></dd>
                        </dl>
                        <input value="Login" type='submit'>
                    </form><br>
                    <p>
                        Pas de compte?
                        <a class="dark_link" href='registration.php'>Inscrivez-vous.</a>
                    </p>

                </article>
            </main>
        </div>
    </body>
</html>
