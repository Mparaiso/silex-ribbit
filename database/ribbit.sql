CREATE TABLE IF NOT EXISTS users(
    `id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL ,
    `username`  varchar(255) UNIQUE KEY NOT NULL,
    `email`  varchar(255) UNIQUE NOT NULL,
    `name` varchar(255) UNIQUE NOT NULL,
    `password_digest` TINYTEXT NOT NULL,
    `avatar_url` TINYTEXT NOT NULL,
    `salt` TINYTEXT NOT NULL,
    `role_id` int(11) NOT NULL,
    `created_at` DATETIME  NOT NULL ,   
    `updated_at` DATETIME  NOT NULL ,
);

# @note @sql default now
CREATE TABLE IF NOT EXISTS roles(
    `id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `title` varchar(255) UNIQUE KEY NOT NULL,
    `ref` int(4) NOT NULL,
    `created_at` DATETIME  NOT NULL ,   
    `updated_at` DATETIME  NOT NULL 
);