<?php
namespace models;

class Message {

    private $conn;

    private $message;
    private $sent_by;
    private $sent_to;

    private $created_at;
    private $updated_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function store($message, $sent_by, $sent_to)
    {
        $query = "INSERT INTO messages (message, sent_by, sent_to, created_at, updated_at) VALUES (:message, :sent_by, :sent_to, NOW(), NOW())";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':sent_by', $sent_by);
        $stmt->bindParam(':sent_to', $sent_to);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function all() {
        $query = "SELECT * FROM messages";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findByUser($userId)
    {
        $query = "SELECT * FROM messages WHERE sent_to = :userId OR sent_by = :userId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function read($id)
    {
        $query = "SELECT * FROM messages WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function update($id, $message)
    {
        $query = "UPDATE messages SET message = :message, updated_at = NOW() WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':message', $message);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete($id)
    {
        $query = "DELETE FROM messages WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getConversation($userId1, $userId2)
    {
        $query = "SELECT * FROM messages WHERE 
            (sent_by = :user1 AND sent_to = :user2) OR 
            (sent_by = :user2 AND sent_to = :user1) 
            ORDER BY created_at ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':user1', $userId1, \PDO::PARAM_INT);
        $stmt->bindValue(':user2', $userId2, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}