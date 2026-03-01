-- ============================================================
-- Photogram Database Schema
-- Auto-generated: 2026-03-01
-- Run this file once to bootstrap the database.
-- All statements use IF NOT EXISTS so they are safe to re-run.
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------
-- Table: auth
-- Stores authentication credentials (username, email, password)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `auth` (
    `id`       INT(11)      NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(64)  NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `email`    VARCHAR(128) NOT NULL,
    `phone`    VARCHAR(32)  DEFAULT NULL,
    `active`   TINYINT(1)   NOT NULL DEFAULT 0,
    `blocked`  TINYINT(1)   NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_auth_username` (`username`),
    UNIQUE KEY `uq_auth_email`    (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table: users
-- Stores extended profile data — linked to auth by id.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
    `id`        INT(11)      NOT NULL,
    `avatar`    TEXT         DEFAULT NULL,
    `bio`       TEXT         DEFAULT NULL,
    `firstname` VARCHAR(64)  DEFAULT NULL,
    `lastname`  VARCHAR(64)  DEFAULT NULL,
    `dob`       DATE         DEFAULT NULL,
    `instagram` VARCHAR(128) DEFAULT NULL,
    `twitter`   VARCHAR(128) DEFAULT NULL,
    `facebook`  VARCHAR(128) DEFAULT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_users_auth` FOREIGN KEY (`id`) REFERENCES `auth` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table: session
-- Stores active login sessions with IP / UA validation.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `session` (
    `id`          INT(11)      NOT NULL AUTO_INCREMENT,
    `uid`         INT(11)      NOT NULL,
    `token`       VARCHAR(64)  NOT NULL,
    `login_time`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ip`          VARCHAR(64)  DEFAULT NULL,
    `user_agent`  TEXT         DEFAULT NULL,
    `active`      TINYINT(1)   NOT NULL DEFAULT 1,
    `fingerprint` VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_session_token` (`token`),
    KEY `idx_session_uid` (`uid`),
    CONSTRAINT `fk_session_auth` FOREIGN KEY (`uid`) REFERENCES `auth` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table: posts
-- Stores user photo posts.
-- owner = email address of the auth user who posted.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `posts` (
    `id`          INT(11)      NOT NULL AUTO_INCREMENT,
    `post_text`   TEXT         DEFAULT NULL,
    `mulit_image` TINYINT(1)   NOT NULL DEFAULT 0,  -- reserved for multi-image support
    `image_uri`   VARCHAR(512) NOT NULL,
    `like_count`  INT(11)      NOT NULL DEFAULT 0,
    `upload_time` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `owner`       VARCHAR(128) NOT NULL,             -- email FK (denormalised for perf)
    PRIMARY KEY (`id`),
    KEY `idx_posts_owner`       (`owner`),
    KEY `idx_posts_upload_time` (`upload_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table: likes
-- Composite PK: md5(user_id + "-" + post_id)
-- `like` column: 1 = liked, 0 = not liked (toggle).
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `likes` (
    `id`        VARCHAR(32)  NOT NULL,   -- md5(user_id + "-" + post_id)
    `user_id`   INT(11)      NOT NULL,
    `post_id`   INT(11)      NOT NULL,
    `like`      TINYINT(1)   NOT NULL DEFAULT 0,
    `timestamp` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_likes_user`    (`user_id`),
    KEY `idx_likes_post`    (`post_id`),
    CONSTRAINT `fk_likes_auth`  FOREIGN KEY (`user_id`) REFERENCES `auth`  (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_likes_posts` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
