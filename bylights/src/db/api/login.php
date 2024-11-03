<?php
    //CORS abilitato 
    // Intestazioni CORS per gestire le richieste
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        header('Access-Control-Allow-Origin: http://localhost:8080');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
        header('Access-Control-Allow-Headers: Content-Type, Accept, Authorization');
        header("Content-Type: application/json");
        http_response_code(200);
        exit();
    }

    // Aggiungere intestazioni per le altre richieste (POST, GET)
    header('Access-Control-Allow-Origin: http://localhost:8080');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
    header('Access-Control-Allow-Headers: Content-Type, Accept, Authorization');
    header("Content-Type: application/json");

    require_once __DIR__ . '/../db-config.php';
    // require_once 'C:/xampp/htdocs/tirocinio/crowdsensing-web-app/bylights/src/db/db-config.php';


    //prende i file di input
    $input = json_decode(file_get_contents("php://input"), true);
    $email = $input['email'];
    $password = $input['password'];

    //stabilisce connessione 'conn' usando db-config
    $conn = $dbh->db;
    

    //QUERY CHE DOVREBBE ESSERE IN FUNCTIONS.PHP
    $query = "SELECT * FROM user WHERE email = ? AND password = ?";

    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die("Error preparing the statement: " . $conn->error);
    }
    
    $stmt->bind_param("ss", $email, $password);
    if ($stmt->execute() === false) {
        die("Error executing the statement: " . $stmt->error);
    }

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo json_encode(["success" => true, "message" => "Login successful"]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid credentials"]);
    }

    
    //chiusura connessione al database
    $stmt->close();
    $conn->close();
?>