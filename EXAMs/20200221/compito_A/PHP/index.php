<?php
$db = mysqli("localhost","root","","febbraio");
if (!empty($_COOKIE["username"]) && !empty($_COOKIE["categoria"])) {
  $stmt = $db->prepare("SELECT * FROM articoli WHERE categoria = ?");
  $stmt->bind_param("s", $_COOKIE["categoria"]);
  $stmt->execute();
  $articles = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  $stmt->close();
} else {
  $articles = $db->query("SELECT * FROM articoli");
  $articles = $articles->fetch_all(MYSQLI_ASSOC);
}
$db->close();
?>

<html lang="it">
  <head>
    <title>Esercizio PHP</title>
  </head>
  <body>
    <div class="header">
      <a  class="home">Esercizio PHP</a>
      <div class="products">
        <a href="index.php">Homepage</a>
        <a href="settings.php">Settings</a>
      </div>
    </div>
    <article>
      <?php foreach ($articles as $article):?>
      <div>
        <h1><?php echo htmlspecialchars($article["titolo"]); ?></h1>
        <p>
          <?php echo htmlspecialchars($article["descrizione"]); ?>
        </p>
      </div>
      <?php endforeach?>
    </article>
  </body>
</html>