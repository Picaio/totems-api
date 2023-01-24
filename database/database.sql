SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tasks
-- ----------------------------
DROP TABLE IF EXISTS `tasks`;
CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `userId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `tasks_users_fk` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of tasks
-- ----------------------------
INSERT INTO `tasks` (`id`, `name`, `status`, `userId`) VALUES (1, 'Go to cinema', 1, 8);
INSERT INTO `tasks` (`id`, `name`, `status`, `userId`) VALUES (2, 'Buy shoes', 0, 8);
INSERT INTO `tasks` (`id`, `name`, `status`, `userId`) VALUES (3, 'Go to shopping', 0, 8);
INSERT INTO `tasks` (`id`, `name`, `status`, `userId`) VALUES (4, 'Pay the credit card ;-)', 1, 8);
INSERT INTO `tasks` (`id`, `name`, `status`, `userId`) VALUES (5, 'Do math homework...', 0, 8);
INSERT INTO `tasks` (`id`, `name`, `status`, `userId`) VALUES (6, 'Just Testing...', 1, 1);


-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `role` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL UNIQUE,
  `password` varchar(128),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` (`name`, `role`, `email`, `password`) VALUES ('Juan', 'user', 'juanmartin@mail.com', 'd5f4da62059760b35de35f8fbd8efb43eee26ac741ef8c6e51782a13ac7d50e927b653160c591616a9dc8a452c877a6b80c00aecba14504756a65f88439fcd1e');
INSERT INTO `users` (`name`, `role`, `email`, `password`) VALUES ('James', 'user', 'jbond@yahoo.net', 'd5f4da62059760b35de35f8fbd8efb43eee26ac741ef8c6e51782a13ac7d50e927b653160c591616a9dc8a452c877a6b80c00aecba14504756a65f88439fcd1e');
INSERT INTO `users` (`name`, `role`, `email`, `password`) VALUES ('Lionel', 'user', 'mess10@gmail.gol', 'd5f4da62059760b35de35f8fbd8efb43eee26ac741ef8c6e51782a13ac7d50e927b653160c591616a9dc8a452c877a6b80c00aecba14504756a65f88439fcd1e');
INSERT INTO `users` (`name`, `role`, `email`, `password`) VALUES ('Carlos', 'user', 'bianchini@hotmail.com.ar', 'd5f4da62059760b35de35f8fbd8efb43eee26ac741ef8c6e51782a13ac7d50e927b653160c591616a9dc8a452c877a6b80c00aecba14504756a65f88439fcd1e');
INSERT INTO `users` (`name`, `role`, `email`, `password`) VALUES ('Diego', 'user', 'diego1010@gmail.com', 'd5f4da62059760b35de35f8fbd8efb43eee26ac741ef8c6e51782a13ac7d50e927b653160c591616a9dc8a452c877a6b80c00aecba14504756a65f88439fcd1e');
INSERT INTO `users` (`name`, `role`, `email`, `password`) VALUES ('One User', 'user', 'one@user.com', 'd5f4da62059760b35de35f8fbd8efb43eee26ac741ef8c6e51782a13ac7d50e927b653160c591616a9dc8a452c877a6b80c00aecba14504756a65f88439fcd1e');
INSERT INTO `users` (`name`, `role`, `email`, `password`) VALUES ('Diegol', 'user', 'diego@gol.com.ar', 'd5f4da62059760b35de35f8fbd8efb43eee26ac741ef8c6e51782a13ac7d50e927b653160c591616a9dc8a452c877a6b80c00aecba14504756a65f88439fcd1e');
INSERT INTO `users` (`name`, `role`, `email`, `password`) VALUES ('Test User', 'user', 'test@user.com', '$2y$10$S9.JvxDbDhESUZvZWmpyleWB4YTHEaCJ5nevlXMHNso8J4X4/Sgeq');

-- ----------------------------
-- Table structure for totems
-- ----------------------------
DROP TABLE IF EXISTS `totems`;
CREATE TABLE `totems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of totems
-- ----------------------------
INSERT INTO `totems` (`id`, `name`, `description`) VALUES ('1', 'My Note 1', 'My first online note');
INSERT INTO `totems` (`id`, `name`, `description`) VALUES ('2', 'Chinese Proverb', 'Those who say it can not be done, should not interrupt those doing it.');
INSERT INTO `totems` (`id`, `name`, `description`) VALUES ('3', 'Long Note 3', 'This is a very large note, or maybe not...');
INSERT INTO `totems` (`id`, `name`, `description`) VALUES ('4', 'Napoleon Hill', 'Whatever the mind of man can conceive and believe, it can achieve.');
INSERT INTO `totems` (`id`, `name`, `description`) VALUES ('5', 'Note 5', 'A Random Note');

INSERT INTO `totems`
    (`name`, `description`)
VALUES
    ('Brian Tracy', 'Develop an attitude of gratitude, and give thanks for everything that happens to you, knowing that every step forward is a step toward achieving something bigger and better than your current situation.'),
    ('Zig Ziglar', 'Your attitude, not your aptitude, will determine your altitude.'),
    ('William James', 'The greatest discovery of my generation is that a human being can alter his life by altering his attitudes.'),
    ('Og Mandino', 'Take the attitude of a student, never be too big to ask questions, never know too much to learn something new.'),
    ('Earl Nightingale', 'Our attitude towards others determines their attitude towards us.'),
    ('Norman Vincent Peale', 'Watch your manner of speech if you wish to develop a peaceful state of mind. Start each day by affirming peaceful, contented and happy attitudes and your days will tend to be pleasant and successful.'),
    ('W. Clement Stone', 'There is little difference in people, but that little difference makes a big difference. The little difference is attitude. The big difference is whether it is positive or negative.'),
    ('Dale Carnegie', 'Happiness does not depend on any external conditions, it is governed by our mental attitude.'),
    ('Walt Disney', 'If you can dream it, you can do it.'),
    ('William Shakespeare', 'Our doubts are traitors and make us lose the good we oft might win by fearing to attempt.'),
    ('Albert Einstein', 'A person who never made a mistake never tried anything new.');

SET FOREIGN_KEY_CHECKS = 1;
