<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Inscription</title> 
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
        <header>
            <img src="resoc.jpg" alt="Logo de notre réseau social"/>
            <nav id="menu">
                <a href="news.php">Actualités</a>
                <a href="wall.php?user_id=">Mur</a>
                <a href="feed.php?user_id=">Flux</a>
                <a href="tags.php?tag_id=">Mots-clés</a>
            </nav>
            <nav id="user">
                <a href="#">Profil</a>
                <ul>
                    <li><a href="settings.php?user_id=">Paramètres</a></li>
                    <li><a href="followers.php?user_id=">Mes suiveurs</a></li>
                    <li><a href="subscriptions.php?user_id=">Mes abonnements</a></li>
                </ul>

            </nav>
        </header>

        <div id="wrapper" >

            <aside>
                <h2>Présentation</h2>
                <p>Bienvenu sur notre réseau social.</p>
            </aside>
            <main>
                <article>
                    <h2>Inscription</h2>
                    <?php
                    $enCoursDeTraitement = isset($_POST['email']);
                    if ($enCoursDeTraitement)
                    {
                        //debug :
                        /* echo "<pre>" . print_r($_POST, 1) . "</pre>"; */
                        $new_email = $_POST['email'];
                        $new_alias = $_POST['pseudo'];
                        $new_passwd = $_POST['motpasse'];

                        $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");

                        //pour info : 
                        //pour éviter les injection sql : https://www.w3schools.com/sql/sql_injection.asp
                        $new_email = $mysqli->real_escape_string($new_email);
                        $new_alias = $mysqli->real_escape_string($new_alias);
                        $new_passwd = $mysqli->real_escape_string($new_passwd);

                        //cryptage mdp  (md5 pas recommandée pour une vraies sécurité)
                        $new_passwd = md5($new_passwd);

                        //requête
                        $lInstructionSql = "INSERT INTO users (id, email, password, alias) "
                                . "VALUES (NULL, "
                                . "'" . $new_email . "', "
                                . "'" . $new_passwd . "', "
                                . "'" . $new_alias . "'"
                                . ");";

                        //exécution requête
                        $ok = $mysqli->query($lInstructionSql);
                        if ( ! $ok)
                        {
                            echo "L'inscription a échouée : " . $mysqli->error;
                        } else
                        {
                            echo "Votre inscription est un succès : " . $new_alias;
                            echo " <a href='login.php'>Connectez-vous.</a>";
                        }
                    }
                    ?>                     
                    <form action="registration.php" method="post">
                        <input type='hidden'name='???' value='achanger'>
                        <dl>
                            <dt><label for='pseudo'>Pseudo</label></dt>
                            <dd><input type='text'name='pseudo'></dd>
                            <dt><label for='email'>E-Mail</label></dt>
                            <dd><input type='email'name='email'></dd>
                            <dt><label for='motpasse'>Mot de passe</label></dt>
                            <dd><input type='password'name='motpasse'></dd>
                        </dl>
                        <input type='submit'>
                    </form>
                </article>
            </main>
        </div>
    </body>
</html>
