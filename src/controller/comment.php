<?php
include_once __DIR__ . "/../model/comment.php";
include_once __DIR__ . "/../view/comment.php";

class CommentPageController
{
    private $model;

    public function __construct()
    {
        $this->model = new CommentModel();
    }

    public static function execute(): void
    {
        $controller = new self();
        $controller->handleRequest();
    }

    private function handleRequest(): void
    {
        session_start();

        if (!isset($_SESSION["user_id"])) {
            header("Location: login");
            exit();
        }

        if (isset($_GET["id"]) && isset($_GET["action"])) {
            $commentId = $_GET["id"];
            $comment = $this->model->getComment($commentId);

            if (!$comment || $comment["id_user"] !== $_SESSION["user_id"]) {
                header("Location: home");
                exit();
            }

            switch ($_GET["action"]) {
                case "edit":
                    if ($_SERVER["REQUEST_METHOD"] === "POST") {
                        $this->handleEditComment($comment);
                    } else {
                        CommentView::renderEditForm($comment);
                    }
                    return;
                case "delete":
                    if ($_SERVER["REQUEST_METHOD"] === "POST") {
                        $this->handleDeleteComment($comment);
                    }
                    return;
            }
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_GET["thread"])) {
            $this->handleNewComment();
        } else {
            header("Location: home");
            exit();
        }
    }

    private function handleNewComment(): void
    {
        $threadId = $_GET["thread"];
        $comment = $_POST["comment"] ?? "";

        if (empty($comment)) {
            header("Location: thread?id=$threadId");
            exit();
        }

        $success = $this->model->createComment($threadId, $comment);

        if ($success) {
            header("Location: thread?id=$threadId");
            exit();
        } else {
            header("Location: thread?id=$threadId");
            exit();
        }
    }

    /**
     * @param array $comment
     */
    private function handleEditComment($comment): void
    {
        $newBody = $_POST["comment"] ?? "";
        if (empty($newBody)) {
            CommentView::renderEditForm($comment, ["Comment cannot be empty"]);
            return;
        }

        if ($this->model->updateComment($comment["id_comment"], $newBody)) {
            header("Location: thread?id=" . $comment["id_post"]);
        } else {
            CommentView::renderEditForm($comment, ["Error updating comment"]);
        }
        exit();
    }

    /**
     * @param array $comment
     */
    private function handleDeleteComment($comment): void
    {
        if ($this->model->deleteComment($comment["id_comment"])) {
            header("Location: thread?id=" . $comment["id_post"]);
        } else {
            header("Location: thread?id=" . $comment["id_post"]);
        }
        exit();
    }
}
