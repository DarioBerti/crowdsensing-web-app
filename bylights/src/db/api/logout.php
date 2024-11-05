<?php
    //iniza sessione
    require_once __DIR__ . '/../../utils/functions.php';
    session_start();

    header("Access-Control-Allow-Origin: http://localhost:8080");
    header("Access-Control-Allow-Credentials: true");

    session_unset();
    session_destroy();

    echo json_encode(['success' => true]);
?>