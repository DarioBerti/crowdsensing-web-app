<?php
    // CORS abilitato con .htaccess

    // Parametri di connessione al database
    $host = '127.0.0.1';
    $dbname = 'bylights';
    $user = 'root';
    $password = '';
    $port = 3308; // Porta del MySQL in XAMPP

    require_once __DIR__ .  '/database.php';
    require_once __DIR__  . '/../utils/functions.php';
    // require_once 'C:/xampp/htdocs/tirocinio/crowdsensing-web-app/bylights/src/db/database.php';
    // require_once 'C:/xampp/htdocs/tirocinio/crowdsensing-web-app/bylights/src/utils/functions.php';
    $dbh = new DatabaseHelper($host, $user, $password, $dbname, $port);
    sec_session_start();
?>