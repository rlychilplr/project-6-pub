<?php
include_once __DIR__ . "/../view/tos.php";

class TOSController
{
    public static function execute(): void
    {
        $controller = new self();
        $controller->handleRequest();
    }

    private function handleRequest(): void
    {
        TOSView::render();
    }
}
