<?php
require "vendor/autoload.php";

class CommentView
{
    /**
     * @param array $comment
     * @param array $errors
     */
    public static function renderEditForm($comment, $errors = []): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        include_once "header.php";
        ?>
        <link rel="stylesheet" href="static/css/thread.css">
        <main>
            <div class="new-thread-container">
                <h2>Edit Comment</h2>

                <?php if (!empty($errors)): ?>
                    <div class="error-messages">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" class="comment-form">
                    <textarea name="comment" required><?php echo htmlspecialchars(
                        $comment["body"]
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
                    <button type="submit">Update Comment</button>
                </form>
            </div>
        </main>
        <?php include_once "footer.php";
    }
}
