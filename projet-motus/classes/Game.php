<?php

class Game {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function getRandomWord($difficulty = null) {
        if ($difficulty) {
            $query = "SELECT * FROM words WHERE difficulty = :difficulty ORDER BY RAND() LIMIT 1";
            $stmt = $this->db->query($query);
            return $this->db->single($stmt, [':difficulty' => $difficulty]);
        } else {
            $query = "SELECT * FROM words ORDER BY RAND() LIMIT 1";
            $stmt = $this->db->query($query);
            return $this->db->single($stmt);
        }
    }
    
    public function getWordById($id) {
        $query = "SELECT * FROM words WHERE id = :id";
        $stmt = $this->db->query($query);
        return $this->db->single($stmt, [':id' => $id]);
    }
    
    public function checkWord($word, $guess) {
        $word = strtoupper($word);
        $guess = strtoupper($guess);
        
        if (strlen($guess) != strlen($word)) {
            return false;
        }
        
        $result = [];
        $wordArray = str_split($word);
        $guessArray = str_split($guess);
        
        // Premier passage: trouver les lettres correctes
        for ($i = 0; $i < count($wordArray); $i++) {
            if ($guessArray[$i] == $wordArray[$i]) {
                $result[$i] = ['letter' => $guessArray[$i], 'status' => 'correct'];
                $wordArray[$i] = '*'; // Marquer comme utilisé
                $guessArray[$i] = '-'; // Marquer comme vérifié
            }
        }
        
        // Deuxième passage: trouver les lettres présentes mais mal placées
        for ($i = 0; $i < count($guessArray); $i++) {
            if ($guessArray[$i] != '-') {
                $pos = array_search($guessArray[$i], $wordArray);
                if ($pos !== false) {
                    $result[$i] = ['letter' => $guessArray[$i], 'status' => 'present'];
                    $wordArray[$pos] = '*'; // Marquer comme utilisé
                } else {
                    $result[$i] = ['letter' => $guessArray[$i], 'status' => 'absent'];
                }
            }
        }
        
        ksort($result);
        return $result;
    }
    
    public function saveScore($userId, $wordId, $attempts, $timeTaken, $completed) {
        $query = "INSERT INTO scores (user_id, word_id, attempts, time_taken, completed) 
                 VALUES (:user_id, :word_id, :attempts, :time_taken, :completed)";
        $stmt = $this->db->query($query);
        
        return $this->db->execute($stmt, [
            ':user_id' => $userId,
            ':word_id' => $wordId,
            ':attempts' => $attempts,
            ':time_taken' => $timeTaken,
            ':completed' => $completed ? 1 : 0
        ]);
    }
    
    public function getLeaderboard($limit = 10) {
        // Convertir et sécuriser la valeur
        $limit = (int)$limit;
        
        $query = "SELECT s.*, u.username, w.word, w.length, w.difficulty 
                 FROM scores s
                 JOIN users u ON s.user_id = u.id
                 JOIN words w ON s.word_id = w.id
                 WHERE s.completed = 1
                 ORDER BY s.attempts ASC, s.time_taken ASC
                 LIMIT $limit";
        $stmt = $this->db->query($query);
        
        return $this->db->resultSet($stmt);
    }
}
?>