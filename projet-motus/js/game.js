
document.addEventListener('DOMContentLoaded', function() {
    // Éléments du DOM
    const gameBoard = document.getElementById('game-board');
    const gameMessage = document.getElementById('game-message');
    const attemptsLeft = document.getElementById('attempts-left');
    const timerElement = document.getElementById('timer');
    const virtualKeyboard = document.getElementById('virtual-keyboard');
    
    // Variables du jeu
    const wordLength = secretWord.length;
    let currentRow = 0;
    let currentCell = 0;
    let gameOver = false;
    let startTime = Date.now();
    let timerInterval;
    let attempts = maxAttempts;
    let currentGuess = '';
    
    // Initialisation du jeu
    initializeGame();
    startTimer();
    
    // Écouteurs d'événements
    document.addEventListener('keydown', handleKeyPress);
    virtualKeyboard.addEventListener('click', handleVirtualKeyPress);
    
    // Fonctions
    function initializeGame() {
        // Créer la grille de jeu
        for (let i = 0; i < maxAttempts; i++) {
            const row = document.createElement('div');
            row.className = 'game-row';
            
            for (let j = 0; j < wordLength; j++) {
                const cell = document.createElement('div');
                cell.className = 'game-cell';
                
                // Afficher la première lettre par défaut
                if (i === 0 && j === 0) {
                    cell.textContent = secretWord[0].toUpperCase();
                    cell.classList.add('filled');
                    cell.classList.add('correct');
                    currentGuess += secretWord[0].toUpperCase();
                    currentCell = 1;
                }
                
                row.appendChild(cell);
            }
            
            gameBoard.appendChild(row);
        }
        
        // Sélectionner la cellule active
        updateActiveCell();
    }
    
    function startTimer() {
        timerInterval = setInterval(updateTimer, 1000);
    }
    
    function updateTimer() {
        const elapsedSeconds = Math.floor((Date.now() - startTime) / 1000);
        const minutes = Math.floor(elapsedSeconds / 60);
        const seconds = elapsedSeconds % 60;
        
        timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }
    
    function handleKeyPress(e) {
        if (gameOver) return;
        
        if (e.key.match(/^[a-zA-Z]$/) && currentCell < wordLength) {
            // Ajouter une lettre
            addLetter(e.key.toUpperCase());
        } else if (e.key === 'Backspace' && currentCell > 0) {
            // Si on est à la première cellule de la première ligne
            if (currentRow === 0 && currentCell === 1) {
                return; // Empêcher de supprimer la première lettre
            }
            // Supprimer une lettre
            removeLetter();
        } else if (e.key === 'Enter') {
            // Valider le mot
            validateGuess();
        }
    }
    
    function handleVirtualKeyPress(e) {
        if (gameOver) return;
        
        if (e.target.classList.contains('key')) {
            const key = e.target.getAttribute('data-key');
            
            if (key.match(/^[A-Z]$/) && currentCell < wordLength) {
                // Ajouter une lettre
                addLetter(key);
            } else if (key === 'Backspace' && currentCell > 0) {
                // Si on est à la première cellule de la première ligne
                if (currentRow === 0 && currentCell === 1) {
                    return; // Empêcher de supprimer la première lettre
                }
                // Supprimer une lettre
                removeLetter();
            } else if (key === 'Enter') {
                // Valider le mot
                validateGuess();
            }
        }
    }
    
    function addLetter(letter) {
        const rows = gameBoard.querySelectorAll('.game-row');
        const cells = rows[currentRow].querySelectorAll('.game-cell');
        
        cells[currentCell].textContent = letter;
        cells[currentCell].classList.add('filled');
        
        currentGuess += letter;
        currentCell++;
        
        updateActiveCell();
    }
    
    function removeLetter() {
        currentCell--;
        
        const rows = gameBoard.querySelectorAll('.game-row');
        const cells = rows[currentRow].querySelectorAll('.game-cell');
        
        cells[currentCell].textContent = '';
        cells[currentCell].classList.remove('filled');
        
        currentGuess = currentGuess.slice(0, -1);
        
        updateActiveCell();
    }
    
    function updateActiveCell() {
        // Supprimer la classe active de toutes les cellules
        const allCells = gameBoard.querySelectorAll('.game-cell');
        allCells.forEach(cell => cell.classList.remove('active'));
        
        // Ajouter la classe active à la cellule courante
        if (currentCell < wordLength && !gameOver) {
            const rows = gameBoard.querySelectorAll('.game-row');
            const cells = rows[currentRow].querySelectorAll('.game-cell');
            
            cells[currentCell].classList.add('active');
        }
    }
    
    function validateGuess() {
        if (currentCell < wordLength) {
            // Mot incomplet
            showMessage('Le mot est incomplet', 'error');
            return;
        }
        
        // Vérifier le mot avec AJAX
        validateWordWithServer();
    }
    
    function validateWordWithServer() {
        // Créer un objet FormData avec les données à envoyer
        const formData = new FormData();
        formData.append('word', secretWord);
        formData.append('guess', currentGuess);
        formData.append('wordId', gameBoard.getAttribute('data-word-id'));
        
        // Configurer la requête
        fetch('validate-word.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                showMessage(data.error, 'error');
            } else {
                processResult(data.result);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            
            // Traitement côté client en cas d'erreur de connexion
            const result = checkGuess(secretWord, currentGuess);
            processResult(result);
        });
    }
    
    function checkGuess(word, guess) {
        const wordArray = word.toUpperCase().split('');
        const guessArray = guess.toUpperCase().split('');
        const result = [];
        
        // Premier passage : lettres correctes
        for (let i = 0; i < wordArray.length; i++) {
            if (guessArray[i] === wordArray[i]) {
                result[i] = { letter: guessArray[i], status: 'correct' };
                wordArray[i] = '*'; // Marquer comme utilisé
                guessArray[i] = '-'; // Marquer comme vérifié
            }
        }
        
        // Deuxième passage : lettres présentes mais mal placées
        for (let i = 0; i < guessArray.length; i++) {
            if (guessArray[i] !== '-') {
                const pos = wordArray.indexOf(guessArray[i]);
                if (pos !== -1) {
                    result[i] = { letter: guessArray[i], status: 'present' };
                    wordArray[pos] = '*'; // Marquer comme utilisé
                } else {
                    result[i] = { letter: guessArray[i], status: 'absent' };
                }
            }
        }
        
        return result;
    }
    
    function processResult(result) {
        const rows = gameBoard.querySelectorAll('.game-row');
        const cells = rows[currentRow].querySelectorAll('.game-cell');
        const keyElements = virtualKeyboard.querySelectorAll('.key');
        
        // Appliquer les styles aux cellules
        for (let i = 0; i < result.length; i++) {
            if (result[i]) {
                cells[i].classList.add(result[i].status);
                
                // Mettre à jour le clavier virtuel
                keyElements.forEach(key => {
                    if (key.getAttribute('data-key') === result[i].letter) {
                        key.classList.add('key-' + result[i].status);
                    }
                });
            }
        }
        
        // Vérifier si le mot est correct
        const isCorrect = result.every(r => r.status === 'correct');
        
        if (isCorrect) {
            // Gagné
            gameWon();
        } else {
            // Perdu cet essai
            currentRow++;
            attempts--;
            attemptsLeft.textContent = attempts;
            
            if (currentRow >= maxAttempts) {
                // Perdu le jeu
                gameLost();
            } else {
                // Continuer le jeu
                currentCell = 0;
                currentGuess = '';
                
                // Pré-remplir la première lettre pour la nouvelle ligne
                const newRowCells = rows[currentRow].querySelectorAll('.game-cell');
                newRowCells[0].textContent = secretWord[0].toUpperCase();
                newRowCells[0].classList.add('filled');
                newRowCells[0].classList.add('correct');
                currentGuess += secretWord[0].toUpperCase();
                currentCell = 1;
                
                updateActiveCell();
            }
        }
    }
    
    function gameWon() {
        clearInterval(timerInterval);
        gameOver = true;
        
        const timeTaken = Math.floor((Date.now() - startTime) / 1000);
        const attemptsUsed = currentRow + 1;
        
        showMessage(`Félicitations! Vous avez trouvé le mot en ${attemptsUsed} essai(s) et ${formatTime(timeTaken)}`, 'success');
        saveScore(true, attemptsUsed, timeTaken);
        
        showEndGameActions();
    }
    
    function gameLost() {
        clearInterval(timerInterval);
        gameOver = true;
        
        const timeTaken = Math.floor((Date.now() - startTime) / 1000);
        
        showMessage(`Dommage! Le mot était: ${secretWord.toUpperCase()}`, 'error');
        saveScore(false, maxAttempts, timeTaken);
        
        showEndGameActions();
    }
    
    function formatTime(seconds) {
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = seconds % 60;
        
        return `${minutes}m ${remainingSeconds}s`;
    }
    
    function showMessage(message, type) {
        gameMessage.textContent = message;
        gameMessage.className = 'game-message';
        
        if (type === 'error') {
            gameMessage.classList.add('error-message');
        } else if (type === 'success') {
            gameMessage.classList.add('success-message');
        }
        
        // Effacer le message après 3 secondes si ce n'est pas un message de fin de jeu
        if (!gameOver) {
            setTimeout(() => {
                gameMessage.textContent = '';
                gameMessage.className = 'game-message';
            }, 3000);
        }
    }
    
    function saveScore(completed, attemptsUsed, timeTaken) {
        const formData = new FormData();
        formData.append('wordId', gameBoard.getAttribute('data-word-id'));
        formData.append('attempts', attemptsUsed);
        formData.append('timeTaken', timeTaken);
        formData.append('completed', completed ? 1 : 0);
        
        fetch('save-score.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Score sauvegardé:', data);
        })
        .catch(error => {
            console.error('Erreur lors de la sauvegarde du score:', error);
        });
    }
    
    function showEndGameActions() {
        // Créer les boutons d'action de fin de jeu
        const actionsDiv = document.createElement('div');
        actionsDiv.className = 'action-buttons';
        
        const newGameBtn = document.createElement('button');
        newGameBtn.className = 'btn btn-primary';
        newGameBtn.textContent = 'Nouvelle partie';
        newGameBtn.addEventListener('click', () => {
            window.location.reload();
        });
        
        const leaderboardBtn = document.createElement('button');
        leaderboardBtn.className = 'btn btn-secondary';
        leaderboardBtn.textContent = 'Voir le classement';
        leaderboardBtn.addEventListener('click', () => {
            window.location.href = 'leaderboard.php';
        });
        
        actionsDiv.appendChild(newGameBtn);
        actionsDiv.appendChild(leaderboardBtn);
        
        // Ajouter à la fin du conteneur de jeu
        document.querySelector('.game-container').appendChild(actionsDiv);
    }
});