<?php

class Prep
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->checkAndCreateTables();
    }

    private function checkAndCreateTables(): void
    {
        $tables = ["Forum", "User", "Posts", "Comments"];
        $existingTables = array_map("strtolower", $this->getExistingTables());

        foreach ($tables as $table) {
            if (!in_array(strtolower($table), $existingTables)) {
                try {
                    $this->createTable($table);
                } catch (Exception $e) {
                    error_log("Error with table $table: " . $e->getMessage());
                }
            }
        }
    }

    private function getExistingTables(): ?array
    {
        try {
            $stmt = $this->pdo->query("SHOW TABLES");
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            error_log("Error getting existing tables: " . $e->getMessage());
            return [];
        }
    }

    /**
     * @param string $tableName
     */
    private function createTable($tableName): void
    {
        // First check if table exists to avoid errors
        $stmt = $this->pdo->prepare("SHOW TABLES LIKE ?");
        $stmt->execute([$tableName]);
        if ($stmt->rowCount() > 0) {
            return;
        }

        $sql = match ($tableName) {
            "Forum" => "CREATE TABLE `Forum` (
                `id_forum` int(11) NOT NULL AUTO_INCREMENT,
                `title` varchar(255) NOT NULL,
                `description` text NOT NULL,
                `icon` varchar(100) NOT NULL,
                PRIMARY KEY (`id_forum`)
            ) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;",

            "User" => "CREATE TABLE `User` (
                `id_user` int(11) NOT NULL AUTO_INCREMENT,
                `username` varchar(50) NOT NULL,
                `password` varchar(97) NOT NULL COMMENT 'use argon2
                nvm argon2 is not supported in xampp D:',
                `email` varchar(100) NOT NULL,
                `profile_picture` mediumblob DEFAULT NULL,
                `profile_picture_type` VARCHAR(30) DEFAULT NULL,
                `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
                `rank` enum('user','admin','moderator') NOT NULL,
                `last_login` datetime DEFAULT NULL,
                `session_token` varchar(255) DEFAULT NULL,
                `token_expires` datetime DEFAULT NULL,
                PRIMARY KEY (`id_user`),
                UNIQUE KEY `username` (`username`),
                UNIQUE KEY `email` (`email`),
                UNIQUE KEY `session_token` (`session_token`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;",

            "Posts" => "CREATE TABLE `Posts` (
                `id_post` int(11) NOT NULL AUTO_INCREMENT,
                `id_user` int(11) NOT NULL,
                `id_forum` int(11) NOT NULL,
                `title` varchar(255) NOT NULL,
                `body` text NOT NULL,
                `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
                `edited_at` datetime DEFAULT NULL,
                PRIMARY KEY (`id_post`),
                KEY `id_user` (`id_user`),
                KEY `id_forum` (`id_forum`),
                CONSTRAINT `Posts_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `User` (`id_user`) ON DELETE CASCADE,
                CONSTRAINT `Posts_ibfk_2` FOREIGN KEY (`id_forum`) REFERENCES `Forum` (`id_forum`) ON DELETE CASCADE
            ) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;",

            "Comments" => "CREATE TABLE `Comments` (
                `id_comment` int(11) NOT NULL AUTO_INCREMENT,
                `id_post` int(11) NOT NULL,
                `id_user` int(11) NOT NULL,
                `body` text NOT NULL,
                `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
                `edited_at` datetime DEFAULT NULL,
                PRIMARY KEY (`id_comment`),
                KEY `id_post` (`id_post`),
                KEY `id_user` (`id_user`),
                CONSTRAINT `Comments_ibfk_1` FOREIGN KEY (`id_post`) REFERENCES `Posts` (`id_post`) ON DELETE CASCADE,
                CONSTRAINT `Comments_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `User` (`id_user`) ON DELETE CASCADE
            ) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;",

            default => throw new Exception("Unknown table: $tableName"),
        };

        $this->pdo->exec($sql);
    }
}
