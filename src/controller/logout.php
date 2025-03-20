<?php

class LogoutPageController
{
    public static function execute(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION["user_id"])) {
            include_once __DIR__ . "/../model/login.php";
            $loginModel = new LoginModel();
            $loginModel->clearRememberToken($_SESSION["user_id"]);
        }

        if (isset($_COOKIE["remember_token"])) {
            setcookie("remember_token", "", time() - 3600, "/");
        }

        session_destroy();

        echo "<script>
            // syncronize logout
            localStorage.setItem('logout', Date.now());

            window.location.href = 'home';
        </script>";
        exit();
    }
}
