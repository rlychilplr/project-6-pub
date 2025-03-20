<?php
include_once __DIR__ . "/ForumORM.php";
include_once __DIR__ . "/PostsORM.php";
include_once __DIR__ . "/CommentsORM.php";
include_once __DIR__ . "/UserORM.php";

class ThreadModel
{
    private $forumORM;
    private $postsORM;
    private $commentsORM;
    private $userORM;

    public function __construct()
    {
        $this->forumORM = new ForumORM();
        $this->postsORM = new PostsORM();
        $this->commentsORM = new CommentsORM();
        $this->userORM = new UserORM();
    }

    /**
     * @param int $forumId
     */
    public function getForum($forumId): ?array
    {
        try {
            return $this->forumORM->findById($forumId);
        } catch (Exception $e) {
            error_log("Error getting forum: " . $e->getMessage());
            return null;
        }
    }

    /**
     * @param int $threadId
     */
    public function getThread($threadId): ?array
    {
        try {
            $thread = $this->postsORM->findById($threadId);
            if ($thread) {
                $user = $this->userORM->findById($thread["id_user"]);
                $thread["username"] = $user["username"] ?? "[deleted]";
                if (
                    isset($user["profile_picture"]) &&
                    $user["profile_picture"]
                ) {
                    $thread["username"] = $user["username"];
                    $thread["last_login"] = $user["last_login"];

                    $thread["profile_picture"] = base64_encode(
                        $user["profile_picture"]
                    );
                    $thread["profile_picture_type"] =
                        $user["profile_picture_type"];
                }
            }
            return $thread;
        } catch (Exception $e) {
            error_log("Error getting thread: " . $e->getMessage());
            return null;
        }
    }

    /**
     * @param int $threadId
     */
    public function getComments($threadId): ?array
    {
        try {
            $comments = $this->commentsORM->findByPost($threadId);
            foreach ($comments as &$comment) {
                $user = $this->userORM->findById($comment["id_user"]);
                $comment["username"] = $user["username"] ?? "[deleted]";
                $comment["last_login"] = $user["last_login"];

                $user = $this->userORM->findById($comment["id_user"]);
                $comment["username"] = $user["username"] ?? "[deleted]";
                if (
                    isset($user["profile_picture"]) &&
                    $user["profile_picture"]
                ) {
                    $comment["profile_picture"] = base64_encode(
                        $user["profile_picture"]
                    );
                    $comment["profile_picture_type"] =
                        $user["profile_picture_type"];
                }
            }
            return $comments;
        } catch (Exception $e) {
            error_log("Error getting comments: " . $e->getMessage());
            return [];
        }
    }

    /**
     * @param int $forumId
     * @param string $title
     * @param string $body
     */
    public function createThread($forumId, $title, $body): ?int
    {
        try {
            $threadData = [
                "id_forum" => $forumId,
                "id_user" => $_SESSION["user_id"],
                "title" => $title,
                "body" => $body,
                "creation_date" => date("Y-m-d H:i:s"),
            ];

            return $this->postsORM->save($threadData);
        } catch (Exception $e) {
            error_log("Error creating thread: " . $e->getMessage());
            return null;
        }
    }

    /**
     * @param int $threadId
     * @param string $title
     * @param string $body
     */
    public function updateThread($threadId, $title, $body): bool
    {
        try {
            $threadData = [
                "title" => $title,
                "body" => $body,
                "edited_at" => date("Y-m-d H:i:s"),
            ];
            return $this->postsORM->save($threadData, $threadId);
        } catch (Exception $e) {
            error_log("Error updating thread: " . $e->getMessage());
            return false;
        }
    }

    /**
     * @param int $threadId
     */
    public function deleteThread($threadId): bool
    {
        try {
            return $this->postsORM->delete($threadId);
        } catch (Exception $e) {
            error_log("Error deleting thread: " . $e->getMessage());
            return false;
        }
    }
}
