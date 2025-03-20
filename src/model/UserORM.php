<?php

require_once __DIR__ . "/BaseORM.php";

class UserORM extends BaseORM
{
    protected $table = "User";
    protected $primaryKey = "id_user";

    /**
     * @param string $username
     */
    public function findByUsername($username): ?array
    {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE username = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$username]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            throw new Exception("Find by username failed: " . $e->getMessage());
        }
    }

    /**
     * @param string $userId
     * @param string $newRank
     */
    public function updateUserRank($userId, $newRank): bool
    {
        try {
            $result = $this->save(
                [
                    "rank" => $newRank,
                ],
                $userId
            );
            return !empty($result);
        } catch (Exception $e) {
            error_log("Error updating user rank: " . $e->getMessage());
            return false;
        }
    }

    /**
     * @param string $username
     */
    public function deleteUserByUsername($username): bool
    {
        try {
            $user = $this->findByUsername($username);
            if (!$user) {
                return false;
            }
            return $this->delete($user["id_user"]);
        } catch (Exception $e) {
            error_log("Error deleting user: " . $e->getMessage());
            return false;
        }
    }
}
