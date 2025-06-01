<?php
$db = new mysqli("localhost", "root", "", "climate");
if ($db->conn_error()) {
  die("Connessione non stabilita");
}
$result = $db->query("SELECT * FROM temperature");
$temps = $result->fetch_all(MYSQLI_ASSOC);
$db->close(); 
?>
<html lang="it">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title>Città Italiane</title>
  </head>
  <body>
    <form action="index.php" method="GET">
      <label for="citta">Città</label>
      <select name="citta">
        <?php foreach($temps as $temp): ?>
          <option><?php echo htmlspecialchars($temp["citta"]) ?></option>
        <?php endforeach ?>
      </select>
      <input type="submit" value="Invia" />
    </form>
    <section>
      <?php 
      if (!empty($_GET["citta"])) {
        foreach($temps as $temp) {
          if ($temp["citta"] === $_GET["citta"]) {
            echo '<p>Città: ' . $temp["citta"] . '</p>';
            echo '<p>Temperatura Minima: ' . $temp["min"] . '</p>';
            echo '<p>Temperatura Massima: ' . $temp["max"] . '</p>';
          }
        }
      }
      ?>
    </section>
  </body>
</html>
