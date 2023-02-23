<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Actualités</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <?php
    include "./header.php";
    ?>
    <div id="wrapper">
        <aside>

            <img class="cercle" src="./assets/LOTR/ring.jpg" alt="Portrait de l'utilisatrice" />
            <section>
                <h3><strong>Présentation</strong></h3>
                <p>Sur cette page vous trouverez les dix dernières publications</p>
                <div class="meteo">
                    <h4>Météo au Mordor :</h4>
                    <p id="weather"></p>
                    <p id="weatherIcon"></p>
                </div>
            </section>
        </aside>
        <main>
            <?php
            include("fonctions.php");

            // DELETE MESSAGE PART
            $deletePost = isset($_POST['postSupprimer']);

            if ($deletePost) {
                // DELETE LIKE FROM THIS POST
                $postId = $_POST['postSupprimer'];
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

            if ($mysqli->connect_error) {
                echo "<article>";
                echo ("Échec de la connexion : " . $mysqli->connect_error);
                echo ("<p>Indice: Vérifiez les parametres de <code>new mysqli(...</code></p>");
                echo "</article>";
                exit();
            }

            $laQuestionEnSql = "
                        SELECT posts.content,
                        posts.created,
                        posts.id,
                        users.alias AS author_name, 
                        users.id AS author_id,  
                        users.image AS author_image,
                        count(likes.id) AS like_number,  
                        GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                        FROM posts
                        JOIN users ON  users.id=posts.user_id
                        LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                        LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                        LEFT JOIN likes      ON likes.post_id  = posts.id 
                        GROUP BY posts.id
                        ORDER BY posts.created DESC  
                        LIMIT 10
                        ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            if (!$lesInformations) {
                echo "<article>";
                echo ("Échec de la requete : " . $mysqli->error);
                echo ("<p>Indice: Vérifiez la requete  SQL suivante dans phpmyadmin<code>$laQuestionEnSql</code></p>");
                exit();
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
                        <time>
                            <?php echo $post['created'] ?>
                        </time>
                    </h3>
                    <address>par <a class="post_author" href="wall.php?user_id=<?php echo $post['author_id'] ?>"><?php echo $post['author_name'] ?></a></address>
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

    <script src="script.js"></script>

</body>

</html>