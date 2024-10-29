<?php
    // Impostazione degli header CORS per consentire richieste da localhost:8080
    header("Access-Control-Allow-Origin: http://localhost:8080");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Content-Type: application/json");

    // Parametri di connessione al database
    $host = '127.0.0.1';
    $dbname = 'bylights';
    $user = 'root';
    $password = '';
    $port = 3308; // Porta del MySQL in XAMPP

    require_once("db/database.php");
    require_once("utils/functions.php");
    $dbh = new DatabaseHelper($host, $dbname, $user, $password, $port);
    sec_session_start();
?>