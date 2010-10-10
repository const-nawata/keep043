DELIMITER //
-- -------------------------------------------------------------------------------------------------
-- -------------------------------------------------------------------------------------------------
 



-- REMARK. Min time is not used!!!
DROP PROCEDURE IF EXISTS `get_any_agenda_month`//
CREATE PROCEDURE `get_any_agenda_month`(IN `p_d_t_now` TIMESTAMP,
										IN `p_month` TINYINT,
										IN `p_year` SMALLINT,
										IN `p_app_type_id` INT,
										IN `p_ag_id` INT,
										IN `p_cat_id` INT,
										IN `p_org_code` VARCHAR(50),
										IN `p_debug` BOOLEAN )
	COMMENT 'gets any available agenda user for selected month and year'
	NOT DETERMINISTIC
BEGIN
	DECLARE `v_ag_id` INT;
-- 	DECLARE `v_pos1`, `v_pos2` INT DEFAULT 1;
	DECLARE	`v_ag_start_time`, `v_ag_end_time` TIME;
	DECLARE `v_first_date`, `v_last_date`, `v_prv_date`, `v_cur_date`, `v_nxt_date`, `v_anx_date`, `del_date`, `v_app_type_last_date` DATE;
	DECLARE	`v_today` DATE DEFAULT DATE_FORMAT( `p_d_t_now`, '%Y-%m-%d' );
	DECLARE	`v_n_get_day` INT DEFAULT 3;

	DECLARE `v_ag_d_t_start`, `v_ag_d_t_end`, `v_max_d_t` TIMESTAMP;

	DECLARE `v_result` VARCHAR(470) DEFAULT '';
	DECLARE `v_busy_items_tbl_name` VARCHAR(20) DEFAULT '';

	DECLARE `v_app_type_dur` INT DEFAULT 0;
	DECLARE `v_app_type_patt` INT  DEFAULT 127;
	DECLARE `v_app_type_start`, `v_app_type_end` TIME DEFAULT '00:00:00';
	DECLARE `v_is_multi` TINYINT(4) DEFAULT 0;


	DECLARE done INT DEFAULT 0;

 	DECLARE cursor_ag_single CURSOR FOR
		SELECT `ca_agendas`.`AGENDA_ID`, `START_TIME`, `END_TIME`  FROM `ca_agendas` WHERE `AGENDA_ID` = `p_ag_id`;

 	DECLARE cursor_ag_cat CURSOR FOR
		SELECT `ca_agendas`.`AGENDA_ID`, `START_TIME`, `END_TIME` 
		FROM `ca_agendas`
		LEFT JOIN `ca_agendas_assigned_categories` ON `ca_agendas_assigned_categories`.`AGENDA_ID` = `ca_agendas`.`AGENDA_ID`
		WHERE `ca_agendas_assigned_categories`.`AGE_CAT_ID` = `p_cat_id`;

 	DECLARE cursor_ag_org CURSOR FOR
		SELECT `ca_agendas`.`AGENDA_ID`, `START_TIME`, `END_TIME`  FROM `ca_agendas` WHERE `ORG_CODE` = `p_org_code`;

	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
		
###	Declarations end ####################






####	DEBUG ZONE START
-- IF `p_debug` THEN
-- 	DROP TABLE IF EXISTS `dbg_busy_items_tbl`;
-- 	CREATE TABLE `dbg_busy_items_tbl`( `cur_date` DATE, `d_t_start` TIMESTAMP, `d_t_end` TIMESTAMP ) ENGINE = MEMORY;
-- END IF;
####	DEBUG ZONE END





	SELECT 
		`TIME`,
		`v_today` + INTERVAL `MAX_TIME` DAY,
		`IS_MULTY`,
		`AT_PERIOD_START_TIME`,
		`AT_PERIOD_END_TIME`,
		`AT_PERIOD_DAY`
	INTO 
		`v_app_type_dur`,
		`v_app_type_last_date`,
		`v_is_multi`,
		`v_app_type_start`,
		`v_app_type_end`,
		`v_app_type_patt`
	FROM `ca_appointment_types` WHERE `ID` = `p_app_type_id` LIMIT 1;


	SET `v_busy_items_tbl_name`	= CONCAT( 'tmp_', `get_marker`() );

	SET @busy_items	= CONCAT( 'CREATE TABLE ', `v_busy_items_tbl_name`, '( `d_t_start` TIMESTAMP, `d_t_end` TIMESTAMP ) ENGINE = MEMORY' );
	PREPARE busy_items_stmt FROM @busy_items;
	EXECUTE busy_items_stmt;


	SET @get_busy_items	= CONCAT(
'INSERT INTO ', `v_busy_items_tbl_name`, ' ( `d_t_start`, `d_t_end`  ) 
( SELECT `d_t_start`, `d_t_end`
FROM (
	SELECT 
		CONCAT( ?, \' \', `START_TIME` ) AS `d_t_start`, 

		IF( ( `START_TIME` > `END_TIME`), 
			CONCAT( DATE_ADD( ?, INTERVAL 1 DAY ), \' \', `END_TIME` ), 
			CONCAT( ?, \' \', `END_TIME` ) ) AS `d_t_end`

		FROM `ca_appointments` 
		LEFT JOIN `ca_app_assigned_agendas` ON `ca_app_assigned_agendas`.`APP_ID` = `ca_appointments`.`APP_ID` 
		LEFT JOIN `ca_daysoff_pattern` ON `ca_daysoff_pattern`.`ID` = `ca_appointments`.`PATT_ID` 
		WHERE ( ? BETWEEN `START_DATE` AND `END_DATE` ) AND 
		NOT ( `END_DATE` = ? AND `START_TIME` > `END_TIME` ) AND 
		`ca_app_assigned_agendas`.`AGENDA_ID` = ? AND 
		`isDateValidByPattern`( ?, `START_DATE`, IFNULL( `CYCLE`, 0 ), IFNULL( `PERIOD`, 0 ), IFNULL( `WEEK_DAYS`, 0 ) ) 

		UNION ALL 

	SELECT CONCAT( ?, \' \', `START_TIME` ) AS `d_t_start`, 
		IF( ( `START_TIME` > `END_TIME`), 
			CONCAT( DATE_ADD( ?, INTERVAL 1 DAY ), \' \', `END_TIME` ), 
			CONCAT( ?, \' \', `END_TIME` ) ) AS `d_t_end`

		FROM `ca_free_times` 
		LEFT JOIN `ca_daysoff_pattern` ON `ca_daysoff_pattern`.`ID` = `ca_free_times`.`PATT_ID`
		WHERE ( ? BETWEEN `START_DATE` AND `END_DATE` ) AND 
		NOT ( `END_DATE` = ? AND `START_TIME` > `END_TIME` ) AND 
		`AGENDA_ID` = ? AND 
		`isDateValidByPattern`( ?, `START_DATE`, IFNULL( `CYCLE`, 0 ), IFNULL( `PERIOD`, 0 ), IFNULL( `WEEK_DAYS`, 0 ) ) 

) AS `ins_tbl`)' );

	PREPARE get_busy_items_stmt FROM @get_busy_items;


	SET `v_first_date`	= CONCAT( `p_year`, '-', `p_month`, '-01' );
	SET `v_last_date`		= LAST_DAY( `v_first_date` );


###	App type pattern, Today and max time dates rendering
	SET `v_cur_date`	= `v_first_date`;
	REPEAT
		IF  ( `v_cur_date` < `v_today` ) OR
			( ( `v_app_type_patt` & `get_week_day_mask`( `v_cur_date` ) ) = 0 ) OR
			IF( `v_app_type_last_date` IS NULL, FALSE, ( `v_cur_date` > `v_app_type_last_date` ) )
		THEN
			SET `v_result` = CONCAT( `v_result`, '"', `v_cur_date`, '":0,' );
		END IF;

		SET `v_cur_date`	= DATE_ADD( `v_cur_date`, INTERVAL 1 DAY );
	UNTIL `v_cur_date` > `v_last_date` END REPEAT; 



	IF `p_ag_id` != -2 THEN
		OPEN cursor_ag_single;
	ELSEIF `p_ag_id` = -2 AND `p_cat_id` = -2 THEN
		OPEN cursor_ag_org;
	ELSE
		OPEN cursor_ag_cat;
	END IF;

	SET @off_items	= 'SELECT COUNT(*) INTO @is_day_off FROM `ca_daysoff` LEFT JOIN `ca_daysoff_pattern` ON `ca_daysoff_pattern`.`ID` = `ca_daysoff`.`PATT_ID`
	WHERE `AGENDA_ID` = ? AND ( ? BETWEEN `START_DATE` AND `END_DATE` ) AND `isDateValidByPattern`( ?, `START_DATE`, IFNULL( `CYCLE`, 0 ), IFNULL( `PERIOD`, 0 ), IFNULL( `WEEK_DAYS`, 0 ) )';
	PREPARE off_items_stmt FROM @off_items;

	

###	Timetables (Agendas) analizing loop
	REPEAT
		IF `p_ag_id` != -2 THEN
			FETCH cursor_ag_single INTO `v_ag_id`, `v_ag_start_time`, `v_ag_end_time`;
		ELSEIF `p_ag_id` = -2 AND `p_cat_id` = -2 THEN
			FETCH cursor_ag_org INTO `v_ag_id`, `v_ag_start_time`, `v_ag_end_time`;
		ELSE
			FETCH cursor_ag_cat INTO `v_ag_id`, `v_ag_start_time`, `v_ag_end_time`;
		END IF;



		IF NOT done THEN
			SET @ag_id = `v_ag_id`;
	
			SET @del_busy_items1	= CONCAT( 'DELETE FROM ', `v_busy_items_tbl_name` );
			PREPARE del_busy_items_stmt1 FROM @del_busy_items1;
			EXECUTE del_busy_items_stmt1;
	
	
	###	All Days analizing loop
			SET `v_cur_date`	= `v_first_date`;
			REPEAT
				SET `v_prv_date`	= `v_cur_date` - INTERVAL 1 DAY;
				SET `v_nxt_date`	= `v_cur_date` + INTERVAL 1 DAY;
				SET `v_anx_date`	= `v_cur_date` + INTERVAL 2 DAY;
	
				SET `v_ag_d_t_start`	= CONCAT( `v_cur_date`, ' ', `v_ag_start_time` );
	
				IF `v_ag_start_time` < `v_ag_end_time` THEN
					SET `v_ag_d_t_end`	= CONCAT( `v_cur_date`, ' ', `v_ag_end_time` );
				ELSE
					SET `v_ag_d_t_end`	= CONCAT( `v_nxt_date`, ' ', `v_ag_end_time` );
				END IF;

				SET @udate = `v_cur_date`;
				EXECUTE off_items_stmt USING @ag_id, @udate, @udate;
	
	### Single Day analizing.
				IF( 
					( LOCATE(`v_cur_date`, `v_result`, 1 ) = 0 ) AND 
					( @is_day_off = 0 ) AND
					( 
						( `v_ag_start_time` = `v_ag_end_time` ) OR
						( ( ( UNIX_TIMESTAMP(`v_ag_d_t_end`) - UNIX_TIMESTAMP(`p_d_t_now`) ) / 60 ) >= `v_app_type_dur` )
						
					)
				) THEN
			
					IF `v_n_get_day` = 3 THEN
						SET `v_n_get_day` = `v_n_get_day` - 1;
						SET @udate		= `v_prv_date`;
						EXECUTE get_busy_items_stmt USING	@udate, @udate, @udate, @udate, @udate, @ag_id, @udate,
															@udate, @udate, @udate, @udate, @udate, @ag_id, @udate;
					END IF;
	
					IF `v_n_get_day` = 2 THEN
						SET `v_n_get_day` = `v_n_get_day` - 1;
						SET @udate	= `v_cur_date`;
						EXECUTE get_busy_items_stmt USING	@udate, @udate, @udate, @udate, @udate, @ag_id, @udate,
															@udate, @udate, @udate, @udate, @udate, @ag_id, @udate;
					END IF;
	
					IF `v_n_get_day` = 1 THEN
						SET `v_n_get_day` = `v_n_get_day` - 1;
						SET @udate	= `v_nxt_date`;
						EXECUTE get_busy_items_stmt USING	@udate, @udate, @udate, @udate, @udate, @ag_id, @udate,
															@udate, @udate, @udate, @udate, @udate, @ag_id, @udate;
					END IF;
	
					SET @udate	= `v_anx_date`;
					EXECUTE get_busy_items_stmt USING	@udate, @udate, @udate, @udate, @udate, @ag_id, @udate,
														@udate, @udate, @udate, @udate, @udate, @ag_id, @udate;
	
	
					### Before agenda and boundary items of timetable deletion.
					SET @del_busy_items2	= 
					CONCAT( 
	'DELETE FROM ', `v_busy_items_tbl_name`, 
	' WHERE `d_t_end` <= \'', `v_ag_d_t_start`, '\' OR `d_t_start` = `d_t_end`' );
	
					PREPARE del_busy_items_stmt2 FROM @del_busy_items2;
					EXECUTE del_busy_items_stmt2;
	
					### Agendas start/end time rendering
					IF `v_ag_start_time` < `v_ag_end_time` THEN
						SET @ins_ttl	= CONCAT( 'INSERT INTO ', `v_busy_items_tbl_name`, 
													' ( `d_t_start`, `d_t_end`  ) VALUES ',
													'( \'', CONCAT( `v_cur_date`, ' ', '00:00:00' ), '\', \'', `v_ag_d_t_start`, '\' ), ',
													'( \'', `v_ag_d_t_end`, '\', \'', CONCAT( `v_nxt_date`, ' ', '00:00:00' ), '\' )'
													);
					ELSEIF `v_ag_start_time` > `v_ag_end_time` THEN
						SET @ins_ttl	= CONCAT( 'INSERT INTO ', `v_busy_items_tbl_name`, 
													' ( `d_t_start`, `d_t_end`  ) VALUES ',
													'( \'', CONCAT( `v_cur_date`, ' ', `v_ag_end_time` ), '\', \'', `v_ag_d_t_start`, '\' ), ',
													'( \'', `v_ag_d_t_end`, '\', \'', CONCAT( `v_nxt_date`, ' ', `v_ag_start_time` ), '\' )'
													);
					ELSE
						SET `v_max_d_t`	= CONCAT( `v_anx_date`, ' ', `v_ag_end_time` );
						SET @ins_ttl	= CONCAT( 'INSERT INTO ', `v_busy_items_tbl_name`, 
													' ( `d_t_start`, `d_t_end`  ) VALUES ',
													'( \'00:00:00\', \'', `v_ag_d_t_start`, '\' ), ',
													'( \'', `v_max_d_t`, '\', \'', `v_max_d_t`, '\' )'
													);
					END IF;
					PREPARE ins_ttl_stmt FROM @ins_ttl;
					EXECUTE ins_ttl_stmt;
	
					### Delete inner busy elements
					SET @del_absorbed	= CONCAT( 'DELETE `intervals` FROM ', `v_busy_items_tbl_name`, ' AS `intervals`,
						( SELECT `oi1`.`d_t_start`, `oi1`.`d_t_end` 
							FROM ',
								`v_busy_items_tbl_name`, ' AS `oi1`,',
								`v_busy_items_tbl_name`, ' AS `oi2`
							WHERE (
								( `oi1`.`d_t_start` >= `oi2`.`d_t_start` AND `oi1`.`d_t_end` <  `oi2`.`d_t_end` ) OR 
								( `oi1`.`d_t_start` >  `oi2`.`d_t_start` AND `oi1`.`d_t_end` <= `oi2`.`d_t_end` )
							)
						) AS `absorbed`
					WHERE `intervals`.`d_t_start` = `absorbed`.`d_t_start` AND `intervals`.`d_t_end` = `absorbed`.`d_t_end`' );
					PREPARE del_absorbed_stmt FROM @del_absorbed;
					EXECUTE del_absorbed_stmt;
	
					### Appointment type start/end time rendering   
					IF `v_app_type_start` < `v_app_type_end` THEN
						SET @app_tp_cond = CONCAT(
	' AND(',
		'(NOT(`fr_d_t_end` <= \'', `v_cur_date`, ' ', `v_app_type_start`, '\' OR `fr_d_t_start` > \'', `v_cur_date`, ' ', `v_app_type_end`, '\') AND ',
		'((UNIX_TIMESTAMP(`fr_d_t_end`)-UNIX_TIMESTAMP(\'', `v_cur_date`, ' ', `v_app_type_start`, '\'))/60) >= ', `v_app_type_dur`, ') OR ',
		'(NOT(`fr_d_t_end` <= \'', `v_nxt_date`, ' ', `v_app_type_start`, '\' OR `fr_d_t_start` > \'', `v_nxt_date`, ' ', `v_app_type_end`, '\') AND ',
		'((UNIX_TIMESTAMP(`fr_d_t_end`)-UNIX_TIMESTAMP(\'', `v_nxt_date`, ' ', `v_app_type_start`, '\'))/60) >= ', `v_app_type_dur`, ')',
	') '
						);
	
					ELSEIF `v_app_type_start` > `v_app_type_end` THEN
						SET @app_tp_cond = CONCAT(
	' AND(',
		'(NOT(`fr_d_t_end` <= \'', `v_prv_date`, ' ', `v_app_type_start`, '\' OR `fr_d_t_start` > \'', `v_cur_date`, ' ', `v_app_type_end`, '\') AND ',
		'((UNIX_TIMESTAMP(`fr_d_t_end`)-UNIX_TIMESTAMP(\'', `v_prv_date`, ' ', `v_app_type_start`, '\'))/60) >= ', `v_app_type_dur`, ') OR ',
		'(NOT(`fr_d_t_end` <= \'', `v_cur_date`, ' ', `v_app_type_start`, '\' OR `fr_d_t_start` > \'', `v_nxt_date`, ' ', `v_app_type_end`, '\') AND ',
		'((UNIX_TIMESTAMP(`fr_d_t_end`)-UNIX_TIMESTAMP(\'', `v_cur_date`, ' ', `v_app_type_start`, '\'))/60) >= ', `v_app_type_dur`, ') OR ',
		'(NOT(`fr_d_t_end` <= \'', `v_nxt_date`, ' ', `v_app_type_start`, '\' OR `fr_d_t_start` > \'', `v_anx_date`, ' ', `v_app_type_end`, '\') AND ',
		'((UNIX_TIMESTAMP(`fr_d_t_end`)-UNIX_TIMESTAMP(\'', `v_nxt_date`, ' ', `v_app_type_start`, '\'))/60) >= ', `v_app_type_dur`, ')',
	') '
						);
					ELSE
						SET @app_tp_cond = '';
					END IF;
	
	-- 				SET @free_d_t_end	= `v_ag_d_t_start`;
					SET @free_d_t_end	= CONCAT( `v_cur_date`, ' 00:00:00');
					SET @is_exists	= 0;
	
	
	
	-- SET @app_tp_cond = '';
	
	
					SET @seek_interval = CONCAT(
	'SELECT `is_exists` INTO  @is_exists 
	 FROM 
		(SELECT 
			1 AS `is_exists`, ',
	-- 		'@free_d_t_end AS `fr_d_t_start`, ',
			'IF( \'', `p_d_t_now`, '\' > @free_d_t_end, \'', `p_d_t_now`, '\', @free_d_t_end  ) AS `fr_d_t_start`, ',
			'@free_d_t_end := `d_t_end`, 
			`d_t_start` AS `fr_d_t_end`
		FROM ','( SELECT `d_t_start`, `d_t_end` FROM ', `v_busy_items_tbl_name`, ' ORDER BY `d_t_start` ) AS `ordered` ',
	') AS result_intervals 
	WHERE 
		`fr_d_t_start` < `fr_d_t_end` AND `fr_d_t_start` < \'', `v_ag_d_t_end`, '\' AND 
		( ( UNIX_TIMESTAMP( `fr_d_t_end` ) - UNIX_TIMESTAMP( `fr_d_t_start` ) ) / 60 ) >= ', `v_app_type_dur`, @app_tp_cond, ' LIMIT 1' );
	
					PREPARE seek_interval_stmt FROM @seek_interval;
					EXECUTE seek_interval_stmt;
	
	
	
	
	
	
	####	DEBUG ZONE START
	-- IF `p_debug` THEN
	-- 				SET @free_d_t_end	= CONCAT( `v_cur_date`, ' 00:00:00');
	-- -- 				SET @is_exists	= 0;
	-- 
	-- 
	-- 
	-- 	SET @dbg_save_busy_items	= CONCAT( 
	-- 'INSERT INTO  `dbg_busy_items_tbl`( `cur_date`, `d_t_start`, `d_t_end` )
	-- 	( SELECT `cur_date`,  `fr_d_t_start` AS `d_t_start`,  `fr_d_t_end` AS `d_t_end`
	--  FROM 
	-- 	(SELECT 
	-- 		\'', `v_cur_date`, '\' AS `cur_date`, ',
	-- -- 		'@free_d_t_end AS `fr_d_t_start`, ',
	-- 		'IF( \'', `p_d_t_now`, '\' > @free_d_t_end, \'', `p_d_t_now`, '\', @free_d_t_end  ) AS `fr_d_t_start`, ',
	-- 		'@free_d_t_end := `d_t_end`, 
	-- 		`d_t_start` AS `fr_d_t_end`
	-- 	FROM ','( SELECT `d_t_start`, `d_t_end` FROM ', `v_busy_items_tbl_name`, ' ORDER BY `d_t_start` ) AS `ordered` ',
	-- ') AS result_intervals 
	-- WHERE 
	-- 	`fr_d_t_start` < `fr_d_t_end` AND  `fr_d_t_start` < \'', `v_ag_d_t_end`, '\' AND 
	-- 	( ( UNIX_TIMESTAMP( `fr_d_t_end` ) - UNIX_TIMESTAMP( `fr_d_t_start` ) ) / 60 ) >= ', `v_app_type_dur`, @app_tp_cond, ' LIMIT 1 )' );
	-- 
	-- 	PREPARE dbg_save_busy_items_stmt FROM @dbg_save_busy_items;
	-- 	EXECUTE dbg_save_busy_items_stmt;
	-- END IF;
	####	DEBUG ZONE END
	
	
	
	
	
	  
					IF @is_exists > 0 THEN
						SET `v_result` = CONCAT( `v_result`, '"', `v_cur_date`, '":1,');
					END IF;
	
				ELSEIF ( `v_n_get_day` < 3 ) THEN			# Single Day anilizing end
					### Before agenda timetable deletion.
					SET @del_busy_items2	= CONCAT( 'DELETE FROM ', `v_busy_items_tbl_name`, ' WHERE `d_t_end` <= \'', `v_ag_d_t_start`, '\' OR `d_t_start` = `d_t_end`' );
					PREPARE del_busy_items_stmt2 FROM @del_busy_items2;
					EXECUTE del_busy_items_stmt2;
	
					SET `v_n_get_day`	= `v_n_get_day` + 1;
				END IF;
	
				SET `v_cur_date`	= `v_nxt_date`;
			UNTIL `v_cur_date` > `v_last_date` END REPEAT;	### All Days analizing loop end
	
	-- 		SET `v_pos1`	= `v_pos2` + 1;
	






		END IF;

	UNTIL done END REPEAT;

-- 	UNTIL `v_pos2` = 0 END REPEAT;		###	Timetables (Agendas) analizing loop end





	SET @busy_items	= CONCAT( 'DROP TABLE IF EXISTS ', `v_busy_items_tbl_name` );
	PREPARE drop_stmt FROM @busy_items;
	EXECUTE drop_stmt;


### Absent Days analizing loop
	SET `v_cur_date`	= `v_first_date`;
	REPEAT
		IF ( LOCATE(`v_cur_date`, `v_result`, 1 ) = 0 ) THEN
			SET `v_result`	= CONCAT( `v_result`, '"', `v_cur_date`, '":0,');
		END IF;

		SET `v_cur_date`	= `v_cur_date` + INTERVAL 1 DAY;
	UNTIL `v_cur_date` > `v_last_date` END REPEAT;	### Absent Days analizing loop end

  
  
	SET `v_result`	= SUBSTRING( `v_result`, 1, ( LENGTH( `v_result` ) - 1 ) );
	SET `v_result`	= CONCAT( '{', `v_result`, '}' );

SELECT `v_result` AS `res`;






END//


-- -------------------------------------------------------------------------------------------------
-- -------------------------------------------------------------------------------------------------
DELIMITER ;