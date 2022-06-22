CREATE TABLE IF NOT EXISTS `post` (
  `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL
) ENGINE=InnoDB;

INSERT INTO `post` (`title`) VALUES ('blog post 1');
INSERT INTO `post` (`title`) VALUES ('blog post 2');