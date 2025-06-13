<?php
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["message" => "Metodo da usare: POST"]);
    exit;
}
$db = new mysqli("localhost", "root", "", "lotto");

if (isset($_POST["extract"])) {
    $result = $db->query("SELECT numero FROM estrazione");
    if ($result->num_rows >= 5) {
        echo json_encode(["message" => "Totale Numeri uguale a 5"]);
        exit;
    } else {
        $number = rand(1,90);
        foreach ($result->fetch_all(MYSQLI_ASSOC) as $row) {
            if ($number == $row["numero"]) {
                echo json_encode(["message" => "Numero già presente"]);
                exit;
            }
        }
        $stmt = $db->prepare("INSERT INTO estrazione('numero') VALUES(?)");
        $stmt->bind_param("i", $number);
        $stmt->execute();
    }
} else if (isset($_POST["new"])) {
    $db->query("DELETE FROM estrazione");
    echo json_encode(["message" => "Partita Iniziata"]);
} else if (isset($_POST["check"])) {
    if (!isset($_POST["sequence"])) {
        echo json_encode(["message" => "Sequenza non inizializzata"]);
        exit;
    }
    $sequence = explode("-", $_POST["sequence"]);
    foreach($sequence as $number) {
        $number = intval($number);
        $stmt = $db->prepare("SELECT numero FROM estrazione WHERE numero = ?");
        $stmt->bind_param("i", $number);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        if (!$result || $result["numero"] != $number) {
            echo json_encode(["message" => "Sequenza non corretta"]);
            exit;
        }
    }
    echo json_encode(["message" => "Sequenza corretta"]);
} else {
    echo json_encode(["message" => "Parametri non inizializzati correttamente"]);
    exit;
}

?>