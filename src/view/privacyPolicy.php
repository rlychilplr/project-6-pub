<?php
class PrivacyPolicyView
{
    public static function render(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        include_once "header.php";
        ?>
        <link rel="stylesheet" href="static/css/privacyPolicy.css">
        <main>
            <div class="privacy-policy-container">
                <header class="header">
                    <h1 class="title">Privacy Policy</h1>
                </header>
                <section class="introduction">
                    <p class="description">Welcome to Noot Forums! Your privacy is important to us. This privacy policy outlines how we collect, use, and protect your information when you use our website.</p>
                </section>

                <section class="information-collection">
                    <h2 class="section-title">Information We Collect</h2>
                    <ul class="info-list">
                        <li class="info-item">Personal information you provide during registration, such as your email address and username.</li>
                        <li class="info-item">Content you post, including comments, discussions, and uploaded media.</li>
                        <li class="info-item">Automatically collected data, such as IP addresses, browser type, and device information.</li>
                    </ul>
                </section>

                <section class="information-use">
                    <h2 class="section-title">How We Use Your Information</h2>
                    <ul class="use-list">
                        <li class="use-item">To operate and maintain the forum.</li>
                        <li class="use-item">To respond to user inquiries and provide support.</li>
                        <li class="use-item">To monitor and analyze website performance and usage trends.</li>
                        <li class="use-item">To enforce our terms of service and community guidelines.</li>
                    </ul>
                </section>

                <section class="information-sharing">
                    <h2 class="section-title">Information Sharing</h2>
                    <p class="sharing-description">We do not sell or share your personal information with third parties except as necessary to operate the forum, comply with legal obligations, or protect our rights.</p>
                </section>

                <section class="cookies">
                    <h2 class="section-title">Cookies and Tracking</h2>
                    <p class="cookies-description">We may use cookies to enhance your experience on the site, these cookies are purely functional and not used for tracking. You can manage your cookie preferences through your browser settings.</p>
                </section>

                <section class="data-security">
                    <h2 class="section-title">Data Security</h2>
                    <p class="security-description">We implement reasonable security measures to protect your information from unauthorized access, alteration, disclosure, or destruction.</p>
                </section>

                <section class="user-rights">
                    <h2 class="section-title">Your Rights</h2>
                    <ul class="rights-list">
                        <li class="rights-item">Access, update, or delete your personal information.</li>
                        <li class="rights-item">Opt-out of email communications.</li>
                        <li class="rights-item">Request clarification on how your data is used.</li>
                    </ul>
                </section>

                <section class="policy-changes">
                    <h2 class="section-title">Policy Changes</h2>
                    <p class="changes-description">We may update this policy from time to time. Changes will be posted on this page, and significant updates will be communicated to registered users.</p>
                </section>
            </div>
        </main>
        <?php include_once "footer.php";
    }
}
