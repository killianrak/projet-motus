<?php

class Score {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function getUserScores($userId) {
        $query = "SELECT s.*, w.word, w.length, w.difficulty 
                 FROM scores s
                 JOIN words w ON s.word_id = w.id
                 WHERE s.user_id = :user_id
                 ORDER BY s.played_at DESC";
        $stmt = $this->db->query($query);
        $this->db->execute($stmt, [':user_id' => $userId]);
        
        return $this->db->resultSet($stmt);
    }
    
    public function getBestScore($userId, $wordId) {
        $query = "SELECT * FROM scores 
                 WHERE user_id = :user_id AND word_id = :word_id AND completed = 1
                 ORDER BY attempts ASC, time_taken ASC
                 LIMIT 1";
        $stmt = $this->db->query($query);
        $this->db->execute($stmt, [':user_id' => $userId, ':word_id' => $wordId]);
        
        return $this->db->single($stmt);
    }
    
    public function getTopPlayersByCompletedGames($limit = 10) {
        // Convertir et sécuriser la valeur
        $limit = (int)$limit;
        
        $query = "SELECT u.id, u.username, COUNT(s.id) as completed_games 
                 FROM users u
                 JOIN scores s ON u.id = s.user_id
                 WHERE s.completed = 1
                 GROUP BY u.id
                 ORDER BY completed_games DESC
                 LIMIT $limit";
        $stmt = $this->db->query($query);
        
        return $this->db->resultSet($stmt);
    }
    
    public function getAverageAttempts() {
        $query = "SELECT AVG(attempts) as avg_attempts 
                 FROM scores 
                 WHERE completed = 1";
        $stmt = $this->db->query($query);
        $this->db->execute($stmt);
        
        $result = $this->db->single($stmt);
        return $result ? $result->avg_attempts : 0;
    }
}
?>