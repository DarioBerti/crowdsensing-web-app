<?php
// Impostazione degli header CORS per consentire richieste da localhost:8080
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");




// Parametri di connessione al database
$host = '127.0.0.1';
$dbname = 'bylights';
$user = 'root';
$password = '';
$port = 3308; // Porta del MySQL in XAMPP

$conn = new mysqli($host, $dbname, $user, $password, $port);
if($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}




//query sql per estrarre i dati
$sql = "SELECT * FROM badge";
$resultQuery = $conn->query($sql);

//preparazione dei dati per il frontend
if($resultQuery->num_rows > 0){
    $datadb = [];
    while($row = $resultQuery->fetch_assoc()){
        $datadb = $row;
    }

    //invio della risposta al frontend
    echo json_encode(["status" => "success", "data" => $datadb]);
}else{
    //invio di messaggio di errore
    echo json_encode(["status" => "error", "message" => "no data found"]);
}

//chiusura connessione al database, dopo aver terminato tutte le operazioni
$conn->close();

?>