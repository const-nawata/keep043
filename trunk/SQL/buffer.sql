delimiter //
--	-------------------------------------------------------------------------------------------------
--	-------------------------------------------------------------------------------------------------
--	-------------------------------------------------------------------------------------------------


DROP TRIGGER IF EXISTS `manager_bfr_ins`//
CREATE TRIGGER `manager_bfr_ins` BEFORE INSERT ON `managers` FOR EACH ROW
BEGIN
	INSERT INTO `logins` ( `logins`.`login` )VALUES( new.`login` );
END//
--	-------------------------------------------------------------------------------------------------

DROP TRIGGER IF EXISTS `manager_bfr_del`//
CREATE TRIGGER `manager_bfr_del` BEFORE DELETE ON `managers` FOR EACH ROW
BEGIN
	DELETE FROM `logins` WHERE `logins`.`login` = old.`login`;
  DELETE FROM `departments` WHERE `departments`.`manager_id` = old.`id`;
END//
--	-------------------------------------------------------------------------------------------------

DROP TRIGGER IF EXISTS `client_bfr_ins`//
CREATE TRIGGER `client_bfr_ins` BEFORE INSERT ON `clients` FOR EACH ROW
BEGIN
	INSERT INTO `logins` ( `logins`.`login` )VALUES( new.`login` );
END//
--	-------------------------------------------------------------------------------------------------

DROP TRIGGER IF EXISTS `client_bfr_del`//
CREATE TRIGGER `client_bfr_del` BEFORE DELETE ON `clients` FOR EACH ROW
BEGIN
	DELETE FROM `logins` WHERE `logins`.`login` = old.`login`;
END//
--	-------------------------------------------------------------------------------------------------

CREATE OR REPLACE VIEW `managers_view` AS
	SELECT	`managers`.`id` AS `id`,
			`managers`.`firstname` AS `firstname`,
			`managers`.`surname` AS `surname`,
			`managers`.`email` AS `email`,
			`managers`.`info` AS `info`,
			CONCAT( `cities`.`name`, ', ', `countries`.`name` ) AS `city_country`
	FROM	`managers`
	LEFT JOIN `cities` ON `cities`.`id` = `managers`.`city_id`
	LEFT JOIN `countries` ON `countries`.id = `cities`.`country_id`
	WHERE `managers`.`level` = 'manager'//
--	-------------------------------------------------------------------------------------------------
/*
CREATE OR REPLACE VIEW `departments_view` AS
	SELECT	`departments`.`id` AS `id`,
			`departments`.`info` AS `info`,
       TRIM( LEADING ', ' FROM CONCAT( TRIM( CONCAT( `managers`.`surname`, ' ', `managers`.`firstname` ) ), ', ', `cities`.`name` ) ) AS `manager`
	FROM	`departments`
	LEFT JOIN `managers` ON `managers`.`id` = `departments`.`manager_id`
  LEFT JOIN `cities` ON `cities`.`id` = `managers`.`city_id`//
--	-------------------------------------------------------------------------------------------------
*/
CREATE OR REPLACE VIEW `depts_view` AS
	SELECT	`departments`.`id` AS `id`,
      `departments`.`name` AS `name`,
			`departments`.`info` AS `info`,
       TRIM( LEADING ', ' FROM CONCAT( TRIM( CONCAT( `managers`.`surname`, ' ', `managers`.`firstname` ) ), ', ', `cities`.`name` ) ) AS `manager`
	FROM	`departments`
	LEFT JOIN `managers` ON `managers`.`id` = `departments`.`manager_id`
  LEFT JOIN `cities` ON `cities`.`id` = `managers`.`city_id`//
--	-------------------------------------------------------------------------------------------------


/*
CREATE OR REPLACE VIEW `admins_view` AS
	SELECT	`managers`.`id` AS `id`,
			`managers`.`firstname` AS `firstname`,
			`managers`.`surname` AS `surname`,
			`managers`.`email` AS `email`,
			`managers`.`info` AS `info`,
			CONCAT( `cities`.`name`, ', ', `countries`.`name` ) AS `city_country`
	FROM	`managers`
	LEFT JOIN `cities` ON `cities`.`id` = `managers`.`city_id`
	LEFT JOIN `countries` ON `countries`.id = `cities`.`country_id`
	WHERE `managers`.`level` = 'admin'//
--	-------------------------------------------------------------------------------------------------
*/

CREATE OR REPLACE VIEW `login_info` AS
	SELECT `logins`.`login` AS `login`,
			`managers`.`id` AS `id`,
			`managers`.`level` AS `level`,
			`managers`.`password` AS `password`
	FROM `managers`, `logins`
	WHERE `managers`.`login` = `logins`.`login`
-- 	LEFT JOIN `logins` ON `managers`.`login` = `logins`.`login`

	UNION ALL

	SELECT `logins`.`login` AS `login`,
			`clients`.`id` AS `id`,
			'client' AS `level`,
			`clients`.`password` AS `password`
	FROM `clients`, `logins`
	WHERE `clients`.`login` = `logins`.`login`//
-- 	LEFT JOIN `logins` ON `clients`.`login_id` = `logins`.`id`//
--	-------------------------------------------------------------------------------------------------

-- DROP TRIGGER IF EXISTS `put_deleted_manager_after`//
/*CREATE TRIGGER `put_deleted_manager_after` AFTER DELETE ON `users` FOR EACH ROW
BEGIN
	IF old.`level` = 'manager' THEN
		INSERT INTO `deleted_managers` ( `deleted_managers`.`user_id` )VALUES( old.`id` );
	END IF;
END//
--	-------------------------------------------------------------------------------------------------
*/
-- DROP EVENT IF EXISTS `event_delete_clients`// -- DROP
-- DROP EVENT IF EXISTS `manager_deletion_cleaner_event`// -- DROP
/*CREATE EVENT `manager_deletion_cleaner_event` ON SCHEDULE 
-- 	EVERY 1 MINUTE COMMENT 'deletes clients and thares views for deleted managers' DO
	EVERY 10 SECOND COMMENT 'deletes clients and theirs views for deleted managers' DO
BEGIN
	DECLARE `manager_id` INT DEFAULT 0;
	DECLARE done INT DEFAULT 0;

	DECLARE cur1 CURSOR FOR
		SELECT `deleted_managers`.`user_id` FROM `deleted_managers`;

	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;


	OPEN cur1;
	FETCH cur1 INTO `manager_id`;
	WHILE NOT done DO
		DELETE FROM `users` WHERE `users`.`owner_id` = `manager_id`;




		FETCH cur1 INTO `manager_id`;
	END WHILE;

	DELETE FROM `deleted_managers`;

END//
-- -------------------------------------------------------------------------------------------------
*/



--	-------------------------------------------------------------------------------------------------
--	-------------------------------------------------------------------------------------------------
--	-------------------------------------------------------------------------------------------------
delimiter ;