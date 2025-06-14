<?php

if (isset($_POST["chiave"]) && isset($_POST["valore"]) && isset($_POST["metodo"])) {
    $key = isset($_POST["chiave"]);
    $value = isset($_POST["valore"]);
    $method = isset($_POST["metodo"]);
    if ($method === "cookie") {
        $all = [];
        if (isset($_COOKIE['chiavi'])) {
            $all = json_decode($_COOKIE['chiavi'], true) ?: [];
        }

        $isset = false;
        if (isset($all[$key])) {
            $isset = true;
        }
        $all[$key] = $value;

        setcookie(
            'chiavi',
            json_encode($all),
            time() + 86400, // 1 giorno
            '/'
        );
        if ($isset) {
            echo "Valore Aggiornato";
        } else {
            echo "Valore Inserito";
        }
    } else if ($method === "db") {
        $db = new mysqli("localhost", "root", "", "febbraio");
        $stmt1 = $db->prepare("SELECT chiave FROM dati WHERE chiave = ?");
        $stmt1->bind_param("s", $key);
        $stmt1->execute();
        if ($stmt1->get_result()->num_rows > 0) {
            $stmt2 = $db->prepare("UPDATE dati SET valore = ? WHERE chiave = ?");
            $stmt2->bind_param("ss", $value, $key);
            $stmt2->execute();
            echo "Valore aggiornato";
        } else {
            $stmt2 = $db->prepare("INSERT INTO dati('chiave','valore') VALUES(?,?)");
            $stmt2->bind_param("ss", $key, $value);
            $stmt2->execute();
            echo "Valore inserito";
        }
        $db->close();
    } else {
        echo "Metodo non disponibile";
        exit;
    }
}

?>