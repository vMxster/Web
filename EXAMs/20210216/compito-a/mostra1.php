<?php
$cookies = [];
if (isset($_COOKIE['chiavi'])) {
    $decoded = json_decode($_COOKIE['chiavi'], true);
    if (is_array($decoded)) {
        $cookies = $decoded;
    }
}

$db = new mysqli("localhost", "root", "", "febbraio");
$result = $db->query("SELECT chiave, valore FROM dati");
$result = $result->fetch_all(MYSQLI_ASSOC);
$db->close();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esercizio PHP</title>
</head>
<body>
    <section>
        <h1>DB</h1>
        <ul>
            <?php
                foreach ($result as $row) {
                    echo "<li>" . htmlspecialchars($row["chiave"]) . " : " . htmlspecialchars($row["valore"])  . "<li/>";
                }
            ?>
        </ul>
    </section>
    <section>
        <h1>COOKIE</h1>
        <ul>
            <?php
                foreach ($cookies as $key => $value) {
                    echo "<li>" . htmlspecialchars($key) . " : " . htmlspecialchars($value)  . "<li/>";
                }
            ?>
        </ul>
    </section>
</body>
</html>