<?php
session_start();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elenco Contagi</title>
</head>
<body>
    <main>
        <section>
            <h1>Elenco dei Contagi Registrati</h1>
            <?php if (isset($_SESSION['contagi']) && !empty($_SESSION['contagi'])): ?>
            <ul>
                <?php foreach ($_SESSION["contagi"] as $data => $contagi): ?>
                    <li><?php echo htmlspecialchars($data); ?> – <?php echo htmlspecialchars($contagi); ?> contagi</li>
                <?php endforeach; ?>
            </ul>
            <?php else: ?>
                <p>Nessun dato disponibile</p>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>