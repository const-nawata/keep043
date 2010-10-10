DELIMITER //





DROP PROCEDURE IF EXISTS `is_day_off`//
CREATE PROCEDURE `is_day_off`( IN `p_ag_id` INT, IN `p_date` DATE, IN `p_start_date` DATE )
	COMMENT 'defines if day is off'
	NOT DETERMINISTIC
BEGIN
	SET @ag_id	= `p_ag_id`;
	SET @v_date	= `p_date`;
	SET @off_items	= 'SELECT COUNT(*) INTO @v_count FROM `ca_daysoff`
	LEFT JOIN `ca_daysoff_pattern` ON `ca_daysoff_pattern`.`ID` = `ca_daysoff`.`PATT_ID`
	WHERE `AGENDA_ID` = ? AND ( ? BETWEEN `START_DATE` AND `END_DATE` ) 
	AND `isDateValidByPattern`( ?, `START_DATE`, IFNULL( `CYCLE`, 0 ), IFNULL( `PERIOD`, 0 ), IFNULL( `WEEK_DAYS`, 0 ) )';
	PREPARE off_items_stmt FROM @off_items;
	EXECUTE off_items_stmt USING @ag_id, @v_date, @v_date;




	SELECT @v_count AS `v_count`;





-- 	SELECT COUNT(*) AS `count`  FROM `ca_daysoff`
-- 	LEFT JOIN `ca_daysoff_pattern` ON `ca_daysoff_pattern`.`ID` = `ca_daysoff`.`PATT_ID`
-- 	WHERE `AGENDA_ID` = `p_ag_id`
-- 	;
END//

CALL `is_day_off`( 1, '2010-10-10', '2010-10-01' )//




/*

DROP FUNCTION IF EXISTS `get_marker`//
CREATE FUNCTION `get_marker`()
	RETURNS VARCHAR(16) CHARSET utf8
	DETERMINISTIC
BEGIN
	DECLARE `marker_r` INT;
	DECLARE `marker`   VARCHAR(16);
	
	SELECT COUNT(*) FROM `CA_STATUSES` WHERE `CODE` = 'marker' AND `ORG_CODE` = 'root' INTO	`marker_r`;
	
	IF marker_r = 0 THEN
		INSERT INTO `CA_STATUSES` (`CODE`, `NAME`, `ORG_CODE`) VALUES ('marker', '0', 'root');
	END IF;

	SELECT `NAME` FROM `CA_STATUSES` WHERE `CODE` = 'marker' AND `ORG_CODE` = 'root' LIMIT 1 INTO `marker_r`;
	
	IF `marker_r` >= 999999 THEN
		SET `marker_r` = 0;
	END IF;

	SET `marker_r` = `marker_r` + 1;

	UPDATE `CA_STATUSES` SET `NAME` = `marker_r` WHERE `CODE` = 'marker' AND `ORG_CODE` = 'root';
	
	SET `marker` = CONCAT( CAST( UNIX_TIMESTAMP() AS CHAR ), LPAD( `marker_r`, 6, '0' ) );
	RETURN `marker`;
END//
-- -------------------------------------------------------------------------------------------------


INSERT INTO `CA_APPOINTMENTS`
  (`CA_APPOINTMENTS`.`APPTYPE_ID`,
  `CA_APPOINTMENTS`.`START_DATE`,
  `CA_APPOINTMENTS`.`START_TIME`,
  `CA_APPOINTMENTS`.`ORG_CODE`)
VALUES
  (1,
   '2010-08-10',
   '23:45:00',
   'aaa')//





INSERT INTO `ca_free_times` 
( `AGENDA_ID`, `START_DATE`, `END_DATE`, `START_TIME`, `END_TIME`, `ORG_CODE` ) VALUES 
(1, '2010-08-01', '2010-08-31', '10:00:00', '13:00:00', 'aaa' ),
-- (1, '2010-08-01', '2010-08-31', '10:00:00', '11:00:00', 'aaa' ),
-- (1, '2010-08-01', '2010-08-31', '11:00:00', '12:00:00', 'aaa' ),
-- (1, '2010-08-01', '2010-08-31', '10:30:00', '11:30:00', 'aaa' ),
-- (1, '2010-08-01', '2010-08-31', '09:00:00', '11:00:00', 'aaa' ),
-- (1, '2010-08-01', '2010-08-31', '14:00:00', '15:00:00', 'aaa' ),
(1, '2010-08-01', '2010-08-31', '11:00:00', '12:00:00', 'aaa' )//

DELETE FROM `tst`//


################### -------------------------------- ######################################################
-- CALL `get_any_agenda_month`( '2010-08-10 12:00:00', 8, 2010, 1, '1', FALSE )//

CALL `get_any_agenda_month`( '2010-08-10 12:00:00', 8, 2010, 1, -2, 1, 'aaa', FALSE )//

SHOW PROCESSLIST//

KILL 47//










DROP PROCEDURE IF EXISTS get_any_agenda_for_month//
CREATE PROCEDURE get_any_agenda_for_month(IN def_start_date DATE, IN def_start_time TIME, IN def_month TINYINT, IN def_year SMALLINT, IN def_app_type_id INT, IN def_agendas VARCHAR(500), IN def_org_code VARCHAR(50))
BEGIN

	DECLARE days_in_month			  INT DEFAULT 0;
	DECLARE i						  INT DEFAULT 0;
	DECLARE count_of_days_beforehand   INT;
	DECLARE normalized_beforehand	  INT;
	DECLARE normalized_beforehand_time TIME;
	DECLARE start_date				 DATE;
	
	DECLARE app_duration			   INT;
	DECLARE app_minutes_beforehand	 INT;
	DECLARE app_day_boundary		   INT;
	DECLARE app_week_days			  VARCHAR(13);
	DECLARE app_contract_start_time    TIME;
	DECLARE app_contract_end_time	  TIME;
	DECLARE app_contract_start		 INT;
	DECLARE app_contract_end		   INT;
	DECLARE app_multy				  INT(2);
	DECLARE def_start_time_in_min	  INT;

	SET def_start_time_in_min = HOUR( def_start_time ) * 60 + MINUTE( def_start_time );

	IF (def_month IS NULL) THEN
		SET def_month = MONTH( def_start_date );
	END IF;
	
	IF (def_year IS NULL) THEN
		SET def_year = YEAR( def_start_date );
	END IF;

	SET start_date = DATE_ADD( MAKEDATE( def_year, 1 ), INTERVAL (def_month - 1) MONTH );
	IF (start_date < def_start_date) THEN
	SET start_date = def_start_date;
	END IF;

	SELECT
	TIME,
	(CASE WHEN MIN_TIME IS NULL THEN 0 ELSE MIN_TIME * 60 END),
	MAX_TIME,
	AT_PERIOD_START_TIME,
	(CASE WHEN AT_PERIOD_END_TIME <> '00:00:00' THEN AT_PERIOD_END_TIME ELSE '23:59:59' END),
	AT_PERIOD_DAY,
	IS_MULTY
	INTO
	app_duration, app_minutes_beforehand, app_day_boundary, app_contract_start_time, app_contract_end_time, app_week_days, app_multy
	FROM
	CA_APPOINTMENT_TYPES
	WHERE
	CA_APPOINTMENT_TYPES.ID = def_app_type_id;
	
	IF (app_day_boundary IS NULL) THEN
	SET def_start_date = start_date;
	SET app_day_boundary = DATEDIFF( LAST_DAY( def_start_date ), def_start_date ) + 1;
	ELSE
	SET app_day_boundary = app_day_boundary - DATEDIFF( start_date, def_start_date ) + 1;
	SET def_start_date = start_date;
	SET days_in_month = DATEDIFF( LAST_DAY( def_start_date ), def_start_date ) + 1;
	IF (app_day_boundary > days_in_month) THEN
	SET app_day_boundary = days_in_month;
	END IF;
	END IF;
	
	SET count_of_days_beforehand = (def_start_time_in_min + app_minutes_beforehand) DIV (24 * 60);
	SET normalized_beforehand = (def_start_time_in_min + app_minutes_beforehand) % 1440;
	SET normalized_beforehand_time = SEC_TO_TIME( normalized_beforehand * 60 );
	SET app_contract_start = HOUR( app_contract_start_time ) * 60 + MINUTE( app_contract_start_time );
	SET app_contract_end = HOUR( app_contract_end_time ) * 60 + MINUTE( app_contract_end_time );
	
	SET @fetch_result = 'SELECT CAST(@def_start_date AS DATE) AS DATE, @_agenda_id AS AGENDA_ID;';
	PREPARE stmt_result FROM @fetch_result;
	
	IF (app_multy = 0) THEN
	DROP TABLE IF EXISTS OUT_OF_CONTRACT_HOURS;
	
	IF (def_agendas = '') THEN
	SET @create_contract_table = CONCAT(
	'CREATE TABLE OUT_OF_CONTRACT_HOURS ENGINE = MEMORY ',
	'SELECT ',
	'  agenda.START_TIME, agenda.END_TIME, agenda.AGENDA_ID agenda_id, agenda.ORG_CODE ',
	'FROM ',
	'  CA_AGENDAS agenda ',
	'WHERE ',
	'  agenda.ORG_CODE = \'', def_org_code, '\' AND agenda.USER_LEVEL = 3 AND BLOCKED_THER = 0;' );
	ELSE
	SET @create_contract_table = CONCAT(
	'CREATE TABLE OUT_OF_CONTRACT_HOURS ENGINE = MEMORY ',
	'SELECT ',
	'  agenda.START_TIME, agenda.END_TIME, agenda.AGENDA_ID agenda_id, agenda.ORG_CODE ',
	'FROM ',
	'  CA_AGENDAS agenda ',
	'WHERE ',
	'  agenda.AGENDA_ID IN (', def_agendas, ')' );
	END IF;
	
	PREPARE stmt_create_contract FROM @create_contract_table;
	EXECUTE stmt_create_contract;
	
	SET @drop_table = 'DROP TABLE IF EXISTS occupancy_intervals;';
	PREPARE stmt_drop FROM @drop_table;
	
	SET @create_occupancy_table = CONCAT(
	'CREATE TABLE occupancy_intervals ENGINE = MEMORY ',
	'SELECT * ',
	'FROM ',
	'  ( ',
	'  SELECT ',
	'    dayoff.START_TIME _start, (CASE WHEN dayoff.START_TIME_END <> \'00:00:00\' THEN dayoff.START_TIME_END ELSE \'23:59:59\' END) _end, dayoff.AGENDA_ID agenda_id ',
	'  FROM ',
	'    CA_DAYSOFF_PATTERN pattern, CA_DAYSOFF dayoff ',
	'  WHERE ? BETWEEN dayoff.START_DATE AND dayoff.END_DATE ',
	CASE WHEN def_agendas = '' THEN CONCAT(
	'    AND dayoff.ORG_CODE = \'', def_org_code, '\''
	) ELSE CONCAT(
	'    AND dayoff.AGENDA_ID IN (', def_agendas, ')'
	) END,
	'    AND pattern.ID = dayoff.PATT_ID ',
	'    AND ((pattern.CYCLE = 1 AND DATEDIFF(?, dayoff.START_DATE) % pattern.PERIOD = 0) ',
	'    OR ((pattern.CYCLE = 2) AND ',
	'    SUBSTR(BIN(255 - pattern.WEEK_DAYS), 9 - DAYOFWEEK(?), 1) = \'0\' AND ',
	'    (DATEDIFF(?, DATE_SUB(dayoff.START_DATE, INTERVAL ',
	'    (CASE WHEN DAYOFWEEK(dayoff.START_DATE) = 1 THEN 6 ELSE DAYOFWEEK(dayoff.START_DATE) - 2 END) DAY)) DIV 7) % pattern.PERIOD = 0)) ',
	
	'  UNION ALL ',	-- -----------------------------------------

	'  SELECT ',
	'    dayoff.START_TIME _start, (CASE WHEN dayoff.START_TIME_END <> \'00:00:00\' THEN dayoff.START_TIME_END ELSE \'23:59:59\' END) _end, dayoff.AGENDA_ID agenda_id ',
	'  FROM ',
	'    CA_DAYSOFF dayoff ',
	'  WHERE ',
	'    ? BETWEEN dayoff.START_DATE AND dayoff.END_DATE ',
	CASE WHEN def_agendas = '' THEN CONCAT(
	'    AND dayoff.ORG_CODE = \'', def_org_code, '\''
	) ELSE CONCAT(
	'    AND dayoff.AGENDA_ID IN (', def_agendas, ')'
	) END,
	'    AND dayoff.PATT_ID IS NULL ',

	'  UNION ALL ',	-- -----------------------------------------

	'  SELECT ',
	'    appointments.START_TIME _start, SEC_TO_TIME(app_types.TIME * 60 + TIME_TO_SEC(appointments.START_TIME)) _end, assigned_agenda.AGENDA_ID agenda_id ',
	'  FROM ',
	'    CA_APPOINTMENTS appointments, CA_APP_ASSIGNED_AGENDA assigned_agenda, CA_APPOINTMENT_TYPES app_types ',
	'  WHERE ',
	'    appointments.DATE = ? AND appointments.ORG_CODE = \'', def_org_code, '\' ',
	'    AND appointments.APP_ID = assigned_agenda.APP_ID ',
	CASE WHEN def_agendas = '' THEN CONCAT(
	'    AND assigned_agenda.ORG_CODE = \'', def_org_code, '\''
	) ELSE CONCAT(
	'    AND assigned_agenda.AGENDA_ID IN (', def_agendas, ')'
	) END,
	'    AND appointments.APPTYPE_ID = app_types.ID ',

	'  UNION ALL ',	-- -----------------------------------------

	'  SELECT ',
	'    \'00:00:00\' _start, agenda.START_TIME _end, agenda.AGENDA_ID agenda_id ',
	'  FROM ',
	'    OUT_OF_CONTRACT_HOURS agenda ',
	'  WHERE ',
	'    agenda.ORG_CODE = \'', def_org_code, '\' ',
	'  UNION ALL ',
	'  SELECT ',
	'    agenda.END_TIME _start, \'23:59:59\' _end, agenda.AGENDA_ID agenda_id ',
	'  FROM ',
	'    OUT_OF_CONTRACT_HOURS agenda ',
	'  WHERE ',
	'    agenda.ORG_CODE = \'', def_org_code, '\' ',
	'  ) AS result_set ',
	'ORDER BY ',
	'  result_set.agenda_id, result_set._start;' );



	PREPARE stmt_create FROM @create_occupancy_table;
	
	SET @clean_absorbtion = CONCAT(
	'DELETE intervals ',
	'FROM ',
	'  occupancy_intervals intervals, ',
	'  (SELECT ',
	'    oi1.agenda_id, oi1._start, oi1._end ',
	'  FROM ',
	'    occupancy_intervals oi1, ',
	'    occupancy_intervals oi2 ',
	'  WHERE ',
	'    oi2.agenda_id = oi1.agenda_id AND ((oi1._start >= oi2._start AND oi1._end < oi2._end) OR ',
	'    (oi1._start > oi2._start AND oi1._end <= oi2._end))) absorbed ',
	'WHERE ',
	'  intervals.agenda_id = absorbed.agenda_id AND intervals._start = absorbed._start AND intervals._end = absorbed._end;' );
	
	SET @get_first_agenda = CONCAT(
	'SELECT ',
	'  AGENDA_ID ',
	'INTO ',
	'  @_agenda_id ',
	'FROM ',
	'  (SELECT ',
	'    agenda_id AS AGENDA_ID, ',
	'    @end_date AS START_TIME, ',
	'    @end_date := CASE WHEN _END >= SEC_TO_TIME( ? * 60 ) THEN TIME_TO_SEC(CAST(_END AS TIME)) DIV 60 ELSE ? END, ',
	'    CASE WHEN _start < \'', app_contract_end_time, '\' THEN TIME_TO_SEC(_start) DIV 60 ELSE ', app_contract_end, ' END AS END_TIME ',
	'  FROM ',
	'    occupancy_intervals ',
	'  ) AS result_intervals ',
	'WHERE ',
	'  END_TIME - START_TIME >= \'', app_duration, '\' ',
	'ORDER BY ',
	'  START_TIME ',
	'LIMIT ',
	'  1;' );
	
	search_single:
	WHILE i < app_day_boundary DO
		SET @def_start_date = ADDDATE( def_start_date, i );
	
		IF (SUBSTRING( app_week_days, (DAYOFWEEK( @def_start_date ) * 2) - 1, 1 ) = '0') THEN
			SET i = i + 1;
			ITERATE search_single;
		END IF;
	
		EXECUTE stmt_drop;
		EXECUTE stmt_create USING @def_start_date, @def_start_date, @def_start_date, @def_start_date, @def_start_date, @def_start_date;
		PREPARE stmt_clean FROM @clean_absorbtion;
		EXECUTE stmt_clean;
	



		SET @_agenda_id = NULL;
	
		IF (i > count_of_days_beforehand OR (i = 0 AND count_of_days_beforehand = 0 AND normalized_beforehand <= app_contract_start)) THEN
			SET @appropriate_start_time = app_contract_start;
		ELSEIF (i = count_of_days_beforehand AND (i > 0 OR normalized_beforehand > app_contract_start)) THEN
			SET @appropriate_start_time = normalized_beforehand;
		ELSE
			SET @appropriate_start_time = 1440; # set end of day
		END IF;
	
		PREPARE stmt_first_agenda FROM @get_first_agenda;
	
		IF (@appropriate_start_time < 1440) THEN
			EXECUTE stmt_first_agenda USING @appropriate_start_time, @appropriate_start_time;
		END IF;
	
		IF (@_agenda_id IS NULL) THEN
			IF (i = 0) THEN
				SET @appropriate_start_time = def_start_time_in_min;
			ELSE
				SET @appropriate_start_time = app_contract_start;
			END IF;
			EXECUTE stmt_first_agenda USING @appropriate_start_time, @appropriate_start_time;
		END IF;
	
		IF (@_agenda_id IS NOT NULL) THEN
			EXECUTE stmt_result;
		END IF;
		
		SET i = i + 1;
	END WHILE search_single;
	
	EXECUTE stmt_drop;



--	MULTI

	ELSE
	SET @fetch_day_result = CONCAT(
	'SELECT ',
	'  assigned_agenda.AGENDA_ID agenda_id ',
	'INTO ',
	'  @_agenda_id ',
	'FROM ',
	'  CA_APPOINTMENTS appointments, CA_APP_ASSIGNED_AGENDA assigned_agenda ',
	'WHERE ',
	'  appointments.DATE = ? AND appointments.ORG_CODE = \'', def_org_code,
	'\'  AND appointments.APP_ID = assigned_agenda.APP_ID ',
	'    AND assigned_agenda.ORG_CODE = \'', def_org_code,
	'\'  AND appointments.APPTYPE_ID = ', def_app_type_id,
	'    AND appointments.START_TIME >= ? ',
	'    AND (SELECT COUNT(*) FROM CA_APP_ASSIGNED_CLIENTS clients WHERE clients.APP_ID = appointments.APP_ID) < appointments.MAX_NUMBER_CLIENT ',
	' LIMIT 1;' );
	PREPARE stmt_day_result FROM @fetch_day_result;
	
	search_multy:
	WHILE i < app_day_boundary
	DO
	SET @def_start_date = ADDDATE( def_start_date, i );
	
	IF (SUBSTRING( app_week_days, (DAYOFWEEK( @def_start_date ) * 2) - 1, 1 ) = '0') THEN
	SET i = i + 1;
	ITERATE search_multy;
	END IF;
	
	SET @_agenda_id = NULL;
	
	IF (i > count_of_days_beforehand OR (i = 0 AND count_of_days_beforehand = 0 AND normalized_beforehand <= app_contract_start)) THEN
	SET @appropriate_start_time = app_contract_start;
	ELSEIF (i = count_of_days_beforehand AND (i > 0 OR normalized_beforehand > app_contract_start)) THEN
	SET @appropriate_start_time = normalized_beforehand_time;
	ELSE
	SET @appropriate_start_time = '23:59:59'; # set end of day
	END IF;
	
	IF (@appropriate_start_time < '23:59:59') THEN
	EXECUTE stmt_day_result USING @def_start_date, @appropriate_start_time;
	END IF;
	
	IF (@_agenda_id IS NULL) THEN
	IF (i = 0) THEN
	SET @appropriate_start_time = def_start_time;
	ELSE
	SET @appropriate_start_time = app_contract_start_time;
	END IF;
	EXECUTE stmt_day_result USING @def_start_date, @appropriate_start_time;
	END IF;
	
	IF (@_agenda_id IS NOT NULL) THEN
	EXECUTE stmt_result;
	END IF;
	
	SET i = i + 1;
	END WHILE search_multy;
	
	END IF;
END//






DELETE `intervals` FROM `ca_free_times` AS `intervals`,
	( SELECT oi1.`START_TIME`, oi1.`END_TIME` 
		FROM 
			`ca_free_times` AS oi1,
			`ca_free_times` AS oi2
		WHERE ((oi1.`START_TIME` >= oi2.`START_TIME` AND oi1.`END_TIME` < oi2.`END_TIME`) OR 
	    (oi1.`START_TIME` > oi2.`START_TIME` AND oi1.`END_TIME` <= oi2.`END_TIME`))) AS absorbed 
WHERE intervals.`START_TIME` = absorbed.`START_TIME` AND intervals.`END_TIME` = absorbed.`END_TIME`
//




 SET @ttt = 2//


SELECT @ttt AS `TTT`,	@ttt := 17//


SELECT @ttt AS `TTT`,	@ttt := 18//

SELECT IF( 2 = 32, 1, 3) AS aaaa //





	SET @clean_absorbtion = CONCAT(
	'DELETE intervals ',
	'FROM ',
	'  occupancy_intervals intervals, ',
	'  (SELECT ',
	'    oi1.agenda_id, oi1._start, oi1._end ',
	'  FROM ',
	'    occupancy_intervals oi1, ',
	'    occupancy_intervals oi2 ',
	'  WHERE ',
	'    oi2.agenda_id = oi1.agenda_id AND ((oi1._start >= oi2._start AND oi1._end < oi2._end) OR ',
	'    (oi1._start > oi2._start AND oi1._end <= oi2._end))) absorbed ',
	'WHERE ',
	'  intervals.agenda_id = absorbed.agenda_id AND intervals._start = absorbed._start AND intervals._end = absorbed._end;' );
*/










DELIMITER ;



-- 	SET @get_busy_items	= CONCAT(
-- 		'INSERT INTO `busy_items_tbl_tmp` ( `d_t_start`, `d_t_end`  ) ',
-- 
-- 		'( SELECT `d_t_start`, `d_t_end` ',
-- 		'FROM (',
-- 			'SELECT ',
-- 				'CONCAT( ?, \' \', `START_TIME` ) AS `d_t_start`, ',
-- 
-- 				'IF( ( `START_TIME` > `END_TIME`), ',
-- 					'CONCAT( DATE_ADD( ?, INTERVAL 1 DAY ), \' \', `END_TIME` ), ',
-- 					'CONCAT( ?, \' \', `END_TIME` ) ) AS `d_t_end` ',
-- 
-- 				'FROM `ca_appointments` ',
-- 				'LEFT JOIN `ca_app_assigned_agendas` ON `ca_app_assigned_agendas`.`APP_ID` = `ca_appointments`.`APP_ID` ',
-- 				'LEFT JOIN `ca_daysoff_pattern` ON `ca_daysoff_pattern`.`ID` = `ca_appointments`.`PATT_ID` ',
-- 				'WHERE ( ? BETWEEN `START_DATE` AND `END_DATE` ) AND ',
-- 				'NOT ( `END_DATE` = ? AND `START_TIME` > `END_TIME` ) AND ',
-- 				'`ca_app_assigned_agendas`.`AGENDA_ID` = ? AND ',
-- 				'`isDateValidByPattern`( ?, `START_DATE`, `END_DATE`, IFNULL( `CYCLE`, 0 ), IFNULL( `PERIOD`, 0 ), IFNULL( `WEEK_DAYS`, 0 ) ) ',
-- 
--  			'UNION ALL ',
-- 
-- 			'SELECT CONCAT( ?, \' \', `START_TIME` ) AS `d_t_start`, ',
-- 				'IF( ( `START_TIME` > `END_TIME`), ',
-- 					'CONCAT( DATE_ADD( ?, INTERVAL 1 DAY ), \' \', `END_TIME` ), ',
-- 					'CONCAT( ?, \' \', `END_TIME` ) ) AS `d_t_end` ',
-- 	
-- 				'FROM `ca_free_times` ',
-- 				'LEFT JOIN `ca_daysoff_pattern` ON `ca_daysoff_pattern`.`ID` = `ca_free_times`.`PATT_ID`',
-- 				'WHERE ( ? BETWEEN `START_DATE` AND `END_DATE` ) AND ',
-- 				'NOT ( `END_DATE` = ? AND `START_TIME` > `END_TIME` ) AND ',
-- 				'`AGENDA_ID` = ? AND ',
-- 				'`isDateValidByPattern`( ?, `START_DATE`, `END_DATE`, IFNULL( `CYCLE`, 0 ), IFNULL( `PERIOD`, 0 ), IFNULL( `WEEK_DAYS`, 0 ) ) ',
-- 
-- 		') AS `ins_tbl` ORDER BY `d_t_start` )'
-- 	);
-- 
