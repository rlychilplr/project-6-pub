<?php
include_once __DIR__ . "/UserORM.php";

class SettingsModel
{
    private $userORM;

    public function __construct()
    {
        $this->userORM = new UserORM();
    }

    /**
     * @param int $userId
     * @param string $imageData
     * blobs don't exist inside php
     * @param string $mimeType
     */
    public function updateProfilePicture($userId, $imageData, $mimeType): bool
    {
        try {
            return $this->userORM->save(
                [
                    "profile_picture" => $imageData,
                    "profile_picture_type" => $mimeType,
                ],
                $userId
            );
        } catch (Exception $e) {
            error_log("Error updating profile picture: " . $e->getMessage());
            return false;
        }
    }
}
