-- ������ ������������ Devart dbForge Studio for MySQL, ������ 4.0.202.1
-- ����: 28.07.2010 22:15:20
-- ������ �������: 5.1.48-community
-- ������ �������: 4.1

-- 
--  ���������� ������� ������
-- 
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS = 0 */;

SET NAMES 'utf8';

--
-- �������� ��� ���� ������ keep
--
DROP DATABASE IF EXISTS keep;
CREATE DATABASE IF NOT EXISTS keep
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

USE keep;

--
-- �������� ��� ������� categories
--
CREATE TABLE IF NOT EXISTS categories(
  id INT(10) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE INDEX id (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

--
-- �������� ��� ������� countries
--
CREATE TABLE IF NOT EXISTS countries(
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (id),
  INDEX id (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 69
AVG_ROW_LENGTH = 364
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

--
-- �������� ��� ������� err_log
--
CREATE TABLE IF NOT EXISTS err_log(
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  level VARCHAR(10) DEFAULT NULL,
  user_id INT(11) UNSIGNED DEFAULT NULL,
  tab_code VARCHAR(10) DEFAULT 'underfind',
  info TEXT DEFAULT NULL,
  `date` DATE DEFAULT NULL,
  `time` TIME DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE INDEX id (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 32
AVG_ROW_LENGTH = 528
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

--
-- �������� ��� ������� logins
--
CREATE TABLE IF NOT EXISTS logins(
  id INT(10) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  login VARCHAR(20) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE INDEX login (login)
)
ENGINE = INNODB
AUTO_INCREMENT = 16
AVG_ROW_LENGTH = 2730
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

--
-- �������� ��� ������� news
--
CREATE TABLE IF NOT EXISTS news(
  id INT(11) NOT NULL AUTO_INCREMENT,
  `date` DATE DEFAULT '0000-00-00',
  content VARCHAR(500) DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE INDEX id (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 12
AVG_ROW_LENGTH = 1489
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

--
-- �������� ��� ������� settings
--
CREATE TABLE IF NOT EXISTS settings(
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  welcome VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT 'Welcome Welcome Welcome Welcome Welcome Welcome Welcome Welcome ',
  email VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE INDEX id (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 2
AVG_ROW_LENGTH = 16384
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

--
-- �������� ��� ������� units
--
CREATE TABLE IF NOT EXISTS units(
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  full_name VARCHAR(10) DEFAULT NULL,
  brief_name VARCHAR(10) DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE INDEX brief_name (brief_name),
  UNIQUE INDEX full_name (full_name),
  UNIQUE INDEX id (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 8
AVG_ROW_LENGTH = 2340
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

--
-- �������� ��� ������� cities
--
CREATE TABLE IF NOT EXISTS cities(
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) DEFAULT NULL,
  country_id INT(10) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (id),
  INDEX city_country_FK (country_id),
  UNIQUE INDEX id (id),
  CONSTRAINT city_country_FK FOREIGN KEY (country_id)
  REFERENCES countries (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 113
AVG_ROW_LENGTH = 165
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

--
-- �������� ��� ������� goods
--
CREATE TABLE IF NOT EXISTS goods(
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) DEFAULT NULL,
  cku VARCHAR(20) DEFAULT NULL,
  in_pack INT(10) UNSIGNED NOT NULL DEFAULT 0,
  unit_id INT(10) UNSIGNED DEFAULT NULL,
  ex_price DOUBLE (15, 3) UNSIGNED NOT NULL DEFAULT 0.000 COMMENT 'Ex-Factory Price (���)',
  rt_price DOUBLE (15, 3) UNSIGNED NOT NULL DEFAULT 0.000 COMMENT 'Retail Price (�������)',
  img_name VARCHAR(20) DEFAULT NULL,
  img_height INT(3) DEFAULT NULL,
  img_width INT(3) DEFAULT NULL,
  PRIMARY KEY (id),
  INDEX goods_unit_FK (unit_id),
  UNIQUE INDEX id (id),
  CONSTRAINT goods_unit_FK FOREIGN KEY (unit_id)
  REFERENCES units (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

--
-- �������� ��� ������� good_catigories
--
CREATE TABLE IF NOT EXISTS good_catigories(
  id INT(10) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  good_id INT(10) UNSIGNED DEFAULT NULL COMMENT 'Points to id of next category of this table',
  category_id INT(10) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (id),
  INDEX good_catigoriy_good_FK (good_id),
  INDEX good_catigory_catigory_FK (category_id),
  UNIQUE INDEX id (id),
  CONSTRAINT good_catigoriy_good_FK FOREIGN KEY (good_id)
  REFERENCES goods (id),
  CONSTRAINT good_catigory_catigory_FK FOREIGN KEY (category_id)
  REFERENCES categories (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_unicode_ci
COMMENT = 'Map table to show categories of good';

--
-- �������� ��� ������� managers
--
CREATE TABLE IF NOT EXISTS managers(
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  level VARCHAR(10) NOT NULL,
  firstname VARCHAR(50) DEFAULT 'Firstname',
  surname VARCHAR(50) DEFAULT 'Sirname',
  city_id INT(10) UNSIGNED DEFAULT NULL,
  info VARCHAR(1000) DEFAULT NULL,
  `password` VARCHAR(50) NOT NULL,
  email VARCHAR(100) DEFAULT NULL,
  login VARCHAR(20) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE INDEX id (id),
  UNIQUE INDEX login (login),
  INDEX user_city_FK (city_id),
  CONSTRAINT FK_managers_cities_id FOREIGN KEY (city_id)
  REFERENCES cities (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 9
AVG_ROW_LENGTH = 4096
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

--
-- �������� ��� ������� clients
--
CREATE TABLE IF NOT EXISTS clients(
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  firstname VARCHAR(50) DEFAULT 'Firstname',
  surname VARCHAR(50) DEFAULT 'Sirname',
  city_id INT(10) UNSIGNED DEFAULT NULL,
  info VARCHAR(1000) DEFAULT NULL,
  `password` VARCHAR(50) NOT NULL,
  email VARCHAR(100) DEFAULT NULL,
  owner_id INT(10) UNSIGNED DEFAULT NULL,
  login VARCHAR(20) DEFAULT NULL,
  PRIMARY KEY (id),
  INDEX city_id_ix (city_id),
  INDEX FK_clients_managers_id (owner_id),
  UNIQUE INDEX id_uix (id),
  UNIQUE INDEX login (login),
  CONSTRAINT FK_clients_cities_id FOREIGN KEY (city_id)
  REFERENCES cities (id),
  CONSTRAINT FK_clients_managers_id FOREIGN KEY (owner_id)
  REFERENCES managers (id) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE = INNODB
AUTO_INCREMENT = 3
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

--
-- �������� ��� ������� departments
--
CREATE TABLE IF NOT EXISTS departments(
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) DEFAULT NULL,
  manager_id INT(10) UNSIGNED NOT NULL DEFAULT 0,
  is_main TINYINT(1) NOT NULL DEFAULT 0,
  info VARCHAR(1000) DEFAULT NULL,
  PRIMARY KEY (id),
  INDEX department_user_FK (manager_id),
  UNIQUE INDEX id (id),
  UNIQUE INDEX name (name),
  CONSTRAINT FK_departments_managers_id FOREIGN KEY (manager_id)
  REFERENCES managers (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 7
AVG_ROW_LENGTH = 5461
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

--
-- �������� ��� ������� stock
--
CREATE TABLE IF NOT EXISTS stock(
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  good_id INT(10) UNSIGNED DEFAULT NULL,
  depatement_id INT(10) UNSIGNED DEFAULT NULL,
  qnt_packs INT(10) UNSIGNED DEFAULT NULL,
  qnt_assr INT(10) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (id),
  INDEX stock_department_FK (depatement_id),
  INDEX stock_good_FK (good_id),
  CONSTRAINT stock_department_FK FOREIGN KEY (depatement_id)
  REFERENCES departments (id),
  CONSTRAINT stock_good_FK FOREIGN KEY (good_id)
  REFERENCES goods (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

DELIMITER $$

--
-- �������� ��� �������� client_bfr_del
--
CREATE TRIGGER client_bfr_del
BEFORE DELETE
ON clients
FOR EACH ROW
BEGIN
  DELETE
  FROM
    `logins`
  WHERE
    `logins`.`login` = old.`login`;
END
$$

--
-- �������� ��� �������� client_bfr_ins
--
CREATE TRIGGER client_bfr_ins
BEFORE INSERT
ON clients
FOR EACH ROW
BEGIN
  INSERT INTO `logins` (`logins`.`login`) VALUES (new.`login`);
END
$$

--
-- �������� ��� �������� err_log_add_rec
--
CREATE TRIGGER err_log_add_rec
BEFORE INSERT
ON err_log
FOR EACH ROW
BEGIN
  SET NEW.`date` = CURDATE();
  SET NEW.`time` = CURTIME();
END
$$

--
-- �������� ��� �������� manager_bfr_del
--
CREATE TRIGGER manager_bfr_del
BEFORE DELETE
ON managers
FOR EACH ROW
BEGIN
  DELETE
  FROM
    `logins`
  WHERE
    `logins`.`login` = old.`login`;
  DELETE
  FROM
    `departments`
  WHERE
    `departments`.`manager_id` = old.`id`;
END
$$

--
-- �������� ��� �������� manager_bfr_ins
--
CREATE TRIGGER manager_bfr_ins
BEFORE INSERT
ON managers
FOR EACH ROW
BEGIN
  INSERT INTO `logins` (`logins`.`login`) VALUES (new.`login`);
END
$$

DELIMITER ;

--
-- �������� ��� ������������� cities_view
--
CREATE OR REPLACE VIEW cities_view AS SELECT
  `cities`.`id` AS `id`, `cities`.`name` AS `name`, `countries`.`name` AS `country`
FROM
  (`cities`
  LEFT JOIN `countries` ON ((`countries`.`id` = `cities`.`country_id`)));

--
-- �������� ��� ������������� clients_6
--
CREATE OR REPLACE VIEW clients_6 AS SELECT
  `clients`.`id` AS `id`, `clients`.`firstname` AS `firstname`, `clients`.`surname` AS `surname`, `clients`.`info` AS `info`, CONCAT(`cities`.`name`, ', ', `countries`.`name`) AS `city_country`, `clients`.`email` AS `email`
FROM
  ((`clients`
  LEFT JOIN `cities` ON ((`cities`.`id` = `clients`.`city_id`)))
  LEFT JOIN `countries` ON ((`countries`.`id` = `cities`.`country_id`)))
WHERE
  (`clients`.`owner_id` = 6);

--
-- �������� ��� ������������� clients_7
--
CREATE OR REPLACE VIEW clients_7 AS SELECT
  `clients`.`id` AS `id`, `clients`.`firstname` AS `firstname`, `clients`.`surname` AS `surname`, `clients`.`info` AS `info`, CONCAT(`cities`.`name`, ', ', `countries`.`name`) AS `city_country`, `clients`.`email` AS `email`
FROM
  ((`clients`
  LEFT JOIN `cities` ON ((`cities`.`id` = `clients`.`city_id`)))
  LEFT JOIN `countries` ON ((`countries`.`id` = `cities`.`country_id`)))
WHERE
  (`clients`.`owner_id` = 7);

--
-- �������� ��� ������������� clients_8
--
CREATE OR REPLACE VIEW clients_8 AS SELECT
  `clients`.`id` AS `id`, `clients`.`firstname` AS `firstname`, `clients`.`surname` AS `surname`, `clients`.`info` AS `info`, CONCAT(`cities`.`name`, ', ', `countries`.`name`) AS `city_country`, `clients`.`email` AS `email`
FROM
  ((`clients`
  LEFT JOIN `cities` ON ((`cities`.`id` = `clients`.`city_id`)))
  LEFT JOIN `countries` ON ((`countries`.`id` = `cities`.`country_id`)))
WHERE
  (`clients`.`owner_id` = 8);

--
-- �������� ��� ������������� departments_6
--
CREATE OR REPLACE VIEW departments_6 AS SELECT
  `departments`.`id` AS `id`, `departments`.`name` AS `name`, `departments`.`info` AS `info`
FROM
  `departments`
WHERE
  (`departments`.`manager_id` = 6);

--
-- �������� ��� ������������� departments_7
--
CREATE OR REPLACE VIEW departments_7 AS SELECT
  `departments`.`id` AS `id`, `departments`.`name` AS `name`, `departments`.`info` AS `info`
FROM
  `departments`
WHERE
  (`departments`.`manager_id` = 7);

--
-- �������� ��� ������������� departments_8
--
CREATE OR REPLACE VIEW departments_8 AS SELECT
  `departments`.`id` AS `id`, `departments`.`name` AS `name`, `departments`.`info` AS `info`
FROM
  `departments`
WHERE
  (`departments`.`manager_id` = 8);

--
-- �������� ��� ������������� login_info
--
CREATE OR REPLACE VIEW login_info AS SELECT
  `logins`.`login` AS `login`, `managers`.`id` AS `id`, `managers`.`level` AS `level`, `managers`.`password` AS `password`
FROM
  (`managers`
  JOIN `logins`)
WHERE
  (`managers`.`login` = `logins`.`login`)
UNION ALL
SELECT
  `logins`.`login` AS `login`, `clients`.`id` AS `id`, 'client' AS `level`, `clients`.`password` AS `password`
FROM
  (`clients`
  JOIN `logins`)
WHERE
  (`clients`.`login` = `logins`.`login`);

--
-- �������� ��� ������������� managers_view
--
CREATE OR REPLACE VIEW managers_view AS SELECT
  `managers`.`id` AS `id`, `managers`.`firstname` AS `firstname`, `managers`.`surname` AS `surname`, `managers`.`email` AS `email`, `managers`.`info` AS `info`, CONCAT(`cities`.`name`, ', ', `countries`.`name`) AS `city_country`
FROM
  ((`managers`
  LEFT JOIN `cities` ON ((`cities`.`id` = `managers`.`city_id`)))
  LEFT JOIN `countries` ON ((`countries`.`id` = `cities`.`country_id`)))
WHERE
  (`managers`.`level` = 'manager');

-- 
-- ����� ������ ��� ������� categories
--
-- ������� �� �������� ������

-- 
-- ����� ������ ��� ������� countries
--
INSERT INTO countries VALUES 
  (1, '�������'),
  (2, '������'),
  (3, '��������'),
  (4, '����������'),
  (5, '������'),
  (6, '�������'),
  (7, '��������'),
  (8, '�����'),
  (10, '��������'),
  (23, '��������������'),
  (28, '�������'),
  (29, '���������'),
  (30, '����� (�����)'),
  (31, '����� (��������)'),
  (33, '�������'),
  (34, '������'),
  (36, '�������� (��������)'),
  (38, '�������'),
  (39, '���������'),
  (40, '����������'),
  (42, '���'),
  (43, '��������'),
  (44, '������'),
  (45, '��������'),
  (46, '�����'),
  (47, '������'),
  (48, '�����'),
  (49, '�������'),
  (51, '�������'),
  (52, '�����������'),
  (53, '����������'),
  (54, '�������'),
  (55, '������'),
  (56, '���������'),
  (58, '����'),
  (59, '������'),
  (60, '�������'),
  (61, '����'),
  (62, '������'),
  (63, '�����'),
  (64, '������'),
  (65, '����������'),
  (66, '�������'),
  (67, '���������'),
  (68, '�������');

-- 
-- ����� ������ ��� ������� err_log
--
INSERT INTO err_log VALUES 
  (1, 'manager', 2, 'lists', 'Caught exception: Bad MySQL result. Resource: PTable::countAllRecs. The whole SQL query is: SELECT count( id ) AS count FROM depts_view', '2010-07-27', '12:54:31'),
  (2, 'manager', 2, 'lists', 'Caught exception: Bad MySQL result. Resource: PTable::countAllRecs. The whole SQL query is: SELECT count( id ) AS count FROM depts_view', '2010-07-27', '12:54:38'),
  (3, 'manager', 2, 'lists', 'Caught exception: Bad MySQL result. Resource: PTable::countAllRecs. The whole SQL query is: SELECT count( id ) AS count FROM depts_view', '2010-07-27', '12:54:43'),
  (4, 'manager', 2, 'lists', 'Caught exception: Bad MySQL result. Resource: PTable::countAllRecs. The whole SQL query is: SELECT count( id ) AS count FROM depts_view', '2010-07-27', '12:54:57'),
  (5, 'manager', 2, 'lists', 'Caught exception: Bad MySQL result. Resource: PTable::countAllRecs. The whole SQL query is: SELECT count( id ) AS count FROM depts_view', '2010-07-27', '12:55:07'),
  (6, 'manager', 2, 'lists', 'Caught exception: Bad MySQL result. Resource: PTable::countAllRecs. The whole SQL query is: SELECT count( id ) AS count FROM depts_view', '2010-07-27', '12:55:24'),
  (7, 'manager', 2, 'lists', 'Caught exception: Bad MySQL result. Resource: PTable::countAllRecs. The whole SQL query is: SELECT count( id ) AS count FROM depts_view', '2010-07-27', '12:56:51'),
  (8, 'manager', 2, 'lists', 'Caught exception: Bad MySQL result. Resource: PTable::countAllRecs. The whole SQL query is: SELECT count( id ) AS count FROM depts_view', '2010-07-27', '12:56:58'),
  (9, 'manager', 2, 'lists', 'Caught exception: Bad MySQL result. Resource: PTable::countAllRecs. The whole SQL query is: SELECT count( id ) AS count FROM depts_view', '2010-07-27', '12:57:21'),
  (10, 'manager', 2, 'lists', 'Caught exception: Bad MySQL result. Resource: PTable::countAllRecs. The whole SQL query is: SELECT count( id ) AS count FROM depts_view', '2010-07-27', '12:58:13'),
  (11, 'manager', 2, 'lists', 'Caught exception: Bad MySQL result. Resource: PTable::countAllRecs. The whole SQL query is: SELECT count( id ) AS count FROM depts_view', '2010-07-27', '12:58:52'),
  (12, 'manager', 2, 'lists', 'Caught exception: Bad MySQL result. Resource: PTable::countAllRecs. The whole SQL query is: SELECT count( id ) AS count FROM depts_view', '2010-07-27', '13:01:46'),
  (13, 'manager', 2, 'lists', 'Caught exception: Bad MySQL result. Resource: PTable::countAllRecs. The whole SQL query is: SELECT count( id ) AS count FROM depts_view', '2010-07-27', '13:01:50'),
  (14, 'manager', 2, 'lists', 'Caught exception: Bad MySQL result. Resource: PTable::countAllRecs. The whole SQL query is: SELECT count( id ) AS count FROM depts_view', '2010-07-27', '18:13:52'),
  (15, 'manager', 2, 'lists', 'Caught exception: Bad MySQL result. Resource: PTable::countAllRecs. The whole SQL query is: SELECT count( id ) AS count FROM depts_view', '2010-07-27', '18:13:53'),
  (16, 'manager', 2, 'lists', 'Caught exception: Bad MySQL result. Resource: PTable::countAllRecs. The whole SQL query is: SELECT count( id ) AS count FROM depts_view', '2010-07-27', '18:13:53'),
  (17, 'manager', 2, 'lists', 'Caught exception: Bad MySQL result. Resource: PTable::countAllRecs. The whole SQL query is: SELECT count( id ) AS count FROM depts_view', '2010-07-27', '18:13:53'),
  (18, 'manager', 2, 'lists', 'Caught exception: Bad MySQL result. Resource: PTable::countAllRecs. The whole SQL query is: SELECT count( id ) AS count FROM depts_view', '2010-07-27', '18:13:53'),
  (19, 'manager', 2, 'lists', 'Caught exception: Bad MySQL result. Resource: PTable::countAllRecs. The whole SQL query is: SELECT count( id ) AS count FROM depts_view', '2010-07-27', '18:34:12'),
  (20, 'manager', 2, 'lists', 'Caught exception: Bad MySQL result. Resource: PTable::countAllRecs. The whole SQL query is: SELECT count( id ) AS count FROM depts_view', '2010-07-27', '18:35:15'),
  (21, 'manager', 2, 'lists', 'Caught exception: Bad MySQL result. Resource: PTable::countAllRecs. The whole SQL query is: SELECT count( id ) AS count FROM depts_view', '2010-07-27', '18:41:43'),
  (22, 'manager', 2, 'lists', 'Caught exception: Bad MySQL result. Resource: PTable::countAllRecs. The whole SQL query is: SELECT count( id ) AS count FROM depts_view', '2010-07-27', '18:42:56'),
  (23, 'manager', 2, 'lists', 'Caught exception: Bad MySQL result. Resource: PTable::countAllRecs. The whole SQL query is: SELECT count( id ) AS count FROM depts_view', '2010-07-27', '18:47:42'),
  (24, 'manager', 2, 'lists', 'Caught exception: Bad MySQL result. Resource: PTable::countAllRecs. The whole SQL query is: SELECT count( id ) AS count FROM depts_view', '2010-07-27', '18:49:55'),
  (25, 'manager', 2, 'lists', 'Caught exception: Bad MySQL result. Resource: PTable::countAllRecs. The whole SQL query is: SELECT count( id ) AS count FROM depts_view', '2010-07-27', '18:50:19'),
  (26, 'manager', 2, 'lists', 'Caught exception: Bad MySQL result. Resource: PTable::countAllRecs. The whole SQL query is: SELECT count( id ) AS count FROM depts_view', '2010-07-27', '18:51:29'),
  (27, 'manager', 2, 'lists', 'Caught exception: Bad MySQL result. Resource: PTable::countAllRecs. The whole SQL query is: SELECT count( id ) AS count FROM depts_view', '2010-07-27', '18:52:38'),
  (28, 'manager', 2, 'lists', 'Caught exception: Bad MySQL result. Resource: PTable::countAllRecs. The whole SQL query is: SELECT count( id ) AS count FROM depts_view', '2010-07-27', '18:52:51'),
  (29, 'manager', 2, 'lists', 'Caught exception: Bad MySQL result. Resource: PTable::countAllRecs. The whole SQL query is: SELECT count( id ) AS count FROM depts_view', '2010-07-27', '18:53:25'),
  (30, 'manager', 2, 'lists', 'Caught exception: Bad MySQL result. Resource: PTable::countAllRecs. The whole SQL query is: SELECT count( id ) AS count FROM depts_view', '2010-07-27', '18:56:20'),
  (31, 'manager', 2, 'lists', 'Caught exception: Bad MySQL result. Resource: PTable::countAllRecs. The whole SQL query is: SELECT count( id ) AS count FROM depts_view', '2010-07-27', '18:59:03');

-- 
-- ����� ������ ��� ������� logins
--
-- INSERT INTO logins VALUES 
--   (9, 'c1'),
--   (10, 'c2'),
--   (13, 'm1'),
--   (14, 'm2'),
--   (15, 'm3'),
--   (6, 'root');

-- 
-- ����� ������ ��� ������� news
--
INSERT INTO news VALUES 
  (1, '2008-09-18', 'Line 1 Information about goods income'),
  (2, '2008-09-17', 'Line 2 Information about goods income'),
  (3, '2008-09-09', 'Line 3 Information about goods income'),
  (4, '2008-09-10', 'Line 4 Information about goods income Information about goods income Information about goods income Information about goods income'),
  (5, '2008-09-12', 'Line 5 Information about goods income Information about goods income Information about goods income Information about goods income Information about goods income'),
  (6, '2008-10-01', 'Line 6 HHHHHH jkhlh;j as;lkjjlk jlkjlkj lkjlkj lkjljlkj lkjlkjlkj lkjlkjlk jhgjhg'),
  (7, '2008-10-03', 'Line 7  Information about goods income Information about goods income Information about goods income Information about goods income Information about goods income'),
  (8, '2008-10-05', 'Line 8  Information about goods income Information about goods income Information about goods income'),
  (9, '2008-10-09', ' Information about goods income Information about goods income Information about goods income Information about goods income'),
  (10, '2008-10-02', ' Information about goods income Information about goods income Information about goods income Information about goods income Information about goods income'),
  (11, '2008-10-04', ' Information about goods income Information about goods income Information about goods income Information about goods income');

-- 
-- ����� ������ ��� ������� settings
--
INSERT INTO settings VALUES 
  (1, '????N�???????? ??N�?�??N�, ?????�?????� N�?�??N�. ??N� ??N??�?????� N�?�??N� ???????�N�N? ?�?�N?. ?�?�N? ??N�N?????N�N� ????N�???????????? ???�??N?N�??N??�N� N???N?N�?�???� N??????�?�??.', 'nawataster@gmail.com');

-- 
-- ����� ������ ��� ������� units
--
INSERT INTO units VALUES 
  (1, '���������', '��'),
  (2, '�����', '�'),
  (3, '����', '�'),
  (4, '���������', '��'),
  (5, '�����', '��'),
  (6, '����', '���'),
  (7, '���������', '��');

-- 
-- ����� ������ ��� ������� cities
--
INSERT INTO cities VALUES 
  (1, '��������', 1),
  (2, '����', 1),
  (3, '�����', 1),
  (6, '������', 2),
  (7, '������', 2),
  (8, '�����-���������', 1),
  (10, '�����', 4),
  (11, '�������', 1),
  (12, '�����-���������', 2),
  (15, '�������������-����������', 2),
  (16, '�����������-��-�����', 2),
  (18, '��������', 1),
  (19, '������', 1),
  (20, '����', 2),
  (21, '��������', 1),
  (23, '���������', 1),
  (24, '�����������', 1),
  (25, '����', 1),
  (26, '����', 1),
  (27, '�������', 1),
  (28, '������ ��������', 2),
  (29, '����', 2),
  (30, '�����', 2),
  (31, '�������', 3),
  (32, '�������', 1),
  (33, '������', 1),
  (34, '������ ���', 1),
  (35, '�����������', 1),
  (36, '�������', 1),
  (37, '�������', 1),
  (38, '�����������', 1),
  (39, '�����', 1),
  (40, '��������', 1),
  (41, '�������', 1),
  (42, '���������', 1),
  (43, '������', 1),
  (44, '������', 2),
  (45, '������', 2),
  (46, '������', 2),
  (47, '�������', 2),
  (48, '�����', 2),
  (49, '�������', 2),
  (50, '���������', 2),
  (51, '������', 2),
  (52, '��������� �����', 2),
  (53, '�����', 2),
  (54, '��������', 2),
  (55, '������', 2),
  (56, '�����', 2),
  (57, '���������', 2),
  (58, '������������', 2),
  (59, '��������', 4),
  (60, '����������', 4),
  (61, '�������', 4),
  (62, '������', 4),
  (63, '����', 4),
  (64, '�������', 4),
  (65, '��������', 2),
  (71, '��������', 6),
  (72, '������', 29),
  (73, '���', 34),
  (74, '����', 62),
  (75, '�������', 60),
  (76, '�������', 63),
  (77, '�����', 8),
  (78, '��������', 2),
  (79, '�����������', 2),
  (80, '�������', 64),
  (81, '������', 2),
  (82, '�������', 2),
  (83, '�����', 38),
  (84, '�������', 38),
  (85, '������', 23),
  (86, '���������', 42),
  (87, '���-����', 42),
  (88, '������ ����', 42),
  (89, '���������', 23),
  (90, '������', 42),
  (91, '�������', 42),
  (92, '������', 33),
  (93, '������', 7),
  (94, '����', 2),
  (95, '���������', 68),
  (96, '�������', 68),
  (97, '����-����', 68),
  (98, '�����', 2),
  (99, '����-���', 29),
  (100, '��������������', 1),
  (101, '��������', 28),
  (103, '������', 2),
  (104, '����', 66),
  (105, '���������', 3),
  (106, '������', 48),
  (107, '����', 48),
  (108, '����', 47),
  (109, '�������', 47),
  (110, '����-���-����', 47),
  (111, '�����', 65),
  (112, '����', 23);

-- 
-- ����� ������ ��� ������� goods
--
-- ������� �� �������� ������

-- 
-- ����� ������ ��� ������� good_catigories
--
-- ������� �� �������� ������

-- 
-- ����� ������ ��� ������� managers
--
INSERT INTO managers VALUES 
  (1, 'admin', 'Admin', 'Root', 1, 'Root Admin', '1', NULL, 'root'),
  (6, 'manager', '����', '�������', 7, '������������', '1', 'eee@eee.eee', 'm1'),
  (7, 'manager', '������', '�������', 7, '�������� �����.', '1', 'eee@eee.eee', 'm2'),
  (8, 'manager', '����������', '����������', 1, '������������, 12/1', '1', 'nawata.press@gmail.com', 'm3');

-- 
-- ����� ������ ��� ������� clients
--
-- ������� �� �������� ������

-- 
-- ����� ������ ��� ������� departments
--
INSERT INTO departments VALUES 
  (4, '��� ����� 01', 6, 0, '����� ��� ����� 01 ����� ��� ����� 01 ����� ��� ����� 01 '),
  (5, '������', 8, 0, '������������'),
  (6, '�����', 8, 0, '�������� �����');

-- 
-- ����� ������ ��� ������� stock
--
-- ������� �� �������� ������

-- 
--  ��������� ������� ������
-- 
/*!40014 SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS */;