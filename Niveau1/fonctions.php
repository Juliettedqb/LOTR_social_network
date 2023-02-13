
<?php
       
        $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");
        if ($mysqli->connect_errno)
        {
            echo("Échec de la connexion : " . $mysqli->connect_error);
            exit();
        }
?>