<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Flux</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <?php
    include "./header.php";
    include("fonctions.php");
    ?>
    <div id="wrapper">
        <?php
        $userId = intval($_GET['user_id']);
        ?>
        <aside>
            <?php
            $laQuestionEnSql = "SELECT * FROM `users` WHERE id= '$userId' ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            $user = $lesInformations->fetch_assoc();
            ?>
            <img src="./assets/user.jpg" alt="Portrait de l'utilisatrice" />
            <section>
                <h3>Présentation</h3>
                <p>Sur cette page vous trouverez tous les message des utilisatrices
                    auxquel est abonnée l'utilisatrice
                    <?php echo $user['alias'] ?>
                    (n°
                    <?php echo $userId ?>)
                </p>
            </section>
        </aside>
        <main>
            <?php
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
                        posts.created,
                        posts.id,
                        users.alias as author_name,
                        users.id as author_id,  
                        count(likes.id) as like_number,  
                        GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                        FROM followers 
                        JOIN users ON users.id=followers.followed_user_id
                        JOIN posts ON posts.user_id=users.id
                        LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                        LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                        LEFT JOIN likes      ON likes.post_id  = posts.id 
                        WHERE followers.following_user_id='$userId' 
                        GROUP BY posts.id
                        ORDER BY posts.created DESC  
                        ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            if (!$lesInformations) {
                echo ("Échec de la requete : " . $mysqli->error);
            }

            while ($post = $lesInformations->fetch_assoc()) {
                ?>
                <article>
                    <h3>
                        <time datetime='2020-02-01 11:12:13'>
                            <?php echo $post['created'] ?>
                        </time>
                    </h3>
                    <address>par
                        <a href="wall.php?user_id=<?php echo $post['author_id'] ?>">
                            <?php echo $post['author_name'] ?>
                        </a>
                    </address>
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