<?php
class PanelView
{
    /**
     * @param array $errors
     * @param array $users
     * @param array $forums
     * @param string $message
     */
    public static function render(
        $errors = [],
        $users = [],
        $forums = [],
        $message = ""
    ): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        include_once "header.php";
        ?>
        <link rel="stylesheet" href="static/css/panel.css">
        <main>
            <div class="new-forum foldable">
                <span class="title" onclick="toggleContent(this)">create a new forum</span>
                <div class="content">
                    <?php if (!empty($errors)): ?>
                        <div class="error-messages">
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo htmlspecialchars(
                                        $error
                                    ); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="form-section">
                            <label for="forum-name">Forum name:  </label>
                            <input type="text" id="forum-name" name="forum-name" placeholder="Announcements" required>
                        </div>
                        <div class="form-section">
                            <label for="forum-description">Forum description:  </label>
                            <input type="text" id="forum-description" name="forum-description" placeholder="Check here for news and updates regarding the forums." required>
                        </div>
                        <div class="form-section">
                            <label for="forum-icon">Forum icon:  </label>
                            <input type="text" id="forum-icon" name="forum-icon" placeholder="<i class='fa-solid fa-bullhorn'></i>" required>
                        </div>
                        <div class="form-section">
                            <button type="submit">Create</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="manage-forums foldable">
                <span class="title" onclick="toggleContent(this)">remove forums</span>
                <div class="content">
                    <table class="forums-table">
                        <thead>
                            <tr>
                                <th>Icon</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($forums as $forum): ?>
                                <tr>
                                    <td><?php echo $forum["icon"]; ?></td>
                                    <td><?php echo htmlspecialchars(
                                        $forum["title"]
                                    ); ?></td>
                                    <td><?php echo htmlspecialchars(
                                        $forum["description"]
                                    ); ?></td>
                                    <td>
                                        <form method="POST" class="forum-actions" onsubmit="return confirm('Are you sure you want to delete this forum? This action cannot be undone.');">
                                            <input type="hidden" name="forum_id" value="<?php echo $forum[
                                                "id_forum"
                                            ]; ?>">
                                            <button type="submit" name="delete_forum" class="delete-button">
                                                <i class="fa-solid fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="manage-users foldable">
                <span class="title" onclick="toggleContent(this)">manage users ranks</span>
                <div class="content">
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Current Rank</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars(
                                        $user["username"]
                                    ); ?></td>
                                    <td><?php echo htmlspecialchars(
                                        $user["rank"]
                                    ); ?></td>
                                    <td>
                                        <form method="POST" class="rank-form">
                                            <input type="hidden" name="user_id" value="<?php echo $user[
                                                "id_user"
                                            ]; ?>">
                                            <select name="new_rank">
                                                <option value="user" <?php echo $user[
                                                    "rank"
                                                ] === "user"
                                                    ? "selected"
                                                    : ""; ?>>User</option>
                                                <option value="moderator" <?php echo $user[
                                                    "rank"
                                                ] === "moderator"
                                                    ? "selected"
                                                    : ""; ?>>Moderator</option>
                                                <option value="admin" <?php echo $user[
                                                    "rank"
                                                ] === "admin"
                                                    ? "selected"
                                                    : ""; ?>>Admin</option>
                                            </select>
                                            <button type="submit" name="update_rank">Update</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="delete-user foldable">
                <span class="title" onclick="toggleContent(this)">ban/delete user</span>
                <div class="content">
                    <?php if ($message): ?>
                        <div class="message <?php echo strpos(
                            $message,
                            "Error"
                        ) !== false || strpos($message, "Cannot") !== false
                            ? "error"
                            : "success"; ?>">
                            <?php echo htmlspecialchars($message); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="delete-user-form" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                        <div class="form-section">
                            <label for="username">Username:</label>
                            <input type="text" id="username" name="username" required placeholder="Enter username to delete">
                            <button type="submit" name="delete_user" class="delete-button">
                                <i class="fa-solid fa-ban"></i> Ban/Delete User
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <script src="static/js/panel.js"></script>
        </main>
        <?php include_once "footer.php";
    }
}
