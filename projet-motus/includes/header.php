<?php

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motus - Le jeu de lettres</title>
    <link rel="stylesheet" href="css/style.css">
    <?php if(isset($gamePageActive) && $gamePageActive): ?>
    <link rel="stylesheet" href="css/game.css">
    <?php endif; ?>
</head>
<body>
    <header>
        <div class="logo">
            <h1>MOTUS</h1>
        </div>
        <nav>
            <ul>
                <?php if(isset($_SESSION['user_id'])): ?>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="game.php">Jouer</a></li>
                <li><a href="leaderboard.php">Classement</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
                <?php else: ?>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="login.php">Connexion</a></li>
                <li><a href="register.php">Inscription</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>
