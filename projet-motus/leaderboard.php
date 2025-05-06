<?php

session_start();
require_once 'config/config.php';
require_once 'classes/Database.php';
require_once 'classes/User.php';
require_once 'classes/Game.php';
require_once 'classes/Score.php';

include 'includes/header.php';

$game = new Game();
$score = new Score();

// Récupérer le classement
$leaderboard = $game->getLeaderboard(20);
$topPlayers = $score->getTopPlayersByCompletedGames(10);
?>

<section class="leaderboard-container">
    <h2>Classement - Wall of Fame</h2>
    
    <div class="leaderboard-tabs">
        <button class="tab-btn active" data-tab="best-scores">Meilleurs scores</button>
        <button class="tab-btn" data-tab="top-players">Joueurs les plus actifs</button>
    </div>
    
    <div class="tab-content active" id="best-scores">
        <table class="leaderboard-table">
            <thead>
                <tr>
                    <th>Rang</th>
                    <th>Joueur</th>
                    <th>Mot</th>
                    <th>Difficulté</th>
                    <th>Tentatives</th>
                    <th>Temps</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php $rank = 1; foreach($leaderboard as $entry): ?>
                <tr>
                    <td><?php echo $rank++; ?></td>
                    <td><?php echo htmlspecialchars($entry->username); ?></td>
                    <td><?php echo htmlspecialchars($entry->word); ?></td>
                    <td><?php echo ucfirst(htmlspecialchars($entry->difficulty)); ?></td>
                    <td><?php echo $entry->attempts; ?>/<?php echo MAX_ATTEMPTS; ?></td>
                    <td><?php echo floor($entry->time_taken / 60) . 'm ' . ($entry->time_taken % 60) . 's'; ?></td>
                    <td><?php echo date('d/m/Y', strtotime($entry->played_at)); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if(count($leaderboard) == 0): ?>
                <tr>
                    <td colspan="7" class="no-data">Aucune donnée disponible</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <div class="tab-content" id="top-players">
        <table class="leaderboard-table">
            <thead>
                <tr>
                    <th>Rang</th>
                    <th>Joueur</th>
                    <th>Parties gagnées</th>
                </tr>
            </thead>
            <tbody>
                <?php $rank = 1; foreach($topPlayers as $player): ?>
                <tr>
                    <td><?php echo $rank++; ?></td>
                    <td><?php echo htmlspecialchars($player->username); ?></td>
                    <td><?php echo $player->completed_games; ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if(count($topPlayers) == 0): ?>
                <tr>
                    <td colspan="3" class="no-data">Aucune donnée disponible</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<script>
    // Script pour les onglets
    document.querySelectorAll('.tab-btn').forEach(button => {
        button.addEventListener('click', () => {
            // Supprimer la classe active de tous les boutons et contenus
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            
            // Ajouter la classe active au bouton cliqué
            button.classList.add('active');
            
            // Afficher le contenu correspondant
            const tabId = button.getAttribute('data-tab');
            document.getElementById(tabId).classList.add('active');
        });
    });
</script>

<?php include 'includes/footer.php'; ?>