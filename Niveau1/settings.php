<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Paramètres</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <?php
    include "./header.php";
    include("fonctions.php");
    ?>
    <div id="wrapper" class='profile'>
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
            <img src="<?php echo $user['image'] ?>" alt="Portrait de l'utilisatrice" />
            <section>
                <h3>Présentation</h3>
                <p>Sur cette page vous trouverez les informations de l'utilisateur.ice
                    n°
                    <?php echo intval($_GET['user_id']) ?>
                </p>

                <?php
                $enCoursNewImage = isset($_POST['newImage']);
                //echo "<pre>" . print_r($_POST['newImage'], 1) . "</pre>";
                if ($enCoursNewImage) {
                    $newImage = "UPDATE users SET image = '" . $_POST['newImage'] . "' WHERE id = '$userId'";
                    $ok = $mysqli->query($newImage);
                    if (!$ok) {
                        echo ("Échec de la requete : " . $mysqli->error);
                    } else {
                        header("Refresh:0");
                    }
                }

                ?>

                <p>Pour modifier votre photo de profil :
                <form action="" method="post">
                    <input type='hidden' name='???' value='achanger'>
                    <select name="newImage">
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
                    </select>
                    <input type='submit'>
                </form>
                </p>


            </section>
        </aside>
        <main>
            <?php

            $userId = intval($_GET['user_id']);

            //on récupère le nom de l'utilisateur.ice
            $laQuestionEnSql = "
                    SELECT users.*, 
                    count(DISTINCT posts.id) as totalpost, 
                    count(DISTINCT given.post_id) as totalgiven, 
                    count(DISTINCT recieved.user_id) as totalrecieved 
                    FROM users 
                    LEFT JOIN posts ON posts.user_id=users.id 
                    LEFT JOIN likes as given ON given.user_id=users.id 
                    LEFT JOIN likes as recieved ON recieved.post_id=posts.id 
                    WHERE users.id = '$userId' 
                    GROUP BY users.id
                    ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            if (!$lesInformations) {
                echo ("Échec de la requete : " . $mysqli->error);
            }
            $user = $lesInformations->fetch_assoc();

            //debug :
            //echo "<pre>" . print_r($user, 1) . "</pre>";
            ?>
            <article class='parameters'>
                <h3>Mes paramètres</h3>
                <dl>
                    <dt>Pseudo</dt>
                    <dd>
                        <?php echo $user['alias'] ?>
                    </dd>
                    <dt>Email</dt>
                    <dd>
                        <?php echo $user['email'] ?>
                    </dd>
                    <dt>Nombre de message</dt>
                    <dd>
                        <?php echo $user['totalpost'] ?>
                    </dd>
                    <dt>Nombre de "J'aime" donnés </dt>
                    <dd>
                        <?php echo $user['totalgiven'] ?>
                    </dd>
                    <dt>Nombre de "J'aime" reçus</dt>
                    <dd>
                        <?php echo $user['totalrecieved'] ?>
                    </dd>
                </dl>

            </article>
        </main>
    </div>
</body>

</html>









