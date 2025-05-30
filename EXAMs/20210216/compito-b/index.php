<?php

class DbOp {
    private $db;
    
    public function __construct() {
        $this->db = new mysqli("localhost", "root", "", "febbraio");
        if ($this->db->connect_error) {
            die("Connessione fallita: " . $this->db->connect_error);
        }
    }

    public function verify_input() {
        if (!isset($_POST["mode"]) || !($_POST["mode"] === "html" || $_POST["mode"] === "json")) {
            die("Errore: Parametri non Inizializzati");
        }
        if (isset($_POST["id"])) {
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM dati WHERE id = ?");
            $stmt->bind_param("i", $_POST["id"]);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                if ($row["count"] == 0) {
                    echo "id non esiste nel db";
                }
            }
        }
    }

    public function select_row() {
        $result = $this->db->query("SELECT * FROM dati");
        if (isset($_POST["id"])) {
            while ($row = $result->fetch_assoc()) {
                if ($row["id"] == $_POST["id"]) {
                    return $row;
                }
            }
        } else {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
    }

    public function print_html($data) {
        echo "<!DOCTYPE html>";
        echo "<html lang='it'>";
        echo "<head><meta charset='UTF-8'><title>Dati</title></head>";
        echo "<body>";
        echo "<table>";
        echo "<thead><tr><th id='id' scope='col'>ID</th><th id='key' scope='col'>Chiave</th><th id='value' scope='col'>Valore</th></tr></thead>";
        echo "<tbody>";
        foreach ($data as $row) {
            echo "<tr>";
            echo "<td headers='id'>". htmlspecialchars($row["id"]) . "</td>";
            echo "<td headers='key'>". htmlspecialchars($row["chiave"]) . "</td>";
            echo "<td headers='value'>" . htmlspecialchars($row["valore"]) . "</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
        echo "</body></html>";
    }

    public function print_json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
    }

}

// Utilizzo della classe
$dbOp = new DbOp();
$dbOp->verify_input();
$data = $dbOp->select_row();

if ($_POST["mode"] === "html") {
    $dbOp->print_html($data);
} else {
    $dbOp->print_json($data);
}
?>