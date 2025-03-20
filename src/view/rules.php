<?php
class RulesView
{
    public static function render(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        include_once "header.php";
        ?>
        <link rel="stylesheet" href="static/css/rules.css">
        <main>
            <section class="forum-rules">
                <header class="header">
                    <h1 class="title">Forum Rules</h1>
                </header>

                <section class="general-rules">
                    <h2 class="section-title">General Rules</h2>
                    <ul class="rules-list">
                        <li class="rules-item">Be respectful to other members. No harassment or bullying is allowed.</li>
                        <li class="rules-item">Use appropriate language and avoid offensive or inflammatory remarks.</li>
                        <li class="rules-item">Do not post spam, advertisements, or self-promotional content.</li>
                    </ul>
                </section>

                <section class="content-rules">
                    <h2 class="section-title">Content Posting Guidelines</h2>
                    <ul class="guidelines-list">
                        <li class="guidelines-item">Ensure content is relevant to the forum's purpose.</li>
                        <li class="guidelines-item">Avoid posting duplicate threads or off-topic discussions.</li>
                        <li class="guidelines-item">Properly credit sources when sharing third-party content.</li>
                    </ul>
                </section>

                <section class="reporting">
                    <h2 class="section-title">Reporting Issues</h2>
                    <p class="reporting-description">If you encounter rule violations or inappropriate content, please report it to the moderators.</p>
                </section>
            </section>
        </main>
        <?php include_once "footer.php";
    }
}
