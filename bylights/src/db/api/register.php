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
    if (is_null($input) || !isset($input['email']) || !isset($input['password']) || !isset($input['name']) || !isset($input['surname']) || !isset($input['username'])) {
        echo json_encode(["success" => false, "message" => "Dati di input axios non validi"]);
        exit();
    }

    $name = $input['name'];
    $surname = $input['surname'];
    $username = $input['username'];
    $email = $input['email'];
    $password = $input['password'];

    //stabilisce connessione 'conn' usando db-config
    $conn = $dbh->db;
    
    // Prepara la query SQL per inserire un nuovo utente
    $query = "INSERT INTO user (username, name, surname, email, password, dateRegistration, badgesAcquired, id_profile_img) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        echo json_encode(["success" => false, "message" => "Errore del server nella preparazione della query"]);
        exit();
    }

    // Verifica se email o username esistono già
    $checkQuery = "SELECT user_id FROM user WHERE email = ? OR username = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("ss", $email, $username);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        // Email o username già esistenti
        echo json_encode(["success" => false, "message" => "Already in use"]);
        exit();
    }

    $checkStmt->close();

    // Hash della password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Imposta badges_acquired a 0 e id_profile_image a NULL
    $badges_acquired = 0;
    $id_profile_img = NULL;
    $dateRegistration = date('Y-m-d H:i:s');

    
    // Binding dei parametri
    $stmt->bind_param("ssssssis", $username, $name, $surname, $email, $hashed_password, $dateRegistration, $badges_acquired, $id_profile_img);
    
    //salva execute
    $executeResult = $stmt->execute();
    
    if ($executeResult === false) {
        echo json_encode(["success" => false, "message" => "server error in query execution"]);
        exit();
    }

    if ($executeResult) {
        //salva dati della sessione
        $_SESSION['user_id'] = $conn->insert_id;
        $_SESSION['username'] = $username;

        echo json_encode(["success" => true, "message" => "Login successful"]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid credentials"]);
    }

    // Chiude la connessione al database
    $stmt->close();
    $conn->close();