<?php

    function requireLogin() : void {
        session_start();
        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?ruta=login');
            exit;
        }
    }

?>