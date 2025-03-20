<?php

require_once __DIR__ . "/BaseORM.php";

class PostsORM extends BaseORM
{
    protected $table = "Posts";
    protected $primaryKey = "id_post";

    /**
     * @param int $forumId
     */
    public function findByForum($forumId): array
    {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id_forum = ? ORDER BY creation_date DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$forumId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Find by forum failed: " . $e->getMessage());
        }
    }
}
