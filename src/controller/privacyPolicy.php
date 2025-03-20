<?php
include_once __DIR__ . "/../view/privacyPolicy.php";

class PrivacyPolicyController
{
    public static function execute(): void
    {
        $controller = new self();
        $controller->handleRequest();
    }

    private function handleRequest(): void
    {
        PrivacyPolicyView::render();
    }
}
