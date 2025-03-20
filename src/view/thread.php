<?php
require "vendor/autoload.php";

class ThreadView
{
    private static $parsedown;

    private static function getParsedown(): Parsedown
    {
        if (!self::$parsedown) {
            self::$parsedown = new Parsedown();
            self::$parsedown->setSafeMode(true); // Enable safe mode
        }
        return self::$parsedown;
    }

    /**
     * @param array $thread
     * @param array|null $comments
     */
    public static function render($thread, $comments = []): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        include_once "header.php";
        ?>
        <link rel="stylesheet" href="static/css/thread.css">
        <main>
            <div class="thread-container">
                <div class="thread-post">
                    <div class="post-author">
                        <div class="author-avatar">
                            <?php if (
                                isset($thread["profile_picture"]) &&
                                $thread["profile_picture_type"]
                            ): ?>
                            <img src="data:<?php echo $thread[
                                "profile_picture_type"
                            ]; ?>;base64,<?php echo $thread[
    "profile_picture"
]; ?>"
                                 alt="<?php echo htmlspecialchars(
                                     $thread["username"]
                                 ); ?>'s profile picture">
                            <?php else: ?>
                                <div class="default-avatar">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="author-name"><?php echo htmlspecialchars(
                            $thread["username"]
                        ); ?></div>
                        <div class="post-date"><?php echo date(
                            "M j, Y g:i a",
                            strtotime($thread["creation_date"])
                        ); ?></div>
                        <div class="last-login">Last seen: <?php echo $thread[
                            "last_login"
                        ]
                            ? date(
                                "M j, Y g:i a",
                                strtotime($thread["last_login"])
                            )
                            : "Never"; ?></div>
                    </div>
                    <div class="post-content">
                        <div class="post-header">
                            <h2 class="post-title"><?php echo htmlspecialchars(
                                $thread["title"]
                            ); ?>
                            <?php if (!empty($thread["edited_at"])): ?>
                                <span class="edited-indicator" title="Last edited: <?php echo date(
                                    "M j, Y g:i a",
                                    strtotime($thread["edited_at"])
                                ); ?>">
                                    <i class="fa-solid fa-pen-to-square"></i> edited
                                </span>
                                <?php endif; ?>
                            </h2>
                            <?php if (
                                isset($_SESSION["user_id"]) &&
                                $_SESSION["user_id"] == $thread["id_user"]
                            ): ?>
                                <div class="post-actions">
                                    <a href="thread?id=<?php echo $thread[
                                        "id_post"
                                    ]; ?>&action=edit" class="edit-button">
                                        <i class="fa-solid fa-edit"></i>
                                    </a>
                                    <form method="POST" action="thread?id=<?php echo $thread[
                                        "id_post"
                                    ]; ?>&action=delete"
                                          class="delete-form" onsubmit="return confirm('Are you sure you want to delete this thread?');">
                                        <button type="submit" class="delete-button">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="post-body">
                            <?php echo self::getParsedown()->text(
                                $thread["body"]
                            ); ?>
                        </div>
                    </div>
                </div>

                <div class="comments-section">
                    <?php if (!empty($comments)): ?>
                        <?php foreach ($comments as $comment): ?>
                        <div class="comment">
                            <div class="comment-author">
                                <div class="author-avatar">
                                    <?php if (
                                        isset($comment["profile_picture"]) &&
                                        $comment["profile_picture"]
                                    ): ?>
                                        <img src="data:<?php echo $comment[
                                            "profile_picture_type"
                                        ] ??
                                            "image/jpeg"; ?>;base64,<?php echo $comment[
    "profile_picture"
]; ?>"
                                             alt="<?php echo htmlspecialchars(
                                                 $comment["username"]
                                             ); ?>'s profile picture">
                                    <?php else: ?>
                                        <div class="default-avatar">
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="author-name"><?php echo htmlspecialchars(
                                    $comment["username"]
                                ); ?></div>
                                <div class="comment-date"><?php echo date(
                                    "M j, Y g:i a",
                                    strtotime($comment["creation_date"])
                                ); ?></div>
                                <div class="last-login">Last seen: <?php echo $comment[
                                    "last_login"
                                ]
                                    ? date(
                                        "M j, Y g:i a",
                                        strtotime($comment["last_login"])
                                    )
                                    : "Never"; ?></div>
                            </div>
                            <div class="comment-main">
                                <div class="comment-header">
                                    <?php if (
                                        isset($_SESSION["user_id"]) &&
                                        $_SESSION["user_id"] ==
                                            $comment["id_user"]
                                    ): ?>
                                    <h2><?php if (
                                        !empty($comment["edited_at"])
                                    ): ?>
                                        <div class="edited-indicator" title="Last edited: <?php echo date(
                                            "M j, Y g:i a",
                                            strtotime($comment["edited_at"])
                                        ); ?>">
                                            <i class="fa-solid fa-pen-to-square"></i> edited
                                        </div>
                                    <?php endif; ?>
                                    </h2>
                                        <div class="comment-actions">
                                            <a href="comment?id=<?php echo $comment[
                                                "id_comment"
                                            ]; ?>&action=edit" class="edit-button">
                                                <i class="fa-solid fa-edit"></i>
                                            </a>
                                            <form method="POST" action="comment?id=<?php echo $comment[
                                                "id_comment"
                                            ]; ?>&action=delete"
                                                  class="delete-form" onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                                <button type="submit" class="delete-button">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="comment-content">
                                    <?php echo self::getParsedown()->text(
                                        $comment["body"]
                                    ); ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION["user_id"])): ?>
                        <form method="POST" action="comment?thread=<?php echo $thread[
                            "id_post"
                        ]; ?>" class="comment-form">
                            <textarea name="comment" placeholder="Write a comment... (Markdown supported)" required></textarea>
                            <div class="markdown-help">
                                <p>Markdown formatting:</p>
                                <ul>
                                    <li>Please use 2 enters to create a new row, 1 enter wil continue on the same line</li>
                                    <li>**bold text**</li>
                                    <li>*italic text*</li>
                                    <li>[example](https://www.example.com)</li>
                                    <li># Heading 1</li>
                                    <li>## Heading 2</li>
                                    <li>- List item</li>
                                    <li>1. Numbered list</li>
                                    <li>> Quote</li>
                                    <li>`code`</li>
                                    <li>and more</li>
                                </ul>
                            </div>
                            <button type="submit">Post Comment</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </main>
        <?php
    }

    /**
     * @param array $forum
     * @param ?array $errors
     */
    public static function renderNewThreadForm($forum, $errors = []): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        include_once "header.php";
        ?>
        <link rel="stylesheet" href="static/css/thread.css">
        <main>
            <div class="new-thread-container">
                <h2>New Thread in <?php echo htmlspecialchars(
                    $forum["title"]
                ); ?></h2>

                <?php if (!empty($errors)): ?>
                    <div class="error-messages">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" class="new-thread-form">
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" id="title" name="title" required>
                    </div>

                    <div class="form-group">
                        <label for="body">Content: (Markdown supported)</label>
                        <textarea id="body" name="body" required></textarea>
                        <div class="markdown-help">
                            <p>Markdown formatting:</p>
                            <ul>
                                <li>Please use 2 enters to create a new row, 1 enter wil continue on the same line</li>
                                <li>**bold text**</li>
                                <li>*italic text*</li>
                                <li>[example](https://www.example.com)</li>
                                <li># Heading 1</li>
                                <li>## Heading 2</li>
                                <li>- List item</li>
                                <li>1. Numbered list</li>
                                <li>> Quote</li>
                                <li>`code`</li>
                                <li>and more</li>
                            </ul>
                        </div>
                    </div>

                    <button type="submit">Create Thread</button>
                </form>
            </div>
        </main>
        <?php
    }

    /**
     * @param array $thread
     * @param array $errors
     */
    public static function renderEditForm($thread, $errors = []): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        include_once "header.php";
        ?>
        <link rel="stylesheet" href="static/css/thread.css">
        <main>
            <div class="new-thread-container">
                <h2>Edit Thread</h2>

                <?php if (!empty($errors)): ?>
                    <div class="error-messages">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" class="new-thread-form">
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars(
                            $thread["title"]
                        ); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="body">Content: (Markdown supported)</label>
                        <textarea id="body" name="body" required><?php echo htmlspecialchars(
                            $thread["body"]
                        ); ?></textarea>
                        <div class="markdown-help">
                            <p>Markdown formatting:</p>
                            <ul>
                                <li>Please use 2 enters to create a new row, 1 enter wil continue on the same line</li>
                                <li>**bold text**</li>
                                <li>*italic text*</li>
                                <li>[example](https://www.example.com)</li>
                                <li># Heading 1</li>
                                <li>## Heading 2</li>
                                <li>- List item</li>
                                <li>1. Numbered list</li>
                                <li>> Quote</li>
                                <li>`code`</li>
                                <li>and more</li>
                            </ul>
                        </div>
                    </div>

                    <button type="submit">Update Thread</button>
                </form>
            </div>
        </main>
        <?php include_once "footer.php";
    }
}
