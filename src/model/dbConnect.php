<?php

require_once __DIR__ . "/prep.php";

function getDatabaseConnection()
{
    static $pdo = null;

    if ($pdo === null) {
        try {
            $host = "localhost";
            $dbname = "forum";
            $username = "root";
            $password = "";

            $pdo = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8",
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );

            $prep = new Prep($pdo);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    return $pdo;
}

// Create the global connection
$pdo = getDatabaseConnection();
