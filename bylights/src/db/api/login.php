<?php
    //servono sempre
    header('Access-Control-Allow-Origin:  http://localhost:8080');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
    header('Access-Control-Allow-Headers: Content-Type, Accept, Authorization');
    header('Access-Control-Allow-Credentials: true'); 
    header("Content-Type: application/json");
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        exit();
    }

    
    //inizia sessione
    // Includi il gestore delle sessioni
    //fa partire la session
    require_once __DIR__ . '/../../utils/session_manager.php';

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
    $query = "SELECT user_id, username, password FROM user WHERE email = ?";

    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        echo json_encode(["success" => false, "message" => "Errore del server nella preparazione della query"]);
        exit();
    }
    
    $stmt->bind_param("s", $email);
    if ($stmt->execute() === false) {
        echo json_encode(["success" => false, "message" => "Errore del server nell'esecuzione della query"]);
        exit();
    }

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Recupera i dati dell'utente
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];


        if (password_verify($password, $hashed_password)) {
            // Password corretta, imposta la sessione
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];

            // Rigenera l'ID della sessione per prevenire session fixation
            session_regenerate_id(true);

            echo json_encode(["success" => true, "message" => "Login successful"]);
        } else {
            // Password errata
            echo json_encode(["success" => false, "message" => "Invalid credentials"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Invalid credentials"]);
    }
    
    //chiusura connessione al database
    $stmt->close();
    $conn->close();
