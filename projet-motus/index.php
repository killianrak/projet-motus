<?php

session_start();
require_once 'config/config.php';
require_once 'classes/Database.php';
require_once 'classes/User.php';
require_once 'classes/Game.php';
require_once 'classes/Score.php';

include 'includes/header.php';
?>

<section class="hero">
    <div class="hero-content">
        <h2>Bienvenue sur Motus</h2>
        <p>Le jeu de lettres où vous devez deviner un mot en 6 essais maximum</p>
        <?php if(!isset($_SESSION['user_id'])): ?>
            <div class="hero-buttons">
                <a href="login.php" class="btn btn-primary">Se connecter</a>
                <a href="register.php" class="btn btn-secondary">S'inscrire</a>
            </div>
        <?php else: ?>
            <div class="hero-buttons">
                <a href="game.php" class="btn btn-primary">Jouer maintenant</a>
                <a href="leaderboard.php" class="btn btn-secondary">Voir le classement</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="how-to-play">
    <h2>Comment jouer</h2>
    <div class="rules">
        <div class="rule">
            <div class="rule-number">1</div>
            <p>La première lettre du mot est affichée par défaut.</p>
        </div>
        <div class="rule">
            <div class="rule-number">2</div>
            <p>Proposez un mot et validez avec la touche Entrée.</p>
        </div>
        <div class="rule">
            <div class="rule-number">3</div>
            <p>Les lettres bien placées seront dans un carré rouge.</p>
        </div>
        <div class="rule">
            <div class="rule-number">4</div>
            <p>Les lettres présentes mais mal placées seront dans un cercle jaune.</p>
        </div>
        <div class="rule">
            <div class="rule-number">5</div>
            <p>Les lettres absentes du mot seront sur fond bleu.</p>
        </div>
        <div class="rule">
            <div class="rule-number">6</div>
            <p>Vous avez 6 essais pour trouver le mot!</p>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>