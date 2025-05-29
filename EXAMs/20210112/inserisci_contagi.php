<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo("Usare Metodo POST!");
    exit();
}

if (!isset($_POST["data"]) || !isset($_POST["contagi"])) {
    echo("Parametri non inizializzati correttamente");
    exit();
}

// Validazione della data nel formato mm-dd-aaaa
$data_obj = DateTime::createFromFormat('m-d-Y', $data_input);
$errori_data = DateTime::getLastErrors();

if ($data_obj && $errori_data['warning_count'] == 0 && $errori_data['error_count'] == 0) {
    $data_formattata = $data_obj->format('m-d-Y');
    $contagi = intval($contagi_input);

    if (!isset($_SESSION['contagi'])) {
        $_SESSION['contagi'] = [];
    }

    if (array_key_exists($data_formattata, $_SESSION['contagi'])) {
        $_SESSION['contagi'][$data_formattata] = $contagi;
        echo("Contagi aggiornati");
    } else {
        $_SESSION['contagi'][$data_formattata] = $contagi;
        echo("Contagi inseriti");
    }
} else {
    echo("La data non è valida. Usa il formato mm-dd-aaaa.");
    exit();
}

?>