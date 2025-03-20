<?php

include_once __DIR__ . "/../model/register.php";
include_once __DIR__ . "/../view/register.php";

class RegisterPageController
{
    private $model;

    public function __construct()
    {
        $this->model = new RegisterModel();
    }

    public static function execute(): void
    {
        $controller = new self();
        $controller->handleRequest();
    }

    private function handleRequest(): void
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->handleRegistration();
        } else {
            error_log("About to render register view");
            RegisterView::render();
            error_log("After render register view");
        }
    }

    private function handleRegistration(): void
    {
        $username = $_POST["username"] ?? "";
        $email = $_POST["email"] ?? "";
        $password = $_POST["password"] ?? "";
        $confirmPassword = $_POST["confirm_password"] ?? "";

        $errors = $this->model->validateInput(
            $username,
            $email,
            $password,
            $confirmPassword
        );

        if (empty($errors)) {
            if ($this->model->registerUser($username, $email, $password)) {
                header("Location: login");
                exit();
            } else {
                $errors[] =
                    "Registration failed. Username or email might already be taken.";
            }
        }

        RegisterView::render($errors);
    }
}
