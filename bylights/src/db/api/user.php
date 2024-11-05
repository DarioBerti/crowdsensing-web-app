<?php
    header('Access-Control-Allow-Origin:  http://localhost:8080');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
    header('Access-Control-Allow-Headers: Content-Type, Accept, Authorization');
    header('Access-Control-Allow-Credentials: true');
    header("Content-Type: application/json");
    
    //CORS abilitato 
    // Intestazioni CORS per gestire le richieste
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        exit();
    }

    //iniza sessione
    require_once __DIR__ . '/../../utils/functions.php';
    sec_session_start();

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['loggedIn' => false]);
        exit();
    }
    
    // Restituisce le informazioni dell'utente
    echo json_encode([
        'loggedIn' => true,
        'user' => [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username']
        ]
    ]);
