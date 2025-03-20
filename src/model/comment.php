<?php

include_once __DIR__ . "/CommentsORM.php";

class CommentModel
{
    private $commentsORM;

    public function __construct()
    {
        $this->commentsORM = new CommentsORM();
    }

    /**
     * @param int $threadId
     * @param string $body
     */
    public function createComment($threadId, $body): bool
    {
        try {
            $commentData = [
                "id_post" => $threadId,
                "id_user" => $_SESSION["user_id"],
                "body" => $body,
                "creation_date" => date("Y-m-d H:i:s"),
            ];

            $this->commentsORM->save($commentData);
            return true;
        } catch (Exception $e) {
            error_log("Error creating comment: " . $e->getMessage());
            return false;
        }
    }

    /**
     * @param int $commentId
     */
    public function getComment($commentId): ?array
    {
        try {
            return $this->commentsORM->findById($commentId);
        } catch (Exception $e) {
            error_log("Error getting comment: " . $e->getMessage());
            return null;
        }
    }

    /**
     * @param int $commentId
     * @param string $body
     */
    public function updateComment($commentId, $body): bool
    {
        try {
            $commentData = [
                "body" => $body,
                "edited_at" => date("Y-m-d H:i:s"),
            ];
            return $this->commentsORM->save($commentData, $commentId);
        } catch (Exception $e) {
            error_log("Error updating comment: " . $e->getMessage());
            return false;
        }
    }

    /**
     * @param int $commentId
     */
    public function deleteComment($commentId): bool
    {
        try {
            return $this->commentsORM->delete($commentId);
        } catch (Exception $e) {
            error_log("Error deleting comment: " . $e->getMessage());
            return false;
        }
    }
}
