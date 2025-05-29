<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo("Errore: metodo di richiesta non valido.");
    exit();
}

if (!isset($_POST["data"])) {
    echo("Variabile POST di data non inizializzate correttamente!");
    exit();
}

$data_input = $_POST['data'];

// Validazione della data nel formato mm-dd-aaaa
$data_obj = DateTime::createFromFormat('m-d-Y', $data_input);
$errori_data = DateTime::getLastErrors();

if ($data_obj && $errori_data['warning_count'] == 0 && $errori_data['error_count'] == 0) {
    $data_formattata = $data_obj->format('m-d-Y');

    if (isset($_SESSION['contagi']) && array_key_exists($data_formattata, $_SESSION['contagi'])) {
        unset($_SESSION['contagi'][$data_formattata]);
        echo "Contagi cancellati con successo.";
    } else {
        echo "Errore: nessun dato trovato per la data $data_formattata.";
    }
} else {
    echo "Errore: la data '$data_input' non è valida. Usa il formato mm-dd-aaaa.";
}


?>