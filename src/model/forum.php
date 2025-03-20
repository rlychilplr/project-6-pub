<?php
include_once __DIR__ . "/ForumORM.php";
include_once __DIR__ . "/PostsORM.php";
include_once __DIR__ . "/UserORM.php";
include_once __DIR__ . "/CommentsORM.php";

class ForumModel
{
    private $forumORM;
    private $postsORM;
    private $userORM;
    private $commentsORM;

    public function __construct()
    {
        $this->forumORM = new ForumORM();
        $this->postsORM = new PostsORM();
        $this->userORM = new UserORM();
        $this->commentsORM = new CommentsORM();
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
     * @param int $forumId
     */
    public function getThreads($forumId): ?array
    {
        try {
            $threads = $this->postsORM->findByForum($forumId);

            // Get user info for each thread
            foreach ($threads as &$thread) {
                $user = $this->userORM->findById($thread["id_user"]);
                $thread["username"] = $user["username"] ?? "[deleted]";

                $comments = $this->commentsORM->findByPost($thread["id_post"]);
                $thread["comment_count"] = count($comments);

                // Get the latest activity date
                $latestDate = $thread["creation_date"];
                if (!empty($comments)) {
                    $lastComment = end($comments);
                    $latestDate = max(
                        $lastComment["creation_date"],
                        $thread["creation_date"]
                    );
                }
                $thread["last_activity"] = $latestDate;
            }

            // Sort threads by last activity (most recent first)
            usort($threads, function ($a, $b) {
                return strtotime($b["last_activity"]) -
                    strtotime($a["last_activity"]);
            });

            return $threads;
        } catch (Exception $e) {
            error_log("Error getting threads: " . $e->getMessage());
            return [];
        }
    }
}
