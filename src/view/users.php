<?php
class UsersView
{
    /**
     * @param array $users
     */
    public static function render($users = []): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        include_once "header.php";
        ?>
        <link rel="stylesheet" href="static/css/users.css"> <!-- Fixed missing quotation mark here -->

        <main class="users-container">
            <div class="users-header">
                <h2 class="users-title"><i class="fa-solid fa-users"></i> Users</h2>
            </div>

            <?php if (empty($users)): ?>
                <div class="no-users">No users found.</div>
            <?php else: ?>
                <table class="users-list">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Rank</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars(
                                    $user["username"]
                                ); ?></td>
                                <td>
                                    <span class="user-rank rank-<?php echo strtolower(
                                        $user["rank"]
                                    ); ?>">
                                        <?php echo htmlspecialchars(
                                            ucfirst($user["rank"])
                                        ); ?>
                                    </span>
                                </td>
                                <td><?php echo date(
                                    "F j, Y",
                                    strtotime($user["creation_date"])
                                ); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </main>
        <?php include_once "footer.php";
    }
}
