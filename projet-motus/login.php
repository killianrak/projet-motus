<?php

session_start();
require_once 'config/config.php';
require_once 'classes/Database.php';
require_once 'classes/User.php';

// Rediriger si déjà connecté
if(isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    if(empty($username) || empty($password)) {
        $error = 'Veuillez remplir tous les champs';
    } else {
        $user = new User();
        $loggedInUser = $user->login($username, $password);
        
        if($loggedInUser) {
            $_SESSION['user_id'] = $loggedInUser->id;
            $_SESSION['username'] = $loggedInUser->username;
            
            header('Location: index.php');
            exit;
        } else {
            $error = 'Nom d\'utilisateur ou mot de passe incorrect';
        }
    }
}

include 'includes/header.php';
?>

<section class="form-container">
    <h2>Connexion</h2>
    
    <?php if($error): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form id="login-form" method="POST" action="">
        <div class="form-group">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" required>
        </div>
        
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <button type="submit" name="login" class="btn btn-primary">Se connecter</button>
    </form>
    
    <p class="form-link">Vous n'avez pas de compte? <a href="register.php">S'inscrire</a></p>
</section>

<script>
document.getElementById('login-form').addEventListener('submit', function(e) {
    let valid = true;
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value.trim();
    
    if(username === '' || password === '') {
        valid = false;
        e.preventDefault();
        alert('Veuillez remplir tous les champs');
    }
    
    return valid;
});
</script>

<?php include 'includes/footer.php'; ?>