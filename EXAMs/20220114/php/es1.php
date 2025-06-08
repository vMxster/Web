<?php
$db = new mysqli("localhost","root","","db_esami");
if (isset($_POST["nome"]) && isset($_POST["cognome"]) 
        && isset($_POST["codice fiscale"]) && isset($_POST["data di nascita"]) 
        && isset($_POST["sesso"])) {
    $nome = $_POST["nome"];
    $cognome = $_POST["cognome"];
    $cf = $_POST["codice fiscale"];
    $dn = $_POST["data di nascita"];
    $sesso = $_POST["sesso"];

    $dn_new = DateTime::createFromFormat("YYYY-MM-DD", $dn);
    
    if (is_string($nome) && is_string($cognome) 
            && strlen($cf) === 16 && $dn === $dn_new
            && ($sesso === "M" || $sesso === "F" || $sesso === "A")) {
        $stmt = $db->prepare("INSERT INTO cittadino VALUES(?,?,?,?,?)");
        $stmt->bind_param("sssss", $nome, $cognome, $cf, $dn, $sesso);
        $stmt->execute();
        echo "Dati Inseriti con successo";
    } else {
        echo "Dati non inseriti";
    }

} else {
    echo "Parametri non inizializzati";
}

if (isset($_POST["id"])) {
    $stmt = $db->prepare("SELECT * FROM cittadino WHERE idcittadino = ?");
    $stmt->bind_param("i", $_POST["id"]);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $db->query("SELECT * FROM cittadino");
}

$result = $result->fetch_all(MYSQLI_ASSOC);
echo "<table>";
echo "<thead>";
echo "<tr>";
echo "<th id='id' scope='col'>ID<th/>";
echo "<th id='nome' scope='col'>Nome<th/>";
echo "<th id='cognome' scope='col'>Cognome<th/>";
echo "<th id='cf' scope='col'>CF<th/>";
echo "<th id='dn' scope='col'>DataNascita<th/>";
echo "<th id='sex' scope='col'>Sesso<th/>";
echo "<tr/>";
echo "<thead/>";
echo "<tbody>";
foreach ($result as $row) {
    echo "<tr>";
    echo "<td headers='id'>" . $row["id"] ."<td/>";
    echo "<td headers='nome'>" . $row["nome"] ."<td/>";
    echo "<td headers='cognome'>" . $row["cognome"] ."<td/>";
    echo "<td headers='cf'>" . $row["codicefiscale"] ."<td/>";
    echo "<td headers='dn'>" . $row["datanascita"] ."<td/>";
    echo "<td headers='sex'>" . $row["sesso"] ."<td/>";
    echo "<tr/>";
}
echo "<tbody/>";
echo "<table/>";
?>