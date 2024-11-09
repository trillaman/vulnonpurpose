<?php
    session_start();
    unset($_SESSION['username']);
    session_destroy();
    echo "Successfully logged out\n";
    header("Location: index.php");
    exit();
?>