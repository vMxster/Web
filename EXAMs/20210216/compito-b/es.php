<?php

class DbOp {
    private $db;

    function __construct() {
        $this->db = new mysqli("localhost","root","","febbraio");
    }

    function verify_input($id) {
        if (!isset($_POST["mode"])) {
            echo "Mode non settato";
            exit;
        }
        if ($_POST["mode"] !== "html" && $_POST["mode"] !== "json") {
            echo "Mode non accettato";
            exit;
        }
        if (isset($_POST["id"])) {
            $stmt = $this->db->prepare("SELECT id FROM dati WHERE id = ?");
            $stmt->bind_param("i", intval($_POST["id"]));
            $stmt->execute();
            return $stmt->get_result()->num_rows > 0;
        }
    }

    function select_row() {
        if (isset($_POST["id"])) {
            $stmt = $this->db->prepare("SELECT id FROM dati WHERE id = ?");
            $stmt->bind_param("i", intval($_POST["id"]));
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        } else {
            $result = $this->db->query("SELECT * FROM dati");
            return $result->fetch_all(MYSQLI_ASSOC);
        }
    }

    function print_html($array) {
        echo "<!DOCTYPE html>";
        echo "<html lang='it'>";
        echo "<head><meta charset='UTF-8'><title>Dati</title></head>";
        echo "<body>";
        echo "<table>";
        echo "<thead>";
        echo "<tr>";
        echo "<th id='id' scope='col'>id<th/>";
        echo "<th id='key' scope='col'>chiave<th/>";
        echo "<th id='value' scope='col'>valore<th/>";
        echo "<tr/>";
        echo "<thead/>";
        echo "<tbody>";
        foreach ($array as $row) {
            echo "<tr>";
            echo "<td headers='id'>" . htmlspecialchars($row["id"]) . "<td/>";
            echo "<td headers='key'>" . htmlspecialchars($row["chiave"]) . "<td/>";
            echo "<td headers='value'>" . htmlspecialchars($row["valore"]) . "<td/>";
            echo "<tr/>";
        }
        echo "<tbody/>";
        echo "<table/>";
        echo "</body>";
        echo "</html>";
    }

    function print_json($array) {
        header('Content-Type: application/json');
        echo json_encode($array);
    }
}

$dbOp = new DbOp();
$dbOp->verify_input();
$data = $dbOp->select_row();
if ($_POST["mode"] === "html") {
    $dbOp->print_html($data);
} else {
    $dbOp->print_json($data);
}
?>