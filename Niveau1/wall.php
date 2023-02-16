<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Mur</title> 
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
        <?php 
            include "./header.php";
        ?>
        <div id="wrapper">
            <?php
            /**
             * Etape 1: Le mur concerne un utilisateur en particulier
             * La première étape est donc de trouver quel est l'id de l'utilisateur
             * Celui ci est indiqué en parametre GET de la page sous la forme user_id=...
             * Documentation : https://www.php.net/manual/fr/reserved.variables.get.php
             * ... mais en résumé c'est une manière de passer des informations à la page en ajoutant des choses dans l'url
             */
            $userId =intval($_GET['user_id']);
            ?>
            <?php
            /**
             * Etape 2: idUser à la base de donnée
             */
            include("fonctions.php");
            ?>

            <aside>
                <?php
                /**
                 * Etape 3: récupérer le nom de l'utilisateur
                 */                
                $laQuestionEnSql = "SELECT * FROM users WHERE id= '$userId' ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                $user = $lesInformations->fetch_assoc();
                //@todo: afficher le résultat de la ligne ci dessous, remplacer XXX par l'alias et effacer la ligne ci-dessous
                //echo "<pre>" . print_r($user, 1) . "</pre>";
                ?>
                <img src="./assets/user.jpg" alt="Portrait de l'utilisatrice"/>
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez tous les message de l'utilisatrice : <?php echo $user['alias'] ?>
                        (n° <?php echo $userId ?>)
                    </p>
                </section>
                <?php 
                    // CHECK IF IS ALREADY FOLLOWED
                    $laQuestionEnSql = "SELECT * FROM followers WHERE followed_user_id= '$userId' AND following_user_id= '" . $_SESSION['connected_id'] . "' ";
                    $lesInformations = $mysqli->query($laQuestionEnSql);
                    $isFollowed = $lesInformations->fetch_assoc();
                    // FOLLOW BUTTON
                    if (isset($idU) AND $userId != $idU AND !$isFollowed) { ?> 
                        <form action="wall.php?user_id=<?php echo $userId?>" method="post">
                                <input type="hidden" name="Follow" value= "True">
                                <input type='submit' value= "Follow"> 
                        </form> <?php 
                    } else if($isFollowed) { ?>
                        <form action="wall.php?user_id=<?php echo $userId?>" method="post">
                                <input type="hidden" name="unFollow" value= "True">
                                <input type='submit' value= "unFollow"> 
                        </form> <?php 
                    } else {
                        echo "Vous ne pouvez pas vous suivre vous même";
                    } ?>
            </aside>
            <main>
            <article>
                    <h2>Poster un message</h2>
                    <?php

                    // FOLLOW PART
                    $enCoursFollow = isset($_POST['Follow']);
                    if ($enCoursFollow) {
                        $suivreUnePersonne = "INSERT INTO followers "
                            ."(id, followed_user_id, following_user_id)" 
                            . "VALUES (NULL, "
                            . $userId . ", "
                            . "'" . $_SESSION['connected_id'] . "')"
                            ;
                        $ok = $mysqli->query($suivreUnePersonne);
                        if (!$ok) {
                            echo("Échec de la requete : " . $mysqli->error);
                        } else {
                            echo("Vous suivez cette personne !");
                            header("Refresh:0");
                        }
                    }

                    // UNFOLLOW PART
                    $enCoursUnFollow = isset($_POST['unFollow']);
                    if ($enCoursUnFollow) {
                        $unFollow = "DELETE FROM followers WHERE followed_user_id= '$userId' AND following_user_id= '" . $_SESSION['connected_id'] . "' ";
                        $ok = $mysqli->query($unFollow);
                        if (!$ok) {
                            echo("Échec de la requete : " . $mysqli->error);
                        } else {
                            echo("Vous ne suivez plus cette personne !");
                            header("Refresh:0");
                        }
                    }

                    // POST MESSAGE PART
                    $authorId = $idU;
                    $enCoursDeTraitement = isset($_POST['message']);
                    if ($enCoursDeTraitement) {
                        $postContent = $_POST['message'];
                        $authorId = intval($mysqli->real_escape_string($authorId));
                        $postContent = $mysqli->real_escape_string($postContent);
                        $lInstructionSql = "INSERT INTO posts "
                                . "(id, user_id, content, created, parent_id) "
                                . "VALUES (NULL, "
                                . $authorId . ", "
                                . "'" . $postContent . "', "
                                . "NOW(), "
                                . "NULL);"
                                ;
                        $ok = $mysqli->query($lInstructionSql);
                        if (!$ok) {
                            echo "Impossible d'ajouter le message: " . $mysqli->error;
                        } else {
                            echo "Message posté en tant que :" . $authorId;
                        }
                    }
                    ?>                     
                    <form action="" method="post">
                        <input type='hidden' name='???' value='achanger'>
                        <dl><label for='message'>Message</label></dt>
                            <dd><textarea name='message'></textarea></dd>
                        </dl>
                        <input type='submit'>
                    </form>               
                </article>
                <?php
                    $laQuestionEnSql = "
                        SELECT posts.content, posts.created, users.alias as author_name, users.id as author_id, 
                        COUNT(likes.id) as like_number, GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                        FROM posts
                        JOIN users ON  users.id=posts.user_id
                        LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                        LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                        LEFT JOIN likes      ON likes.post_id  = posts.id 
                        WHERE posts.user_id='$userId' 
                        GROUP BY posts.id
                        ORDER BY posts.created DESC  
                        ";
                    $lesInformations = $mysqli->query($laQuestionEnSql);
                    if ( ! $lesInformations) {
                        echo("Échec de la requete : " . $mysqli->error);
                    }
                    while ($post = $lesInformations->fetch_assoc()) {
                        //echo "<pre>" . print_r($post, 1) . "</pre>";?>                
                        <article>
                            <h3>
                                <time datetime='2020-02-01 11:12:13' ><?php echo $post['created'] ?></time>
                            </h3>
                            <address>par <?php echo $post['author_name'] ?></address>
                            <div>
                                <p><?php echo $post['content'] ?></p>
                            </div>                                            
                            <footer>
                                <small>♥ <?php echo $post['like_number'] ?></small>
                                <a href="">#<?php echo $post['taglist'] ?></a>,
                            </footer>
                        </article>
                <?php } ?>
            </main>
        </div>
    </body>
</html>