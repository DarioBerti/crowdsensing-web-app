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

    //prende i file di input
    $input = json_decode(file_get_contents("php://input"), true);

    require_once __DIR__ . '/../db-config.php';

    //iniza sessione
    require_once __DIR__ . '/../../utils/functions.php';
    sec_session_start();

    // Verifica se utente è loggato
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(["success" => false, "message" => "user not logged in"]);
        exit();
    }

    $user_id = $_SESSION['user_id'];

    //stabilisce connessione 'conn' usando db-config
    $conn = $dbh->db;

    $query = "SELECT brightness, latitude, longitude, path_time, path_date FROM path WHERE view_user_id = ?";

    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        echo json_encode([
            "success" => false,
            "message" => "Errore del server nella preparazione della query",
            "error" => $conn->error
        ]);
        exit();
    }
    
    $stmt->bind_param("i",$user_id);
    if ($stmt->execute() === false) {
        echo json_encode(["success" => false, "message" => "Errore del server nell'esecuzione della query"]);
        exit();
    }
    
    //ottieni i risultati dello statement
    $result = $stmt->get_result();

    if($result == false){
        echo json_encode(["success" => false, "message" => "Errore nel recupero dei risultati"]);
        exit();
    }

    //salva tutte le info dei path
    while($row = $result->fetch_assoc()){
        $paths_id[] = $row['path_id'];
    }