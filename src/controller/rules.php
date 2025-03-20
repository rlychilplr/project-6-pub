<?php
include_once __DIR__ . "/../view/rules.php";

class RulesController
{
    public static function execute(): void
    {
        $controller = new self();
        $controller->handleRequest();
    }

    private function handleRequest(): void
    {
        RulesView::render();
    }
}
