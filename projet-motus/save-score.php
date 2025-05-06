<?php

session_start();
require_once 'config/config.php';
require_once 'classes/Database.php';
require_once 'classes/Game.php';

// Vérification de la connexion utilisateur
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Utilisateur non connecté']);
    exit;
}

// Vérification de la méthode de requête
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Méthode non autorisée']);
    exit;
}

// Récupération des données
$wordId = isset($_POST['wordId']) ? intval($_POST['wordId']) : 0;
$attempts = isset($_POST['attempts']) ? intval($_POST['attempts']) : 0;
$timeTaken = isset($_POST['timeTaken']) ? intval($_POST['timeTaken']) : 0;
$completed = isset($_POST['completed']) ? (bool)$_POST['completed'] : false;

// Validation des données
if ($wordId <= 0 || $attempts <= 0 || $timeTaken < 0) {
    echo json_encode(['error' => 'Données invalides']);
    exit;
}

$game = new Game();
$result = $game->saveScore($_SESSION['user_id'], $wordId, $attempts, $timeTaken, $completed);

echo json_encode(['success' => $result]);
exit;
?>
