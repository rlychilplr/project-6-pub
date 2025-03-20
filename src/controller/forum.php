<?php
include_once __DIR__ . "/../model/forum.php";
include_once __DIR__ . "/../view/forum.php";

class ForumPageController
{
    private $model;

    public function __construct()
    {
        $this->model = new ForumModel();
    }

    public static function execute(): void
    {
        $controller = new self();
        $controller->handleRequest();
    }

    private function handleRequest(): void
    {
        $forumId = $_GET["id"] ?? null;

        if (!$forumId) {
            header("Location: home");
            exit();
        }

        $forum = $this->model->getForum($forumId);
        $threads = $this->model->getThreads($forumId);

        if (!$forum) {
            header("Location: home");
            exit();
        }

        ForumView::render($forum, $threads);
    }
}
