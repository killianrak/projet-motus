<?php

?>
    </main>
    <footer>
        <p>&copy; <?php echo date('Y'); ?> - Motus - Projet étudiant</p>
    </footer>
    
    <?php if(isset($gamePageActive) && $gamePageActive): ?>
    <script src="js/game.js"></script>
    <?php endif; ?>
</body>
</html>