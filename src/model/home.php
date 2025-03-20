<?php

include_once __DIR__ . "/ForumORM.php";
include_once __DIR__ . "/PostsORM.php";
include_once __DIR__ . "/CommentsORM.php";
include_once __DIR__ . "/UserORM.php";

class HomeModel
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

    public function getForums(): ?array
    {
        try {
            $forums = $this->forumORM->findAll();

            foreach ($forums as &$forum) {
                $threads = $this->postsORM->findByForum($forum["id_forum"]);
                $forum["thread_count"] = count($threads);

                $forum["post_count"] = 0;
                $forum["latest_post"] = null;

                foreach ($threads as $thread) {
                    $comments = $this->commentsORM->findByPost(
                        $thread["id_post"]
                    );
                    $forum["post_count"] += count($comments);

                    if (
                        !$forum["latest_post"] ||
                        strtotime($thread["creation_date"]) >
                            strtotime($forum["latest_post"]["date"])
                    ) {
                        $user = $this->userORM->findById($thread["id_user"]);
                        $forum["latest_post"] = [
                            "title" => $thread["title"],
                            "id" => $thread["id_post"],
                            "date" => $thread["creation_date"],
                            "author" => $user["username"] ?? "[deleted]",
                        ];
                    }

                    foreach ($comments as $comment) {
                        if (
                            empty($forum["latest_post"]) ||
                            strtotime($comment["creation_date"]) >
                                strtotime($forum["latest_post"]["date"])
                        ) {
                            $user = $this->userORM->findById(
                                $comment["id_user"]
                            );
                            $forum["latest_post"] = [
                                "title" => "Re: " . $thread["title"],
                                "id" => $thread["id_post"],
                                "date" => $comment["creation_date"],
                                "author" => $user["username"] ?? "[deleted]",
                            ];
                        }
                    }
                }
            }

            return $forums;
        } catch (Exception $e) {
            error_log("Error getting forums: " . $e->getMessage());
            return [];
        }
    }
}
