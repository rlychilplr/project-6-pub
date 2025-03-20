<?php

include_once __DIR__ . "/UserORM.php";

class RegisterModel
{
    private $orm;

    public function __construct()
    {
        $this->orm = new UserORM();
    }

    /**
     * @param string $username
     * @param string $email
     * @param string $password
     */
    public function registerUser($username, $email, $password): bool
    {
        // i was planning on using argon2 but unfortunately that hashing algorith isn't supported by xampp or my local php install D:. so bcrypt it is
        try {
            /**
             * no additional salt since bcrypt automatically includes a salt https://en.wikipedia.org/wiki/Bcrypt
             *  here is what a 'double' salt inplementation would look like:
             * ```
             *  $salt = bin2hex(random_bytes(32));
             *  $cost = 15;
             *
             *  $hashedPassword = password_hash($password . $salt, PASSWORD_BCRYPT, [
             *      "cost" => $cost,
             *  ]);
             * ```
             */
            $cost = 15; // can't be higher then 31 with bcrypt, tried 20 but that slowed things down to a crawl (takes multiple seconds to generate hash)
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT, [
                "cost" => $cost,
            ]);

            $userData = [
                "username" => $username,
                "email" => $email,
                "password" => $hashedPassword,
                "rank" => "user",
            ];

            $this->orm->save($userData);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param string $username
     * @param string $email
     * @param string $password
     * @param string $confirmPassword
     */
    public function validateInput(
        $username,
        $email,
        $password,
        $confirmPassword
    ): ?array {
        $errors = [];

        if (strlen($username) < 3 || strlen($username) > 50) {
            $errors[] = "Username must be between 3 and 50 characters";
        }

        $patern =
            '/^[a-zA-Z0-9!@#$%^&*()_+\-={}:<>?,.\/;\[\]]+(?: [a-zA-Z0-9!@#$%^&*()_+\-={}:<>?,.\/;\[\]]+)*$/';
        if (!preg_match($patern, $username)) {
            $errors[] =
                "Username contains invalid characters, has trailing spaces, or starts/ends with a space.";
        }
        unset($patern);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }

        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long";
        }

        if ($password !== $confirmPassword) {
            $errors[] = "Passwords do not match";
        }

        return $errors;
    }
}
