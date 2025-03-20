<?php

include_once __DIR__ . "/ForumORM.php";
include_once __DIR__ . "/UserORM.php";

class PanelModel
{
    private $forumORM;

    public function __construct()
    {
        $this->forumORM = new ForumORM();
    }

    /**
     * @param string $title
     * @param string $description
     * @param string $icon
     */
    public function createForum($title, $description, $icon): bool
    {
        try {
            $errors = $this->validateForumInput($title, $description, $icon);

            if (empty($errors)) {
                $forumData = [
                    "title" => $title,
                    "description" => $description,
                    "icon" => $icon,
                ];

                $this->forumORM->save($forumData);
                return true;
            }

            return false;
        } catch (Exception $e) {
            error_log("Error creating forum: " . $e->getMessage());
            return false;
        }
    }

    /**
     * @param string $title
     * @param string $description
     * @param string $icon
     */
    public function validateForumInput($title, $description, $icon): ?array
    {
        $errors = [];

        if (
            trim($title) === "" ||
            trim($description) === "" ||
            trim($icon) === ""
        ) {
            $errors[] = "All fields are required";
        }

        if (
            !preg_match(
                '/<i class=[\'"]fa-(?:solid|regular|brands) fa-[\w-]+[\'"]><\/i>/',
                $icon
            )
        ) {
            $errors[] = "Invalid icon format. Must be a Font Awesome icon";
        }

        return $errors;
    }

    /**
     * @param int $userId
     * @param string $newRank
     */
    public function updateUserRank($userId, $newRank): bool
    {
        try {
            $userORM = new UserORM();
            if (!in_array($newRank, ["user", "moderator", "admin"])) {
                return false;
            }
            return $userORM->updateUserRank($userId, $newRank);
        } catch (Exception $e) {
            error_log("Error updating user rank: " . $e->getMessage());
            return false;
        }
    }

    public function getAllUsers(): array
    {
        try {
            $userORM = new UserORM();
            return $userORM->findAll();
        } catch (Exception $e) {
            error_log("Error getting users: " . $e->getMessage());
            return [];
        }
    }

    /**
     * @param int $forumId
     */
    public function deleteForum($forumId): bool
    {
        try {
            return $this->forumORM->delete($forumId);
        } catch (Exception $e) {
            error_log("Error deleting forum: " . $e->getMessage());
            return false;
        }
    }

    public function getAllForums(): ?array
    {
        try {
            return $this->forumORM->findAll();
        } catch (Exception $e) {
            error_log("Error getting forums: " . $e->getMessage());
            return [];
        }
    }

    /**
     * @param string $username
     */
    public function deleteUser($username): ?array
    {
        try {
            $userORM = new UserORM();
            $user = $userORM->findByUsername($username);

            if (!$user) {
                return [false, "User not found"];
            }

            if ($user["rank"] === "admin") {
                return [false, "Cannot delete an administrator"];
            }

            if ($userORM->deleteUserByUsername($username)) {
                return [true, "User successfully deleted"];
            }
            return [false, "Error deleting user"];
        } catch (Exception $e) {
            error_log("Error deleting user: " . $e->getMessage());
            return [false, "Error deleting user"];
        }
    }
}
