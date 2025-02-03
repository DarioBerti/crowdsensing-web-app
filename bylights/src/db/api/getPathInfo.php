<?php
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

    $conn = $dbh->db;

    $path_id = isset($_GET['path_id']) ? intval($_GET['path_id']) : 0;

    if ($path_id > 0){
        $query = "SELECT brightness,latitude,longitude,path_time,path_date,recordedPoints FROM path WHERE path_id = ?";

        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            echo json_encode([
                "success" => false,
                "message" => "Errore del server nella preparazione della query",
                "error" => $conn->error
            ]);
            exit();
        }

        $stmt->bind_param("i",$path_id);
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

        // Controlla se ci sono righe nel risultato
        if ($row = $result->fetch_assoc()) {
            echo json_encode(["success" => true,
                "brightness" => $row['brightness'],
                "latitude" => $row['latitude'],
                "longitude" => $row['longitude'],
                "path_time" => $row['path_time'],
                "path_date" => $row['path_date'],
                "recordedPoints" => json_decode($row['recordedPoints'])
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Nessun dato trovato per il path_id fornito"]);
        }

        $stmt->close();
    }else{
        echo json_encode(["success" => false, "message" => "Invalid path_id"]);
    }

    $conn->close();