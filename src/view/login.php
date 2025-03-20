<?php

class LoginView
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
        <link rel="stylesheet" href="static/css/login.css">
        <div class="login-container">
            <div class="login-card">
                <div class="login-header">
                    <h3>Login</h3>
                </div>
                <div class="login-body">
                    <?php if (!empty($errors)):
                        echo '<div class="error-messages"><ul>';
                        foreach ($errors as $error):
                            echo "<li>" . htmlspecialchars($error) . "</li>";
                        endforeach;
                        echo "</ul></div>";
                    endif; ?>

                    <form method="POST" action="" class="login-form" id="loginForm">
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <div class="input-with-icon">
                                <i class="fa-solid fa-user input-icon"></i>
                                <input type="text" id="username" name="username" placeholder="Username" required autofocus>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password">Password:</label>
                            <div class="input-with-icon">
                                <i class="fa-solid fa-lock input-icon"></i>
                                <input type="password" id="password" name="password" placeholder="Password" required>
                            </div>
                        </div>

                        <div class="form-group password-toggle">
                            <label class="checkbox-label">
                                <input type="checkbox" id="show-password">
                                <span>Show Password</span>
                            </label>
                        </div>
                        <script>
                          document.getElementById('show-password').addEventListener('change', function() {
                            const passwordField = document.getElementById('password');
                            passwordField.type = this.checked ? 'text' : 'password';
                          });
                        </script>

                        <div class="form-group remember-me">
                            <label class="checkbox-label">
                                <input type="checkbox" name="remember_me" id="remember_me">
                                <span>Stay logged in</span>
                            </label>
                        </div>

                        <button type="submit">Login</button>
                    </form>
                </div>
            </div>
        </div>
        <?php require_once __DIR__ . "/footer.php";
    }
}
