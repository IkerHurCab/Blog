<?php 

namespace models;

class Comment {
    private $conn;

    private $content;
    private $userId; 
    private $postId;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function store($content, $userId, $postId)
    {
        $query = "INSERT INTO comments (content, user_id, post_id) VALUES (:content, :user_id, :post_id)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':user_id', $userId); 
        $stmt->bindParam(':post_id', $postId);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function all() {
        $query = "SELECT * FROM comments";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findByPost($postId)
    {
        $query = "SELECT * FROM comments WHERE post_id = :post_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':post_id', $postId);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function read($id)
    {
        $query = "SELECT * FROM comments WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function update($id, $content)
    {
        $query = "UPDATE comments SET content = :content WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':content', $content);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function destroy($id)
    {
        $query = "DELETE FROM comments WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}