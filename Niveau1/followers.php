<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Mes abonnés </title> 
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
        <?php 
            include "./header.php";
            include("fonctions.php");
        ?>
        <div id="wrapper">          
            <aside>
                <img src="./assets/user.jpg" alt="Portrait de l'utilisatrice"/>
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez la liste des personnes qui
                        suivent les messages de l'utilisatrice
                        n° <?php echo intval($_GET['user_id']) ?></p>

                </section>
            </aside>
            <main class='contacts'>
                <?php
                    $userId = intval($_GET['user_id']);
                    $laQuestionEnSql = "
                        SELECT users.*
                        FROM followers
                        LEFT JOIN users ON users.id=followers.following_user_id
                        WHERE followers.followed_user_id='$userId'
                        GROUP BY users.id
                        ";
                    $lesInformations = $mysqli->query($laQuestionEnSql);
                    while ($user = $lesInformations->fetch_assoc()) {
                        //echo "<pre>" . print_r($user, 1) . "</pre>"; ?>
                        <article>
                            <img src="./assets/user.jpg" alt="Portrait de l'utilisatrice"/>
                            <h3>
                                <a href="wall.php?user_id=<?php echo $user['id'] ?>">
                                    <?php echo $user['alias'] ?>
                                </a>
                            </h3>
                            <p>id : <?php echo $user['id'] ?></p>
                        </article>
                <?php } ?>
            </main>
        </div>
    </body>
</html>
