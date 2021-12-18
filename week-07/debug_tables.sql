--
-- Setup the debug report table
--
CREATE TABLE IF NOT EXISTS `debug_report` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `bug` TEXT NOT NULL,
    `product` INT NOT NULL DEFAULT 0,
    `version`  INT NOT NULL DEFAULT 0,
    `os` INT NOT NULL DEFAULT 0,
    `hardware` INT NOT NULL DEFAULT 0,
    `occurrence` INT NOT NULL DEFAULT 0,
    `solution` TEXT NOT NULL,
    PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

--
-- Setup the debug product table
--
CREATE TABLE IF NOT EXISTS `debug_product` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL DEFAULT '',
    `description` TEXT NOT NULL,
    PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

--
-- Setup the debug product version table
--
CREATE TABLE IF NOT EXISTS `debug_product_version` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(64) NOT NULL DEFAULT '',
    PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

--
-- Setup the debug os table
--
CREATE TABLE IF NOT EXISTS `debug_os` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL DEFAULT '',
    PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

--
-- Setup the debug hardware table
--
CREATE TABLE IF NOT EXISTS `debug_hardware` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(64) NOT NULL DEFAULT '',
    PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `debug_product`
--
INSERT INTO `debug_product` (`id`, `name`, `description`) VALUES
(1, 'SimpleScreenRecorder', 'SimpleScreenRecorder is a Linux program that was created to record programs and games.'),
(2, 'OBS Studio', 'OBS Studio is a free, open-source, and cross-platform screencasting and streaming app.'),
(3, 'GIMP - GNU Image Manipulation Program', ' GIMP is a cross-platform image editor available for GNU/Linux, macOS, Windows and more operating systems. It is free software, you can change its source code and distribute your changes.<br /><br />Whether you are a graphic designer, photographer, illustrator, or scientist, GIMP provides you with sophisticated tools to get your job done. You can further enhance your productivity with GIMP thanks to many customization options and 3rd party plugins.'),
(4, 'Telegram Messenger', 'Telegram delivers messages faster than any other application. Powerful. Telegram has no limits on the size of your media and chats.'),
(5, 'Joomla', 'Joomla! is an award-winning content management system (CMS), which enables you to build web sites and powerful online applications.'),
(6, 'Joomla Component Builder', 'Build extremely complex components in a fraction of the time.'),
(7, 'OctoJoom', 'With this script we can easily deploy docker containers of Joomla and Openssh. This combination of these tools give rise to a powerful and very secure shared development environment.');

--
-- Dumping data for table `debug_product_version`
--
INSERT INTO `debug_product_version` (`id`, `name`) VALUES
(1, '0.3.6'),
(2, '0.3.7'),
(3, '0.3.8'),
(4, '0.3.9'),
(5, '0.3.10'),
(6, '0.3.11'),
(7, '0.4.0'),
(8, '0.4.1'),
(9, '0.4.2'),
(10, '24.0.1'),
(11, '24.0.2'),
(12, '24.0.3'),
(13, '25.0.0'),
(14, '25.0.1'),
(15, '25.0.2'),
(16, '25.1.0'),
(17, '25.2.0'),
(18, '26.2.0'),
(19, '27.1.0'),
(20, '27.1.1'),
(21, '27.1.2'),
(22, '27.1.3'),
(23, '2.99.6'),
(24, '2.99.8'),
(25, '2.10.26'),
(26, '2.10.28'),
(27, '7.8.2'),
(28, '7.9.0'),
(29, '7.9.1'),
(30, '7.9.3'),
(31, '8.1.1'),
(32, '8.2.1'),
(33, '1.5.0'),
(34, '2.5.0'),
(35, '3.0.0'),
(36, '3.10.0'),
(37, '4.0.0'),
(38, '1.5.0'),
(39, '2.5.0'),
(40, '2.8.0'),
(41, '2.12.0'),
(42, '2.17.0'),
(43, '3.0.0'),
(44, '1.0.0'),
(45, '1.1.0'),
(46, '2.0.0'),
(47, '2.1.0'),
(48, '3.0.0');

--
-- Dumping data for table `debug_os`
--
INSERT INTO `debug_os` (`id`, `name`) VALUES
(1, 'Linux'),
(2, 'Mac'),
(3, 'Windows');

--
-- Dumping data for table `debug_hardware`
--
INSERT INTO `debug_hardware` (`id`, `name`) VALUES
(1, '32 Bit'),
(2, '64 Bit');

--
-- Dumping dummy data for table `debug_report`
--
INSERT INTO `debug_report` (`id`, `bug`, `product`, `version`, `os`, `hardware`, `occurrence`, `solution`) VALUES
(1, 'Hang on save of large recordings', 1, 2, 2, 2, 10, 'Mac limitation, not software related'),
(2, 'Sends out sparks in the reactor', 1, 4, 3, 2, 2, 'Run!'),
(3, 'Leaks private audio recordings to BSA', 2, 21, 3, 2, 7, 'ignored'),
(4, 'Makes strange sound in morning hours', 2, 22, 3, 2, 12, 'feed the cat'),
(5, 'Makes the images look old', 3, 25, 3, 2, 21, 'expected when image is old'),
(6, 'Adds a smile to your face', 3, 25, 3, 2, 17, 'what, where did you get my picture?'),
(7, 'Gives false idea of privacy', 4, 32, 3, 2, 1962342, 'normal behaviour of all instant messaging software');
