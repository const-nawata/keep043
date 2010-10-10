DELIMITER //
-- -------------------------------------------------------------------------------------------------
-- -------------------------------------------------------------------------------------------------
/*
DROP FUNCTION IF EXISTS `AppAgendasIds`//
CREATE FUNCTION `AppAgendasIds`( app_id INT( 11 ) )
	RETURNS TEXT CHARSET utf8 NOT DETERMINISTIC
	COMMENT 'Return assigned to appointment agendas ids'
BEGIN
	DECLARE done   INT DEFAULT 0;
	DECLARE ag_id  INT;
	DECLARE ag_ids TEXT DEFAULT '';

	DECLARE cur1 CURSOR FOR
		SELECT	`CA_APP_ASSIGNED_AGENDAS`.`AGENDA_ID`
		FROM `CA_APP_ASSIGNED_AGENDAS`
		WHERE `CA_APP_ASSIGNED_AGENDAS`.`APP_ID` = app_id;

	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

	OPEN cur1;
	FETCH cur1 INTO ag_id;
	WHILE NOT done DO
		IF( ag_ids = '' ) THEN
			SET ag_ids = ag_id;
		ELSE
			SET ag_ids = CONCAT( ag_ids, ',', ag_id );
		END IF;

		FETCH cur1 INTO ag_id;
	END WHILE;
	CLOSE cur1;

	RETURN ag_ids;
END//
-- -------------------------------------------------------------------------------------------------

DROP FUNCTION IF EXISTS `AppClientsIds`//
CREATE FUNCTION `AppClientsIds`( app_id INT( 11 ) )
	RETURNS TEXT CHARSET utf8 NOT DETERMINISTIC
	COMMENT 'Return assigned to appointment agendas ids'
BEGIN
	DECLARE done			INT DEFAULT 0;
	DECLARE cl_id, id_val	CHAR(11);
	DECLARE cl_ids			TEXT DEFAULT '';

	DECLARE cur1 CURSOR FOR
		SELECT IFNULL( `CA_APP_ASSIGNED_CLIENTS`.`CLIENT_ID`, 'null' )
		FROM `CA_APP_ASSIGNED_CLIENTS`
		WHERE `CA_APP_ASSIGNED_CLIENTS`.`APP_ID` = app_id;

	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

	OPEN cur1;
	FETCH cur1 INTO cl_id;
	WHILE NOT done DO
		IF (cl_ids = '') THEN
			SET cl_ids = cl_id;
		ELSE
			SET cl_ids = CONCAT( cl_ids, ',', cl_id );
		END IF;

		FETCH cur1 INTO cl_id;
	END WHILE;
	CLOSE cur1;

	RETURN cl_ids;
END//
-- -------------------------------------------------------------------------------------------------
*/
DROP FUNCTION IF EXISTS `isDateValidByPattern`//
CREATE FUNCTION `isDateValidByPattern`( `p_date` DATE, `p_start_date` DATE, `p_cycle` TINYINT(4), `p_period` INT(11), `p_week_days` TINYINT(4) )
	RETURNS BOOLEAN NOT DETERMINISTIC
	COMMENT 'Return pattern definitions'
BEGIN
	DECLARE `count_days` INT;
	DECLARE `week_monday`, `first_week_monday` DATE;


	CASE `p_cycle`
		WHEN 1 THEN		#	Day Cycle
			IF ( DATEDIFF( `p_date`, `p_start_date` ) MOD `p_period` ) = 0 THEN
				RETURN TRUE;
			ELSE
				RETURN FALSE;
			END IF;

-- 			RETURN TRUE;

		WHEN 2 THEN		#	Week Cycle
			SET `count_days` = DATE_FORMAT( `p_date`, '%w' );
			SET `count_days` = IF( !`count_days`, 7, `count_days`) - 1;
			SET `week_monday` = `p_date` - INTERVAL `count_days` DAY;

			SET `count_days` = DATE_FORMAT( `p_start_date`, '%w' );
			SET `count_days` = IF( !`count_days`, 7, `count_days`) - 1;
			SET `first_week_monday` = `p_start_date` - INTERVAL `count_days` DAY;

			IF 
				( ( DATEDIFF( `week_monday`, `first_week_monday` ) / 7 ) MOD `p_period` ) = 0
				AND 
				( `p_week_days` & `get_week_day_mask`( `p_date` ) )
			THEN
				RETURN TRUE;
			ELSE
				RETURN FALSE;
			END IF;
		ELSE
			RETURN TRUE;
	END CASE;


	RETURN TRUE;
END//
-- -------------------------------------------------------------------------------------------------

DROP FUNCTION IF EXISTS `get_week_day_mask`//
CREATE FUNCTION `get_week_day_mask`( `chk_date` DATE )
	RETURNS INT NOT DETERMINISTIC
	COMMENT 'Return mask of week day'
BEGIN
	DECLARE `v_week_day` INT;
	SET `v_week_day`	= DAYOFWEEK( `chk_date` );

	RETURN CASE `v_week_day`
		WHEN 1 THEN 1
		WHEN 2 THEN 2
		WHEN 3 THEN 4
		WHEN 4 THEN 8
		WHEN 5 THEN 16
		WHEN 6 THEN 32
		WHEN 7 THEN 64 END;
END//

-- -------------------------------------------------------------------------------------------------
-- -------------------------------------------------------------------------------------------------
DELIMITER ;
