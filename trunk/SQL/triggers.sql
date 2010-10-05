delimiter //
--	-------------------------------------------------------------------------------------------------
--	-------------------------------------------------------------------------------------------------
--	-------------------------------------------------------------------------------------------------


/*
DROP TRIGGER IF EXISTS `err_log_add_rec`//
CREATE TRIGGER `err_log_add_rec`
	BEFORE INSERT ON `err_log` FOR EACH ROW
BEGIN
	SET NEW.`date` = CURDATE();
	SET NEW.`time` = CURTIME();
END//
--	-------------------------------------------------------------------------------------------------

DROP TRIGGER IF EXISTS `delete_manager_clients`//
CREATE TRIGGER `delete_manager_clients`
	BEFORE DELETE ON `users` FOR EACH ROW
BEGIN
	IF old.`level` = 'manager' THEN
		DELETE `users` FROM `users`
		LEFT JOIN `clients` ON `clients`.`manager_id` = old.`id`
		WHERE `users`.`id` = `clients`.`client_id`;
	END IF;
END//
--	-------------------------------------------------------------------------------------------------
*/


/*
DROP TRIGGER IF EXISTS `add_user_after`//
CREATE TRIGGER `add_user_after`
	AFTER INSERT ON `users` FOR EACH ROW
BEGIN
	DECLARE `manager_id` INT DEFAULT 0;

	IF new.`level` = 'manager' THEN
		
-- 		SET @sql_stmt1 = CONCAT(
-- 'CREATE OR REPLACE VIEW `clients_', new.id, '` AS
-- 	SELECT 	`users`.`id` AS `id`,
-- 			`users`.`firstname` AS `firstname`,
-- 			`users`.`surname` AS `surname`,
-- 			`users`.`info` AS `info`,
-- 			`users`.`email` AS `email`
-- 	LEFT JOIN `cities` ON `cities`.`id` = `users`.`city_id`
-- 	LEFT JOIN `countries` ON `countries`.`id` = `cities`.`country_id`
-- 	WHERE	`users`.`owner_id` = ', new.id, ' AND
-- 			`users`.`level` = \'client\'' );
-- 
-- 		PREPARE stmt1 FROM @sql_stmt1;
-- 		EXECUTE stmt1;


CREATE OR REPLACE VIEW `clients` AS
	SELECT 	`users`.`id` AS `id`,
			`users`.`firstname` AS `firstname`,
			`users`.`surname` AS `surname`,
			`users`.`info` AS `info`,
			`users`.`email` AS `email`
	FROM `users`
	LEFT JOIN `cities` ON `cities`.`id` = `users`.`city_id`
	LEFT JOIN `countries` ON `countries`.`id` = `cities`.`country_id`
	WHERE	`users`.`owner_id` = new.id AND
			`users`.`level` = 'client';

-- 	CREATE OR REPLACE VIEW `clients_` AS
-- 		SELECT	`users`.`id` AS `id`,
-- 				`users`.`firstname` AS `firstname`,
-- 				`users`.`surname` AS `surname`, 
-- 				`users`.`info` AS `info`,
-- 				`users`.`email` AS `email`








-- 		CREATE OR REPLACE VIEW cities_view AS SELECT `cities`.`id` AS `id`,
-- 		`cities`.`name` AS `name`,
-- 		`countries`.`name` AS `country` FROM (`cities` LEFT JOIN `countries` ON ((`countries`.`id` = `cities`.`country_id`)));
		
	END IF;
END//
--	-------------------------------------------------------------------------------------------------
*/
/*
DROP TRIGGER IF EXISTS `add_user_after`//
CREATE TRIGGER `add_user_after`
	AFTER INSERT ON `users` FOR EACH ROW
BEGIN
	IF new.`level` = 'manager' THEN
		INSERT INTO `new_managers` ( `new_managers`.`user_id` )VALUES( new.`id` );
	END IF;
END//
--	-------------------------------------------------------------------------------------------------
*/
DROP PROCEDURE IF EXISTS `create_client_view`// -- DROP
CREATE PROCEDURE `create_client_view`()
NOT DETERMINISTIC
SQL SECURITY DEFINER
COMMENT 'to create clients` views'
BEGIN



	DECLARE `manager_id` INT DEFAULT 0;
	DECLARE done INT DEFAULT 0;

	DECLARE cur1 CURSOR FOR
		SELECT `new_managers`.`user_id` FROM `new_managers`;

	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;


	OPEN cur1;
	FETCH cur1 INTO `manager_id`;
	WHILE NOT done DO



-- SELECT `manager_id`;


	SET @sql_stmt1 = CONCAT(
'CREATE OR REPLACE VIEW `clients_', manager_id, '` AS
	SELECT 	`users`.`id` AS `id`,
			`users`.`firstname` AS `firstname`,
			`users`.`surname` AS `surname`,
			`users`.`info` AS `info`,
			`users`.`email` AS `email`
	FROM `users`
	LEFT JOIN `cities` ON `cities`.`id` = `users`.`city_id`
	LEFT JOIN `countries` ON `countries`.`id` = `cities`.`country_id`
	WHERE	`users`.`owner_id` = ', manager_id, ' AND
			`users`.`level` = \'client\'' );

	PREPARE stmt1 FROM @sql_stmt1;
	EXECUTE stmt1;






-- 		DELETE FROM `new_managers` WHERE `new_managers`.`user_id` = `manager_id`;

		FETCH cur1 INTO `manager_id`;
	END WHILE;




END//
-- -------------------------------------------------------------------------------------------------

DROP EVENT IF EXISTS `event_create_client_view`// -- DROP
/*
CREATE EVENT `event_create_client_view` ON SCHEDULE EVERY 10 SECOND COMMENT 'checks managers table and creates clients` views ' DO
BEGIN
-- 	DECLARE `manager_id` INT DEFAULT 0;
-- 	DECLARE done INT DEFAULT 0;
-- 
-- 	DECLARE cur1 CURSOR FOR
-- 		SELECT `new_managers`.`user_id` FROM `new_managers`;
-- 
-- 	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
-- 
-- 
-- 	OPEN cur1;
-- 	FETCH cur1 INTO `manager_id`;
-- 	WHILE NOT done DO

		CALL `create_client_view`();

-- 		DELETE FROM `new_managers` WHERE `new_managers`.`user_id` = `manager_id`;
-- 
-- 		FETCH cur1 INTO `manager_id`;
-- 	END WHILE;

END//
-- -------------------------------------------------------------------------------------------------
*/




--	-------------------------------------------------------------------------------------------------
--	-------------------------------------------------------------------------------------------------
--	-------------------------------------------------------------------------------------------------
delimiter ;
