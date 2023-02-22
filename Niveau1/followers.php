<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Mes abonnés </title>
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
            $laQuestionEnSql = "SELECT * FROM users WHERE id= '$userId' ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            $user = $lesInformations->fetch_assoc();
            //echo "<pre>" . print_r($user, 1) . "</pre>";
            ?>
            <img class="cercle" src="<?php echo $user['image'] ?>" alt="Portrait de l'utilisatrice" />
            <section>
                <h3>Présentation</h3>
                <p>Sur cette page vous trouverez la liste des personnes qui
                    suivent les messages de 
                    <?php echo $user['alias'] ?>
                </p>

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
                    <img src="<?php echo $user['image'] ?>" alt="blason" />
                    <h3>
                        <a class="dark_link" href="wall.php?user_id=<?php echo $user['id'] ?>">
                            <?php echo $user['alias'] ?>
                        </a>
                    </h3>
                    <p>id :
                        <?php echo $user['id'] ?>
                    </p>
                </article>
            <?php } ?>
        </main>
    </div>
</body>

</html>