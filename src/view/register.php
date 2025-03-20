<?php

class RegisterView
{
    /**
     * @param array $errors
     */
    public static function render($errors = []): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        require_once __DIR__ . "/header.php";
        ?>
        <link rel="stylesheet" href="static/css/register.css">
        <div class="register-container">
            <div class="register-card">
                <div class="register-header">
                    <h3>Register</h3>
                </div>
                <div class="register-body">
                    <?php if (!empty($errors)):
                        echo '<div class="error-messages"><ul>';
                        foreach ($errors as $error):
                            echo "<li>" . htmlspecialchars($error) . "</li>";
                        endforeach;
                        echo "</ul></div>";
                    endif; ?>

                    <form method="POST" action="" class="register-form" id="registrationForm">
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <div class="input-with-icon">
                                <i class="fa-solid fa-user input-icon"></i>
                                <input type="text" id="username" name="username" placeholder="Username" required autofocus>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Email:</label>
                            <div class="input-with-icon">
                                <i class="fa-solid fa-envelope input-icon"></i>
                                <input type="email" id="email" name="email" placeholder="Email" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password">Password:</label>
                            <div class="input-with-icon">
                                <i class="fa-solid fa-lock input-icon"></i>
                                <input type="password" id="password" name="password" placeholder="Password" minlength="8" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Confirm Password:</label>
                            <div class="input-with-icon">
                                <i class="fa-solid fa-lock input-icon"></i>
                                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                            </div>
                        </div>

                        <button type="submit">Register</button>
                    </form>
                </div>
            </div>
        </div>
        <script src="static/js/register.js"></script>
        <?php
    }
}
