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
            <img src="./assets/user.jpg" alt="Portrait de l'utilisatrice" />
            <section>
                <h3>Présentation</h3>
                <p>Sur cette page vous trouverez les derniers messages de
                    tous les utilisatrices du site.</p>
            </section>
        </aside>
        <main>
            <?php
            include("fonctions.php");

            if ($mysqli->connect_error) {
                echo "<article>";
                echo ("Échec de la connexion : " . $mysqli->connect_error);
                echo ("<p>Indice: Vérifiez les parametres de <code>new mysqli(...</code></p>");
                echo "</article>";
                exit();
            }

           // LIKE PART

            $isLiked = isset($_POST['like']);
            $postId = isset($_POST['postId']);

            // debug : 
            echo "<pre>" . print_r($_POST['postId'], 1) . "</pre>";
            echo "<pre>" . print_r(isset($_POST['postId']), 1) . "</pre>";

            if ($isLiked) {
                $likedPost = "INSERT INTO likes "
                    . "(id, user_id, post_id)"
                    . "VALUES (NULL, "
                    . $idU . ", "
                    . $_POST['postId'] . ")"
                ;
                echo ($likedPost);
                $ok = $mysqli->query($likedPost);
                if (!$ok) {
                    echo ("Échec de la requete : " . $mysqli->error);
                } else {
                    //echo ("Vous aimez cette publication!");
                    /* header("Refresh:0"); */
                }
            }

            $laQuestionEnSql = "
                        SELECT posts.content,
                        posts.id,
                        posts.created,
                        users.alias AS author_name, 
                        users.id AS author_id,  
                        count(likes.id) AS like_number,  
                        GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                        FROM posts
                        JOIN users ON  users.id=posts.user_id
                        LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                        LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                        LEFT JOIN likes      ON likes.post_id  = posts.id 
                        GROUP BY posts.id
                        ORDER BY posts.created DESC  
                        LIMIT 5
                        ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            if (!$lesInformations) {
                echo "<article>";
                echo ("Échec de la requete : " . $mysqli->error);
                echo ("<p>Indice: Vérifiez la requete  SQL suivante dans phpmyadmin<code>$laQuestionEnSql</code></p>");
                exit();
            }

            while ($post = $lesInformations->fetch_assoc()) {
                echo "<pre>" . print_r($post, 1) . "</pre>"; ?>
                <article>
                    <h3>
                        <time>
                            <?php echo $post['created'] ?>
                        </time>
                    </h3>
                    <address>par <a href="wall.php?user_id=<?php echo $post['author_id'] ?>"><?php echo $post['author_name'] ?></a></address>
                    <div>
                        <p>
                            <?php echo $post['content'] ?>
                        </p>
                    </div>
                        <footer>
                            <small>
                                <small>♥
                                    <?php echo $post['like_number'] ?> likes
                                </small>
                                <form action="" method="post">
                                    <input type="hidden" name="like" value="True">
                                    <input type="hidden" name="postId" value="<?php echo $post['id'] ?>">
                                    <input type='submit' value="like">
                                </form>
                                <?php echo $post['id'] ?>
                            </small>
                            <a href="">#
                                <?php echo $post['taglist'] ?>
                            </a>
                    </footer>
                </article>
            <?php } ?>
        </main>
    </div>
</body>

</html>