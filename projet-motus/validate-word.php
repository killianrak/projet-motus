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
$word = isset($_POST['word']) ? strtoupper(trim($_POST['word'])) : '';
$guess = isset($_POST['guess']) ? strtoupper(trim($_POST['guess'])) : '';
$wordId = isset($_POST['wordId']) ? intval($_POST['wordId']) : 0;

// Validation des données
if (empty($word) || empty($guess) || $wordId <= 0) {
    echo json_encode(['error' => 'Données invalides']);
    exit;
}

// Vérification de la longueur du mot proposé
if (strlen($guess) !== strlen($word)) {
    echo json_encode(['error' => 'Le mot proposé doit avoir la même longueur que le mot à deviner']);
    exit;
}

$game = new Game();
$result = $game->checkWord($word, $guess);

echo json_encode(['result' => $result]);
exit;
?>
