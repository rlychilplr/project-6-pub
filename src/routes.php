<?php
include_once __DIR__ . "/router.php";

include_once __DIR__ . "/controller/home.php";
include_once __DIR__ . "/controller/register.php";
include_once __DIR__ . "/controller/login.php";
include_once __DIR__ . "/controller/logout.php";
include_once __DIR__ . "/controller/panel.php";
include_once __DIR__ . "/controller/forum.php";
include_once __DIR__ . "/controller/thread.php";
include_once __DIR__ . "/controller/comment.php";
include_once __DIR__ . "/controller/privacyPolicy.php";
include_once __DIR__ . "/controller/tos.php";
include_once __DIR__ . "/controller/rules.php";
include_once __DIR__ . "/controller/settings.php";
include_once __DIR__ . "/controller/users.php";

// get dir (relative to webroot) of file currently executing
$scriptDir = dirname($_SERVER["PHP_SELF"]);
// remove query params
$requestUri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
// returns absoulte server fs path moved 2 dirs down
$baseDir = dirname(dirname($_SERVER["SCRIPT_FILENAME"]));

// Function to convert file path to URL path
function getRelativePath($from, $to)
{
    // Some compatible way to set this up
    $from = is_dir($from) ? rtrim($from, "\/") . "/" : $from;
    $to = is_dir($to) ? rtrim($to, "\/") . "/" : $to;
    $from = str_replace("\\", "/", $from);
    $to = str_replace("\\", "/", $to);

    $from = explode("/", $from);
    $to = explode("/", $to);

    $relPath = $to;

    foreach ($from as $depth => $dir) {
        // Find first non-matching dir
        if ($dir === $to[$depth]) {
            array_shift($relPath);
        }
    }
    $path = implode("/", $relPath);
    return $path;
}

// Get the relative path for static assets
$staticBasePath = getRelativePath($baseDir, __DIR__ . "/static");

// Get the route path
$routePath = substr($requestUri, strlen($scriptDir));
$routePath = "/" . ltrim($routePath, "/");

$router = new Router(function () {
    echo "404 Page not found.";
});
$router->addRoute("/", function () {
    header("Location: home");
});
$router->addRoute("/home", function () {
    HomePageController::execute();
});
$router->addRoute("/register", function () {
    RegisterPageController::execute();
});
$router->addRoute("/login", function () {
    LoginPageController::execute();
});
$router->addRoute("/logout", function () {
    LogoutPageController::execute();
});
$router->addRoute("/panel", function () {
    PanelPageController::execute();
});
$router->addRoute("/forum", function () {
    ForumPageController::execute();
});
$router->addRoute("/thread", function () {
    ThreadPageController::execute();
});
$router->addRoute("/comment", function () {
    CommentPageController::execute();
});
$router->addRoute("/privacy-policy", function () {
    PrivacyPolicyController::execute();
});
$router->addRoute("/tos", function () {
    TOSController::execute();
});
$router->addRoute("/rules", function () {
    RulesController::execute();
});
$router->addRoute("/settings", function () {
    SettingsController::execute();
});
$router->addRoute("/users", function () {
    UsersController::execute();
});

// Modify static file handler to use correct base path
$router->addRoute("/static/(.*)", function ($path) {
    $fullPath = __DIR__ . "/static/" . $path;
    if (file_exists($fullPath)) {
        $extension = pathinfo($fullPath, PATHINFO_EXTENSION);
        $contentTypes = [
            "css" => "text/css",
            "js" => "application/javascript",
            "woff2" => "font/woff2",
            "ttf" => "font/ttf",
            "png" => "image/png",
            "jpg" => "image/jpg",
        ];

        if (isset($contentTypes[$extension])) {
            header("Content-Type: " . $contentTypes[$extension]);
            header("Cache-Control: public, max-age=31536000");
            readfile($fullPath);
            exit();
        }
    }
    http_response_code(404);
    echo "File not found";
    exit();
});
