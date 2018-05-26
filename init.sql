DROP DATABASE IF EXISTS `bookshelf`;
CREATE DATABASE `bookshelf` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;

USE `bookshelf`;

CREATE TABLE `genre` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `author` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `language` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `picture` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `book` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` TEXT NOT NULL,
  `isbn_number` varchar(255) NOT NULL,
  `publication_date` date NOT NULL,
  `picture` varchar(255) NOT NULL,
  `genre_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  FOREIGN KEY (`genre_id`) REFERENCES `genre`(`id`),
  FOREIGN KEY (`author_id`) REFERENCES `author`(`id`),
  FOREIGN KEY (`language_id`) REFERENCES `language`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;