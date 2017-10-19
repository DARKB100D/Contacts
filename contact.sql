-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Окт 19 2017 г., 07:20
-- Версия сервера: 10.2.9-MariaDB
-- Версия PHP: 7.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `contact`
--

-- --------------------------------------------------------

--
-- Структура таблицы `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `idType` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  `idWorker` int(11) NOT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Триггеры `contacts`
--
DELIMITER $$
CREATE TRIGGER `deleteContacts` AFTER DELETE ON `contacts` FOR EACH ROW DELETE FROM search WHERE `realId`=OLD.`id` AND `tableId`=4
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `insertContacts` AFTER INSERT ON `contacts` FOR EACH ROW INSERT INTO search (`id`,`realId`,`tableId`,`text`) VALUES
    (NEW.`idWorker`, NEW.`id`, 4, NEW.`type`),
    (NEW.`idWorker`, NEW.`id`, 4, NEW.`value`)
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `updateContacts` AFTER UPDATE ON `contacts` FOR EACH ROW BEGIN
DELETE FROM search WHERE `realId`=OLD.`id` AND `tableId`=4;
INSERT INTO search (`id`,`realId`,`tableId`,`text`) VALUES
    (NEW.`idWorker`, NEW.`id`, 4, NEW.`type`),
    (NEW.`idWorker`, NEW.`id`, 4, NEW.`value`);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `idOrganization` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Триггеры `departments`
--
DELIMITER $$
CREATE TRIGGER `deleteDepartments` AFTER DELETE ON `departments` FOR EACH ROW BEGIN
DELETE FROM search WHERE `id` IN (SELECT * FROM (SELECT id FROM search WHERE `realid`=OLD.`id` AND `tableId`=2) AS p);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `updateDepartments` AFTER UPDATE ON `departments` FOR EACH ROW UPDATE search SET `text`=NEW.`name` WHERE `text`=OLD.`name` AND `tableId`=2
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `fields`
--

CREATE TABLE `fields` (
  `id` int(11) NOT NULL,
  `idTable` int(11) NOT NULL,
  `fieldName` varchar(25) NOT NULL,
  `fieldShowName` varchar(64) NOT NULL,
  `type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `fields`
--

INSERT INTO `fields` (`id`, `idTable`, `fieldName`, `fieldShowName`, `type`) VALUES
(1, 1, 'name', 'Название организации', 0),
(2, 2, 'name', 'Название отдела / подразделения', 0),
(3, 3, 'surname', 'Фамилия', 0),
(4, 3, 'name', 'Имя', 0),
(5, 3, 'middlename', 'Отчество', 0),
(7, 3, 'idDepartment', 'Отдел / Подразделение', 1),
(9, 2, 'idOrganization', 'Организация', 1),
(10, 4, 'idWorker', 'Сотрудник', 1),
(13, 1, 'id', 'id', 1),
(14, 3, 'role', 'Должность', 0),
(16, 4, 'idType', 'Тип', 2),
(17, 4, 'value', 'Значение', 0),
(18, 4, 'type', 'Примечание', 0),
(22, 5, 'login', 'Логин', 0),
(23, 5, 'password', 'Пароль', 3);

-- --------------------------------------------------------

--
-- Структура таблицы `log`
--

CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `idTable` int(11) NOT NULL,
  `h2` varchar(255) NOT NULL,
  `idUser` int(11) NOT NULL,
  `operation` varchar(6) NOT NULL,
  `way` varchar(45) NOT NULL,
  `time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `organizations`
--

CREATE TABLE `organizations` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Триггеры `organizations`
--
DELIMITER $$
CREATE TRIGGER `deleteOrganizations` AFTER DELETE ON `organizations` FOR EACH ROW BEGIN
DELETE FROM search WHERE `id` IN (SELECT * FROM (SELECT id FROM search WHERE `realid`=OLD.`id` AND `tableId`=1) AS p);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `updateOrganizations` AFTER UPDATE ON `organizations` FOR EACH ROW UPDATE search SET `text`=NEW.`name` WHERE `text`=OLD.`name` AND `tableId`=1
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `search`
--

CREATE TABLE `search` (
  `id` int(11) DEFAULT NULL,
  `realId` int(11) DEFAULT NULL,
  `tableId` int(11) DEFAULT NULL,
  `text` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tables`
--

CREATE TABLE `tables` (
  `id` int(11) NOT NULL,
  `tableName` varchar(25) NOT NULL,
  `tableShowName` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tables`
--

INSERT INTO `tables` (`id`, `tableName`, `tableShowName`) VALUES
(1, 'organizations', 'Организации '),
(2, 'departments', 'Отделы '),
(3, 'workers', 'Сотрудники '),
(4, 'contacts', 'Контактная информация '),
(5, 'users', 'Администраторы');

-- --------------------------------------------------------

--
-- Структура таблицы `types`
--

CREATE TABLE `types` (
  `id` int(11) NOT NULL,
  `icon` varchar(120) NOT NULL,
  `old_icon` varchar(120) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `types`
--

INSERT INTO `types` (`id`, `icon`, `old_icon`, `name`) VALUES
(1, 'email', '<i class=\"fa fa-envelope-o\" aria-hidden=\"true\"></i>', 'Почта'),
(2, 'phone', '<i class=\"fa fa-phone\" aria-hidden=\"true\"></i>', 'Телефон'),
(3, 'location_on', '<i class=\"fa fa-map-marker\" aria-hidden=\"true\"></i>', 'Адрес'),
(4, 'card_giftcard', '<i class=\"fa fa-gift\" aria-hidden=\"true\"></i>', 'День рождения');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` varchar(64) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(64) DEFAULT NULL,
  `surname` varchar(64) DEFAULT NULL,
  `middlename` varchar(64) DEFAULT NULL,
  `hash` varchar(32) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `name`, `surname`, `middlename`, `hash`) VALUES
(1, 'admin', '$2y$10$Ijn3j9mna3gKaNSBaAMuwuiLreihv9CzLP4MQY6nFK4GBxNp3FVR.', 'admin', 'admin', 'admin', '840259b609b356d76db4f56809d72d16');

-- --------------------------------------------------------

--
-- Структура таблицы `workers`
--

CREATE TABLE `workers` (
  `id` int(11) NOT NULL,
  `surname` varchar(64) DEFAULT NULL,
  `name` varchar(64) DEFAULT NULL,
  `middlename` varchar(64) DEFAULT NULL,
  `idDepartment` int(11) NOT NULL,
  `role` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Триггеры `workers`
--
DELIMITER $$
CREATE TRIGGER `delete` AFTER DELETE ON `workers` FOR EACH ROW DELETE FROM search WHERE `id`=OLD.`id`
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `insert` AFTER INSERT ON `workers` FOR EACH ROW BEGIN
DECLARE departmentName VARCHAR(25);
DECLARE organizationId INT(11);
DECLARE organizationName VARCHAR(35);

SELECT name INTO @departmentName FROM departments WHERE `id`=NEW.`idDepartment`;

SELECT idOrganization INTO @organizationId FROM departments WHERE `id`=NEW.`idDepartment`;

SELECT name INTO @organizationName FROM organizations WHERE`id`=@organizationId;

INSERT INTO search (`id`,`realId`,`tableId`,`text`) VALUES
    (NEW.`id`, NEW.`id`, 3, NEW.`surname`),
    (NEW.`id`, NEW.`id`, 3, NEW.`name`),
    (NEW.`id`, NEW.`id`, 3, NEW.`middlename`),
    (NEW.`id`, NEW.`id`, 3, NEW.`role`),
    (NEW.`id`, NEW.`idDepartment`, 2, @departmentName), 
    (NEW.`id`, @organizationId, 1, @organizationName);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update` AFTER UPDATE ON `workers` FOR EACH ROW BEGIN
  DELETE FROM search WHERE `id`=OLD.`id` AND `tableId`=3;
  INSERT INTO search (`id`,`realId`,`tableId`,`text`) VALUES
    (NEW.`id`, NEW.`id`, 3, NEW.`surname`),
    (NEW.`id`, NEW.`id`, 3, NEW.`name`),    
    (NEW.`id`, NEW.`id`, 3, NEW.`middlename`),
    (NEW.`id`, NEW.`id`, 3, NEW.`role`);
END
$$
DELIMITER ;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idType` (`idType`),
  ADD KEY `idWorker` (`idWorker`);

--
-- Индексы таблицы `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idOrganization` (`idOrganization`);

--
-- Индексы таблицы `fields`
--
ALTER TABLE `fields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idTable` (`idTable`),
  ADD KEY `idTable_2` (`idTable`);

--
-- Индексы таблицы `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `search`
--
ALTER TABLE `search` ADD FULLTEXT KEY `IX_search_text` (`text`);

--
-- Индексы таблицы `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `workers`
--
ALTER TABLE `workers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idDepartment` (`idDepartment`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=312;

--
-- AUTO_INCREMENT для таблицы `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT для таблицы `fields`
--
ALTER TABLE `fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT для таблицы `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `tables`
--
ALTER TABLE `tables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `types`
--
ALTER TABLE `types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `workers`
--
ALTER TABLE `workers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=176;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `contacts_ibfk_1` FOREIGN KEY (`idType`) REFERENCES `types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contacts_ibfk_2` FOREIGN KEY (`idWorker`) REFERENCES `workers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `departments_ibfk_1` FOREIGN KEY (`idOrganization`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `fields`
--
ALTER TABLE `fields`
  ADD CONSTRAINT `fields_ibfk_1` FOREIGN KEY (`idTable`) REFERENCES `tables` (`id`);

--
-- Ограничения внешнего ключа таблицы `workers`
--
ALTER TABLE `workers`
  ADD CONSTRAINT `workers_ibfk_1` FOREIGN KEY (`idDepartment`) REFERENCES `departments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
