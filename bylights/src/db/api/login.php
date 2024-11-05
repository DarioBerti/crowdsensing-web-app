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

    //inizia sessione
    require_once __DIR__ . '/../../utils/functions.php';
    sec_session_start();


    require_once __DIR__ . '/../db-config.php';

    //prende i file di input
    $input = json_decode(file_get_contents("php://input"), true);

    // Verifica input preso da axios post
    if (is_null($input) || !isset($input['email']) || !isset($input['password'])) {
        echo json_encode(["success" => false, "message" => "Dati di input axios non validi"]);
        exit();
    }

    $email = $input['email'];
    $password = $input['password'];

    //stabilisce connessione 'conn' usando db-config
    $conn = $dbh->db;
    

    //QUERY CHE DOVREBBE ESSERE IN FUNCTIONS.PHP
    $query = "SELECT * FROM user WHERE email = ? AND password = ?";

    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        echo json_encode(["success" => false, "message" => "Errore del server nella preparazione della query"]);
        exit();
    }
    
    $stmt->bind_param("ss", $email, $password);
    if ($stmt->execute() === false) {
        echo json_encode(["success" => false, "message" => "Errore del server nell'esecuzione della query"]);
        exit();
    }

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Recupera i dati dell'utente
        $row = $result->fetch_assoc();

        //salva dati della sessione
        //CON IL LOGIN FA PURE PARTIRE LA SESSIONE E SALVA I DATI
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['username'] = $row['username'];

        echo json_encode(["success" => true, "message" => "Login successful"]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid credentials"]);
    }

    
    //chiusura connessione al database
    $stmt->close();
    $conn->close();
