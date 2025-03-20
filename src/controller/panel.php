<?php
include_once __DIR__ . "/../model/panel.php";
include_once __DIR__ . "/../view/panel.php";

class PanelPageController
{
    private $model;
    private static $executed = false;

    public function __construct()
    {
        $this->model = new PanelModel();
    }

    public static function execute(): void
    {
        if (self::$executed) {
            return;
        }

        $controller = new self();
        $controller->handleRequest();
        self::$executed = true;
    }

    private function handleRequest(): void
    {
        session_start();
        if (!isset($_SESSION["user_id"])) {
            header("Location: login");
            exit();
        }

        if ($_SESSION["rank"] !== "admin") {
            PanelView::render([
                "Access denied: Administrator privileges required",
            ]);
            return;
        }

        $message = "";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (isset($_POST["update_rank"])) {
                $userId = $_POST["user_id"] ?? null;
                $newRank = $_POST["new_rank"] ?? null;

                if ($userId && $newRank) {
                    $success = $this->model->updateUserRank($userId, $newRank);
                    if (!$success) {
                        PanelView::render(["Error updating user rank"]);
                        return;
                    }
                }
            } elseif (isset($_POST["delete_forum"])) {
                $forumId = $_POST["forum_id"] ?? null;

                if ($forumId) {
                    $success = $this->model->deleteForum($forumId);
                    if (!$success) {
                        PanelView::render(["Error deleting forum"]);
                        return;
                    }
                    header("Location: panel");
                    exit();
                }
            } elseif (isset($_POST["delete_user"])) {
                $username = trim($_POST["username"] ?? "");
                if ($username) {
                    [$success, $deleteMessage] = $this->model->deleteUser(
                        $username
                    );
                    $message = $deleteMessage;
                }
            } else {
                $this->handleForumCreation();
            }
        }

        $users = $this->model->getAllUsers();
        $forums = $this->model->getAllForums();
        PanelView::render([], $users, $forums, $message);
    }

    private function handleForumCreation(): void
    {
        if ($_SESSION["rank"] !== "admin") {
            PanelView::render([
                "Access denied: Administrator privileges required",
            ]);
            return;
        }

        $title = $_POST["forum-name"] ?? "";
        $description = $_POST["forum-description"] ?? "";
        $icon = $_POST["forum-icon"] ?? "";

        $errors = $this->model->validateForumInput($title, $description, $icon);

        if (empty($errors)) {
            $success = $this->model->createForum($title, $description, $icon);
            if ($success) {
                header("Location: home");
                exit();
            } else {
                PanelView::render(["Error creating forum"]);
            }
        } else {
            PanelView::render($errors);
        }
    }
}
