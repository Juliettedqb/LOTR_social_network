<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Mur</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
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
        $userId = intval($_GET['user_id']);
        ?>
        <?php
        /**
         * Etape 2: idUser à la base de donnée
         */
        include("fonctions.php");

        // DELETE MESSAGE PART
        $deletePost = isset($_POST['postSupprimer']);

        if ($deletePost) {
            $postId = $_POST['postSupprimer'];
            // DELETE LIKE FROM THIS POST
            $deleteLike = "DELETE FROM likes WHERE post_id= '$postId' ";
            $ok = $mysqli->query($deleteLike);
            if (!$ok) {
                echo ("Échec de la requete : " . $mysqli->error);
            } else {
                header("Refresh:0");
            }
            
            // DELETE POST
            
            $deletePost = "DELETE FROM posts WHERE id= '$postId' ";
            $ok = $mysqli->query($deletePost);
            if (!$ok) {
                echo ("Échec de la requete : " . $mysqli->error);
            } else {
                header("Refresh:0");
            }
        }
        ?>

        <aside>
            <?php
            $laQuestionEnSql = "SELECT * FROM users WHERE id= '$userId' ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            $user = $lesInformations->fetch_assoc();
            //echo "<pre>" . print_r($user, 1) . "</pre>";
            ?>
            <img class="cercle" src="<?php echo $user['image'] ?>" alt="Portrait de l'utilisatrice" />
            <section class="parchemin">
                <h3>Présentation</h3>
                <p>Sur cette page vous trouverez tous les message de
                    <?php echo $user['alias'] ?>
                </p>
                <!-- Number of posts -->
                <p>Nombre de message :
                    <?php
                    $laQuestionEnSql = "SELECT * FROM posts WHERE user_id= '$userId' ";
                    $lesInformations = $mysqli->query($laQuestionEnSql);
                    $nbPosts = $lesInformations->num_rows;
                    ?>
                    <a class="number_follow">
                        <?php echo $nbPosts ?>
                    </a>
                    <!-- Number of following -->
                <p>Nombre d'abonnement :
                    <?php
                    $laQuestionEnSql = "SELECT * FROM followers WHERE following_user_id= '$userId' ";
                    $lesInformations = $mysqli->query($laQuestionEnSql);
                    $nbFollowing = $lesInformations->num_rows;
                    ?>
                    <a class="number_follow" href="./subscriptions.php?user_id=<?php echo $userId ?>"><?php echo $nbFollowing ?></a>
                    <!-- Number of followed -->
                <p>Nombre d'abonné :
                    <?php
                    $laQuestionEnSql = "SELECT * FROM followers WHERE followed_user_id= '$userId' ";
                    $lesInformations = $mysqli->query($laQuestionEnSql);
                    $nbFollowed = $lesInformations->num_rows;
                    ?>
                    <a class="number_follow" href="./followers.php?user_id=<?php echo $userId ?>"><?php echo $nbFollowed ?></a>
            </section>
            <?php
            // CHECK IF IS ALREADY FOLLOWED
            $laQuestionEnSql = "SELECT * FROM followers WHERE followed_user_id= '$userId' AND following_user_id= '" . $_SESSION['connected_id'] . "' ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            $isFollowed = $lesInformations->fetch_assoc();
            /* echo "<pre>" . print_r($isFollowed, 1) . "</pre>"; */
            // FOLLOW BUTTON
            if (isset($idU) and $userId != $idU and !$isFollowed) { ?>
                <form class="marginLeft" action="wall.php?user_id=<?php echo $userId ?>" method="post">
                    <input type="hidden" name="Follow" value="True">
                    <input class="button-60" role="button" type='submit' value="Follow">
                </form>
            <?php
            } else if ($isFollowed) { ?>
                    <form class="marginLeft" action="wall.php?user_id=<?php echo $userId ?>" method="post">
                        <input type="hidden" name="unFollow" value="True">
                        <input class="button-60" role="button" type='submit' value="unFollow">
                    </form>
            <?php
            } else {
                echo "";
            } ?>

        </aside>
        <main>

            <?php if ($idU == $userId) { ?>

                <article class="papier">
                    <h2>Poster un message</h2>

                    <form action="" method="post">

                        <dl><label for='message'>Message</label></dt>
                            <dd><textarea name='message'></textarea></dd>
                        </dl>
                        <input class="button-55" role="button" type='submit'>
                    </form>
                </article>
            <?php } ?>

            <?php
            // LIKE PART
            $likePost = isset($_POST['Like']);
            if ($likePost) {
                $postId = $_POST['postId'];
                $ajoutLike = "INSERT INTO likes "
                    . "(id, user_id, post_id)"
                    . "VALUES (NULL, "
                    . $idU . ", "
                    . $postId . ")"
                ;
                $ok = $mysqli->query($ajoutLike);
                if (!$ok) {
                    echo ("Échec de la requete : " . $mysqli->error);
                } else {
                    header("Refresh:0");
                }
            }

            // UNLIKE PART
            $unLikePost = isset($_POST['unLike']);
            if ($unLikePost) {
                $postId = $_POST['postId'];
                $unLike = "DELETE FROM likes WHERE user_id= '" . $idU . "' AND post_id= '$postId' ";
                $ok = $mysqli->query($unLike);
                if (!$ok) {
                    echo ("Échec de la requete : " . $mysqli->error);
                } else {
                    header("Refresh:0");
                }
            }

            // FOLLOW PART
            $enCoursFollow = isset($_POST['Follow']);
            if ($enCoursFollow) {
                $suivreUnePersonne = "INSERT INTO followers "
                    . "(id, followed_user_id, following_user_id)"
                    . "VALUES (NULL, "
                    . $userId . ", "
                    . $idU . ")"
                ;
                $ok = $mysqli->query($suivreUnePersonne);
                if (!$ok) {
                    echo ("Échec de la requete : " . $mysqli->error);
                } else {
                    echo ("Vous suivez cette personne !");
                    header("Refresh:0");
                }
            }

            // UNFOLLOW PART
            $enCoursUnFollow = isset($_POST['unFollow']);
            if ($enCoursUnFollow) {
                $unFollow = "DELETE FROM followers WHERE followed_user_id= '$userId' AND following_user_id= '" . $idU . "' ";
                $ok = $mysqli->query($unFollow);
                if (!$ok) {
                    echo ("Échec de la requete : " . $mysqli->error);
                } else {
                    echo ("Vous ne suivez plus cette personne !");
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
                    . "(id, user_id, content, created) "
                    . "VALUES (NULL, "
                    . $authorId . ", "
                    . "'" . $postContent . "', "
                    . "NOW())"
                ;
                $ok = $mysqli->query($lInstructionSql);
                if (!$ok) {
                    echo "Impossible d'ajouter le message: " . $mysqli->error;
                }
            }

            // SELECT POST ID 
            $laQuestionEnSql = "
                    SELECT posts.content, posts.created, posts.id, users.alias as author_name,
                    users.id AS author_id,
                    users.image AS author_image, 
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
            if (!$lesInformations) {
                echo ("Échec de la requete : " . $mysqli->error);
            }
            while ($post = $lesInformations->fetch_assoc()) {
                /* echo "<pre>" . print_r($post, 1) . "</pre>"; */?>
                <article>
                    <img id="osef" src="<?php echo $post['author_image'] ?>" alt="blason" />
                    <style>
                        #osef {
                            float: right;
                            height: 3.2em;
                            border-radius: 50%;
                        }
                    </style>
                    <h3>
                        <time datetime='2020-02-01 11:12:13'>
                            <?php echo $post['created'] ?>
                        </time>
                    </h3>
                    <address>par
                        <?php echo $post['author_name'] ?>
                    </address>
                    <div>
                        <p>
                            <?php echo $post['content'] ?>
                        </p>
                    </div>
                    <footer>
                        <small>
                            <small>
                                ⚔️
                                <?php echo $post['like_number'] ?>
                            </small>
                            <?php
                            $checkLike = "SELECT * FROM likes WHERE user_id= '" . $_SESSION['connected_id'] . "' AND post_id= '" . $post['id'] . "' ";
                            $ok = $mysqli->query($checkLike);
                            if ($ok->num_rows == 0) {
                                ?>
                                <form class="like" action="" method="post">
                                    <input type="hidden" name="postId" value="<?php echo $post['id'] ?>">
                                    <input type="hidden" name="Like" value="True">
                                    <input class="button-60" role="button" type='submit' value="Sword">
                                </form>
                            <?php
                            } else {
                                ?>
                                <form class="like" action="" method="post">
                                    <input type="hidden" name="postId" value="<?php echo $post['id'] ?>">
                                    <input type="hidden" name="unLike" value="True">
                                    <input class="button-60" role="button" type='submit' value="no Sword">
                                </form>
                            <?php
                            }
                            ?>
                            <!-- button supprimer msg -->
                            <?php
                            if ($idU == $post['author_id']) {
                                ?>
                                <form class="deleteButton" action="" method="post">
                                    <input type="hidden" name="postSupprimer" value="<?php echo $post['id'] ?>">
                                    <input class="button-60" role="button" type='submit' value="Supprimer">
                                </form>
                            <?php
                            }
                            ?>
                        </small>
                    </footer>
                </article>
            <?php } ?>
        </main>
    </div>
</body>

</html>