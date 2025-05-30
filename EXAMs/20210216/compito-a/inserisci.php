<?php

if (!isset($_POST["chiave"]) || !isset($_POST["valore"]) || !isset($_POST["metodo"])) {
    echo "Parametri non inizializzati";
    exit;
}

if ($_POST["metodo"] !== "cookie" || $_POST["metodo"] !== "db") {
    echo "Metodo non supportato";
    exit;
}

if ($_POST["metodo"] === "cookie") {
    setcookie($_POST["chiave"], $_POST["valore"]);
    if (isset($_COOKIE["chiave"])) {
        echo "Valore Cookie di Chiave aggiornato";
        exit;
    }
} elseif ($_POST["metodo"] === "db") {
    $db = new mysqli("localhost", "root", "", "febbraio");

    $stmt = $db->prepare("SELECT chiave FROM dati WHERE chiave = ?");
    $stmt->bind_param("s", $_POST["chiave"]);
    $result = $stmt->execute()->get_result()->fetch_all(MYSQLI_ASSOC);
    if ($result->num_rows > 0) {
        echo "Valore Aggiornato";
        $stmt = $db->prepare("UPDATE dati 
            SET valore = ?, 
            WHERE chiave = ?");
        $stmt->bind_param("ss", $_POST["valore"], $_POST["chiave"]);
        $stmt->execute();
    } else {
        $stmt = $db->prepare("INSERT INTO dati VALUES(?,?)");
        $stmt->bind_param("ss", $_POST["chiave"], $_POST["valore"]);
        $stmt->execute();
    }

    $db->close();
}
?>