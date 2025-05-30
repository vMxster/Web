<?php
$db = new mysqli("localhost", "root", "", "febbraio");

if ($db->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Recupera i dati dal database
$sql = "SELECT chiave, valore FROM dati";
$result = $db->query($sql);

// Prepara l'array dei dati del database
$dati_db = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dati_db[$row['chiave']] = $row['valore'];
    }
}
$db->close();

// Recupera i cookie, escludendo quelli tecnici
$cookie_tecnici = [];
$cookie_tecnici["PHPSESSID"] = 0;
$dati_cookie = array_diff_key($_COOKIE, $cookie_tecnici);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elenco Chiave:Valore</title>
</head>
<body>
    <main>
        <section>
            <h1>DB</h1>
            <ul>
                <?php foreach ($dati_db as $key => $value): ?>
                    <li><?php echo htmlspecialchars($key) ?> : <?php echo htmlspecialchars($value) ?></li>
                <?php endforeach ?>
            </ul>
        </section>
        <section>
            <h1>Cookie</h1>
            <ul>
                <?php foreach ($dati_cookie as $key => $value): ?>
                    <li><?php echo htmlspecialchars($key) ?> : <?php echo htmlspecialchars($value) ?></li>
                <?php endforeach ?>
            </ul>
        </section>
    </main>
</body>
</html>