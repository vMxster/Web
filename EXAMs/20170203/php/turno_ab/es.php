<?php
header('Content-Type: application/json');

// Connessione al DB
$db = new mysqli("localhost", "root", "", "matematica");

// Verifica della connessione
if ($mysqli->connect_error) {
    echo json_encode([
        "risultato" => 0,
        "errore" => "Errore di connessione al DB"
    ]);
    exit;
}

// Controllo se è presente il parametro 'sequenza' e se è un numero positivo
if (!isset($_GET['sequenza']) || !is_numeric($_GET['sequenza']) || $_GET['sequenza'] <= 0) {
    echo json_encode([
        "risultato" => 0,
        "errore" => "Parametro sequenza mancante o non valido"
    ]);
    exit;
}

$sequenza = intval($_GET['sequenza']);

// Prepara ed esegue la query
$stmt = $db->prepare("SELECT numero FROM numeri WHERE sequenza = ? ORDER BY ordine ASC");
$stmt->bind_param("i", $sequenza);
$stmt->execute();
$rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Estrai i numeri in un array
$numeri = [];
foreach ($rows as $row) {
    $numeri[] = $row['numero'];
}

// Se la sequenza non esiste nel DB
if (count($numeri) == 0) {
    echo json_encode([
        "risultato" => 0,
        "errore" => "Nessuna sequenza trovata nel database"
    ]);
    exit;
}

// Controllo Sequenza
if ($numeri[0] !== 1 && $numeri[1] !== 1) {
    echo json_encode([
        "risultato" => 0,
        "sequenza" => $numeri
    ]);
    exit;
}
for($i=2 ; $i<count($numeri) ; $i++) {
    $fibo = $numeri[$i-1] + $numeri[$i-2];
    if(!($fibo === $numeri[$i])) {
        $result = [
            "risultato" => 0,
            "sequenza" => $numeri
        ];
        echo(json_encode($result));
        exit();
    }
}
$result = [
            "risultato" => 1,
            "sequenza" => $numeri
];
echo(json_encode($result));
exit();


?>