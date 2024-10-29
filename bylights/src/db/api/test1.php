<?php
    // CORS
    // METTERE QUESTA INTESTAZIONE AD OGNI PHP API
    header("Access-Control-Allow-Origin: http://localhost:8080");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Content-Type: application/json");



    //chiusura connessione al database, dopo aver terminato tutte le operazioni
    $conn->close();

?>