<?php
// Impostazioni di connessione al database
$host = 'localhost';
$dbname = 'db_esami';
$username = 'root';
$password = '';

// Connessione al database
$conn = new mysqli($host, $username, $password, $dbname);

// Verifica della connessione
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

// Funzione per validare i dati
function valida_dati($data) {
    $errors = [];

    // Validazione nome
    if (!isset($data['nome']) || trim($data['nome']) === '') {
        $errors[] = "Il campo 'nome' è obbligatorio.";
    }

    // Validazione cognome
    if (!isset($data['cognome']) || trim($data['cognome']) === '') {
        $errors[] = "Il campo 'cognome' è obbligatorio.";
    }

    // Validazione codice fiscale
    if (!isset($data['codicefiscale']) || strlen(trim($data['codicefiscale'])) !== 16) {
        $errors[] = "Il 'codice fiscale' deve contenere esattamente 16 caratteri.";
    }

    // Validazione data di nascita
    if (!isset($data['datanascita']) || !data_valida($data['datanascita'])) {
        $errors[] = "La 'data di nascita' non è valida o non è nel formato YYYY-MM-DD.";
    }

    // Validazione sesso
    if (!isset($data['sesso']) || !in_array(strtoupper($data['sesso']), ['M', 'F', 'A'])) {
        $errors[] = "Il campo 'sesso' deve essere 'M', 'F' o 'A'.";
    }

    return $errors;
}

function data_valida($data) {
    $d = DateTime::createFromFormat('Y-m-d', $data);
    return $d && $d->format('Y-m-d') === $data;
}

// Gestione dell'inserimento dei dati
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = valida_dati($_POST);

    if (empty($errors)) {
        // Preparazione dei dati
        $nome = trim($_POST['nome']);
        $cognome = trim($_POST['cognome']);
        $codicefiscale = trim($_POST['codicefiscale']);
        $datanascita = trim($_POST['datanascita']);
        $sesso = trim($_POST['sesso']);

        $stmt = $conn->prepare("INSERT INTO cittadino (nome, cognome, codicefiscale, datanascita, sesso)
                VALUES (?,?,?,?,?)");
        $stmt->bind_param("sssss",
            $nome,
            $cognome,
            $codicefiscale,
            $datanascita,
            $sesso);
        $stmt->execute();
    } else {
        // Visualizzazione degli errori
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
    }
}

// Visualizzazione dei dati
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

if ($id) {
    // Visualizza il record con l'ID specificato
    $sql = "SELECT * FROM cittadino WHERE idcittadino = $id";
} else {
    // Visualizza tutti i record
    $sql = "SELECT * FROM cittadino";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Elenco Cittadini</h2>";
    echo "<table>";
    echo "<tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Cognome</th>
            <th>Codice Fiscale</th>
            <th>Data di Nascita</th>
            <th>Sesso</th>
          </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['idcittadino']}</td>
                <td>{$row['nome']}</td>
                <td>{$row['cognome']}</td>
                <td>{$row['codicefiscale']}</td>
                <td>{$row['datanascita']}</td>
                <td>{$row['sesso']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>Nessun record trovato.</p>";
}

$conn->close();
?>