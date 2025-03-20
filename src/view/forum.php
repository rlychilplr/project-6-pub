<?php
class ForumView
{
    /**
     * @param array $forum
     * @param array $threads
     */
    public static function render($forum, $threads = []): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        include_once "header.php";
        ?>
        <link rel="stylesheet" href="static/css/forum.css">
        <main>
            <div class="forum-header">
                <h2><?php echo $forum[
                    "icon"
                ]; ?> <?php echo htmlspecialchars($forum["title"]); ?></h2>
                <p><?php echo htmlspecialchars($forum["description"]); ?></p>
            </div>

            <?php if (isset($_SESSION["user_id"])): ?>
                <div class="new-thread-button">
                    <a href="thread?forum=<?php echo $forum[
                        "id_forum"
                    ]; ?>" class="button">
                        <i class="fa-solid fa-plus"></i> New Thread
                    </a>
                </div>
            <?php endif; ?>

            <table class="threads">
                <thead>
                    <tr>
                        <th class="thread-title">Thread</th>
                        <th class="thread-author">Author</th>
                        <th class="thread-stats">Replies</th>
                        <th class="thread-last-post">Last Post</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($threads)): ?>
                        <tr>
                            <td colspan="4" class="no-threads">
                                No threads have been created yet.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($threads as $thread): ?>
                            <tr>
                                <td class="thread-title">
                                    <a href="thread?id=<?php echo $thread[
                                        "id_post"
                                    ]; ?>">
                                        <?php echo htmlspecialchars(
                                            $thread["title"]
                                        ); ?>
                                    </a>
                                </td>
                                <td class="thread-author">
                                    <?php echo htmlspecialchars(
                                        $thread["username"]
                                    ); ?>
                                </td>
                                <td class="thread-stats">
                                    <?php echo htmlspecialchars(
                                        $thread["comment_count"]
                                    ); ?>
                                </td>
                                <td class="thread-last-post">
                                    <?php echo date(
                                        "M j, Y g:i a",
                                        strtotime($thread["last_activity"])
                                    ); ?>
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
