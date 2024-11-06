<?php
// Imposta i parametri del cookie della sessione
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => 'localhost', // Modifica con il tuo dominio
        'secure' => false, // Imposta su true se usi HTTPS
        'httponly' => true,
        'samesite' => 'Strict'
    ]);

    //inizia sessione
    require_once __DIR__ . '/functions.php';
    sec_session_start();

    // Implementa il timeout della sessione
    $timeout_duration = 1800; // 30 minuti

    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
        session_unset();
        session_destroy();
        echo json_encode(["success" => false, "message" => "Sessione scaduta."]);
        exit();
    }

    $_SESSION['LAST_ACTIVITY'] = time();