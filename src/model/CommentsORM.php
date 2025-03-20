<?php

require_once __DIR__ . "/BaseORM.php";

class CommentsORM extends BaseORM
{
    protected $table = "Comments";
    protected $primaryKey = "id_comment";

    /**
     * @param int $postId
     */
    public function findByPost($postId): array
    {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id_post = ? ORDER BY creation_date ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$postId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Find by post failed: " . $e->getMessage());
        }
    }
}
