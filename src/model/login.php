<?php

include_once __DIR__ . "/UserORM.php";

class LoginModel
{
    private $orm;
    private const TOKEN_EXPIRY_DAYS = 30;

    public function __construct()
    {
        $this->orm = new UserORM();
    }

    /**
     * @param string $username
     * @param string $password
     * php doesn't know what varchar is??
     */
    public function validateLogin($username, $password): array|bool
    {
        try {
            $user = $this->orm->findByUsername($username);
            if ($user && password_verify($password, $user["password"])) {
                $lastLoginTime = $this->orm->save(
                    [
                        "last_login" => date("Y-m-d H:i:s"),
                    ],
                    $user["id_user"]
                );

                return [
                    "id_user" => $user["id_user"],
                    "username" => $user["username"],
                    "rank" => $user["rank"],
                ];
            }
            return false;
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * @param int $userId
     */
    public function createRememberToken($userId): ?string
    {
        try {
            // adding these perameters to `uniqid` is faster according to this comment on php.net https://www.php.net/manual/en/function.uniqid.php#75201
            $token = md5(uniqid("", true));
            $expiry = date(
                "Y-m-d H:i:s",
                strtotime("+" . self::TOKEN_EXPIRY_DAYS . " days")
            );

            $result = $this->orm->save(
                [
                    "session_token" => $token,
                    "token_expires" => $expiry,
                ],
                $userId
            );

            error_log("Save result: " . print_r($result, true));

            return $token;
        } catch (Exception $e) {
            error_log("Error creating remember token: " . $e->getMessage());
            return null;
        }
    }

    /**
     * @param string $token
     */
    public function validateRememberToken($token): ?array
    {
        try {
            $users = $this->orm->findAll();
            foreach ($users as $user) {
                if ($user["session_token"] === $token) {
                    if (strtotime($user["token_expires"]) > time()) {
                        return [
                            "id_user" => $user["id_user"],
                            "username" => $user["username"],
                            "rank" => $user["rank"],
                        ];
                    }
                    $this->clearRememberToken($user["id_user"]);
                    return null;
                }
            }
            return null;
        } catch (Exception $e) {
            error_log("Error validating remember token: " . $e->getMessage());
            return null;
        }
    }

    /**
     * @param int $userId
     */
    public function clearRememberToken($userId): void
    {
        try {
            $this->orm->save(
                [
                    "session_token" => null,
                    "token_expires" => null,
                ],
                $userId
            );
        } catch (Exception $e) {
            error_log("Error clearing remember token: " . $e->getMessage());
        }
    }
}
