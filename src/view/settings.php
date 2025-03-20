<?php
class SettingsView
{
    /**
     * @param ?array $errors
     */
    public static function render($errors = []): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        include_once "header.php";
        ?>
        <link rel="stylesheet" href="static/css/settings.css">
        <main>
            <div class="settings-container">
                <h2>Account Settings</h2>

                <?php if (!empty($errors)): ?>
                    <div class="error-messages">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data" class="settings-form">
                    <div class="profile-section">
                        <div class="current-profile">
                            <h3>Profile Picture</h3>
                            <?php if (
                                isset($_SESSION["profile_picture"]) &&
                                $_SESSION["profile_picture"]
                            ): ?>
                                <img src="data:<?php echo $_SESSION[
                                    "profile_picture_type"
                                ] ??
                                    "image/jpeg"; ?>;base64,<?php echo $_SESSION[
    "profile_picture"
]; ?>"
                                     alt="Current profile picture"
                                     class="profile-picture">
                            <?php else: ?>
                                <div class="no-profile-picture">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="upload-section">
                            <label for="profile_picture">Upload new profile picture:</label>
                            <input type="file"
                                   id="profile_picture"
                                   name="profile_picture"
                                   accept="image/jpeg,image/png,image/webp">
                            <p class="file-info">Maximum file size: 16MB. Supported formats: JPEG, PNG, WebP</p>
                        </div>
                        <button type="submit" name="update_profile" class="update-button">Update Profile Picture</button>
                    </div>
                </form>
            </div>
        </main>
        <?php include_once "footer.php";
    }
}
