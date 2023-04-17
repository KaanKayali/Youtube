<?php
    session_start();
    unset($_SESSION["email"]);

    echo $_SESSION["email"];
?>