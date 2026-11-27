<?php
    require_once __DIR__ . '/modulos/seguridad.php';
    $_SESSION = [];
    session_destroy();
    header('Location: index.php');
    exit;
