<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Inscription</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <header>
        <img src="./assets/LOTR/gollumLogo2.webp" alt="Logo de notre réseau social" />

        <nav id="menu">
            <p id=title>Gollum <br>Book</p>
            <a href="news.php"><button class="button-62" role="button">News</button></a>
            <a href="wall.php?user_id=<?php echo $idU ?>"><button class="button-62" role="button">My Page</button></a>
            <!-- RESEARCH BAR -->
            <a class="search-a">
                <form id="searchbox" action="" method="post">
                    <input class="research" type="text" size="40" name="search"
                        placeholder=" Rechercher un utilisateur">
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
    <div id="wrapper">

        <aside>
            <img class="cercle" src="./assets/LOTR/ring.jpg" alt="Portrait de l'utilisatrice" />
            <h2>Présentation</h2>
            <p>Bienvenu sur notre réseau social... Gollum Book.</p>
        </aside>
        <main>
            <article>
                <h2>Inscription</h2>
                <?php
                $enCoursDeTraitement = isset($_POST['email']);
                if ($enCoursDeTraitement) {
                    echo "<pre>" . print_r($_POST, 1) . "</pre>";
                    $new_email = $_POST['email'];
                    $new_alias = $_POST['pseudo'];
                    $new_passwd = $_POST['motpasse'];
                    $new_picture = $_POST['photo'];

                    $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");

                    //pour info : 
                    //pour éviter les injection sql : https://www.w3schools.com/sql/sql_injection.asp
                    $new_email = $mysqli->real_escape_string($new_email);
                    $new_alias = $mysqli->real_escape_string($new_alias);
                    $new_passwd = $mysqli->real_escape_string($new_passwd);

                    //cryptage mdp  (md5 pas recommandée pour une vraies sécurité)
                    $new_passwd = md5($new_passwd);

                    //requête
                    $lInstructionSql = "INSERT INTO users (id, email, password, alias, image) "
                        . "VALUES (NULL, "
                        . "'" . $new_email . "', "
                        . "'" . $new_passwd . "', "
                        . "'" . $new_alias . "', "
                        . "'" . $new_picture . "'"
                        . ");";

                    //exécution requête
                    $ok = $mysqli->query($lInstructionSql);
                    if (!$ok) {
                        echo "L'inscription a échouée : " . $mysqli->error;
                    } else {
                        echo "Votre inscription est un succès : " . $new_alias;
                        echo " <a href='login.php'>Connectez-vous.</a>";
                        header("Location: login.php?user_id= " . $_SESSION['connected_id']);
                    }
                }
                ?>
                <form action="registration.php" method="post">
                    <input type='hidden' name='???' value='achanger'>
                    <select name="photo">
                        <option value="./assets/LOTR/gollum.jpg">Gollum</option>
                        <option value="./assets/LOTR/legolas.jpg">Legolas</option>
                        <option value="./assets/LOTR/aragorn.jpg">Aragorn</option>
                        <option value="./assets/LOTR/arwen.jpg">Arwen</option>
                        <option value="./assets/LOTR/boromir.jpg">Boromir</option>
                        <option value="./assets/LOTR/frodon.jpg">Frodon</option>
                        <option value="./assets/LOTR/galadriel.jpg">Galadriel</option>
                        <option value="./assets/LOTR/gandalf.jpg">Gandalf</option>
                        <option value="./assets/LOTR/gimli.jpg">Gimli</option>
                        <option value="./assets/LOTR/saroumane.jpg">Saroumane</option>
                        <option value="./assets/LOTR/sauron.jpg">Sauron</option>
                        <option value="./assets/LOTR/sam.jpg">Sam</option>
                        <option value="./assets/LOTR/eowyn.jpg">Eowyn</option>
                        <option value="./assets/LOTR/theoden.jpg">Theoden</option>
                        <option value="./assets/LOTR/urukhai.jpg">Urukhai</option>
                    </select>
                    <dl>
                        <dt><label for='pseudo'>Pseudo</label></dt>
                        <dd><input type='text' name='pseudo'></dd>
                        <dt><label for='email'>E-Mail</label></dt>
                        <dd><input type='email' name='email'></dd>
                        <dt><label for='motpasse'>Mot de passe</label></dt>
                        <dd><input type='password' name='motpasse'></dd>
                    </dl>
                    <input type='submit'>
                </form>
            </article>
        </main>
    </div>
</body>

</html>