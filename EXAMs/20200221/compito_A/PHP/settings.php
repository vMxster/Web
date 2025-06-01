<?php
if (isset($_POST["remember"])) {
  if (!empty($_POST["username"]) && !empty($_POST["notizie"])) {
    setcookie("username", $_POST['username'], time() + 3600, "/");
    setcookie("categoria", $_POST['notizie'], time() + 3600, "/");
  }
}
$username = isset($_COOKIE['username']) ? $_COOKIE['username'] : '';
$categoria = isset($_COOKIE['categoria']) ? $_COOKIE['categoria'] : '';
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

    <form action="settings.php" method="post" style="border: 2px dotted blue; text-align:center; width: 400px;">
	   <p>
          <label for="username">Username </label>
          <input name="username" type="text" value="<?php echo htmlspecialchars($username); ?>" >
	   </p>
     <p>
       <label for="notizie">Categoria notizie:</label>
       <select name="notizie">
         <option value="">--------</option>
          <option value="politica" <?php if ($categoria === 'politica') echo 'selected'; ?>>Politica</option>
          <option value="attualità" <?php if ($categoria === 'attualità') echo 'selected'; ?>>Attualità</option>
          <option value="sport" <?php if ($categoria === 'sport') echo 'selected'; ?>>Sport</option>
          <option value="scienze" <?php if ($categoria === 'scienze') echo 'selected'; ?>>Scienze</option>
        </select>
	   </p>
		 <p>
      <input type="checkbox" name="remember" /> Remember me
	   </p>
		<p>
      <input type="submit" value="submit"></input>
    </p>
    </form>

  </body>
</html>