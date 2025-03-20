<?php

include_once __DIR__ . "/../model/login.php";
include_once __DIR__ . "/../view/login.php";

class LoginPageController
{
    private static $executed = false;
    private $model;
    private const COOKIE_NAME = "remember_token";
    private const COOKIE_DURATION = 2592000; // 30 days in seconds

    public function __construct()
    {
        $this->model = new LoginModel();
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
        $this->checkRememberCookie();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->handleLogin();
        } else {
            LoginView::render();
        }
    }

    private function checkRememberCookie(): void
    {
        if (isset($_COOKIE[self::COOKIE_NAME])) {
            $token = $_COOKIE[self::COOKIE_NAME];
            $user = $this->model->validateRememberToken($token);

            if ($user) {
                session_start();
                $_SESSION["user_id"] = $user["id_user"];
                $_SESSION["username"] = $user["username"];
                $_SESSION["rank"] = $user["rank"];

                header("Location: home");
                exit();
            } else {
                // Invalid or expired token, clear the cookie
                setcookie(self::COOKIE_NAME, "", time() - 3600, "/");
            }
        }
    }

    private function handleLogin(): void
    {
        $username = $_POST["username"] ?? "";
        $password = $_POST["password"] ?? "";
        $rememberMe = isset($_POST["remember_me"]);

        $user = $this->model->validateLogin($username, $password);

        if ($user) {
            session_start();
            $_SESSION["user_id"] = $user["id_user"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["rank"] = $user["rank"];

            if ($rememberMe) {
                $token = $this->model->createRememberToken($user["id_user"]);
                if ($token) {
                    setcookie(
                        self::COOKIE_NAME,
                        $token,
                        time() + self::COOKIE_DURATION,
                        "/"
                    );
                }
            }

            echo "<script>
                window.location.href = 'home';
            </script>";
            exit();
        } else {
            $errors = ["Invalid username or password"];
            LoginView::render($errors);
        }
    }
}
