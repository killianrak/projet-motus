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

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    if(empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Veuillez remplir tous les champs';
    } elseif($password !== $confirm_password) {
        $error = 'Les mots de passe ne correspondent pas';
    } elseif(strlen($password) < 6) {
        $error = 'Le mot de passe doit contenir au moins 6 caractères';
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email invalide';
    } else {
        $user = new User();
        
        // Vérifie si le nom d'utilisateur existe déjà
        if($user->findUserByUsername($username)) {
            $error = 'Ce nom d\'utilisateur est déjà pris';
        } 
        // Vérifie si l'email existe déjà
        elseif($user->findUserByEmail($email)) {
            $error = 'Cet email est déjà utilisé';
        }
        // Inscription
        else {
            if($user->register($username, $email, $password)) {
                $success = 'Inscription réussie! Vous pouvez maintenant vous connecter';
            } else {
                $error = 'Une erreur est survenue lors de l\'inscription';
            }
        }
    }
}

include 'includes/header.php';
?>

<section class="form-container">
    <h2>Inscription</h2>
    
    <?php if($error): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if($success): ?>
        <div class="success-message"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <form id="register-form" method="POST" action="">
        <div class="form-group">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <div class="form-group">
            <label for="confirm_password">Confirmer le mot de passe</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        
        <button type="submit" name="register" class="btn btn-primary">S'inscrire</button>
    </form>
    
    <p class="form-link">Vous avez déjà un compte? <a href="login.php">Se connecter</a></p>
</section>

<script>
document.getElementById('register-form').addEventListener('submit', function(e) {
    let valid = true;
    const username = document.getElementById('username').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    const confirmPassword = document.getElementById('confirm_password').value.trim();
    
    if(username === '' || email === '' || password === '' || confirmPassword === '') {
        valid = false;
        alert('Veuillez remplir tous les champs');
    } else if(password !== confirmPassword) {
        valid = false;
        alert('Les mots de passe ne correspondent pas');
    } else if(password.length < 6) {
        valid = false;
        alert('Le mot de passe doit contenir au moins 6 caractères');
    } else if(!validateEmail(email)) {
        valid = false;
        alert('Email invalide');
    }
    
    if(!valid) {
        e.preventDefault();
    }
    
    return valid;
});

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}
</script>

<?php include 'includes/footer.php'; ?>
