CREATE DATABASE gallery;

USE gallery;

CREATE TABLE IF NOT EXISTS `photos` (
  `id` binary(16) DEFAULT (uuid_to_bin(uuid())) PRIMARY KEY,
  `path` varchar(255) UNIQUE NOT NULL,
  `album_id` binary(16),
  `owner_id` binary(16) NOT NULL,
  `mime` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `users` (
  `id` binary(16) DEFAULT (uuid_to_bin(uuid())) PRIMARY KEY,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(320) UNIQUE NOT NULL,
  `passwd_hash` varchar(72) NOT NULL,
  `role` ENUM("admin", "regular") NOT NULL
);

CREATE TABLE IF NOT EXISTS `albums` (
  `id` binary(16) DEFAULT (uuid_to_bin(uuid())) PRIMARY KEY,
  `name` varchar(255) UNIQUE NOT NULL,
  `owner_id` binary(16) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `comments` (
  `id` binary(16) DEFAULT (uuid_to_bin(uuid())) PRIMARY KEY,
  `content` varchar(510) NOT NULL,
  `owner_id` binary(16) NOT NULL,
  `photo_id` binary(16) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `rates` (
  `id` binary(16) DEFAULT (uuid_to_bin(uuid())) PRIMARY KEY,
  `stars` int NOT NULL,
  `photo_id` binary(16) NOT NULL,
  `owner_id` binary(16) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE `albums` ADD FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`);

ALTER TABLE `photos` ADD FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`);
ALTER TABLE `photos` ADD FOREIGN KEY (`album_id`) REFERENCES `albums` (`id`);

ALTER TABLE `comments` ADD FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`);
ALTER TABLE `comments` ADD FOREIGN KEY (`photo_id`) REFERENCES `photos` (`id`);

ALTER TABLE `rates` ADD FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`);
ALTER TABLE `rates` ADD FOREIGN KEY (`photo_id`) REFERENCES `photos` (`id`);

ALTER TABLE `albums` ADD FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`);


INSERT INTO `users` (id, first_name, last_name, email, passwd_hash, role) VALUE (uuid_to_bin("7fba41eb-61fe-4c95-9bfe-5c4ee1fd076a"), "John", "Doe", "example@example.com", "$2a$12$juN33VDOusigZ7zhNscntemgVxC0F5/NasF5NwCAavaZwC/lmEpua", "admin");
INSERT INTO `photos` (id, path, owner_id, mime) VALUE (uuid_to_bin("ebc534ab-89a9-48bd-a141-adc09dabba8c"),  "/img/ebc534ab-89a9-48bd-a141-adc09dabba8c", uuid_to_bin("7fba41eb-61fe-4c95-9bfe-5c4ee1fd076a"), "image/jpeg");