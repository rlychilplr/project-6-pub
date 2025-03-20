<?php
include_once __DIR__ . "/../model/thread.php";
include_once __DIR__ . "/../view/thread.php";

class ThreadPageController
{
    private $model;

    public function __construct()
    {
        $this->model = new ThreadModel();
    }

    public static function execute(): void
    {
        $controller = new self();
        $controller->handleRequest();
    }

    private function handleRequest(): void
    {
        session_start();

        if (isset($_GET["id"])) {
            $threadId = $_GET["id"];
            $thread = $this->model->getThread($threadId);

            if (!$thread) {
                header("Location: home");
                exit();
            }

            if (isset($_GET["action"])) {
                switch ($_GET["action"]) {
                    case "edit":
                        if ($_SERVER["REQUEST_METHOD"] === "POST") {
                            $this->handleEditThread($thread);
                        } else {
                            $this->renderEditForm($thread);
                        }
                        return;
                    case "delete":
                        if ($_SERVER["REQUEST_METHOD"] === "POST") {
                            $this->handleDeleteThread($thread);
                        }
                        return;
                }
            }

            $comments = $this->model->getComments($threadId);
            ThreadView::render($thread, $comments);
        } elseif (isset($_GET["forum"])) {
            if (!isset($_SESSION["user_id"])) {
                header("Location: login");
                exit();
            }

            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $this->handleNewThread($_GET["forum"]);
            } else {
                $forum = $this->model->getForum($_GET["forum"]);
                if ($forum) {
                    ThreadView::renderNewThreadForm($forum);
                } else {
                    header("Location: home");
                    exit();
                }
            }
        } else {
            header("Location: home");
            exit();
        }
    }

    /**
     * @param array $thread
     */
    private function handleEditThread($thread): void
    {
        if (
            !isset($_SESSION["user_id"]) ||
            $_SESSION["user_id"] != $thread["id_user"]
        ) {
            header("Location: thread?id=" . $thread["id_post"]);
            exit();
        }

        $title = $_POST["title"] ?? "";
        $body = $_POST["body"] ?? "";

        if (empty($title) || empty($body)) {
            ThreadView::renderEditForm($thread, ["All fields are required"]);
            return;
        }

        if ($this->model->updateThread($thread["id_post"], $title, $body)) {
            header("Location: thread?id=" . $thread["id_post"]);
        } else {
            ThreadView::renderEditForm($thread, ["Error updating thread"]);
        }
        exit();
    }

    /**
     * @param array $thread
     */
    private function handleDeleteThread($thread): void
    {
        if (
            !isset($_SESSION["user_id"]) ||
            $_SESSION["user_id"] != $thread["id_user"]
        ) {
            header("Location: thread?id=" . $thread["id_post"]);
            exit();
        }

        if ($this->model->deleteThread($thread["id_post"])) {
            header("Location: forum?id=" . $thread["id_forum"]);
        } else {
            header("Location: thread?id=" . $thread["id_post"]);
        }
        exit();
    }

    /**
     * @param array $thread
     * @param array $errors
     */
    private function renderEditForm($thread, $errors = []): void
    {
        if (
            !isset($_SESSION["user_id"]) ||
            $_SESSION["user_id"] != $thread["id_user"]
        ) {
            header("Location: thread?id=" . $thread["id_post"]);
            exit();
        }

        ThreadView::renderEditForm($thread, $errors);
    }

    /**
     * @param int $forumId
     */
    private function handleNewThread($forumId): void
    {
        $title = $_POST["title"] ?? "";
        $body = $_POST["body"] ?? "";

        if (empty($title) || empty($body)) {
            $forum = $this->model->getForum($forumId);
            ThreadView::renderNewThreadForm($forum, [
                "All fields are required",
            ]);
            return;
        }

        $threadId = $this->model->createThread($forumId, $title, $body);
        if ($threadId) {
            header("Location: thread?id=$threadId");
            exit();
        } else {
            $forum = $this->model->getForum($forumId);
            ThreadView::renderNewThreadForm($forum, ["Error creating thread"]);
        }
    }
}
