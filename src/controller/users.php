<?php
include_once __DIR__ . "/../model/users.php";
include_once __DIR__ . "/../view/users.php";

class UsersController
{
    private $model;

    public function __construct()
    {
        $this->model = new UsersModel();
    }

    public static function execute(): void
    {
        $controller = new self();
        $controller->handleRequest();
    }

    private function handleRequest(): void
    {
        $users = $this->model->getAllUsers();
        UsersView::render($users);
    }
}
