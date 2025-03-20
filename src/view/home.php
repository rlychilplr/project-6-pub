<?php
class HomeView
{
    /**
     * @param array $forums
     */
    public static function render($forums = []): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        include_once "header.php";
        ?>
        <link rel="stylesheet" href="static/css/home.css">
        <main>
            <table class="forums">
                <thead>
                    <tr>
                        <td colspan="5" class="forum-header">
                            <i class="fa-solid fa-house nav-icon"></i> General
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($forums)): ?>
                        <tr>
                            <td colspan="5" class="no-forums">
                                No forums available
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($forums as $forum): ?>
                            <tr>
                                <td class="table-icon">
                                    <?php echo $forum["icon"]; ?>
                                </td>
                                <td class="forum-info">
                                    <div class="forum-name">
                                        <a href="forum?id=<?php echo $forum[
                                            "id_forum"
                                        ]; ?>">
                                            <?php echo htmlspecialchars(
                                                $forum["title"]
                                            ); ?>
                                        </a>
                                    </div>
                                    <div class="forum-description">
                                        <?php echo htmlspecialchars(
                                            $forum["description"]
                                        ); ?>
                                    </div>
                                </td>
                                <td class="thread-count">
                                    <?php echo $forum["thread_count"]; ?>
                                    <br> threads
                                </td>
                                <td class="post-count">
                                    <?php echo $forum["post_count"]; ?>
                                    <br> comments
                                </td>
                                <td class="latest-post">
                                    <?php if ($forum["latest_post"]): ?>
                                        <a href="thread?id=<?php echo $forum[
                                            "latest_post"
                                        ]["id"]; ?>">
                                            <?php echo htmlspecialchars(
                                                $forum["latest_post"]["title"]
                                            ); ?>
                                        </a><br>
                                        <span class="post-info">
                                            by <?php echo htmlspecialchars(
                                                $forum["latest_post"]["author"]
                                            ); ?><br>
                                            <?php echo date(
                                                "M j, Y g:i a",
                                                strtotime(
                                                    $forum["latest_post"][
                                                        "date"
                                                    ]
                                                )
                                            ); ?>
                                        </span>
                                    <?php else: ?>
                                        No posts yet
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </main>

        <?php include_once "footer.php";
    }
}
