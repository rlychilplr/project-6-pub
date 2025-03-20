<?php
// this would usually be set by the webhosting application (nginx, apachy, caddy, mongoose, etc.)
header("X-XSS-Protection: 1; mode=block");
header(
    "Content-Security-Policy: default-src 'self'; style-src 'self' 'unsafe-inline'; script-src 'self' 'unsafe-inline'; img-src * data:;"
    // `*` is nessesairy since i want to allow people to include images from off-site sources
    // for some reason `*` doesn't include `data`???, at least the pfp's don't work without it'
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noot Forums</title>
    <link rel="stylesheet" href="static/css/all.min.css"> <!-- font awesome -->
    <link rel="stylesheet" href="static/css/header.css">
    <link rel="icon" href="static/img/favicon.png">
    <script src="static/js/auth.js"></script>
</head>
<body>
    <nav class="nav-main">
        <div class="nav-container">
            <div class="nav-section">
                <a href="home" class="nav-home">
                    <i class="fa-solid fa-house nav-icon"></i>
                </a>
                <a href="users" class="nav-users">
                    <i class="fa-solid fa-user nav-icon"></i>
                    Users
                </a>
            </div>
            <div class="nav-section">
                <?php if (isset($_SESSION["user_id"])) {
                    // Show admin panel link only to admins
                    if (
                        isset($_SESSION["rank"]) &&
                        $_SESSION["rank"] === "admin"
                    ) { ?>
                        <a href="panel" class="nav-panel">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                            Admin Panel
                        </a>
                        <?php } ?>
                    <a href="settings" class="nav-settings">
                        <i class="fa-solid fa-user-gear"></i>
                        Settings
                    </a>
                    <a href="logout" class="nav-logout">
                        <i class="fa-solid fa-sign-out-alt"></i>
                        Logout
                    </a>
                    <?php
                } else {
                     ?>
                    <a href="login" class="nav-login">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        Login
                    </a>
                    <a href="register" class="nav-register">
                        <i class="fa-solid fa-user-plus"></i>
                        Register
                    </a>
                    <?php
                } ?>
            </div>
        </div>
        <div class="logo-container">
            <!-- im a software developer, not a graphics designer, leave me alone -->
            <img src="static/img/noot.png">
        </div>
        <div class="current-location">
            <?php
            require_once __DIR__ . "/../model/BreadcrumbHelper.php";

            $currentPath = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
            $currentQuery = parse_url($_SERVER["REQUEST_URI"], PHP_URL_QUERY);
            $queryParams = null; // stop complaining phpactor
            parse_str($currentQuery ?? "", $queryParams);

            $route = trim(
                substr($currentPath, strlen(dirname($_SERVER["SCRIPT_NAME"]))),
                "/"
            );
            $route = $route ?: "home";

            $breadcrumbs = BreadcrumbHelper::getBreadcrumbs(
                $route,
                $queryParams
            );

            foreach ($breadcrumbs as $index => $crumb) {
                if ($index > 0) {
                    echo ' <i class="fa-solid fa-angle-right"></i> ';
                }

                if ($crumb["url"]) {
                    echo '<a href="' .
                        $crumb["url"] .
                        '" class="breadcrumb-link">' .
                        htmlspecialchars($crumb["title"]) .
                        "</a>";
                } else {
                    echo '<span class="breadcrumb-current">' .
                        htmlspecialchars($crumb["title"]) .
                        "</span>";
                }
            }
            ?>
        </div>
    </nav>
