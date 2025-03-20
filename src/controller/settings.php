<?php
include_once __DIR__ . "/../model/settings.php";
include_once __DIR__ . "/../view/settings.php";

class SettingsController
{
    private $model;

    public function __construct()
    {
        $this->model = new SettingsModel();
    }

    public static function execute(): void
    {
        $controller = new self();
        $controller->handleRequest();
    }

    private function handleRequest(): void
    {
        session_start();

        if (!isset($_SESSION["user_id"])) {
            header("Location: login");
            exit();
        }

        $errors = [];

        if (
            $_SERVER["REQUEST_METHOD"] === "POST" &&
            isset($_POST["update_profile"])
        ) {
            if (
                isset($_FILES["profile_picture"]) &&
                $_FILES["profile_picture"]["error"] === UPLOAD_ERR_OK
            ) {
                $file = $_FILES["profile_picture"];

                $allowed_types = ["image/jpeg", "image/png", "image/webp"];
                $max_size = 16 * 1024 * 1024; // 16MB, see also `.htaccess`

                /**
                 * Get actual MIME type from file content
                 * MIME type is the filetype indication inside the actual file, not the extension behind the filename
                 * i have encountered multiple files with incorrect file extensions on the web, these still work bc of MIME.
                 * Windows made an interesting? decision  to be reliant on file extensions, unix based OS's like linux, macos and bsd or not dependand on the correct file extension, rather they are just indicators for the user as to what kind of file you are looking at
                 * https://en.wikipedia.org/wiki/Media_type
                 */
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime_type = finfo_file($finfo, $file["tmp_name"]);
                finfo_close($finfo);

                if (!in_array($mime_type, $allowed_types)) {
                    $errors[] =
                        "Invalid file type. Please upload a JPEG, PNG, or WebP image.";
                } elseif ($file["size"] > $max_size) {
                    $errors[] = "File is too large. Maximum size is 16MB.";
                } else {
                    $image_data = file_get_contents($file["tmp_name"]);
                    if (
                        $this->model->updateProfilePicture(
                            $_SESSION["user_id"],
                            $image_data,
                            $mime_type
                        )
                    ) {
                        $_SESSION["profile_picture"] = base64_encode(
                            $image_data
                        );
                        $_SESSION["profile_picture_type"] = $mime_type;
                        header("Location: settings");
                        exit();
                    } else {
                        $errors[] = "Failed to update profile picture.";
                    }
                }
            }
        }

        SettingsView::render($errors);
    }
}
