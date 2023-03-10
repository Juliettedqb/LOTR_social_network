<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Mes abonnements</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <?php
    include "./header.php";
    ?>
    <div id="wrapper">
        <?php
        $userId = intval($_GET['user_id']);
        ?>
        <?php
        include("fonctions.php");
        ?>
        <?php
        $laQuestionEnSql = "SELECT * FROM users WHERE id= '$userId' ";
        $lesInformations = $mysqli->query($laQuestionEnSql);
        $user = $lesInformations->fetch_assoc();
        ?>
        <aside>
            <img class="cercle" src="<?php echo $user['image'] ?>" alt="Portrait de l'utilisatrice" />
            <section>
                <h3>Présentation</h3>
                <p>Sur cette page vous trouverez la liste des personnes dont
                   <?php echo $user['alias'] ?>
                    suit les messages
                </p>

            </section>
        </aside>
        <main class='contacts'>
            <?php
            // Etape 1: récupérer l'id de l'utilisateur
            $userId = intval($_GET['user_id']);
            // Etape 2: se connecter à la base de donnée
            include("fonctions.php");
            // Etape 3: récupérer le nom de l'utilisateur
            $laQuestionEnSql = "
                    SELECT users.* 
                    FROM followers 
                    LEFT JOIN users ON users.id=followers.followed_user_id 
                    WHERE followers.following_user_id='$userId'
                    GROUP BY users.id
                    ";
            $lesInformations = $mysqli->query($laQuestionEnSql);

            if (!$lesInformations) {
                echo "<article>";
                echo ("Échec de la requete : " . $mysqli->error);
                echo ("<p>Indice: Vérifiez la requete  SQL suivante dans phpmyadmin<code>$laQuestionEnSql</code></p>");
                exit();
            }
            // Etape 4: à vous de jouer
            //@todo: faire la boucle while de parcours des abonnés et mettre les bonnes valeurs ci dessous 
            
            while ($user = $lesInformations->fetch_assoc()) {
                //echo "<pre>" . print_r($user, 1) . "</pre>";
                ?>

                <article>
                    <img id="osef"src="<?php echo $user['image'] ?>" alt="blason" />
                    <style>
                        #osef {
                            float: right;
                            height: 6.2em;
                            border-radius: 50%;
                        }
                    </style>
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