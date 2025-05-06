<?php

session_start();
require_once 'config/config.php';
require_once 'classes/Database.php';
require_once 'classes/User.php';
require_once 'classes/Game.php';
require_once 'classes/Score.php';

// Rediriger si non connecté
if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Variable pour le css spécifique au jeu
$gamePageActive = true;

// Obtention du niveau de difficulté
$difficulty = isset($_GET['difficulty']) ? $_GET['difficulty'] : null;
if(!in_array($difficulty, [DIFFICULTY_EASY, DIFFICULTY_MEDIUM, DIFFICULTY_HARD])) {
    
$difficulty = DIFFICULTY_MEDIUM;
}

// Initialisation du jeu
$game = new Game();
$randomWord = $game->getRandomWord($difficulty);

include 'includes/header.php';
?>

<section class="game-container">
    <div class="game-header">
        <h2>Motus - Niveau <?php echo ucfirst($difficulty); ?></h2>
        <div class="difficulty-selector">
            <a href="game.php?difficulty=<?php echo DIFFICULTY_EASY; ?>" class="btn <?php echo $difficulty == DIFFICULTY_EASY ? 'btn-active' : ''; ?>">Facile</a>
            <a href="game.php?difficulty=<?php echo DIFFICULTY_MEDIUM; ?>" class="btn <?php echo $difficulty == DIFFICULTY_MEDIUM ? 'btn-active' : ''; ?>">Moyen</a>
            <a href="game.php?difficulty=<?php echo DIFFICULTY_HARD; ?>" class="btn <?php echo $difficulty == DIFFICULTY_HARD ? 'btn-active' : ''; ?>">Difficile</a>
        </div>
        <div class="game-info">
            <p>Longueur du mot: <span id="word-length"><?php echo strlen($randomWord->word); ?></span> lettres</p>
            <p>Essais restants: <span id="attempts-left"><?php echo MAX_ATTEMPTS; ?></span></p>
            <p>Temps: <span id="timer">00:00</span></p>
        </div>
    </div>
    
    <div class="game-board" id="game-board" data-word-id="<?php echo $randomWord->id; ?>"></div>
    
    <div class="game-message" id="game-message"></div>
    
    <div class="virtual-keyboard" id="virtual-keyboard">
        <div class="keyboard-row">
            <button class="key" data-key="A">A</button>
            <button class="key" data-key="Z">Z</button>
            <button class="key" data-key="E">E</button>
            <button class="key" data-key="R">R</button>
            <button class="key" data-key="T">T</button>
            <button class="key" data-key="Y">Y</button>
            <button class="key" data-key="U">U</button>
            <button class="key" data-key="I">I</button>
            <button class="key" data-key="O">O</button>
            <button class="key" data-key="P">P</button>
        </div>
        <div class="keyboard-row">
            <button class="key" data-key="Q">Q</button>
            <button class="key" data-key="S">S</button>
            <button class="key" data-key="D">D</button>
            <button class="key" data-key="F">F</button>
            <button class="key" data-key="G">G</button>
            <button class="key" data-key="H">H</button>
            <button class="key" data-key="J">J</button>
            <button class="key" data-key="K">K</button>
            <button class="key" data-key="L">L</button>
            <button class="key" data-key="M">M</button>
        </div>
        <div class="keyboard-row">
            <button class="key" data-key="W">W</button>
            <button class="key" data-key="X">X</button>
            <button class="key" data-key="C">C</button>
            <button class="key" data-key="V">V</button>
            <button class="key" data-key="B">B</button>
            <button class="key" data-key="N">N</button>
            <button class="key key-wide" data-key="Backspace">⌫</button>
            <button class="key key-wide" data-key="Enter">Entrée</button>
        </div>
    </div>
</section>

<script>
    // Mot secret à deviner (pour JS)
    const secretWord = "<?php echo $randomWord->word; ?>";
    const maxAttempts = <?php echo MAX_ATTEMPTS; ?>;
</script>

<?php include 'includes/footer.php'; ?>