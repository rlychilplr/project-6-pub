<?php
class TOSView
{
    public static function render(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        include_once "header.php";
        ?>
        <link rel="stylesheet" href="static/css/tos.css">
        <main>
            <section class="terms-of-service">
                <header class="header">
                    <h1 class="title">Terms of Service</h1>
                </header>

                <section class="introduction">
                    <p class="description">Welcome to Noot Forums. By using this website, you agree to comply with and be bound by the following terms and conditions.</p>
                </section>

                <section class="user-responsibilities">
                    <h2 class="section-title">User Responsibilities</h2>
                    <ul class="responsibilities-list">
                        <li class="responsibilities-item">You must provide accurate registration information and keep your account secure.</li>
                        <li class="responsibilities-item">You are responsible for all activities under your account.</li>
                        <li class="responsibilities-item">Do not post illegal, offensive, or inappropriate content.</li>
                    </ul>
                </section>

                <section class="account-termination">
                    <h2 class="section-title">Account Termination</h2>
                    <p class="termination-description">We reserve the right to suspend or terminate accounts that violate these terms or community guidelines.</p>
                </section>

                <section class="content-ownership">
                    <h2 class="section-title">Content Ownership</h2>
                    <p class="ownership-description">You retain ownership of any content you post, but grant us a license to display, distribute, and reproduce it within the forum.</p>
                </section>

                <section class="modifications">
                    <h2 class="section-title">Modifications to Terms</h2>
                    <p class="modifications-description">We may update these terms periodically. Continued use of the forum constitutes acceptance of these changes.</p>
                </section>
            </section>
        </main>
        <?php include_once "footer.php";
    }
}
