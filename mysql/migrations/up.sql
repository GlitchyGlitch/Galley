CREATE DATABASE gallery;

USE gallery;

CREATE TABLE IF NOT EXISTS `photos` (
  `id` binary(16) DEFAULT (uuid_to_bin(uuid())) PRIMARY KEY,
  `path` varchar(255) UNIQUE,
  `owner` binary(16),
  `mime` varchar(255),
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `users` (
  `id` binary(16) DEFAULT (uuid_to_bin(uuid())) PRIMARY KEY,
  `email` varchar(320) UNIQUE,
  `passwd_hash` varchar(72),
  `role` ENUM("admin", "regular")
);

CREATE TABLE IF NOT EXISTS `albums` (
  `id` binary(16) DEFAULT (uuid_to_bin(uuid())) PRIMARY KEY,
  `name` varchar(255),
  `owner` binary(16)
);

CREATE TABLE IF NOT EXISTS `comments` (
  `id` binary(16) DEFAULT (uuid_to_bin(uuid())) PRIMARY KEY,
  `comment` varchar(510),
  `owner` binary(16)
);

CREATE TABLE IF NOT EXISTS `rates` (
  `id` binary(16) DEFAULT (uuid_to_bin(uuid())) PRIMARY KEY,
  `stars` int,
  `owner` binary(16)
);

ALTER TABLE `albums` ADD FOREIGN KEY (`owner`) REFERENCES `users` (`id`);

ALTER TABLE `photos` ADD FOREIGN KEY (`owner`) REFERENCES `users` (`id`);

ALTER TABLE `comments` ADD FOREIGN KEY (`owner`) REFERENCES `users` (`id`);

ALTER TABLE `rates` ADD FOREIGN KEY (`owner`) REFERENCES `users` (`id`);

INSERT INTO `users` (id, email, passwd_hash, role) VALUE (uuid_to_bin("7fba41eb-61fe-4c95-9bfe-5c4ee1fd076a"), "example@example.com", "$2a$12$juN33VDOusigZ7zhNscntemgVxC0F5/NasF5NwCAavaZwC/lmEpua", "admin")