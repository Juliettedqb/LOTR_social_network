<?php
session_start();
unset($_SESSION['connected_id']);
header("Location: login.php");

?>