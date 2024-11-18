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

    // Verifica input preso da axios post
    if (is_null($input) || !isset($input['averageBrightness']) || !isset($input['initialLatitude']) || !isset($input['initialLongitude']) || !isset($input['pathDate']) || !isset($input['pathTime']) || !isset($input['userId'])) {
        echo json_encode(["success" => false, "message" => "Dati di input axios non validi"]);
        exit();
    }

    $averageBrightness = $input['averageBrightness'];
    $initialLatitude = $input['initialLatitude'];
    $initialLongitude = $input['initialLongitude'];
    $pathDate = $input['pathDate'];
    $pathTime = $input['pathTime'];
    $userId = $input['userId'];

    //stabilisce connessione 'conn' usando db-config
    $conn = $dbh->db;

    //QUERY CHE DOVREBBE ESSERE IN FUNCTIONS.PHP
    //path id autoincrementa. viewuserid, deladminid, anaadminid sono nullable
    $query = "INSERT INTO path (brightness, Rec_user_id, latitude, longitude, path_time, path_date) VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        echo json_encode([
            "success" => false,
            "message" => "Errore del server nella preparazione della query",
            "error" => $conn->error
        ]);
        exit();
    }
    
    $stmt->bind_param("diddss", $averageBrightness, $userId, $initialLatitude, $initialLongitude, $pathTime, $pathDate);
    if ($stmt->execute() === false) {
        echo json_encode(["success" => false, "message" => "Errore del server nell'esecuzione della query"]);
        exit();
    }

    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "inserimento ha avuto successo"]);
    } else {
        echo json_encode(["success" => false, "message" => "inserimento non ha avuto successo"]);
    }

    // Chiudi lo statement e la connessione
    $stmt->close();
    $conn->close();