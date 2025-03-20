<?php
include_once __DIR__ . "/../model/home.php";
include_once __DIR__ . "/../view/home.php";

class HomePageController
{
    private $model;

    public function __construct()
    {
        $this->model = new HomeModel();
    }

    public static function execute(): void
    {
        $controller = new self();
        $controller->handleRequest();
    }

    private function handleRequest(): void
    {
        $forums = $this->model->getForums();
        HomeView::render($forums);
    }
}
