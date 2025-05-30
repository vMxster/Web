<?php
header('Content-Type: application/json');

if (isset($_POST["action"]) && ($_POST["action"] === "extract" 
        || $_POST["action"] === "new" || $_POST["action"] === "check")) {

    $action = $_POST["action"];
    $db = new mysqli("localhost", "root", "", "lotto");
    if ($db->connect_error) {
        echo json_encode(['message' => 'Errore di connessione al database']);
        exit();
    }

    if ($action === "extract") {
        $number = mt_rand(1,90);
        $result = $db->query("SELECT COUNT(id) as count FROM estrazione");
        $row = $result->fetch_assoc();
        if ($row["count"] <= 4) {
            $stmt = $db->prepare("SELECT numero FROM estrazione WHERE numero = ?");
            $stmt->bind_param("i", $number);
            $result = $stmt->execute()->get_result();
            while ($row = $result->fetch_assoc()) {
                if ($row["numero"] == $number) {
                    echo json_encode([
                        "message" => "Numero già presente"
                    ]);
                    exit();
                }
            }
            $stmt = $db->prepare("INSERT INTO estrazione VALUES(?)");
            $stmt->bind_param("i", $number);
            $stmt->execute();
            echo json_encode([
                "message" => "Numero nuovo inserito"
            ]);
            exit();
        } else {
            echo json_encode([
                "message" => "Sono già presenti 5 numeri"
            ]);
            exit();
        }
    } elseif($action === "new") {
        $db->query("DELETE FROM estrazione");
        echo json_encode([
            "message" => "Partita nuova creata"
        ]);
        exit();
    } elseif($action === "check") {
        if (!isset($_POST["sequence"])) {
            echo json_encode([
                "message" => "Parametro sequence non inizializzata"
            ]);
            exit();
        }
        $numbers = explode("-", $_POST["sequence"]);
        $result = $db->query("SELECT numero FROM estrazione");
        $count = 0;
        while ($row = $result->fetch_assoc()) {
            foreach ($numbers as $number) {
                if ($row["numero"] == $number) {
                    $count++;
                }
            }
        }
        if ($count === 5) {
            echo json_encode([
                "message" => "Vittoria"
            ]);
        } else {
            echo json_encode([
                "message" => "Sconfitta"
            ]);
        }
    }
}

?>