<?php
include_once __DIR__ . "/UserORM.php";

class UsersModel
{
    private $userORM;

    public function __construct()
    {
        $this->userORM = new UserORM();
    }

    public function getAllUsers(): array
    {
        try {
            return $this->userORM->findAll();
        } catch (Exception $e) {
            error_log("Error getting users: " . $e->getMessage());
            throw $e;
        }
    }
}
