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

    require_once __DIR__ . '/../db-config.php';
    
    //iniza sessione
    require_once __DIR__ . '/../../utils/functions.php';
    sec_session_start();

    //prende i file di input
    $input = json_decode(file_get_contents("php://input"), true);

    // Verifica se utente è loggato
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(["success" => false, "message" => "user not logged in"]);
        exit();
    }

    $user_id = $_SESSION['user_id'];

    //stabilisce connessione 'conn' usando db-config
    $conn = $dbh->db;

    //QUERY CHE DOVREBBE ESSERE IN FUNCTIONS.PHP
    //ritorna vettore di tutti gli id dei path di tale user
    $query = "SELECT path_id FROM path WHERE Rec_user_id = ?";

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

    //salva tutti gli id dei path
    $paths_id = [];
    while($row = $result->fetch_assoc()){
        $paths_id[] = $row['path_id'];
    }

    if (count($paths_id) > 0) {
        //ora che la richiesta di vedere i path ha avuto successo, aggiorno la FK in nella tabella 'path': 'view_user_id'
        //AGGIORNA IL CAMPO FK VIEW_USER_ID DI PATH, a meno che non sia già stato aggiornato
        $query2 = "UPDATE path SET view_user_id = ? WHERE Rec_user_id = ? AND (view_user_id IS NULL OR view_user_id <> ?)";
        $stmt2 = $conn->prepare($query2);
        if ($stmt2 === false) {
            echo json_encode([
                "success" => false,
                "message" => "Errore del server nella preparazione della query",
                "error" => $conn->error
            ]);
            exit();
        }
        $stmt2->bind_param("iii", $user_id, $user, $user);
        $checkResult = $stmt2->execute();
        if ($checkResult === false) {
            echo json_encode(["success" => false, "message" => "Errore del server nell'esecuzione della query", "error" => $stmt2->error]);
            exit();
        }
        if(!$checkResult){
            echo json_encode(["success" => false, "message" => "Errore del server nell'esecuzione della query"]);
        }

        //continua con la query precedente
        echo json_encode([
            "success" => true,
            "message" => "la richiesta ha avuto successo",
            "paths_id" => $paths_id
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "la richiesta non ha ritornato nessun path"]);
    }

    // Chiudi lo statement e la connessione
    $stmt->close();
    $stmt2->close();
    $conn->close();